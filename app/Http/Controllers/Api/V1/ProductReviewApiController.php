<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\ProductReviewResource;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductReviewApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $params = $this->getPaginationParams($request);

        $query = ProductReview::with(['user', 'product'])
            ->where('is_approved', true);

        // Filter by product
        if ($request->has('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        $reviews = $query->latest()->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($reviews, ProductReviewResource::class);
    }

    public function show(ProductReview $review): JsonResponse
    {
        if (!$review->is_approved) {
            $user = auth()->user();
            // Only allow viewing unapproved reviews if user owns it or is admin
            if (!$user || ($user->id !== $review->user_id && !$user->is_admin)) {
                return $this->notFoundResponse('Review');
            }
        }

        $review->load(['user', 'product']);

        return $this->successResponse(new ProductReviewResource($review), 'Review retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string',
        ]);

        // Check if user already reviewed this product
        $existingReview = ProductReview::where('user_id', $user->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return $this->errorResponse('You have already reviewed this product', 400);
        }

        $validated['user_id'] = $user->id;
        $validated['is_approved'] = false; // Requires approval

        $review = ProductReview::create($validated);
        $review->load(['user', 'product']);

        return $this->successResponse(new ProductReviewResource($review), 'Review submitted successfully. It will be published after approval.', 201);
    }
}

