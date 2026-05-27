<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    public $timestamps = false;

    protected $fillable = ['country_group', 'method', 'weight_up_to', 'price', 'sort'];

    protected function casts(): array
    {
        return [
            'weight_up_to' => 'float',
            'price'        => 'float',
            'sort'         => 'integer',
        ];
    }
}
