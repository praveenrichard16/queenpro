<?php

namespace App\Services;

class CurrencyService
{
    /**
     * Get the currency symbol
     *
     * @return string
     */
    public static function symbol(): string
    {
        return config('currency.symbol', 'ر.س');
    }

    /**
     * Get the currency code
     *
     * @return string
     */
    public static function code(): string
    {
        return config('currency.code', 'SAR');
    }

    /**
     * Get the currency locale
     *
     * @return string
     */
    public static function locale(): string
    {
        return config('currency.locale', 'ar_SA');
    }

    /**
     * Format an amount with currency symbol
     *
     * @param float|int|string $amount
     * @param int $decimals
     * @return string
     */
    public static function format($amount, int $decimals = 2): string
    {
        $formatted = number_format((float) $amount, $decimals);
        return static::symbol() . ' ' . $formatted;
    }

    /**
     * Format an amount without currency symbol (just the number)
     *
     * @param float|int|string $amount
     * @param int $decimals
     * @return string
     */
    public static function formatAmount($amount, int $decimals = 2): string
    {
        return number_format((float) $amount, $decimals);
    }
}

