<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerAddress extends Model
{
    protected $table = 'partner_address';

    protected $fillable = [
        'partner_id', 'address', 'latitude', 'longitude'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}
