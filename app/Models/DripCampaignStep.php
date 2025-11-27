<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DripCampaignStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'drip_campaign_id',
        'step_number',
        'delay_hours',
        'template_id',
        'channel',
        'is_active',
        'conditions',
        'wait_until_event',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'conditions' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DripCampaign::class, 'drip_campaign_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MarketingTemplate::class, 'template_id');
    }
}
