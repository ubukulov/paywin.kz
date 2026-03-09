<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'balance_before',
        'balance_after',
        'description',
        'source_type',
        'source_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeNameAttribute()
    {
        return [
            'cashback'        => 'Кешбэк за покупку',
            'withdrawal'      => 'Вывод средств',
            'referral_income' => 'Реферальный бонус',
            'sale_income'     => 'Доход от продажи',
            'deposit'         => 'Пополнение баланса',
            'refund'          => 'Возврат средств',
        ][$this->type] ?? 'Операция';
    }
}
