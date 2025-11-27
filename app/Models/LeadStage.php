<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_default',
        'is_won',
        'is_lost',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
