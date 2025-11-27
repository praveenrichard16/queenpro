<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => (float) $this->value,
            'min_amount' => $this->min_amount ? (float) $this->min_amount : null,
            'max_discount' => $this->max_discount ? (float) $this->max_discount : null,
            'usage_limit' => $this->usage_limit,
            'used_count' => (int) $this->used_count,
            'valid_from' => $this->valid_from?->toISOString(),
            'valid_until' => $this->valid_until?->toISOString(),
            'status' => $this->status,
            'description' => $this->description,
            'is_public' => (bool) $this->is_public,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

