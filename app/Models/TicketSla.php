<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketSla extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'response_minutes' => 'integer',
        'resolution_minutes' => 'integer',
        'is_default' => 'boolean',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}

