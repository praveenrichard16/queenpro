<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class PhoneNumberService
{
    /**
     * Common country codes mapping
     */
    protected static array $countryCodes = [
        'IN' => '91',  // India
        'US' => '1',   // United States
        'GB' => '44',  // United Kingdom
        'AE' => '971', // UAE
        'SA' => '966', // Saudi Arabia
        'PK' => '92',  // Pakistan
        'BD' => '880', // Bangladesh
        'LK' => '94',  // Sri Lanka
        'NP' => '977', // Nepal
        'MY' => '60',  // Malaysia
        'SG' => '65',  // Singapore
        'PH' => '63',  // Philippines
        'ID' => '62',  // Indonesia
        'TH' => '66',  // Thailand
        'VN' => '84',  // Vietnam
        'CN' => '86',  // China
        'JP' => '81',  // Japan
        'KR' => '82',  // South Korea
        'AU' => '61',  // Australia
        'NZ' => '64',  // New Zealand
        'CA' => '1',   // Canada
        'MX' => '52',  // Mexico
        'BR' => '55',  // Brazil
        'AR' => '54',  // Argentina
        'ZA' => '27',  // South Africa
        'EG' => '20',  // Egypt
        'NG' => '234', // Nigeria
        'KE' => '254', // Kenya
        'FR' => '33',  // France
        'DE' => '49',  // Germany
        'IT' => '39',  // Italy
        'ES' => '34',  // Spain
        'NL' => '31',  // Netherlands
        'BE' => '32',  // Belgium
        'CH' => '41',  // Switzerland
        'AT' => '43',  // Austria
        'SE' => '46',  // Sweden
        'NO' => '47',  // Norway
        'DK' => '45',  // Denmark
        'FI' => '358', // Finland
        'PL' => '48',  // Poland
        'RU' => '7',   // Russia
        'TR' => '90',  // Turkey
    ];

    /**
     * Normalize phone number and add country code if missing
     * 
     * @param string|null $phone The phone number to normalize
     * @param string|null $countryCode Optional country code (e.g., '91', '1')
     * @param string|null $countryIso Optional country ISO code (e.g., 'IN', 'US')
     * @return array Returns ['phone' => normalized_phone, 'country_code' => country_code, 'formatted' => formatted_phone]
     */
    public static function normalize(?string $phone, ?string $countryCode = null, ?string $countryIso = null): array
    {
        if (empty($phone)) {
            return [
                'phone' => null,
                'country_code' => $countryCode,
                'formatted' => null,
            ];
        }

        // Clean the phone number (remove spaces, dashes, parentheses, etc.)
        $cleaned = preg_replace('/[^\d+]/', '', trim($phone));
        
        // Remove leading + if present
        $cleaned = ltrim($cleaned, '+');
        
        // If empty after cleaning, return null
        if (empty($cleaned)) {
            return [
                'phone' => null,
                'country_code' => $countryCode,
                'formatted' => null,
            ];
        }

        // Determine country code
        $detectedCountryCode = $countryCode;
        
        // If country ISO provided but no country code, get from mapping
        if (!$detectedCountryCode && $countryIso && isset(self::$countryCodes[$countryIso])) {
            $detectedCountryCode = self::$countryCodes[$countryIso];
        }
        
        // If still no country code, try to detect from phone number or use default
        if (!$detectedCountryCode) {
            $detectedCountryCode = self::detectCountryCode($cleaned);
        }
        
        // If still no country code, use default from settings
        if (!$detectedCountryCode) {
            $detectedCountryCode = self::getDefaultCountryCode();
        }

        // Check if phone number already starts with country code
        $phoneWithCode = self::ensureCountryCode($cleaned, $detectedCountryCode);
        
        return [
            'phone' => $phoneWithCode,
            'country_code' => $detectedCountryCode,
            'formatted' => self::format($phoneWithCode, $detectedCountryCode),
        ];
    }

    /**
     * Ensure phone number has country code prefix
     */
    protected static function ensureCountryCode(string $phone, ?string $countryCode): string
    {
        if (!$countryCode) {
            return $phone;
        }

        // Remove leading zeros (common in some countries)
        $phone = ltrim($phone, '0');
        
        // Check if phone already starts with country code
        if (str_starts_with($phone, $countryCode)) {
            return $phone;
        }
        
        // Add country code
        return $countryCode . $phone;
    }

    /**
     * Detect country code from phone number format
     */
    protected static function detectCountryCode(string $phone): ?string
    {
        // Remove leading zeros
        $phone = ltrim($phone, '0');
        
        // Try to match known country code patterns
        foreach (self::$countryCodes as $iso => $code) {
            if (str_starts_with($phone, $code)) {
                return $code;
            }
        }
        
        return null;
    }

    /**
     * Get default country code from settings
     */
    public static function getDefaultCountryCode(): ?string
    {
        $default = Setting::getValue('default_country_code');
        
        if ($default) {
            return $default;
        }
        
        // Fallback to common defaults (India)
        return '91';
    }

    /**
     * Format phone number for display
     */
    public static function format(string $phone, ?string $countryCode = null): string
    {
        if (empty($phone)) {
            return '';
        }

        // Remove country code if provided for formatting
        if ($countryCode && str_starts_with($phone, $countryCode)) {
            $localNumber = substr($phone, strlen($countryCode));
            return '+' . $countryCode . ' ' . $localNumber;
        }
        
        // If phone starts with country code, try to format it
        if (strlen($phone) > 10) {
            // Likely has country code
            foreach (self::$countryCodes as $iso => $code) {
                if (str_starts_with($phone, $code)) {
                    $localNumber = substr($phone, strlen($code));
                    return '+' . $code . ' ' . $localNumber;
                }
            }
        }
        
        return $phone;
    }

    /**
     * Format phone number for WhatsApp API (digits only, no +)
     */
    public static function formatForWhatsApp(string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // Remove all non-digit characters
        return preg_replace('/[^\d]/', '', $phone);
    }

    /**
     * Validate phone number format
     */
    public static function validate(string $phone, ?string $countryCode = null): bool
    {
        if (empty($phone)) {
            return false;
        }

        $normalized = self::normalize($phone, $countryCode);
        
        if (empty($normalized['phone'])) {
            return false;
        }

        // Basic validation: should be 10-15 digits
        $digitsOnly = preg_replace('/[^\d]/', '', $normalized['phone']);
        
        return strlen($digitsOnly) >= 10 && strlen($digitsOnly) <= 15;
    }

    /**
     * Get country code from country ISO code
     */
    public static function getCountryCodeFromIso(string $iso): ?string
    {
        return self::$countryCodes[strtoupper($iso)] ?? null;
    }

    /**
     * Get all available country codes
     */
    public static function getAvailableCountryCodes(): array
    {
        return self::$countryCodes;
    }
}

