<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'lead_source_id' => $this->lead_source_id,
            'lead_stage_id' => $this->lead_stage_id,
            'expected_value' => $this->expected_value ? (float) $this->expected_value : null,
            'notes' => $this->notes,
            'assigned_to' => $this->assigned_to,
            'lead_score' => $this->lead_score,
            'next_followup_date' => $this->next_followup_date?->toDateString(),
            'next_followup_time' => $this->next_followup_time,
            'source' => $this->whenLoaded('source', function () {
                return new LeadSourceResource($this->source);
            }),
            'stage' => $this->whenLoaded('stage', function () {
                return new LeadStageResource($this->stage);
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

