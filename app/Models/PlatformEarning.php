<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformEarning extends Model
{
    use HasFactory;

    protected $table = 'platform_earnings';

    protected $fillable = [
        'order_id',
        'partner_id',
        'agent_id',
        'gross_amount',
        'bank_fee_amount',
        'agent_fee_amount',
        'platform_net_amount',
        'type',
    ];

    /**
     * Автоматическое приведение типов для точной работы с финансами
     */
    protected $casts = [
        'gross_amount'        => 'float',
        'bank_fee_amount'     => 'float',
        'agent_fee_amount'    => 'float',
        'platform_net_amount' => 'float',
    ];

    /* ==========================================
     *  ОТНОШЕНИЯ (RELATIONSHIPS)
     * ========================================== */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
