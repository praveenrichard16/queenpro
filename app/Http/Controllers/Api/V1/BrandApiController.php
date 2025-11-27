<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $query = Brand::query();

        // Only show active brands for non-admin
        $user = auth()->user();
        if (!$user || (!$user->is_admin && !$user->is_super_admin)) {
            $query->where('is_active', true);
        }

        $brands = $query->orderBy('name')->get();

        return $this->successResponse(BrandResource::collection($brands), 'Brands retrieved successfully');
    }

    public function show(Brand $brand): JsonResponse
    {
        // Check if brand is active
        $user = auth()->user();
        if (!$brand->is_active && (!$user || (!$user->is_admin && !$user->is_super_admin))) {
            return $this->notFoundResponse('Brand');
        }

        return $this->successResponse(new BrandResource($brand), 'Brand retrieved successfully');
    }
}

