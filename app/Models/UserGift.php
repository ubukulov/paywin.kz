<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserGift extends Model
{
    protected $table = 'user_gifts';

    protected $fillable = [
        'user_id',
        'share_id',
        'name',
        'status',
        'valid_until',
        'source_type',
        'source_id',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
        'valid_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function share(): BelongsTo
    {
        return $this->belongsTo(Share::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
