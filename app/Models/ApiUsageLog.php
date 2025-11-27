<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\PersonalAccessToken;

class ApiUsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'token_id',
        'user_id',
        'endpoint',
        'method',
        'status_code',
        'ip_address',
        'user_agent',
        'response_time',
        'request_headers',
        'request_body',
        'response_body',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($log) {
            $log->created_at = now();
        });
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(PersonalAccessToken::class, 'token_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeForToken($query, $tokenId)
    {
        return $query->where('token_id', $tokenId);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status_code', '>=', 200)
            ->where('status_code', '<', 300);
    }

    public function scopeFailed($query)
    {
        return $query->where('status_code', '>=', 400);
    }
}

