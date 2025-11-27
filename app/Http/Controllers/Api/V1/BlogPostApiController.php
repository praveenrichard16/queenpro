<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogPostApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = BlogPost::with(['category', 'author', 'tags']);

        // Only show published posts for non-admin
        $user = auth()->user();
        if (!$user || (!$user->is_admin && !$user->is_super_admin)) {
            $query->where('is_published', true)
                ->where('published_at', '<=', now());
        }

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('title', 'like', "%{$searchParams['search']}%")
                    ->orWhere('excerpt', 'like', "%{$searchParams['search']}%")
                    ->orWhere('content', 'like', "%{$searchParams['search']}%");
            });
        }

        // Category filter
        if ($request->has('category_id')) {
            $query->where('blog_category_id', $request->get('category_id'));
        }

        // Featured filter
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $posts = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($posts, BlogPostResource::class);
    }

    public function show(BlogPost $post): JsonResponse
    {
        // Check if post is published
        $user = auth()->user();
        if (!$post->is_published && (!$user || (!$user->is_admin && !$user->is_super_admin))) {
            return $this->notFoundResponse('Blog post');
        }

        $post->load(['category', 'author', 'tags']);

        return $this->successResponse(new BlogPostResource($post), 'Blog post retrieved successfully');
    }
}

