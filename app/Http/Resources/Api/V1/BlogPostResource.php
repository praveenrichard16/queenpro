<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image_path' => $this->featured_image_path ? asset('storage/' . $this->featured_image_path) : null,
            'featured_image_alt' => $this->featured_image_alt,
            'is_published' => (bool) $this->is_published,
            'is_featured' => (bool) $this->is_featured,
            'published_at' => $this->published_at?->toISOString(),
            'category' => $this->whenLoaded('category', function () {
                return new BlogCategoryResource($this->category);
            }),
            'author' => $this->whenLoaded('author', function () {
                return new UserResource($this->author);
            }),
            'tags' => $this->whenLoaded('tags', function () {
                return BlogTagResource::collection($this->tags);
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

