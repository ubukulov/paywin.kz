<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\User;
use App\Models\UserGift;
use App\Enums\PromotionEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PlatformPromotionService
{
    /**
     * Запустить проверку и выдачу глобальных акций платформы
     *
     * @param User $user — Кто участвует
     * @param string $eventType — Событие (registration, purchase)
     * @param Model|null $source — К какому объекту привязать (Order или сама модель User при регистрации)
     */
    public function checkAndGiveGifts(User $user, string $eventType, ?Model $source = null)
    {
        // 1. Ищем все активные акции платформы по текущему типу события
        $promotions = Promotion::where('is_active', true)
            ->where('type', $eventType)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->get();

        foreach ($promotions as $promotion) {

            // Защита от дублирования: проверяем, не получал ли уже пользователь приз по этой акции
            $alreadyHasGift = UserGift::where('user_id', $user->id)
                ->where('source_type', $source ? get_class($source) : get_class($user))
                ->where('source_id', $source ? $source->id : $user->id)
                ->exists();

            if ($alreadyHasGift) {
                continue;
            }

            $gift = new UserGift();
            $gift->user_id = $user->id;
            $gift->share_id = null; // Поле null, так как это акция платформы, а не партнера!
            $gift->source_id = $source ? $source->id : $user->id;
            $gift->source_type = $source ? get_class($source) : get_class($user);
            $gift->status = 'available';

            // 2. Логика в зависимости от типа награды ( reward_type )
            if ($promotion->reward_type === 'raffle') {

                // Сценарий: Участие в розыгрыше (Генерируем билет)
                $ticketNumber = 'PW-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);

                $gift->name = "Билет на розыгрыш: " . $promotion->title;
                $gift->data = [
                    'type' => 'raffle',
                    'promotion_id' => $promotion->id,
                    'ticket_number' => $ticketNumber
                ];

            } else {

                // Сценарий: Гарантированное (Выбираем случайный приз из списка призов JSON)
                $prizesList = $promotion->prizes ?? [];

                if (empty($prizesList)) {
                    continue; // Если админ не добавил призы в список, пропускаем
                }

                // Перемешиваем и берем один случайный приз
                $randomPrize = collect($prizesList)->random();
                $prizeName = $randomPrize['name'] ?? $randomPrize['id'] ?? 'Гарантированный приз';

                $gift->name = $prizeName;
                $gift->data = [
                    'type' => 'gift',
                    'promotion_id' => $promotion->id,
                    'prizes' => [
                        ['name' => $prizeName]
                    ]
                ];
            }

            $gift->save();
        }
    }
}
