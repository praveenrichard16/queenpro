<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WebhookEndpoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'url',
        'secret',
        'source',
        'events',
        'is_active',
        'timeout',
        'max_attempts',
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($endpoint) {
            if (empty($endpoint->secret)) {
                $endpoint->secret = Str::random(32);
            }
        });
    }

    public function logs()
    {
        return $this->hasMany(WebhookLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSource($query, string $source)
    {
        return $query->where('source', $source)
            ->orWhereNull('source');
    }

    public function scopeForEvent($query, string $eventType)
    {
        return $query->whereJsonContains('events', $eventType)
            ->orWhereNull('events');
    }

    public function shouldHandleEvent(string $eventType): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // If no events specified, handle all events
        if (empty($this->events)) {
            return true;
        }

        // Check if event is in the events array
        return in_array($eventType, $this->events);
    }
}

