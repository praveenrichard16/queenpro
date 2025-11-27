<?php

namespace App\Models;

use App\Enums\TicketPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'default_priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (TicketCategory $category): void {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }

            $category->default_priority = static::normalizePriority($category->default_priority);
            $category->is_active = $category->is_active ?? true;
        });

        static::updating(function (TicketCategory $category): void {
            if ($category->isDirty('name')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }

            if ($category->isDirty('default_priority')) {
                $category->default_priority = static::normalizePriority($category->default_priority);
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $index = 1;

        while (static::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$base}-{$index}";
            $index++;
        }

        return $slug;
    }

    protected static function normalizePriority($value): ?string
    {
        if ($value instanceof TicketPriority) {
            return $value->value;
        }

        if (is_string($value) && in_array($value, TicketPriority::values(), true)) {
            return $value;
        }

        if (empty($value)) {
            return null;
        }

        return $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}

