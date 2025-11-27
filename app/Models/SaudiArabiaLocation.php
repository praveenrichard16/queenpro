<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaudiArabiaLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'region_name_ar',
        'region_name_en',
        'city_id',
        'city_name_ar',
        'city_name_en',
        'district_id',
        'district_name_ar',
        'district_name_en',
        'postal_code',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active locations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by state/region
     */
    public function scopeByState($query, $state)
    {
        return $query->where('region_name_en', $state);
    }

    /**
     * Scope to filter by city
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city_name_en', $city);
    }

    /**
     * Scope to search locations
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('city_name_en', 'like', "%{$term}%")
              ->orWhere('region_name_en', 'like', "%{$term}%")
              ->orWhere('postal_code', 'like', "%{$term}%")
              ->orWhere('city_name_ar', 'like', "%{$term}%")
              ->orWhere('region_name_ar', 'like', "%{$term}%");
        });
    }

    /**
     * Get unique states/regions as array
     */
    public static function getStates()
    {
        return static::active()
            ->select('region_name_en')
            ->distinct()
            ->whereNotNull('region_name_en')
            ->orderBy('region_name_en')
            ->pluck('region_name_en')
            ->toArray();
    }

    /**
     * Get cities by state/region as array
     */
    public static function getCitiesByState($state)
    {
        return static::active()
            ->where('region_name_en', $state)
            ->whereNotNull('city_name_en')
            ->select('city_name_en')
            ->distinct()
            ->orderBy('city_name_en')
            ->pluck('city_name_en')
            ->toArray();
    }

    /**
     * Get postal codes by city and state
     */
    public static function getPostalCodes($city = null, $state = null)
    {
        $query = static::active()->whereNotNull('postal_code');
        
        if ($city) {
            $query->where('city_name_en', $city);
        }
        
        if ($state) {
            $query->where('region_name_en', $state);
        }
        
        return $query->select('postal_code')
            ->distinct()
            ->orderBy('postal_code')
            ->pluck('postal_code');
    }
}

