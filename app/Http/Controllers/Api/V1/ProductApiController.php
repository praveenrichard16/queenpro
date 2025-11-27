<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Product::with(['category', 'brand', 'images']);

        // Apply filters
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('name', 'like', "%{$searchParams['search']}%")
                    ->orWhere('description', 'like', "%{$searchParams['search']}%")
                    ->orWhere('sku', 'like', "%{$searchParams['search']}%");
            });
        }

        // Category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Brand filter
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->get('brand_id'));
        }

        // Active filter
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        } else {
            $query->where('is_active', true); // Default to active only
        }

        // Featured filter
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        // Stock filter
        if ($request->has('in_stock')) {
            if ($request->boolean('in_stock')) {
                $query->where('stock_quantity', '>', 0);
            } else {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        // Sort
        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $products = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($products, ProductResource::class);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'brand', 'images', 'reviews' => function ($query) {
            $query->where('is_approved', true)->latest();
        }]);

        // Calculate average rating
        $product->average_rating = $product->reviews()->avg('rating');

        return $this->successResponse(new ProductResource($product), 'Product retrieved successfully');
    }
}

