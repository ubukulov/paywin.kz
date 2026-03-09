<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerImage extends Model
{
    protected $table = 'partner_images';

    protected $fillable = [
        'partner_id', 'image'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}
