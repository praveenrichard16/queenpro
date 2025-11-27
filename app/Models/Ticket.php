<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'due_at' => 'datetime',
        'last_customer_reply_at' => 'datetime',
        'last_staff_reply_at' => 'datetime',
        'escalated_at' => 'datetime',
        'meta' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket): void {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = static::generateTicketNumber();
            }

            if (empty($ticket->priority)) {
                $ticket->priority = TicketPriority::MEDIUM;
            }

            if (empty($ticket->status)) {
                $ticket->status = TicketStatus::OPEN;
            }
        });
    }

    protected static function generateTicketNumber(): string
    {
        do {
            $number = 'TCK-' . strtoupper(Str::random(8));
        } while (static::query()->where('ticket_number', $number)->exists());

        return $number;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function sla(): BelongsTo
    {
        return $this->belongsTo(TicketSla::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function attachments(): HasManyThrough
    {
        return $this->hasManyThrough(
            TicketAttachment::class,
            TicketMessage::class
        );
    }

    public function scopeWithStatus(Builder $builder, TicketStatus $status): Builder
    {
        return $builder->where('status', $status->value);
    }

    public function scopeAssignedTo(Builder $builder, int $userId): Builder
    {
        return $builder->where('assigned_to', $userId);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, [
            TicketStatus::OPEN,
            TicketStatus::IN_PROGRESS,
            TicketStatus::AWAITING_CUSTOMER,
        ], true);
    }
}

