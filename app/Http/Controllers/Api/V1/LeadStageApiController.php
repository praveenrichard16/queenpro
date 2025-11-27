<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\LeadStageResource;
use App\Models\LeadStage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadStageApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        $stages = LeadStage::where('is_active', true)->orderBy('order')->get();

        return $this->successResponse(LeadStageResource::collection($stages), 'Lead stages retrieved successfully');
    }

    public function show(LeadStage $leadStage): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        return $this->successResponse(new LeadStageResource($leadStage), 'Lead stage retrieved successfully');
    }
}

