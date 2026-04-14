<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Share extends Model
{
    protected $fillable = [
        'partner_id',
        'type',
        'title',
        'code',
        'from_date',
        'to_date',
        'count',
        'used_count',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
        'from_date' => 'datetime',
        'to_date' => 'datetime',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function getClients()
    {
        $clients = UserGift::where(['share_id' => $this->id, 'status' => 'got'])->get();
        return count($clients);
    }

    // Получить остаток
    public function getRemainder()
    {
        $clients = UserGift::where(['share_id' => $this->id, 'status' => 'got'])->get();
        return $this->cnt - count($clients);
    }

    public function getProfit()
    {
        $clients = UserGift::where(['prizes.share_id' => $this->id, 'prizes.status' => 'got', 'payments.pg_status' => 'ok'])
            ->join('payments', 'payments.id', '=', 'prizes.payment_id')
            ->get();

        return $clients->sum('amount');
    }

    public function scopeActualPromocodes($query)
    {
        return $query
            ->where('type', 'promocode')
            ->whereNotNull('title')
            ->where(function ($q) {
                $q->whereNull('from_date')
                    ->orWhere('from_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('to_date')
                    ->orWhere('to_date', '>=', now());
            });
    }

    /**
     * Заготовка запроса для выбора только активных акций по датам
     */
    public function scopeActive($query)
    {
        $now = now();

        return $query->where('from_date', '<=', $now)
            ->where('to_date', '>=', $now);
    }

    public function getMyPromoLink(): string
    {
        return route('referral.link', ['code' => $this->title . Auth::id()]);
    }

    public function getRealAgentPercentAttribute()
    {
        $partnerPercent = $this->data['agent_percent']; // Например, 10
        $bankFee = 3; // Комиссия TipTopPay
        $agentShareOfNet = 0.7; // Доля агента (70%)

        // Формула: (10 - 3) * 0.7 = 4.9
        $result = ($partnerPercent - $bankFee) * $agentShareOfNet;

        // Возвращаем 0, если партнер дает меньше 3%, чтобы не было минуса
        return max(0, $result);
    }
}
