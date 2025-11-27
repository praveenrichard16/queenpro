<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'customer_email',
        'cart_data',
        'last_activity',
        'is_abandoned',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'last_activity' => 'datetime',
        'is_abandoned' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

