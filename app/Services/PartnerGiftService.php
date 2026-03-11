<?php

namespace App\Services;

use App\Enums\TransactionEnum;
use App\Enums\UserDiscountEnum;
use App\Enums\UserGiftEnum;
use App\Models\User;
use App\Models\Share;
use App\Models\UserDiscount;
use App\Models\UserGift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PartnerGiftService
{
    /**
     * Основной метод розыгрыша приза после оплаты
     * * @param User $user — Покупатель
     * @param User $partner — Владелец бизнеса
     * @param float $amount — Сумма чека
     * @param Model $trigger — Объект-триггер (Payment или Transaction)
     */
    public function checkAndAssignPrize(User $user, User $partner, float $amount, Model $trigger)
    {
        // 1. Поиск подходящих акций по сумме чека и лимитам
        $eligibleShares = Share::where('partner_id', $partner->id)
            ->where('type', '!=', 'promocode') // Только призы (gift, discount, cashback)
            ->active()
            ->get()
            ->filter(function ($share) use ($amount) {
                $fromOrder = (float)($share->data['from_order'] ?? 0);
                // Подходит, если сумма чека >= минимальной и лимит не исчерпан
                return $amount >= $fromOrder && ($share->count == 0 || $share->used_count < $share->count);
            });

        if ($eligibleShares->isEmpty()) return null;

        // 2. Алгоритм вероятности (Roll the dice)
        $wonShare = null;
        // Сортируем от самых редких (маленький c_winning) к частым
        foreach ($eligibleShares->sortBy('data.c_winning') as $share) {
            $chance = (int)($share->data['c_winning'] ?? 0);

            // Если "кубик" (1-100) попал в диапазон шанса — это выигрыш
            if (mt_rand(1, 100) <= $chance) {
                $wonShare = $share;
                break; // Выигрывает только один (самый редкий из сработавших)
            }
        }

        if (!$wonShare) return null;

        // 3. Атомарная фиксация выигрыша
        return DB::transaction(function () use ($user, $partner, $wonShare, $trigger) {
            $lockedShare = Share::where('id', $wonShare->id)->lockForUpdate()->first();

            // Повторная проверка лимита внутри блокировки БД
            if ($lockedShare->count > 0 && $lockedShare->used_count >= $lockedShare->count) {
                return null;
            }

            // Распределение данных по твоим критериям
            switch ($lockedShare->type) {
                case 'gift': // КРИТЕРИЙ 1: Физический подарок
                    $this->savePhysicalGift($user, $lockedShare, $trigger);
                    break;

                case 'discount': // КРИТЕРИЙ 2: Скидка
                    $this->saveDiscount($user, $lockedShare, $trigger);
                    break;

                case 'cashback': // КРИТЕРИЙ 3: Кешбэк
                    $this->applyCashback($user, $partner, $lockedShare, $trigger);
                    break;
            }

            // Увеличиваем общий счетчик использований акции
            $lockedShare->increment('used_count');

            return $lockedShare;
        });
    }

    protected function savePhysicalGift(User $user, Share $share, Model $trigger): void
    {
        UserGift::create([
            'user_id'     => $user->id,
            'share_id'    => $share->id,
            'name'        => $share->title,
            'status'      => UserGiftEnum::AVAILABLE->value, // Ожидает выдачи партнером
            'valid_until' => now()->addDays(30),
            'source_type' => get_class($trigger),
            'source_id'   => $trigger->id,
            'data'        => $share->data, // Сохраняем весь контекст акции
        ]);
    }

    protected function saveDiscount(User $user, Share $share, Model $trigger): void
    {
        UserDiscount::create([
            'user_id'     => $user->id,
            'share_id'    => $share->id,
            'percent'     => (int)($share->data['size'] ?? 0),   // Размер скидки %
            'amount'      => (float)($share->data['max_sum'] ?? 0), // Макс. сумма скидки
            'status'      => UserDiscountEnum::ACTIVE->value,
            'valid_until' => now()->addMonth(),
            'source_type' => get_class($trigger),
            'source_id'   => $trigger->id,
            'data'        => $share->data,
        ]);
    }

    protected function applyCashback(User $user, User $partner, Share $share, Model $trigger): void
    {
        $cashbackAmount = (float)($share->data['size'] ?? 0);

        // Используем твой метод changeBalance для мгновенного зачисления денег
        $user->changeBalance(
            $cashbackAmount,
            TransactionEnum::CASHBACK,
            $share,
            "Кешбэк за покупку у {$partner->partnerProfile?->company}",
            [
                'trigger_id' => $trigger->id,
                'trigger_type' => get_class($trigger)
            ]
        );
    }
}
