<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $query = Category::query();

        // Only show active categories for non-admin
        $user = auth()->user();
        if (!$user || (!$user->is_admin && !$user->is_super_admin)) {
            $query->where('is_active', true);
        }

        // Parent filter
        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->get('parent_id'));
        } elseif ($request->get('only_parents', false)) {
            $query->whereNull('parent_id');
        }

        $categories = $query->orderBy('name')->get();

        return $this->successResponse(CategoryResource::collection($categories), 'Categories retrieved successfully');
    }

    public function show(Category $category): JsonResponse
    {
        // Check if category is active
        $user = auth()->user();
        if (!$category->is_active && (!$user || (!$user->is_admin && !$user->is_super_admin))) {
            return $this->notFoundResponse('Category');
        }

        return $this->successResponse(new CategoryResource($category), 'Category retrieved successfully');
    }
}

