<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => $this->status?->value ?? $this->status,
            'priority' => $this->priority?->value ?? $this->priority,
            'customer_id' => $this->customer_id,
            'assigned_to' => $this->assigned_to,
            'ticket_category_id' => $this->ticket_category_id,
            'customer' => $this->whenLoaded('customer', function () {
                return new UserResource($this->customer);
            }),
            'assignee' => $this->whenLoaded('assignee', function () {
                return new UserResource($this->assignee);
            }),
            'category' => $this->whenLoaded('category', function () {
                return new TicketCategoryResource($this->category);
            }),
            'messages' => $this->whenLoaded('messages', function () {
                return TicketMessageResource::collection($this->messages);
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

