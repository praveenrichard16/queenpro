<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Models\ShippingMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingMethodApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $query = ShippingMethod::query();

        // Only show active methods for non-admin
        $user = auth()->user();
        if (!$user || (!$user->is_admin && !$user->is_super_admin)) {
            $query->where('is_active', true);
        }

        $methods = $query->orderBy('sort_order')->get();

        return $this->successResponse($methods->map(function ($method) {
            return [
                'id' => $method->id,
                'name' => $method->name,
                'code' => $method->code,
                'type' => $method->type,
                'cost' => (float) $method->cost,
                'free_shipping_threshold' => $method->free_shipping_threshold ? (float) $method->free_shipping_threshold : null,
                'is_active' => (bool) $method->is_active,
            ];
        }), 'Shipping methods retrieved successfully');
    }

    public function show(ShippingMethod $shippingMethod): JsonResponse
    {
        // Check if method is active
        $user = auth()->user();
        if (!$shippingMethod->is_active && (!$user || (!$user->is_admin && !$user->is_super_admin))) {
            return $this->notFoundResponse('Shipping method');
        }

        return $this->successResponse([
            'id' => $shippingMethod->id,
            'name' => $shippingMethod->name,
            'code' => $shippingMethod->code,
            'type' => $shippingMethod->type,
            'cost' => (float) $shippingMethod->cost,
            'free_shipping_threshold' => $shippingMethod->free_shipping_threshold ? (float) $shippingMethod->free_shipping_threshold : null,
            'is_active' => (bool) $shippingMethod->is_active,
        ], 'Shipping method retrieved successfully');
    }
}

