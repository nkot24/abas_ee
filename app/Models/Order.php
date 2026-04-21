<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'items', 'total', 'first_name', 'last_name', 'email',
        'phone', 'address', 'city', 'postal_code', 'country',
        'payment_method', 'status', 'payment_id',
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
    ];
}
