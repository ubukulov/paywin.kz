<?php
namespace App\Services;

use App\Models\PartnerGiftRule;
use App\Models\PartnerGiftAllocation;
use Illuminate\Support\Facades\DB;

class RewardsService
{
    /**
     * Обрабатывает заказ: выделяет партнерские подарки и начисляет билеты в активные рейфлы.
     *
     * @param \App\Models\Order $order
     * @return array info about allocations and tickets
     */
    public function processOrder(\App\Models\Order $order): array
    {
        $allocations = [];
        $ticketsInfo = [];

        $total = (float) $order->total;
        $userId = $order->user_id;

        DB::transaction(function() use ($order, $total, $userId, &$allocations, &$ticketsInfo) {
            // 1) Отбирать правила подходящие по порогу
            $rules = PartnerGiftRule::with('gift')->where('min_order_total','<=',$total)
                ->where(function($q) use ($total){
                    $q->whereNull('max_order_total')->orWhere('max_order_total','>=',$total);
                })->get();

            foreach($rules as $rule) {
                // ограничение на число подарков на пользователя
                if($rule->max_per_user) {
                    $given = PartnerGiftAllocation::where('partner_gift_id',$rule->partner_gift_id)
                        ->where('user_id',$userId)->count();
                    if($given >= $rule->max_per_user) continue;
                }

                $chance = (float) $rule->chance;
                $rand = mt_rand(1,10000)/100; // 0.01 precision
                if($rand <= $chance) {
                    // выдаём подарок
                    $allocation = PartnerGiftAllocation::create([
                        'partner_gift_id' => $rule->partner_gift_id,
                        'order_id' => $order->id,
                        'user_id' => $userId,
                        'status' => 'allocated',
                        'meta' => ['awarded_at' => now()->toDateTimeString()],
                    ]);
                    $allocations[] = $allocation->load('gift');
                }
            }

            // 2) Начисление билетов для активных paywin_raffles
            /*$activeRaffles = PaywinRaffle::where('starts_at','<=',now())->where('ends_at','>=',now())->get();
            foreach($activeRaffles as $raffle){
                // правило: tickets_per_1000 -> 1 билет за каждые 1000 KZT
                $per = max(1, (int)$raffle->tickets_per_1000);
                $tickets = (int) floor($total / 1000) * $per;
                if($tickets <= 0) continue;
                $entry = PaywinRaffleEntry::create([
                    'raffle_id' => $raffle->id,
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'tickets' => $tickets,
                    'meta' => ['total' => $total],
                ]);
                $ticketsInfo[] = ['raffle_id'=>$raffle->id,'tickets'=>$tickets];
            }*/
        });

        return ['allocations'=>$allocations,'tickets'=>$ticketsInfo];
    }
}
