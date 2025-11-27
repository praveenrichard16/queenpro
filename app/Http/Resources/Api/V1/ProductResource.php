<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'price' => (float) $this->price,
            'selling_price' => $this->selling_price ? (float) $this->selling_price : null,
            'sku' => $this->sku,
            'stock_quantity' => (int) $this->stock_quantity,
            'is_active' => (bool) $this->is_active,
            'is_featured' => (bool) $this->is_featured,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'tax_class_id' => $this->tax_class_id,
            'images' => $this->whenLoaded('images', function () {
                return ProductImageResource::collection($this->images);
            }),
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
            'brand' => $this->whenLoaded('brand', function () {
                return new BrandResource($this->brand);
            }),
            'reviews_count' => $this->when(isset($this->reviews_count), $this->reviews_count),
            'average_rating' => $this->when(isset($this->average_rating), $this->average_rating),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

