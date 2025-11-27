<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    use HasFactory;

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'lead_id',
        'followup_date',
        'followup_time',
        'notes',
        'status',
        'outcome',
        'created_by',
        'reminder_status',
        'reminder_sent_at',
        'reminder_channel',
        'reminder_attempts',
    ];

    protected $casts = [
        'followup_date' => 'date',
        'reminder_sent_at' => 'datetime',
        'lead_id' => 'integer',
        'reminder_attempts' => 'integer',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }
}

