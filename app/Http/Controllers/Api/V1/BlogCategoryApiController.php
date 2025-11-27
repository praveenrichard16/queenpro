<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\BlogCategoryResource;
use App\Models\BlogCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogCategoryApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $categories = BlogCategory::orderBy('name')->get();

        return $this->successResponse(BlogCategoryResource::collection($categories), 'Blog categories retrieved successfully');
    }

    public function show(BlogCategory $category): JsonResponse
    {
        return $this->successResponse(new BlogCategoryResource($category), 'Blog category retrieved successfully');
    }
}

