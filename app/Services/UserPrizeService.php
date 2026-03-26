<?php

namespace App\Services;

use App\Enums\UserGiftEnum;
use App\Models\UserGift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserPrizeService
{
    public function getMyPrizes()
    {
        return Auth::user()->gifts()
            ->with(['share.partner.partnerProfile', 'source'])
            ->latest()
            ->get();
    }

    public function activate($prizeId): array
    {
        $user = auth()->user();

        // Ищем подарок именно этого пользователя
        $gift = UserGift::where('id', $prizeId)
            ->where('user_id', $user->id)
            ->where('status', 'available') //
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Если это физический подарок — генерируем код подтверждения или меняем статус на "claimed"
            $gift->update([
                'status' => UserGiftEnum::CLAIMED->value,
                'data' => array_merge($gift->data ?? [], [
                    'activated_at' => now()->toDateTimeString(),
                ])
            ]);

            DB::commit();

            return [
                'message' => 'Подарок активирован!',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Ошибка активания подарка: ' . $e->getMessage());
        }
    }
}
