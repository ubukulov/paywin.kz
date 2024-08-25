<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'cnt', 'promo', 'size', 'from_order', 'to_order', 'c_winning', 'from_date', 'to_date',
        'max_sum'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getClients()
    {
        $clients = Prize::where(['share_id' => $this->id, 'status' => 'got'])->get();
        return count($clients);
    }

    // Получить остаток
    public function getRemainder()
    {
        $clients = Prize::where(['share_id' => $this->id, 'status' => 'got'])->get();
        return $this->cnt - count($clients);
    }

    public function getProfit()
    {
        $clients = Prize::where(['prizes.share_id' => $this->id, 'prizes.status' => 'got', 'payments.pg_status' => 'ok'])
            ->join('payments', 'payments.id', '=', 'prizes.payment_id')
            ->get();

        return $clients->sum('amount');
    }
}
