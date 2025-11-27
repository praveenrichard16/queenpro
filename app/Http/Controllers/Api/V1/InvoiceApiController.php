<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse('You do not have permission to view invoices');
        }

        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Invoice::with(['items']);

        // Filter by order ownership if not admin
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            $query->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('invoice_number', 'like', "%{$searchParams['search']}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $invoices = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($invoices, InvoiceResource::class);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        // Check access through order
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            if ($invoice->order && $invoice->order->user_id !== $user->id) {
                return $this->forbiddenResponse('You do not have permission to view this invoice');
            }
        }

        $invoice->load(['items']);

        return $this->successResponse(new InvoiceResource($invoice), 'Invoice retrieved successfully');
    }
}

