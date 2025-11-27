<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'short_description',
        'long_description',
        'specification',
        'price',
        'selling_price',
        'category_id',
        'brand_id',
        'image',
        'stock_quantity',
        'is_active',
        'is_featured',
        'slug',
        'tax_rate',
        'tax_class_id',
        'enable_countdown_timer',
        'countdown_timer_end',
        'whatsapp_product_id',
        'is_synced_to_whatsapp',
        'whatsapp_synced_at',
        'whatsapp_sync_error',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'enable_countdown_timer' => 'boolean',
        'countdown_timer_end' => 'datetime',
        'is_synced_to_whatsapp' => 'boolean',
        'whatsapp_synced_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });

        static::updating(function (Product $product): void {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $index = 1;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$baseSlug}-{$index}";
            $index++;
        }

        return $slug;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category')->withTimestamps();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute')
            ->withPivot('attribute_value_id', 'custom_value')
            ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function getFormattedPriceAttribute()
    {
        return \App\Services\CurrencyService::format($this->price);
    }

    /**
     * Return the effective unit price (selling price if set, otherwise base price)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->selling_price !== null ? $this->selling_price : $this->price;
    }

    public function getFormattedEffectivePriceAttribute()
    {
        return \App\Services\CurrencyService::format($this->effective_price);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->selling_price !== null && $this->selling_price < $this->price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }
}
