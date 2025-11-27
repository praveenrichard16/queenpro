<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\LeadSourceResource;
use App\Models\LeadSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadSourceApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        $sources = LeadSource::where('is_active', true)->orderBy('name')->get();

        return $this->successResponse(LeadSourceResource::collection($sources), 'Lead sources retrieved successfully');
    }

    public function show(LeadSource $leadSource): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        return $this->successResponse(new LeadSourceResource($leadSource), 'Lead source retrieved successfully');
    }
}

