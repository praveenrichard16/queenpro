<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\LeadResource;
use App\Models\Lead;
use App\Services\LeadAnalyticsService;
use App\Services\LeadScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse('You do not have permission to view leads');
        }

        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Lead::with(['source', 'stage', 'user']);

        // Filter by user access unless admin
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('assigned_to', $user->id)
                    ->orWhere('created_by', $user->id);
            });
        }

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('name', 'like', "%{$searchParams['search']}%")
                    ->orWhere('email', 'like', "%{$searchParams['search']}%")
                    ->orWhere('phone', 'like', "%{$searchParams['search']}%");
            });
        }

        // Stage filter
        if ($request->has('stage_id')) {
            $query->where('lead_stage_id', $request->get('stage_id'));
        }

        // Source filter
        if ($request->has('source_id')) {
            $query->where('lead_source_id', $request->get('source_id'));
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $leads = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($leads, LeadResource::class);
    }

    public function show(Lead $lead): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        // Check access
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            if ($lead->user_id !== $user->id && $lead->assigned_to !== $user->id && $lead->created_by !== $user->id) {
                return $this->forbiddenResponse('You do not have permission to view this lead');
            }
        }

        $lead->load(['source', 'stage', 'user']);

        return $this->successResponse(new LeadResource($lead), 'Lead retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to create leads');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'lead_source_id' => 'nullable|exists:lead_sources,id',
            'lead_stage_id' => 'nullable|exists:lead_stages,id',
            'expected_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $user = auth()->user();
        $validated['created_by'] = $user->id;
        $validated['user_id'] = $user->id;

        $lead = Lead::create($validated);
        app(LeadScoringService::class)->refresh($lead);
        $lead->load(['source', 'stage']);

        // Trigger webhook
        app(\App\Services\WebhookService::class)->send('lead.created', $lead->toArray(), 'lead');

        return $this->successResponse(new LeadResource($lead), 'Lead created successfully', 201);
    }

    public function update(Request $request, Lead $lead): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to update leads');
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'lead_source_id' => 'nullable|exists:lead_sources,id',
            'lead_stage_id' => 'nullable|exists:lead_stages,id',
            'expected_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead->update($validated);
        app(LeadScoringService::class)->refresh($lead);
        $lead->load(['source', 'stage']);

        // Trigger webhook
        app(\App\Services\WebhookService::class)->send('lead.updated', $lead->toArray(), 'lead');

        return $this->successResponse(new LeadResource($lead), 'Lead updated successfully');
    }

    public function destroy(Lead $lead): JsonResponse
    {
        if (!$this->hasPermission('delete')) {
            return $this->forbiddenResponse('You do not have permission to delete leads');
        }

        $lead->delete();

        return $this->successResponse(null, 'Lead deleted successfully');
    }

    public function recalculateScore(Lead $lead): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to update leads');
        }

        $score = app(LeadScoringService::class)->refresh($lead);

        return $this->successResponse(['lead_score' => $score], 'Lead score recalculated successfully');
    }

    public function analytics(LeadAnalyticsService $service): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        $data = $service->getOverview();

        return $this->successResponse($data, 'Analytics retrieved successfully');
    }

    public function trash(): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        $leads = Lead::onlyTrashed()->paginate(20);

        return $this->paginatedResponse($leads, LeadResource::class);
    }

    public function restore(int $leadId): JsonResponse
    {
        if (!$this->hasPermission('delete')) {
            return $this->forbiddenResponse();
        }

        $lead = Lead::onlyTrashed()->findOrFail($leadId);
        $lead->restore();

        return $this->successResponse(new LeadResource($lead), 'Lead restored successfully');
    }

    public function forceDelete(int $leadId): JsonResponse
    {
        if (!$this->hasPermission('delete')) {
            return $this->forbiddenResponse();
        }

        $lead = Lead::onlyTrashed()->findOrFail($leadId);
        $lead->forceDelete();

        return $this->successResponse(null, 'Lead permanently deleted');
    }
}

