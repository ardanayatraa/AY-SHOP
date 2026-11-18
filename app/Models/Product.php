<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'sizes',
        'colors',
        'material',
        'image',
        'is_featured',
        'is_active',
        'sold_count',
    ];

    protected function casts(): array
    {
        return [
            'sizes' => 'array',
            'colors' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'integer',
            'discount_price' => 'integer',
            'stock' => 'integer',
            'sold_count' => 'integer',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getEffectivePriceAttribute(): int
    {
        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return $this->discount_price;
        }

        return $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
        }

        return 0;
    }
}

