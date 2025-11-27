<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'desktop_image_path',
        'mobile_image_path',
        'alt_text',
        'button_text',
        'button_link',
        'show_title',
        'show_description',
        'show_button',
        'button_size',
        'button_color',
        'title_position',
        'description_position',
        'button_position',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_title' => 'boolean',
        'show_description' => 'boolean',
        'show_button' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}

