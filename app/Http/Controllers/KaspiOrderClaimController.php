<?php

namespace App\Http\Controllers;

use App\Enums\OrderEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KaspiOrderClaimController extends Controller
{
    private string $baseUrl = 'https://kaspi.kz/shop/api/v2';

    public function claimOrder(Request $request)
    {
        $request->validate([
            'kaspi_code' => 'required|string',
        ]);

        $kaspiCode = trim($request->input('kaspi_code'));
        $user = $request->user();

        // 1. Проверяем дубликат заказа
        $alreadyExists = Order::where('kaspi_code', $kaspiCode)
            ->orWhere('data->kaspi_code', $kaspiCode)
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'success' => false,
                'message' => 'Этот номер заказа Kaspi уже зарегистрирован в системе.'
            ], 422);
        }

        $kaspiToken = config('services.kaspi.token');

        if (!$kaspiToken) {
            Log::error('Kaspi API Token not configured in services.kaspi.token');
            return response()->json([
                'success' => false,
                'message' => 'Ошибка конфигурации Kaspi API на сервере.'
            ], 500);
        }

        // 2. Запрос в Kaspi API (14 дней)
        $fromDate = now()->subDays(14)->timestamp * 1000;
        $toDate = now()->timestamp * 1000;

        $kaspiResponse = Http::withHeaders([
            'Content-Type' => 'application/vnd.api+json',
            'X-Auth-Token' => $kaspiToken,
        ])->get("{$this->baseUrl}/orders", [
            'filter' => [
                'orders' => [
                    'code' => $kaspiCode,
                    'creationDate' => [
                        '$ge' => $fromDate,
                        '$le' => $toDate
                    ]
                ]
            ]
        ]);

        if (!$kaspiResponse->successful()) {
            Log::error('Kaspi API Claim Error Response:', ['body' => $kaspiResponse->body()]);
            return response()->json([
                'success' => false,
                'message' => 'Не удалось проверить заказ в Kaspi API. Попробуйте чуть позже.'
            ], 500);
        }

        $ordersData = $kaspiResponse->json('data', []);

        if (empty($ordersData)) {
            return response()->json([
                'success' => false,
                'message' => 'Заказ с таким номером не найден в Kaspi. Проверьте номер.'
            ], 404);
        }

        $kaspiOrder = $ordersData[0];
        $kaspiOrderId = $kaspiOrder['id'];
        $totalPrice = $kaspiOrder['attributes']['totalPrice'] ?? 0;

        // 3. Получение товарных позиций
        $entriesResponse = Http::withHeaders([
            'Content-Type' => 'application/vnd.api+json',
            'X-Auth-Token' => $kaspiToken,
        ])->get("{$this->baseUrl}/orders/{$kaspiOrderId}/entries");

        $entries = $entriesResponse->json('data', []);

        // 4. Транзакция записи заказа и финансовой транзакции
        try {
            DB::transaction(function () use ($user, $kaspiOrder, $kaspiCode, $entries, $totalPrice) {

                // А. Создаем заказ
                $order = Order::create([
                    'user_id'          => $user->id,
                    'city_id'          => $user->city_id ?? null,
                    'subtotal'         => $totalPrice,
                    'discount'         => 0,
                    'shipping_cost'    => 0,
                    'total'            => $totalPrice,
                    'status'           => OrderEnum::PAID->value ?? 'paid',
                    'payment_method'   => 'kaspi',
                    'shipping_method'  => 'kaspi_delivery',
                    'shipping_address' => 'Заказ оформлен в Kaspi Shop',
                    'data'             => [
                        'kaspi_id'       => $kaspiOrder['id'],
                        'kaspi_code'     => $kaspiCode,
                        'customer_name'  => $kaspiOrder['attributes']['customer']['name'] ?? $user->name,
                        'customer_phone' => $kaspiOrder['attributes']['customer']['cellPhone'] ?? null,
                    ]
                ]);

                // Б. Создаем позиции заказа (order_items)
                foreach ($entries as $entry) {
                    $offerId   = $entry['attributes']['offerId'] ?? null;
                    $quantity  = $entry['attributes']['quantity'] ?? 1;
                    $price     = $entry['attributes']['basePrice'] ?? 0;
                    $itemTotal = $price * $quantity;

                    $product = Product::where('sku', $offerId)->first();

                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $product?->id,
                        'partner_id'   => $product?->partner_id,
                        'product_name' => $product?->name ?? "Товар Kaspi (#{$offerId})",
                        'product_sku'  => $offerId,
                        'quantity'     => $quantity,
                        'price'        => $price,
                        'total'        => $itemTotal,
                    ]);

                    if ($product && isset($product->quantity)) {
                        $product->decrement('quantity', $quantity);
                    }
                }

                // В. ФИКСИРУЕМ ФИНАНСОВУЮ ТРАНЗАКЦИЮ (Transaction)
                $userBalance = $user->balance ?? 0; // Текущий баланс пользователя

                Transaction::create([
                    'user_id'        => $user->id,
                    'amount'         => $totalPrice,
                    'type'           => 'kaspi_claim', // или 'purchase' / 'deposit' в зависимости от вашего Enum
                    'balance_before' => $userBalance,
                    'balance_after'  => $userBalance, // Так как покупка была внешней через Kaspi
                    'description'    => "Регистрация покупки из Kaspi (Заказ #{$kaspiCode})",
                    'source_type'    => Order::class,
                    'source_id'      => $order->id,
                    'data'           => [
                        'kaspi_code' => $kaspiCode,
                        'kaspi_id'   => $kaspiOrder['id'],
                        'provider'   => 'kaspi_shop',
                    ],
                ]);

                // Г. Запуск промо-акций и выдачи призов/подарков Paywin
                $platformPromotionService = app(\App\Services\PlatformPromotionService::class);
                if ($platformPromotionService) {
                    $platformPromotionService->checkAndGiveGifts($user, 'purchase', $order);
                }
            });

            return response()->json([
                'success'    => true,
                'message'    => 'Заказ успешно привязан! Бонус зарегистрирован.',
                'order_code' => $kaspiCode
            ]);

        } catch (\Throwable $e) {
            Log::error('Kaspi Order Claim Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении заказа на сервере.'
            ], 500);
        }
    }
}
