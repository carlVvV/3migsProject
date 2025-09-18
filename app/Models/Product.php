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
        'is_new_arrival',
        'is_new_design',
        'product_type',
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
        'is_new_arrival' => 'boolean',
        'is_new_design' => 'boolean',
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

    /**
     * Get the total stock quantity across all variants.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->variants()->sum('stock_quantity');
    }

    /**
     * Check if product is in stock.
     */
    public function isInStock(): bool
    {
        return $this->getTotalStockAttribute() > 0;
    }

    /**
     * Check if product stock is low.
     */
    public function isLowStock(int $threshold = 5): bool
    {
        $totalStock = $this->getTotalStockAttribute();
        return $totalStock > 0 && $totalStock <= $threshold;
    }

    /**
     * Get the stock status.
     */
    public function getStockStatusAttribute(): string
    {
        if (!$this->isInStock()) {
            return 'out_of_stock';
        }
        
        if ($this->isLowStock()) {
            return 'low_stock';
        }
        
        return 'in_stock';
    }

    /**
     * Check if product is a new arrival.
     */
    public function isNewArrival(): bool
    {
        return $this->is_new_arrival;
    }

    /**
     * Check if product is a new design.
     */
    public function isNewDesign(): bool
    {
        return $this->is_new_design;
    }

    /**
     * Get product type label.
     */
    public function getProductTypeLabelAttribute(): string
    {
        return ucfirst($this->product_type);
    }

    /**
     * Get status badges for display.
     */
    public function getStatusBadgesAttribute(): array
    {
        $badges = [];
        
        if ($this->is_new_arrival) {
            $badges[] = [
                'text' => 'New Arrival',
                'class' => 'bg-green-100 text-green-800'
            ];
        }
        
        if ($this->is_new_design) {
            $badges[] = [
                'text' => 'New Design',
                'class' => 'bg-blue-100 text-blue-800'
            ];
        }
        
        if ($this->is_featured) {
            $badges[] = [
                'text' => 'Featured',
                'class' => 'bg-purple-100 text-purple-800'
            ];
        }
        
        return $badges;
    }

    /**
     * Scope for in-stock products.
     */
    public function scopeInStock($query)
    {
        return $query->whereHas('variants', function ($q) {
            $q->where('stock_quantity', '>', 0);
        });
    }

    /**
     * Scope for out-of-stock products.
     */
    public function scopeOutOfStock($query)
    {
        return $query->whereDoesntHave('variants', function ($q) {
            $q->where('stock_quantity', '>', 0);
        });
    }
}
