<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'category_id',
        'base_price',
        'sale_price',
        'status',
        'is_featured',
        'is_customizable',
        'customization_options',
        'measurements',
        'main_image',
        'gallery_images',
        'view_count',
    ];

    protected $casts = [
        'customization_options' => 'array',
        'measurements' => 'array',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_customizable' => 'boolean',
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the current price (sale price if available, otherwise base price).
     */
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->base_price;
    }

    /**
     * Check if the product is on sale.
     */
    public function isOnSale(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->base_price;
    }
}
