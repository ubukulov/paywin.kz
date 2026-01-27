<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Referral extends Model
{
    protected $table = 'referrals';

    protected $fillable = [
        'agent_id', 'client_id', 'promo_code', 'source'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function agent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Количество активировавших клиентов
     */
    public function activatedCount(): int
    {
        return $this->where('promo_code', $this->promo_code)
            ->count();
    }

    public function getEarn(): int
    {
        $promoPartner = preg_replace('/[^A-Z]/', '', $this->promo_code);
        $share = Share::where(['title' => $promoPartner, 'promo' => 'discount'])->first();
        if (!$share) {
            return 0;
        }

        return UserBalance::where(['user_id' => Auth::id(), 'promocode_id' => $share->id, 'status' => 'ok', 'type' => 'payment'])->sum('amount');
    }
}
