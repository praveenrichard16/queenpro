<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function getValue(string $key, $default = null)
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = static::query()->where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return $setting->castValue();
        });
    }

    public static function setValue(string $key, $value, string $type = 'string'): Setting
    {
        Cache::forget("setting_{$key}");

        // Encode to JSON if type is 'json' and value is array or object
        if ($type === 'json' && (is_array($value) || is_object($value))) {
            $value = json_encode($value);
        }

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    public function castValue()
    {
        return match ($this->type) {
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }
}

