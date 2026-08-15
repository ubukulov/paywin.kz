<?php

namespace App\Services;

use App\Models\Promocode;
use App\Models\Referral;
use App\Models\Share;
use App\Models\User;
use App\Enums\TransactionEnum;
use Exception;
use Illuminate\Support\Facades\DB;

class PromoService
{
    public function activate(User $user, string $rawCode): string
    {
        $promoCode = strtoupper(trim($rawCode));
        $agentId = null;

        // 1. Сначала ищем в таблице персональных промокодов агентов
        $agentPromo = Promocode::with('share')->where('code', $promoCode)->first();

        if ($agentPromo) {
            $share = $agentPromo->share;
            $agentId = $agentPromo->agent_id;
        } else {
            // 2. Если не нашли у агентов, ищем в базовых акциях партнеров
            $share = Share::where('code', $promoCode)
                ->orWhere('title', $promoCode)
                ->active()
                ->first();
        }

        if (!$share) {
            throw new Exception('Промокод неактуален или не существует');
        }

        // 3. Строгая валидация (нельзя свой, нельзя повторно от этого же партнера)
        $this->validateActivation($user, $share);

        return DB::transaction(function () use ($share, $user, $promoCode, $agentId) {
            $lockedShare = Share::where('id', $share->id)->lockForUpdate()->first();

            // Проверка лимита
            if ($lockedShare->count > 0 && $lockedShare->used_count >= $lockedShare->count) {
                throw new Exception('Лимит активаций этого промокода исчерпан');
            }

            // --- Привязка реферала ---
            // Если код агентский и у пользователя еще нет реферера для этого партнера/акции
            if ($agentId && !Referral::where(['user_id' => $user->id, 'share_id' => $share->id])->exists()) {
                Referral::create([
                    'agent_id' => $agentId,
                    'share_id' => $share->id,
                    'user_id'  => $user->id,
                    'percent'  => $share->data['agent_percent'] ?? 10
                ]);
            }

            // Начисление бонуса
            $bonusAmount = (float)($lockedShare->data['size'] ?? 0);

            if ($bonusAmount > 0) {
                // Начисляем клиенту
                $user->changeBalance(
                    $bonusAmount,
                    TransactionEnum::PROMOCODE,
                    $lockedShare,
                    "Активация промокода {$promoCode}"
                );

                // Списываем у партнера (владельца акции)
                $partner = $lockedShare->partner;
                if ($partner) {
                    $partner->changeBalance(
                        -$bonusAmount,
                        TransactionEnum::PROMO_PAYOUT,
                        $user,
                        "Списание за активацию кода клиентом"
                    );
                }
            }

            $lockedShare->increment('used_count');

            return $this->getSuccessMessage($lockedShare);
        });
    }

    protected function validateActivation(User $user, Share $share): void
    {
        // 1. Нельзя активировать свой же код (если юзер - партнер этой акции)
        if ($share->partner_id === $user->id) {
            throw new Exception('Вы не можете активировать собственный промокод');
        }

        // 2. Проверка: активировал ли юзер УЖЕ какую-либо акцию этого конкретного ПАРТНЕРА
        // Ищем все акции (shares), принадлежащие данному партнеру
        $partnerShareIds = Share::where('partner_id', $share->partner_id)->pluck('id');

        $alreadyUsedPartnerPromo = $user->transactions()
            ->where('type', TransactionEnum::PROMOCODE->value)
            ->where('source_type', Share::class)
            ->whereIn('source_id', $partnerShareIds)
            ->exists();

        if ($alreadyUsedPartnerPromo) {
            throw new Exception('Вы уже активировали промокод этого магазина/партнера');
        }
    }

    protected function getSuccessMessage(Share $share): string
    {
        return match ($share->type) {
            'money'    => "Бонус успешно начислен на ваш баланс",
            'discount' => "Скидка будет применена при вашей следующей покупке",
            'gift'     => "Подарок добавлен в ваш профиль",
            default    => "Промокод успешно активирован"
        };
    }
}
