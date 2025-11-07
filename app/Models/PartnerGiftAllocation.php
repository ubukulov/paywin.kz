<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PartnerGiftAllocation extends Model
{
    use HasFactory;

    protected $table = 'partner_gift_allocations';

    protected $fillable = [
        'partner_gift_id',
        'order_id',
        'user_id',
        'status',
        'meta',
    ];

    public function partnerGift(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PartnerGift::class, 'partner_gift_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
