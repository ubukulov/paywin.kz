<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'shipping_cost',
        'total',
        'status',
        'payment_method',
        'shipping_method',
        'shipping_address',
        'meta',
    ];
}
