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
    public function activate(User $user, string $rawCode)
    {
        $promoCode = strtoupper(trim($rawCode));
        $agentId = null;

        // 1. Сначала ищем в таблице персональных промокодов агентов
        $agentPromo = Promocode::where('code', $promoCode)->first();

        if ($agentPromo) {
            $share = $agentPromo->share;
            $agentId = $agentPromo->agent_id;
        } else {
            // 2. Если не нашли у агентов, ищем в базовых акциях партнеров
            // Используем поле 'code' (или 'title', как у тебя в базе)
            $share = Share::where('code', $promoCode)
                ->orWhere('title', $promoCode)
                ->active()
                ->first();
        }

        if (!$share) {
            throw new Exception('Промокод неактуален или не существует');
        }

        // 3. Валидация (нельзя свой, нельзя дважды)
        $this->validateActivation($user, $share);

        return DB::transaction(function () use ($share, $user, $promoCode, $agentId) {
            $lockedShare = Share::where('id', $share->id)->lockForUpdate()->first();

            // Проверка лимита (isActive уже проверяет это, но lock надежнее)
            if ($lockedShare->count > 0 && $lockedShare->used_count >= $lockedShare->count) {
                throw new Exception('Лимит активаций этого промокода исчерпан');
            }

            // --- НОВОЕ: Привязка реферала ---
            // Если код был агентским, и у юзера еще нет реферера — привязываем
            if ($agentId && !Referral::where(['user_id' => $user->id])->exists()) {
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
        // Нельзя активировать свой же код (если юзер - партнер этой акции)
        if ($share->partner_id === $user->id) {
            throw new Exception('Вы не можете активировать собственный промокод');
        }

        // Проверка: использовал ли юзер этот промокод ранее через транзакции
        $alreadyUsed = $user->transactions()
            ->where('type', TransactionEnum::PROMOCODE->value)
            ->where('source_id', $share->id)
            ->where('source_type', Share::class)
            ->exists();

        if ($alreadyUsed) {
            throw new Exception('Вы уже активировали этот промокод');
        }
    }

    protected function getSuccessMessage(Share $share): string
    {
        // Логика сообщения на основе типа бонуса из UI
        // В форме это кнопки: Скидка, Деньги, Подарок
        return match ($share->type) {
            'money'    => "Бонус успешно начислен на ваш баланс",
            'discount' => "Скидка будет применена при вашей следующей покупке",
            'gift'     => "Подарок добавлен в ваш профиль",
            default    => "Промокод успешно активирован"
        };
    }
}
