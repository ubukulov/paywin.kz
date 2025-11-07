<?php

namespace App\Models;

use App\Http\Middleware\Partner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PartnerGift extends Model
{
    use HasFactory;

    protected $table = 'partner_gifts';
    protected $fillable = [
        'partner_id',
        'title',
        'description',
        'type',
        'partner',
        'payload',
        'active',
    ];

    public function partner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
