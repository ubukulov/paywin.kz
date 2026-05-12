<?php

namespace App\Models;

use App\Enums\PromotionEnum;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'title',
        'type',
        'scope',
        'reward_type',
        'prizes',
        'start_at',
        'end_at',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'prizes' => 'array',
        'type' => PromotionEnum::class,
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function gifts()
    {
        return $this->morphMany(UserGift::class, 'source');
    }
}
