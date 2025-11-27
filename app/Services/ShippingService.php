<?php

namespace App\Services;

use App\Models\ShippingMethod;

class ShippingService
{
    /**
     * Calculate shipping cost for an order
     */
    public function calculateShipping(ShippingMethod $method, float $subtotal, float $weight = 0, array $address = []): float
    {
        // Check if free shipping threshold is met
        if ($method->free_shipping_threshold && $subtotal >= $method->free_shipping_threshold) {
            return 0;
        }

        switch ($method->type) {
            case 'free_shipping':
                return 0;
                
            case 'flat_rate':
                return (float) $method->cost;
                
            case 'weight_based':
                return $this->calculateWeightBasedShipping($method, $weight);
                
            case 'location_based':
                return $this->calculateLocationBasedShipping($method, $address);
                
            default:
                return (float) $method->cost;
        }
    }

    /**
     * Calculate weight-based shipping
     */
    protected function calculateWeightBasedShipping(ShippingMethod $method, float $weight): float
    {
        $settings = $method->settings ?? [];
        $baseCost = (float) $method->cost;
        $perKgRate = $settings['per_kg_rate'] ?? 0;
        
        return $baseCost + ($weight * $perKgRate);
    }

    /**
     * Calculate location-based shipping
     */
    protected function calculateLocationBasedShipping(ShippingMethod $method, array $address): float
    {
        $settings = $method->settings ?? [];
        $country = $address['country'] ?? '';
        $state = $address['state'] ?? '';
        
        // Check for country-specific rates
        if (isset($settings['country_rates'][$country])) {
            return (float) $settings['country_rates'][$country];
        }
        
        // Check for state-specific rates
        if (isset($settings['state_rates'][$state])) {
            return (float) $settings['state_rates'][$state];
        }
        
        // Default cost
        return (float) $method->cost;
    }

    /**
     * Get available shipping methods for checkout
     */
    public function getAvailableMethods(): \Illuminate\Database\Eloquent\Collection
    {
        return ShippingMethod::active()->ordered()->get();
    }

    /**
     * Get shipping method by code
     */
    public function getByCode(string $code): ?ShippingMethod
    {
        return ShippingMethod::where('code', $code)->where('is_active', true)->first();
    }
}

