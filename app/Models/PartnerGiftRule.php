<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PartnerGiftRule extends Model
{
    use HasFactory;

    protected $table = 'partner_gift_rules';

    protected $fillable = [
        'partner_gift_id',
        'min_order_total',
        'max_order_total',
        'chance',
        'max_per_user',
    ];

    public function partnerGift(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PartnerGift::class, 'partner_gift_id');
    }
}
