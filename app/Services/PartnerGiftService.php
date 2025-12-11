<?php

namespace App\Services;

use App\Models\PartnerGiftRule;
use App\Models\PartnerGiftAllocation;

class PartnerGiftService
{
    public function getAvailableGiftsForUser(int $userId, float $orderTotal)
    {
        // 1. Получаем все правила, подходящие по сумме
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

            // 2. Проверяем лимит max_per_user
            $usedCount = PartnerGiftAllocation::where('user_id', $userId)
                ->where('partner_gift_id', $rule->partner_gift_id)
                ->count();

            if ($rule->max_per_user !== null && $usedCount >= $rule->max_per_user) {
                continue;
            }

            // 3. Проверяем шанс (0% исключаем)
            if ($rule->chance <= 0) {
                continue;
            }

            $candidates[] = [
                'rule'  => $rule,
                'chance' => $rule->chance,
            ];
        }

        // 4. Нет допустимых правил
        if (empty($candidates)) {
            return null;
        }

        // 5. Выбираем подарок по вероятности
        $totalChance = array_sum(array_column($candidates, 'chance'));
        $rand = mt_rand(1, $totalChance);
        $current = 0;

        foreach ($candidates as $item) {
            $current += $item['chance'];

            if ($rand <= $current) {
                return $item['rule']->partnerGift; // 🎁 возвращаем выигранный подарок
            }
        }

        return null; // теоретически не случится, но оставляем на всякий случай
    }
}
