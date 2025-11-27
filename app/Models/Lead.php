<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Services\PhoneNumberService;

class Lead extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'phone_country_code',
        'lead_source_id',
        'lead_stage_id',
        'expected_value',
        'notes',
        'created_by',
        'assigned_to',
        'next_followup_date',
        'next_followup_time',
        'lead_score',
    ];

    protected $casts = [
        'next_followup_date' => 'date',
        'lead_score' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function stage()
    {
        return $this->belongsTo(LeadStage::class, 'lead_stage_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class, 'converted_to_lead_id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function followups()
    {
        return $this->hasMany(LeadFollowup::class);
    }

    public function nextScheduledFollowup()
    {
        return $this->hasOne(LeadFollowup::class)
            ->scheduled()
            ->orderBy('followup_date')
            ->orderBy('followup_time');
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function scopeWithNextFollowup($query)
    {
        return $query->with('nextScheduledFollowup');
    }

    public function scopeTodayFollowups($query)
    {
        return $query->whereDate('next_followup_date', Carbon::today());
    }

    public function scopeUpcomingFollowups($query)
    {
        return $query->whereDate('next_followup_date', '>=', Carbon::today());
    }

    public function refreshNextFollowupSchedule(): void
    {
        $next = $this->followups()
            ->scheduled()
            ->orderBy('followup_date')
            ->orderBy('followup_time')
            ->first();

        $this->forceFill([
            'next_followup_date' => $next?->followup_date,
            'next_followup_time' => $next?->followup_time,
        ])->save();
    }

    /**
     * Set phone number and automatically add country code if missing
     */
    public function setPhoneAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['phone'] = null;
            return;
        }

        $normalized = PhoneNumberService::normalize($value, $this->phone_country_code);
        
        $this->attributes['phone'] = $normalized['phone'];
        
        // Update country code if it was detected/added
        if ($normalized['country_code'] && empty($this->phone_country_code)) {
            $this->attributes['phone_country_code'] = $normalized['country_code'];
        }
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        return PhoneNumberService::format($this->phone, $this->phone_country_code);
    }
}
