<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    protected $table = 'referrals';

    protected $fillable = [
        'agent_id',
        'share_id',
        'user_id',
        'percent',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function share(): BelongsTo
    {
        return $this->belongsTo(Share::class);
    }

    /**
     * Статистика: сколько человек пришло по этой акции к этому агенту
     */
    public function activatedCount(): int
    {
        return self::where('agent_id', $this->agent_id)
            ->where('share_id', $this->share_id)
            ->count();
    }

    /**
     * Статистика: сколько заработано денег по этой конкретной акции
     */
    public function getEarn(): float
    {
        // Ищем все транзакции типа referral_income, связанные с этим агентом
        // и через таблицу referrals (source) привязанные к этой акции
        return Transaction::where('user_id', $this->agent_id)
            ->where('type', 'referral_income')
            ->whereHasMorph('source', [self::class], function($query) {
                $query->where('share_id', $this->share_id);
            })
            ->sum('amount');
    }
}
