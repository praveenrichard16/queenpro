<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'content',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const TYPE_PRIVACY_POLICY = 'privacy_policy';
    public const TYPE_TERMS_AND_CONDITIONS = 'terms_and_conditions';
    public const TYPE_REFUND_AND_RETURN_POLICY = 'refund_and_return_policy';

    public static function getTypes(): array
    {
        return [
            self::TYPE_PRIVACY_POLICY => 'Privacy Policy',
            self::TYPE_TERMS_AND_CONDITIONS => 'Terms and Conditions',
            self::TYPE_REFUND_AND_RETURN_POLICY => 'Refund and Return Policy',
        ];
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }
}
