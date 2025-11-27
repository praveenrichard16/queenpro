<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadFollowupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lead_id' => $this->lead_id,
            'followup_date' => $this->followup_date?->toDateString(),
            'followup_time' => $this->followup_time,
            'notes' => $this->notes,
            'status' => $this->status,
            'outcome' => $this->outcome,
            'reminder_status' => $this->reminder_status,
            'reminder_sent_at' => $this->reminder_sent_at?->toISOString(),
            'reminder_channel' => $this->reminder_channel,
            'reminder_attempts' => $this->reminder_attempts,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

