<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\Share;
use App\Models\User;
use App\Models\UserBalance;
use Exception;
use Illuminate\Support\Facades\DB;

class PromoService
{
    public function activate(User $user, string $rawCode)
    {
        $promoCode = strtoupper(trim($rawCode));
        $promoPartner = preg_replace('/[^A-Z]/', '', $promoCode);
        $agentId = (int) filter_var($promoCode, FILTER_SANITIZE_NUMBER_INT);

        // 1. Поиск акции
        $share = Share::where('title', $promoPartner)
            ->where('from_date', '<=', now())
            ->where('to_date', '>=', now())
            ->first();

        if (!$share) {
            throw new Exception('Промокод неактуален или не существует');
        }

        // 2. Валидация
        $this->validateActivation($user, $agentId, $share);

        // 3. Логика применения в зависимости от типа
        return DB::transaction(function () use ($share, $agentId, $user, $promoCode) {

            // Атомарное уменьшение лимита
            $affected = Share::where('id', $share->id)
                ->where('cnt', '>', 0)
                ->decrement('cnt');

            if (!$affected) {
                throw new Exception('Лимит активаций этого промокода исчерпан');
            }

            // Создаем реферальную связь, если её еще нет (First Click)
            if ($agentId !== 0 && !Referral::where('client_id', $user->id)->exists()) {
                Referral::create([
                    'agent_id'   => $agentId,
                    'share_id'   => $share->id,
                    'client_id'  => $user->id,
                    'promo_code' => $promoCode,
                    'source'     => 'promo',
                ]);
            }

            // Начисление сущности (Деньги или метка активации для Скидок/Подарков)
            UserBalance::create([
                'user_id'      => $user->id,
                'promocode_id' => $share->id,
                'type'         => 'promocode',
                'amount'       => ($share->promo === 'money') ? $share->size : 0,
                'status'       => ($share->promo === 'money') ? 'ok' : 'active',
            ]);

            return match ($share->promo) {
                'money'    => "Бонус {$share->size} начислен на ваш баланс",
                'discount' => "Скидка {$share->size}% будет применена при оформлении заказа",
                'gift'     => "Подарок будет добавлен к вашему следующему заказу",
                default    => "Промокод успешно активирован"
            };
        });
    }

    protected function validateActivation(User $user, $agentId, $share)
    {
        if ($agentId !== 0 && $agentId === $user->id) {
            throw new Exception('Нельзя использовать собственный промокод');
        }

        // Проверка: использовал ли юзер этот промокод ранее
        $alreadyUsed = UserBalance::where('user_id', $user->id)
            ->where('promocode_id', $share->id)
            ->exists();

        if ($alreadyUsed) {
            throw new Exception('Вы уже активировали этот промокод');
        }
    }
}
