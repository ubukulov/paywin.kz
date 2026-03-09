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

    public function getMyPromoLink(): string
    {
        return route('referral.link', ['code' => $this->title . Auth::id()]);
    }
}
