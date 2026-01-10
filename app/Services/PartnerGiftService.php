<?php

namespace App\Services;

use App\Models\PartnerGiftRule;
use App\Models\PartnerGiftAllocation;

class PartnerGiftService
{
    public function getAvailableGiftForUser(int $userId, float $orderTotal)
    {
        // 1. Подходящие правила по сумме
        $rules = PartnerGiftRule::with('partnerGift')
            ->where('min_order_total', '<=', $orderTotal)
            ->where(function ($q) use ($orderTotal) {
                $q->whereNull('max_order_total')
                    ->orWhere('max_order_total', '>=', $orderTotal);
            })
            ->get();

        if ($rules->isEmpty()) {
            return null;
        }

        $candidates = [];

        foreach ($rules as $rule) {

            // 2. Лимит на пользователя
            $usedCount = PartnerGiftAllocation::where('user_id', $userId)
                ->where('partner_gift_id', $rule->partner_gift_id)
                ->count();

            if ($rule->max_per_user !== null && $usedCount >= $rule->max_per_user) {
                continue;
            }

            // 3. Исключаем нулевой шанс
            if ($rule->chance <= 0) {
                continue;
            }

            $candidates[] = [
                'rule'   => $rule,
                'chance' => $rule->chance,
            ];
        }

        // 4. Нет кандидатов
        if (empty($candidates)) {
            return null;
        }

        // 5. Розыгрыш одного подарка по весам
        $totalChance = array_sum(array_column($candidates, 'chance'));
        $rand = mt_rand(1, $totalChance);

        $current = 0;
        foreach ($candidates as $item) {
            $current += $item['chance'];

            if ($rand <= $current) {
                return $item['rule']->partnerGift; // 🎁 один победитель
            }
        }

        return null;
    }

    public function getAvailableGiftsForUser(float $orderTotal)
    {
        return PartnerGiftRule::where('min_order_total', '<=', $orderTotal)
                ->selectRaw('partner_gifts.*')
                ->join('partner_gifts', 'partner_gifts.id', '=', 'partner_gift_rules.partner_gift_id')
                ->get();
    }
}
