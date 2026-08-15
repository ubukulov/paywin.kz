<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'partner_id',
        'warehouse_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'price',
        'total',
        'is_preorder',
        'estimated_delivery_at',
        'delivered_at',
    ];

    /**
     * Автоматическое приведение типов
     */
    protected $casts = [
        'is_preorder'           => 'boolean',
        'estimated_delivery_at' => 'datetime',
        'delivered_at'          => 'datetime',
        'price'                 => 'float',
        'total'                 => 'float',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(PartnerWarehouse::class, 'warehouse_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
