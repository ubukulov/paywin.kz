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

        // Бизнес-валидация
        $this->validatePromo($user, $agentId, $promoPartner);

        $share = Share::where('title', $promoPartner)
            ->where('from_date', '<=', now())
            ->where('to_date', '>=', now())
            ->first();

        if (!$share) {
            throw new Exception('Промокод неактуален');
        }

        // Логика активации
        if ($share->promo === 'money') {
            return $this->applyMoneyPromo($user, $agentId, $promoCode, $share);
        }

        if ($share->type === 'discount') {
            throw new Exception('Промокод будет применён при оплате');
        }

        throw new Exception('Неизвестный тип промокода');
    }

    protected function validatePromo(User $user, $agentId, $promoPartner)
    {
        if (!$agentId || $agentId === $user->id) {
            throw new Exception('Некорректный промокод');
        }

        $agent = User::find($agentId);
        if (!$agent || $agent->user_type !== 'user') {
            throw new Exception('Агент не найден');
        }

        if (Referral::where('client_id', $user->id)->exists()) {
            throw new Exception('Вы уже привязаны к агенту');
        }
    }

    protected function applyMoneyPromo($user, $agentId, $promoCode, $share)
    {
        return DB::transaction(function () use ($share, $agentId, $user, $promoCode) {
            Referral::create([
                'agent_id'   => $agentId,
                'client_id'  => $user->id,
                'promo_code' => $promoCode,
                'source'     => 'promo',
            ]);

            UserBalance::create([
                'user_id'      => $user->id,
                'promocode_id' => $share->id,
                'type'         => 'promocode',
                'amount'       => $share->size,
                'status'       => 'ok',
            ]);

            return true;
        });
    }
}
