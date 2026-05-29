<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Product extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'description',
        'description_en',
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
        'locker_eligible',
        'on_sale',
        'sale_price',
        'sale_percent',
    ];

    protected $casts = [
        'active'          => 'boolean',
        'locker_eligible' => 'boolean',
        'on_sale'         => 'boolean',
        'sale_price'      => 'decimal:2',
        'sale_percent'    => 'integer',
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

    protected static function booted(): void
    {
        static::saved(function (Product $product) {
            if ($product->name_en && !$product->wasChanged('name') && !$product->wasChanged('description')) {
                return;
            }
            try {
                $tr = new GoogleTranslate('en');
                $tr->setSource('et');
                $updates = [];
                if (!$product->name_en || $product->wasChanged('name')) {
                    $updates['name_en'] = $tr->translate($product->name);
                }
                if ($product->description && (!$product->description_en || $product->wasChanged('description'))) {
                    $updates['description_en'] = $tr->translate($product->description);
                }
                if ($updates) {
                    $product->updateQuietly($updates);
                }
            } catch (\Exception) {}
        });
    }

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
