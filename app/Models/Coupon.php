<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'status',
        'description',
        'offer_type',
        'user_segment',
        'minimum_purchase_amount',
        'per_user_limit',
        'is_public',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'minimum_purchase_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_public' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_product');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_category');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'coupon_brand');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user');
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Scopes
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('offer_type', 'product')
            ->whereHas('products', function ($q) use ($productId) {
                $q->where('products.id', $productId);
            });
    }

    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('offer_type', 'category')
            ->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
    }

    public function scopeForBrand($query, $brandId)
    {
        return $query->where('offer_type', 'brand')
            ->whereHas('brands', function ($q) use ($brandId) {
                $q->where('brands.id', $brandId);
            });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('offer_type', 'user')
            ->whereHas('users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });
    }

    public function scopeFirstTimeBuyers($query)
    {
        return $query->where('user_segment', 'first_time_buyers');
    }

    public function scopeRepeatCustomers($query)
    {
        return $query->where('user_segment', 'repeat_customers');
    }

    /**
     * Check if coupon is valid
     */
    public function isValid(?User $user = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        // Check per-user limit if user is provided
        if ($user && $this->per_user_limit) {
            $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
            if ($userUsageCount >= $this->per_user_limit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount for a given subtotal
     */
    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        // Check minimum amount requirement
        if ($this->min_amount && $subtotal < $this->min_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            
            // Apply max discount limit if set
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            // Fixed amount
            $discount = min($this->value, $subtotal);
        }

        return round($discount, 2);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(?User $user = null, ?Order $order = null): void
    {
        $this->increment('used_count');

        // Track individual usage
        CouponUsage::create([
            'coupon_id' => $this->id,
            'user_id' => $user?->id,
            'order_id' => $order?->id,
            'used_at' => now(),
        ]);
    }

    /**
     * Check if coupon can be used by a specific user
     */
    public function canBeUsedByUser(?User $user, float $cartTotal = 0, ?array $cartItems = null): bool
    {
        if (!$this->isValid($user)) {
            return false;
        }

        // Check if it's a user-specific offer
        if ($this->offer_type === 'user' && $user) {
            if (!$this->users()->where('users.id', $user->id)->exists()) {
                return false;
            }
        }

        // Check user segment restrictions
        if ($this->user_segment && $user) {
            if ($this->user_segment === 'first_time_buyers' && !$this->isFirstTimeBuyer($user)) {
                return false;
            }

            if ($this->user_segment === 'repeat_customers' && !$this->isRepeatCustomer($user)) {
                return false;
            }

            if ($this->user_segment === 'minimum_purchase' && $this->minimum_purchase_amount) {
                if ($cartTotal < $this->minimum_purchase_amount) {
                    return false;
                }
            }
        }

        // Check billing amount requirement
        if ($this->offer_type === 'billing_amount' && $this->min_amount) {
            if ($cartTotal < $this->min_amount) {
                return false;
            }
        }

        // Check product/category/brand restrictions if cart items provided
        if ($cartItems) {
            if ($this->offer_type === 'product') {
                $productIds = collect($cartItems)->pluck('product_id')->toArray();
                $applicableProducts = $this->products()->whereIn('products.id', $productIds)->exists();
                if (!$applicableProducts) {
                    return false;
                }
            }

            if ($this->offer_type === 'category') {
                $productIds = collect($cartItems)->pluck('product_id')->toArray();
                $categoryIds = Product::whereIn('id', $productIds)
                    ->with('categories')
                    ->get()
                    ->pluck('categories')
                    ->flatten()
                    ->pluck('id')
                    ->unique()
                    ->toArray();
                $applicableCategories = $this->categories()->whereIn('categories.id', $categoryIds)->exists();
                if (!$applicableCategories) {
                    return false;
                }
            }

            if ($this->offer_type === 'brand') {
                $productIds = collect($cartItems)->pluck('product_id')->toArray();
                $brandIds = Product::whereIn('id', $productIds)->pluck('brand_id')->filter()->unique()->toArray();
                $applicableBrands = $this->brands()->whereIn('brands.id', $brandIds)->exists();
                if (!$applicableBrands) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check if user is a first-time buyer
     */
    public function isFirstTimeBuyer(User $user): bool
    {
        return !$user->orders()->exists();
    }

    /**
     * Check if user is a repeat customer
     */
    public function isRepeatCustomer(User $user): bool
    {
        return $user->orders()->count() > 0;
    }
}
