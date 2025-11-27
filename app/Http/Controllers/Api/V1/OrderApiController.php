<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Order::with(['items.product', 'user']);

        // Filter by user access unless admin
        $query = $this->filterByUserAccess($query, 'user_id');

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('order_number', 'like', "%{$searchParams['search']}%")
                    ->orWhere('customer_name', 'like', "%{$searchParams['search']}%")
                    ->orWhere('customer_email', 'like', "%{$searchParams['search']}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $orders = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($orders, OrderResource::class);
    }

    public function show(Order $order): JsonResponse
    {
        if (!$this->canAccessResource($order, 'user_id')) {
            return $this->forbiddenResponse('You do not have permission to access this order');
        }

        $order->load(['items.product', 'user']);

        return $this->successResponse(new OrderResource($order), 'Order retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to create orders');
        }

        // Validation and order creation logic would go here
        // For now, return a placeholder response
        return $this->errorResponse('Order creation endpoint - implementation required', 501);
    }
}

