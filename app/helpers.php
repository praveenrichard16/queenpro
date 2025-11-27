<?php

if (!function_exists('currency')) {
    /**
     * Format an amount with currency symbol
     *
     * @param float|int|string $amount
     * @param int $decimals
     * @return string
     */
    function currency($amount, int $decimals = 2): string
    {
        return \App\Services\CurrencyService::format($amount, $decimals);
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get the currency symbol
     *
     * @return string
     */
    function currency_symbol(): string
    {
        return \App\Services\CurrencyService::symbol();
    }
}

if (!function_exists('currency_code')) {
    /**
     * Get the currency code
     *
     * @return string
     */
    function currency_code(): string
    {
        return \App\Services\CurrencyService::code();
    }
}

