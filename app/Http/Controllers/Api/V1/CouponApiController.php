<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Coupon::query();

        // Only show active coupons for non-admin users
        $user = auth()->user();
        if (!$user || (!$user->is_admin && !$user->is_super_admin)) {
            $query->where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                });
        }

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('code', 'like', "%{$searchParams['search']}%")
                    ->orWhere('description', 'like', "%{$searchParams['search']}%");
            });
        }

        // Status filter (admin only)
        if ($request->has('status') && $user && ($user->is_admin || $user->is_super_admin)) {
            $query->where('status', $request->get('status'));
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $coupons = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($coupons, CouponResource::class);
    }

    public function show(Coupon $coupon): JsonResponse
    {
        // Check if coupon is accessible
        $user = auth()->user();
        if (!$coupon->isValid() && (!$user || (!$user->is_admin && !$user->is_super_admin))) {
            return $this->notFoundResponse('Coupon');
        }

        return $this->successResponse(new CouponResource($coupon), 'Coupon retrieved successfully');
    }

    public function validate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $validated['code'])->first();

        if (!$coupon) {
            return $this->errorResponse('Invalid coupon code', 404);
        }

        if (!$coupon->isValid()) {
            return $this->errorResponse('Coupon is not valid or has expired', 400);
        }

        $discount = 0;
        if (isset($validated['amount'])) {
            $discount = $coupon->calculateDiscount($validated['amount']);
        }

        return $this->successResponse([
            'coupon' => new CouponResource($coupon),
            'discount' => $discount,
            'valid' => true,
        ], 'Coupon validated successfully');
    }
}

