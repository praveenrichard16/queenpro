<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\LeadFollowupResource;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Services\LeadReminderService;
use App\Services\LeadScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadFollowupApiController extends ApiBaseController
{
    public function index(Lead $lead): JsonResponse
    {
        if (!$this->hasPermission('read') || !$this->canAccessLead($lead)) {
            return $this->forbiddenResponse();
        }

        $followups = $lead->followups()->latest()->paginate(20);

        return $this->paginatedResponse($followups, LeadFollowupResource::class);
    }

    public function store(Request $request, Lead $lead): JsonResponse
    {
        if (!$this->hasPermission('write') || !$this->canAccessLead($lead)) {
            return $this->forbiddenResponse();
        }

        $data = $this->validateFollowup($request);
        $data['created_by'] = auth()->id();
        $data['status'] = $data['status'] ?? LeadFollowup::STATUS_SCHEDULED;

        $followup = $lead->followups()->create($data);
        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return $this->successResponse(new LeadFollowupResource($followup), 'Followup scheduled successfully', 201);
    }

    public function show(Lead $lead, LeadFollowup $followup): JsonResponse
    {
        if (!$this->hasPermission('read') || !$this->canAccessFollowup($lead, $followup)) {
            return $this->forbiddenResponse();
        }

        return $this->successResponse(new LeadFollowupResource($followup), 'Followup retrieved successfully');
    }

    public function update(Request $request, Lead $lead, LeadFollowup $followup): JsonResponse
    {
        if (!$this->hasPermission('write') || !$this->canAccessFollowup($lead, $followup)) {
            return $this->forbiddenResponse();
        }

        $data = $this->validateFollowup($request, false);
        $followup->update($data);
        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return $this->successResponse(new LeadFollowupResource($followup), 'Followup updated successfully');
    }

    public function destroy(Lead $lead, LeadFollowup $followup): JsonResponse
    {
        if (!$this->hasPermission('delete') || !$this->canAccessFollowup($lead, $followup)) {
            return $this->forbiddenResponse();
        }

        $followup->delete();
        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return $this->successResponse(null, 'Followup deleted successfully');
    }

    public function complete(Request $request, Lead $lead, LeadFollowup $followup): JsonResponse
    {
        if (!$this->hasPermission('write') || !$this->canAccessFollowup($lead, $followup)) {
            return $this->forbiddenResponse();
        }

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

        return $this->successResponse(new LeadFollowupResource($followup), 'Followup marked as completed');
    }

    public function cancel(Request $request, Lead $lead, LeadFollowup $followup): JsonResponse
    {
        if (!$this->hasPermission('write') || !$this->canAccessFollowup($lead, $followup)) {
            return $this->forbiddenResponse();
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $followup->update([
            'status' => LeadFollowup::STATUS_CANCELLED,
            'outcome' => $validated['reason'] ?? $followup->outcome,
        ]);

        $lead->refreshNextFollowupSchedule();
        app(LeadScoringService::class)->refresh($lead);

        return $this->successResponse(new LeadFollowupResource($followup), 'Followup cancelled');
    }

    public function sendReminder(Lead $lead, LeadFollowup $followup, LeadReminderService $service): JsonResponse
    {
        if (!$this->hasPermission('write') || !$this->canAccessFollowup($lead, $followup)) {
            return $this->forbiddenResponse();
        }

        $service->sendReminderForFollowup($followup);

        return $this->successResponse(new LeadFollowupResource($followup->fresh()), 'Reminder sent successfully');
    }

    protected function validateFollowup(Request $request, bool $requireDate = true): array
    {
        return $request->validate([
            'followup_date' => [$requireDate ? 'required' : 'nullable', 'date'],
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

    protected function canAccessLead(Lead $lead): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        if ($user->is_admin || $user->is_super_admin) {
            return true;
        }

        return in_array($user->id, array_filter([
            $lead->user_id,
            $lead->assigned_to,
            $lead->created_by,
        ]), true);
    }

    protected function canAccessFollowup(Lead $lead, LeadFollowup $followup): bool
    {
        if ($lead->id !== $followup->lead_id) {
            return false;
        }

        return $this->canAccessLead($lead);
    }
}

