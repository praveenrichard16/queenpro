<?php

namespace App\Services;

use App\Models\Product;
use App\Models\TaxClass;

class TaxService
{
    /**
     * Calculate tax for a product
     */
    public function calculateProductTax(Product $product, float $price): float
    {
        $taxRate = $this->getProductTaxRate($product);
        
        if ($taxRate <= 0) {
            return 0;
        }
        
        return ($price * $taxRate) / 100;
    }

    /**
     * Get tax rate for a product
     */
    public function getProductTaxRate(Product $product): float
    {
        // If product has a specific tax rate, use it
        if ($product->tax_rate !== null) {
            return (float) $product->tax_rate;
        }
        
        // Otherwise, use tax class rate
        if ($product->tax_class_id && $product->taxClass) {
            return (float) $product->taxClass->rate;
        }
        
        return 0;
    }

    /**
     * Calculate tax for cart items
     */
    public function calculateCartTax(array $cartItems): float
    {
        $totalTax = 0;
        
        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $itemSubtotal = $item['price'] * $item['quantity'];
                $taxAmount = $this->calculateProductTax($product, $itemSubtotal);
                $totalTax += $taxAmount;
            }
        }
        
        return round($totalTax, 2);
    }

    /**
     * Get all active tax classes
     */
    public function getActiveTaxClasses()
    {
        return TaxClass::active()->orderBy('name')->get();
    }
}

