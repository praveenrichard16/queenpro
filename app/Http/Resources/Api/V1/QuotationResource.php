<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lead_id' => $this->lead_id,
            'quote_number' => $this->quote_number,
            'status' => $this->status,
            'total_amount' => (float) $this->total_amount,
            'currency' => $this->currency,
            'valid_until' => $this->valid_until?->toDateString(),
            'notes' => $this->notes,
            'lead' => $this->whenLoaded('lead', function () {
                return new LeadResource($this->lead);
            }),
            'items' => $this->whenLoaded('items', function () {
                return QuotationItemResource::collection($this->items);
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

