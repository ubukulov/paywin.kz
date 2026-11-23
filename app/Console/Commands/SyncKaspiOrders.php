<?php

namespace App\Console\Commands;

use App\Enums\OrderEnum;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PlatformPromotionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncKaspiOrders extends Command
{
    protected $signature = 'kaspi:sync-orders {--hours=24 : За сколько последних часов проверять заказы}';
    protected $description = 'Автоматическая синхронизация заказов из Kaspi API и сопоставление с пользователями Paywin';

    private string $baseUrl = 'https://kaspi.kz/shop/api/v2';

    public function handle(): int
    {
        $kaspiToken = config('services.kaspi.token');
        if (!$kaspiToken) {
            $this->error('Не настроен services.kaspi.token');
            return self::FAILURE;
        }

        $hours = (int) $this->option('hours');
        $fromDate = now()->subHours($hours)->timestamp * 1000;
        $toDate = now()->timestamp * 1000;

        // Запрашиваем заказы из Kaspi API (берём оплаченные / принятые / завершённые, исключая отменённые)
        $response = Http::withHeaders([
            'Content-Type' => 'application/vnd.api+json',
            'X-Auth-Token' => $kaspiToken,
        ])->get("{$this->baseUrl}/orders", [
            'page' => [
                'number' => 0,
                'size'   => 100,
            ],
            'filter' => [
                'orders' => [
                    'creationDate' => [
                        '$ge' => $fromDate,
                        '$le' => $toDate,
                    ],
                ],
            ],
        ]);

        if (!$response->successful()) {
            Log::error('Kaspi Sync API Error', ['status' => $response->status(), 'body' => $response->body()]);
            $this->error('Ошибка запроса к Kaspi API: ' . $response->status());
            return self::FAILURE;
        }

        $orders = $response->json('data', []);
        $this->info('Получено заказов из Kaspi: ' . count($orders));

        $cities = City::all();
        $syncedCount = 0;

        foreach ($orders as $kaspiOrder) {
            $attributes = $kaspiOrder['attributes'] ?? [];
            $kaspiId    = $kaspiOrder['id'];
            $kaspiCode  = (string) ($attributes['code'] ?? '');
            $status     = $attributes['status'] ?? '';
            $state      = $attributes['state'] ?? '';

            // Пропускаем отменённые заказы и заказы без кода
            if (!$kaspiCode || in_array($status, ['CANCELLED', 'CANCELLING', 'RETURNED'], true) || $state === 'ARCHIVE') {
                // Примечание: если нужно учитывать уже выданные (COMPLETED), уберите проверку на ARCHIVE
                if ($status !== 'COMPLETED') {
                    continue;
                }
            }

            // 1. Проверяем, не привязан ли уже этот заказ
            if (Order::where('data->kaspi_code', $kaspiCode)->exists()) {
                continue;
            }

            // 2. Получаем состав заказа (entries), чтобы сверить товары и сумму
            $entriesResponse = Http::withHeaders([
                'Content-Type' => 'application/vnd.api+json',
                'X-Auth-Token' => $kaspiToken,
            ])->get("{$this->baseUrl}/orders/{$kaspiId}/entries");

            if (!$entriesResponse->successful()) {
                continue;
            }

            $entries = $entriesResponse->json('data', []);

            // 3. Определяем пользователя по имени, городу и сумме покупки
            $user = $this->matchUser($attributes, $entries, $cities);

            if (!$user) {
                $this->line("Заказ #{$kaspiCode}: точный пользователь не найден, пропускаем.");
                continue;
            }

            // 4. Сохраняем заказ, позиции, транзакцию и выдаём бонусы
            try {
                $this->createOrderForUser($user, $kaspiOrder, $entries, $cities);
                $syncedCount++;
                $this->info("Заказ #{$kaspiCode} успешно привязан к пользователю #{$user->id} ({$user->email})");
            } catch (\Throwable $e) {
                Log::error("Kaspi Sync Error for order #{$kaspiCode}: " . $e->getMessage());
            }
        }

        $this->info("Синхронизация завершена. Привязано новых заказов: {$syncedCount}");
        return self::SUCCESS;
    }

    /**
     * Поиск пользователя по имени, городу и сумме покупки
     */
    private function matchUser(array $attributes, array $entries, $cities): ?User
    {
        $customer  = $attributes['customer'] ?? [];
        $firstName = mb_strtolower(trim($customer['firstName'] ?? ''));
        $lastName  = mb_strtolower(trim($customer['lastName'] ?? ''));
        $fullName  = trim("{$firstName} {$lastName}");

        if ($firstName === '') {
            return null;
        }

        // Определяем город из адреса доставки Kaspi
        $townName = mb_strtolower(trim($attributes['deliveryAddress']['town'] ?? ''));
        $matchedCity = $townName
            ? $cities->first(fn($c) => mb_strtolower(trim($c->name)) === $townName)
            : null;

        $totalPrice = (float) ($attributes['totalPrice'] ?? 0);

        // Проверяем, совпадает ли сумма заказа с ценой наших товаров из entries (с учётом количества)
        $hasMatchingProduct = false;
        $calculatedTotal = 0;

        foreach ($entries as $entry) {
            $offerId  = $entry['attributes']['offerId'] ?? null;
            $qty      = (int) ($entry['attributes']['quantity'] ?? 1);
            $price    = (float) ($entry['attributes']['basePrice'] ?? 0);
            $calculatedTotal += $price * $qty;

            if ($offerId && Product::where('sku', $offerId)->exists()) {
                $hasMatchingProduct = true;
            }
        }

        // Сверяем сумму из атрибутов заказа с суммой позиций
        if (!$hasMatchingProduct || abs($totalPrice - $calculatedTotal) > 1) {
            return null;
        }

        // Ищем пользователей, которые заходили/регистрировались за последние 48 часов
        // и у которых совпадает имя (либо полное имя, либо первое слово в name)
        $candidates = User::query()
            ->where('updated_at', '>=', now()->subHours(48))
            ->where(function ($q) use ($firstName, $fullName) {
                $q->whereRaw('LOWER(TRIM(name)) = ?', [$firstName])
                    ->orWhereRaw('LOWER(TRIM(name)) = ?', [$fullName])
                    ->orWhereRaw('LOWER(SUBSTRING_INDEX(TRIM(name), " ", 1)) = ?', [$firstName]);
            })
            ->when($matchedCity, function ($q) use ($matchedCity) {
                // Если город в заказе определён — фильтруем по city_id пользователя (или допускаем null, если город не был выбран)
                $q->where(function ($sub) use ($matchedCity) {
                    $sub->where('city_id', $matchedCity->id)
                        ->orWhereNull('city_id');
                });
            })
            ->get();

        // Чтобы исключить ошибку при совпадении тёзок в одном городе, привязываем только при однозначном совпадении (1 кандидат)
        if ($candidates->count() === 1) {
            return $candidates->first();
        }

        return null;
    }

    /**
     * Создание заказа, товарных позиций, финансовой транзакции и начисление призов
     */
    private function createOrderForUser(User $user, array $kaspiOrder, array $entries, $cities): void
    {
        $attributes = $kaspiOrder['attributes'] ?? [];
        $kaspiCode  = (string) $attributes['code'];
        $totalPrice = (float) ($attributes['totalPrice'] ?? 0);

        $townName = mb_strtolower(trim($attributes['deliveryAddress']['town'] ?? ''));
        $matchedCity = $townName
            ? $cities->first(fn($c) => mb_strtolower(trim($c->name)) === $townName)
            : null;

        DB::transaction(function () use ($user, $kaspiOrder, $attributes, $kaspiCode, $entries, $totalPrice, $matchedCity) {
            $customerName = trim(($attributes['customer']['firstName'] ?? '') . ' ' . ($attributes['customer']['lastName'] ?? '')) ?: $user->name;

            $order = Order::create([
                'user_id'          => $user->id,
                'city_id'          => $matchedCity?->id ?? $user->city_id,
                'subtotal'         => $totalPrice,
                'discount'         => 0,
                'shipping_cost'    => 0,
                'total'            => $totalPrice,
                'status'           => OrderEnum::PAID->value ?? 'paid',
                'payment_method'   => 'kaspi',
                'shipping_method'  => 'kaspi_delivery',
                'shipping_address' => $attributes['deliveryAddress']['formattedAddress'] ?? 'Заказ оформлен в Kaspi Shop',
                'data'             => [
                    'kaspi_id'       => $kaspiOrder['id'],
                    'kaspi_code'     => $kaspiCode,
                    'customer_name'  => $customerName,
                    'customer_phone' => $attributes['customer']['cellPhone'] ?? null,
                    'sync_source'    => 'auto_cron',
                ],
            ]);

            foreach ($entries as $entry) {
                $offerId   = $entry['attributes']['offerId'] ?? null;
                $quantity  = (int) ($entry['attributes']['quantity'] ?? 1);
                $price     = (float) ($entry['attributes']['basePrice'] ?? 0);
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

            $userBalance = $user->balance ?? 0;

            Transaction::create([
                'user_id'        => $user->id,
                'amount'         => $totalPrice,
                'type'           => 'kaspi_claim',
                'balance_before' => $userBalance,
                'balance_after'  => $userBalance,
                'description'    => "Автосинхронизация покупки из Kaspi (Заказ #{$kaspiCode})",
                'source_type'    => Order::class,
                'source_id'      => $order->id,
                'data'           => [
                    'kaspi_code' => $kaspiCode,
                    'kaspi_id'   => $kaspiOrder['id'],
                    'provider'   => 'kaspi_shop',
                ],
            ]);

            app(PlatformPromotionService::class)->checkAndGiveGifts($user, 'purchase', $order);
        });
    }
}
