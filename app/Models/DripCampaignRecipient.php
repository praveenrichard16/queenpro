<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DripCampaignRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'drip_campaign_id',
        'recipient_type',
        'recipient_id',
        'current_step',
        'status',
        'started_at',
        'completed_at',
        'last_sent_at',
        'next_send_at',
        'last_error',
        'retry_count',
        'last_step_payload',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'next_send_at' => 'datetime',
        'last_step_payload' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DripCampaign::class, 'drip_campaign_id');
    }

    public function recipient(): MorphTo
    {
        return $this->morphTo('recipient', 'recipient_type', 'recipient_id');
    }

    public function getRecipientModelAttribute()
    {
        return match($this->recipient_type) {
            'enquiry' => Enquiry::find($this->recipient_id),
            'lead' => Lead::find($this->recipient_id),
            'customer' => User::find($this->recipient_id),
            default => null,
        };
    }
}
