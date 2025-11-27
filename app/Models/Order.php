<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\PhoneNumberService;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_phone_country_code',
        'shipping_address',
        'billing_address',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'tax_amount',
        'shipping_method_id',
        'shipping_amount',
        'total_amount',
        'status',
        'payment_method',
        'payment_transaction_id',
        'notes',
        'referral_code',
        'affiliate_id'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function affiliateCommission()
    {
        return $this->hasOne(AffiliateCommission::class);
    }

    public function getFormattedTotalAttribute()
    {
        return \App\Services\CurrencyService::format($this->total_amount);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set customer phone number and automatically add country code if missing
     */
    public function setCustomerPhoneAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['customer_phone'] = null;
            return;
        }

        $normalized = PhoneNumberService::normalize($value, $this->customer_phone_country_code);
        
        $this->attributes['customer_phone'] = $normalized['phone'];
        
        // Update country code if it was detected/added
        if ($normalized['country_code'] && empty($this->customer_phone_country_code)) {
            $this->attributes['customer_phone_country_code'] = $normalized['country_code'];
        }
    }

    /**
     * Get formatted customer phone number
     */
    public function getFormattedCustomerPhoneAttribute(): ?string
    {
        if (empty($this->customer_phone)) {
            return null;
        }

        return PhoneNumberService::format($this->customer_phone, $this->customer_phone_country_code);
    }
}
