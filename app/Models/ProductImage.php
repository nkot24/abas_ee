<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'is_main',
        'sort_order',
    ];

    protected $casts = [
        'is_main'    => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['url'];

    protected static function booted(): void
    {
        static::saved(function (ProductImage $image) {
            if ($image->is_main) {
                static::where('product_id', $image->product_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_main' => false]);
            }
        });
    }

    public function getUrlAttribute(): string
    {
        return '/storage/' . $this->path;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
