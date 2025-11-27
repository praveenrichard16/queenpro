<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\PhoneNumberService;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'type',
        'contact_name',
        'contact_phone',
        'contact_phone_country_code',
        'street',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set contact phone number and automatically add country code if missing
     */
    public function setContactPhoneAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['contact_phone'] = null;
            return;
        }

        // Try to get country code from country field if available
        $countryCode = $this->contact_phone_country_code;
        if (!$countryCode && $this->country) {
            $countryCode = PhoneNumberService::getCountryCodeFromIso($this->country);
        }

        $normalized = PhoneNumberService::normalize($value, $countryCode);
        
        $this->attributes['contact_phone'] = $normalized['phone'];
        
        // Update country code if it was detected/added
        if ($normalized['country_code'] && empty($this->contact_phone_country_code)) {
            $this->attributes['contact_phone_country_code'] = $normalized['country_code'];
        }
    }

    /**
     * Get formatted contact phone number
     */
    public function getFormattedContactPhoneAttribute(): ?string
    {
        if (empty($this->contact_phone)) {
            return null;
        }

        return PhoneNumberService::format($this->contact_phone, $this->contact_phone_country_code);
    }
}

