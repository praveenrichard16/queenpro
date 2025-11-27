<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\EnquiryResource;
use App\Models\Enquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnquiryApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse('You do not have permission to view enquiries');
        }

        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Enquiry::with(['user', 'product']);

        // Filter by user access unless admin
        $query = $this->filterByUserAccess($query, 'user_id');

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('subject', 'like', "%{$searchParams['search']}%")
                    ->orWhere('message', 'like', "%{$searchParams['search']}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Product filter
        if ($request->has('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $enquiries = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($enquiries, EnquiryResource::class);
    }

    public function show(Enquiry $enquiry): JsonResponse
    {
        if (!$this->canAccessResource($enquiry, 'user_id')) {
            return $this->forbiddenResponse('You do not have permission to view this enquiry');
        }

        $enquiry->load(['user', 'product']);

        return $this->successResponse(new EnquiryResource($enquiry), 'Enquiry retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';

        $enquiry = Enquiry::create($validated);
        $enquiry->load(['user', 'product']);

        return $this->successResponse(new EnquiryResource($enquiry), 'Enquiry created successfully', 201);
    }

    public function update(Request $request, Enquiry $enquiry): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to update enquiries');
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|string',
            'notes' => 'nullable|string',
        ]);

        $enquiry->update($validated);
        $enquiry->load(['user', 'product']);

        return $this->successResponse(new EnquiryResource($enquiry), 'Enquiry updated successfully');
    }
}

