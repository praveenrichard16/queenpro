<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DripCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'channel',
        'is_active',
        'timezone',
        'send_window_start',
        'send_window_end',
        'max_retries',
        'audience_filters',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'audience_filters' => 'array',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(DripCampaignStep::class)->orderBy('step_number');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(DripCampaignRecipient::class);
    }

    public function getActiveStepsAttribute()
    {
        return $this->steps()->where('is_active', true)->get();
    }
}
