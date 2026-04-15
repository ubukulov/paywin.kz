<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Promocode extends Model
{
    protected $fillable = [
        'agent_id', 'share_id', 'code'
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function share(): BelongsTo
    {
        return $this->belongsTo(Share::class, 'share_id');
    }

    public function isValid(): bool
    {
        $parent = $this->share;
        return $parent && $parent->isActive();
    }
}
