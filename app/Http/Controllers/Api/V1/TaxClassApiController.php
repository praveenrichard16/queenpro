<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Models\TaxClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaxClassApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $query = TaxClass::query();

        // Only show active classes for non-admin
        $user = auth()->user();
        if (!$user || (!$user->is_admin && !$user->is_super_admin)) {
            $query->where('is_active', true);
        }

        $classes = $query->orderBy('name')->get();

        return $this->successResponse($classes->map(function ($taxClass) {
            return [
                'id' => $taxClass->id,
                'name' => $taxClass->name,
                'rate' => (float) $taxClass->rate,
                'description' => $taxClass->description,
                'is_active' => (bool) $taxClass->is_active,
            ];
        }), 'Tax classes retrieved successfully');
    }

    public function show(TaxClass $taxClass): JsonResponse
    {
        // Check if class is active
        $user = auth()->user();
        if (!$taxClass->is_active && (!$user || (!$user->is_admin && !$user->is_super_admin))) {
            return $this->notFoundResponse('Tax class');
        }

        return $this->successResponse([
            'id' => $taxClass->id,
            'name' => $taxClass->name,
            'rate' => (float) $taxClass->rate,
            'description' => $taxClass->description,
            'is_active' => (bool) $taxClass->is_active,
        ], 'Tax class retrieved successfully');
    }
}

