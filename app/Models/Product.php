<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'badge',
        'material',
        'height',
        'length',
        'width',
        'thickness',
        'weight',
        'active',
    ];

    protected $casts = [
        'active'    => 'boolean',
        'price'     => 'decimal:2',
        'category'  => 'array',
        'height'    => 'integer',
        'length'    => 'integer',
        'width'     => 'integer',
        'thickness' => 'decimal:2',
        'weight'    => 'decimal:2',
    ];

    protected $appends = ['main_image_url'];
    protected $hidden  = ['mainImage'];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function mainImage(): HasOne
    {
        // Prefer the image marked as main; fall back to the first uploaded image.
        return $this->hasOne(ProductImage::class)->orderByDesc('is_main')->orderBy('sort_order')->orderBy('id');
    }

    public function getMainImageUrlAttribute(): ?string
    {
        if ($this->relationLoaded('mainImage') && $this->mainImage) {
            return $this->mainImage->url;
        }
        return null;
    }
}
