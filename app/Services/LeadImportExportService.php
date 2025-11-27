<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadImportExportService
{
    public function export(array $filters = [], string $format = 'csv'): StreamedResponse
    {
        $query = Lead::with(['source', 'stage', 'assignee']);

        $stageFilter = $filters['stage_id'] ?? $filters['lead_stage_id'] ?? null;
        if (!empty($stageFilter)) {
            $query->where('lead_stage_id', $stageFilter);
        }

        $sourceFilter = $filters['source_id'] ?? $filters['lead_source_id'] ?? null;
        if (!empty($sourceFilter)) {
            $query->where('lead_source_id', $sourceFilter);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        $filename = 'leads_' . now()->format('Y-m-d_His') . '.' . ($format === 'xlsx' ? 'csv' : $format);

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'name',
                'email',
                'phone',
                'lead_source',
                'lead_stage',
                'expected_value',
                'notes',
                'assigned_email',
                'lead_score',
                'next_followup_date',
                'next_followup_time',
            ]);

            $query->orderBy('id')->chunk(500, function ($chunk) use ($handle) {
                foreach ($chunk as $lead) {
                    fputcsv($handle, [
                        $lead->name,
                        $lead->email,
                        $lead->phone,
                        $lead->source?->name,
                        $lead->stage?->name,
                        $lead->expected_value,
                        $lead->notes,
                        $lead->assignee?->email,
                        $lead->lead_score,
                        $lead->next_followup_date,
                        $lead->next_followup_time,
                    ]);
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function import(UploadedFile $file, string $mode = 'create'): array
    {
        $mode = strtolower($mode);
        if (!in_array($mode, ['create', 'update', 'replace'], true)) {
            $mode = 'create';
        }

        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            throw new \RuntimeException('Unable to open uploaded file.');
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            throw new \RuntimeException('CSV is empty.');
        }

        $headers = array_map(fn ($header) => strtolower(trim($header)), $headers);

        $summary = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        if ($mode === 'replace') {
            Lead::query()->delete();
        }

        $scoringService = app(LeadScoringService::class);
        $rowNumber = 1;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                if (count(array_filter($row)) === 0) {
                    continue;
                }

                $combined = $this->combineRow($headers, $row);
                $data = $this->mapRow($combined);

                if (empty($data['email'])) {
                    $summary['skipped']++;
                    $summary['errors'][] = "Row {$rowNumber}: Email is required.";
                    continue;
                }

                $lead = Lead::where('email', $data['email'])->first();

                if (!$lead) {
                    if ($mode === 'update') {
                        $summary['skipped']++;
                        continue;
                    }
                    $lead = new Lead();
                    $summary['created']++;
                } else {
                    $summary['updated']++;
                }

                $lead->fill($data);
                $lead->created_by = $lead->created_by ?? Auth::id();
                $lead->save();

                $scoringService->refresh($lead);
            }

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error('Lead import failed', [
                'error' => $throwable->getMessage(),
            ]);
            throw $throwable;
        } finally {
            fclose($handle);
        }

        return $summary;
    }

    protected function mapRow(array $row): array
    {
        $row = array_change_key_case($row, CASE_LOWER);

        $leadSource = null;
        if (!empty($row['lead_source'])) {
            $leadSource = LeadSource::firstOrCreate(
                ['slug' => \Str::slug($row['lead_source'])],
                ['name' => $row['lead_source']]
            );
        }

        $leadStage = null;
        if (!empty($row['lead_stage'])) {
            $leadStage = LeadStage::firstOrCreate(
                ['slug' => \Str::slug($row['lead_stage'])],
                ['name' => $row['lead_stage']]
            );
        }

        $assignedUser = null;
        if (!empty($row['assigned_email'])) {
            $assignedUser = User::where('email', $row['assigned_email'])->first();
        }

        return array_filter([
            'name' => $row['name'] ?? null,
            'email' => $row['email'] ?? null,
            'phone' => $row['phone'] ?? null,
            'lead_source_id' => $leadSource?->id,
            'lead_stage_id' => $leadStage?->id,
            'expected_value' => Arr::get($row, 'expected_value'),
            'notes' => $row['notes'] ?? null,
            'assigned_to' => $assignedUser?->id,
            'lead_score' => $row['lead_score'] ?? null,
            'next_followup_date' => $row['next_followup_date'] ?? null,
            'next_followup_time' => $row['next_followup_time'] ?? null,
        ], fn ($value) => !is_null($value) && $value !== '');
    }

    protected function combineRow(array $headers, array $row): array
    {
        $countHeaders = count($headers);
        $countRow = count($row);

        if ($countRow < $countHeaders) {
            $row = array_pad($row, $countHeaders, null);
        } elseif ($countRow > $countHeaders) {
            $row = array_slice($row, 0, $countHeaders);
        }

        return array_combine($headers, $row) ?: [];
    }
}

