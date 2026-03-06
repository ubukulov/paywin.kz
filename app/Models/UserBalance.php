<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    protected $table = 'user_balances';

    protected $fillable = [
        'user_id', 'pg_payment_id', 'promocode_id', 'payment_id', 'type', 'amount', 'status'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function share()
    {
        return $this->belongsTo(Share::class, 'promocode_id');
    }

    public function getText(): string
    {
        if ($this->status == 'ok' && $this->type == 'promocode') return "Активация промокода";
        if ($this->status == 'ok' && $this->type == 'payment') return "Пополнение";
        if ($this->status == 'ok' && $this->type == 'cashback') return "Кешбек";
        if ($this->status == 'ok' && $this->type == 'referral') return "Доход от реферала";
        if ($this->status == 'withdraw') return "Вывод";

        return "Пополнение/Вывод";
    }
}
