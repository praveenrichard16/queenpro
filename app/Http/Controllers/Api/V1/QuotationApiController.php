<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\QuotationResource;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuotationApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse('You do not have permission to view quotations');
        }

        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Quotation::with(['lead', 'items']);

        // Filter by lead ownership if not admin
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            $query->whereHas('lead', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('assigned_to', $user->id);
            });
        }

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('quote_number', 'like', "%{$searchParams['search']}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Lead filter
        if ($request->has('lead_id')) {
            $query->where('lead_id', $request->get('lead_id'));
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $quotations = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($quotations, QuotationResource::class);
    }

    public function show(Quotation $quotation): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        // Check access through lead
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            $lead = $quotation->lead;
            if ($lead && $lead->user_id !== $user->id && $lead->assigned_to !== $user->id) {
                return $this->forbiddenResponse('You do not have permission to view this quotation');
            }
        }

        $quotation->load(['lead', 'items']);

        return $this->successResponse(new QuotationResource($quotation), 'Quotation retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to create quotations');
        }

        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'status' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $quotation = Quotation::create([
            'lead_id' => $validated['lead_id'],
            'status' => $validated['status'] ?? 'draft',
            'total_amount' => $validated['total_amount'],
            'currency' => $validated['currency'] ?? 'SAR',
            'valid_until' => $validated['valid_until'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create items
        foreach ($validated['items'] as $item) {
            $quotation->items()->create([
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $quotation->load(['lead', 'items']);

        return $this->successResponse(new QuotationResource($quotation), 'Quotation created successfully', 201);
    }

    public function update(Request $request, Quotation $quotation): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to update quotations');
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|string',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'valid_until' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $quotation->update($validated);
        $quotation->load(['lead', 'items']);

        return $this->successResponse(new QuotationResource($quotation), 'Quotation updated successfully');
    }

    public function destroy(Quotation $quotation): JsonResponse
    {
        if (!$this->hasPermission('delete')) {
            return $this->forbiddenResponse('You do not have permission to delete quotations');
        }

        $quotation->delete();

        return $this->successResponse(null, 'Quotation deleted successfully');
    }
}

