<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Services\LeadScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadFollowupController extends Controller
{
    public function store(Request $request, Lead $lead): RedirectResponse
    {
        $data = $this->validateFollowup($request);
        $data['status'] = $data['status'] ?? LeadFollowup::STATUS_SCHEDULED;
        $data['created_by'] = auth()->id();

        $lead->followups()->create($data);
        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return back()->with('success', 'Followup scheduled successfully.');
    }

    public function update(Request $request, Lead $lead, LeadFollowup $followup): RedirectResponse
    {
        $this->ensureFollowupBelongsToLead($lead, $followup);
        $data = $this->validateFollowup($request);
        $data['status'] = $data['status'] ?? $followup->status;

        $followup->update($data);
        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return back()->with('success', 'Followup updated successfully.');
    }

    public function destroy(Lead $lead, LeadFollowup $followup): RedirectResponse
    {
        $this->ensureFollowupBelongsToLead($lead, $followup);
        $followup->delete();
        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return back()->with('success', 'Followup removed successfully.');
    }

    public function complete(Request $request, Lead $lead, LeadFollowup $followup): RedirectResponse
    {
        $this->ensureFollowupBelongsToLead($lead, $followup);
        $validated = $request->validate([
            'outcome' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $followup->update([
            'status' => LeadFollowup::STATUS_COMPLETED,
            'outcome' => $validated['outcome'] ?? $followup->outcome,
            'notes' => $validated['notes'] ?? $followup->notes,
        ]);

        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return back()->with('success', 'Followup marked as completed.');
    }

    public function cancel(Request $request, Lead $lead, LeadFollowup $followup): RedirectResponse
    {
        $this->ensureFollowupBelongsToLead($lead, $followup);
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $followup->update([
            'status' => LeadFollowup::STATUS_CANCELLED,
            'outcome' => $validated['reason'] ?? $followup->outcome,
        ]);

        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return back()->with('success', 'Followup cancelled successfully.');
    }

    protected function validateFollowup(Request $request): array
    {
        return $request->validate([
            'followup_date' => ['required', 'date'],
            'followup_time' => ['nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:' . implode(',', [
                LeadFollowup::STATUS_SCHEDULED,
                LeadFollowup::STATUS_COMPLETED,
                LeadFollowup::STATUS_CANCELLED,
            ])],
            'outcome' => ['nullable', 'string', 'max:255'],
        ]);
    }

    protected function ensureFollowupBelongsToLead(Lead $lead, LeadFollowup $followup): void
    {
        if ($followup->lead_id !== $lead->id) {
            abort(404);
        }
    }
}

