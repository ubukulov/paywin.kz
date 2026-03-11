<?php

namespace App\Services;

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

        // 1. Поиск акции (только по названию, без парсинга ID агента)
        $share = Share::where('title', $promoCode)
            ->active() // Используем наш Scope из модели Share
            ->first();

        if (!$share) {
            throw new Exception('Промокод неактуален или не существует');
        }

        // 2. Валидация
        $this->validateActivation($user, $share);

        // 3. Применение в транзакции
        return DB::transaction(function () use ($share, $user, $promoCode) {

            // Блокируем строку акции для защиты от одновременных нажатий
            $lockedShare = Share::where('id', $share->id)->lockForUpdate()->first();
            $limit = (int)($lockedShare->count ?? 0);

            if ($limit > 0 && $lockedShare->used_count >= $limit) {
                throw new Exception('Лимит активаций этого промокода исчерпан');
            }

            // Начисление бонуса (если тип "Деньги")
            $bonusAmount = (float)($lockedShare->data['size'] ?? 0);

            if ($bonusAmount > 0) {
                // Начисляем пользователю
                $user->changeBalance(
                    $bonusAmount,
                    TransactionEnum::PROMOCODE, // Используем наш Enum
                    $lockedShare,
                    "Активация промокода {$promoCode}"
                );

                // Списываем у партнера
                $partner = $lockedShare->partner;
                if ($partner) {
                    $partner->changeBalance(
                        -$bonusAmount,
                        TransactionEnum::PROMO_PAYOUT, // Добавь этот тип в Enum
                        $user,
                        "Списание за активацию кода клиентом"
                    );
                }
            }

            // Увеличиваем счетчик использований
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
