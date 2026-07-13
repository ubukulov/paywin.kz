<?php

namespace App\Http\Controllers;

use App\Enums\OrderEnum;
use App\Enums\TransactionEnum;
use App\Enums\UserDiscountEnum;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\UserDiscount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PartnerGiftService;
use App\Services\TipTopPayService;

class CheckoutController extends BaseController
{
    protected PartnerGiftService $partnerGiftService;
    protected TipTopPayService $tipTopPayService;

    const BANK_FEE_PERCENT = 3;        // Фиксированная комиссия эквайринга
    const AGENT_SHARE_OF_NET = 0.7;    // 70% от чистого остатка агенту
    const PLATFORM_SHARE_OF_NET = 0.3; // 30% от чистого остатка нам

    public function __construct(PartnerGiftService $partnerGiftService, TipTopPayService $tipTopPayService)
    {
        $this->partnerGiftService = $partnerGiftService;
        $this->tipTopPayService = $tipTopPayService;
    }

    public function index()
    {
        $user = Auth::user();

        // 1. Корзина или 1-клик покупка
        if (session()->has('instant_checkout')) {
            $instantData = session()->get('instant_checkout');
            $product = Product::findOrFail($instantData['product_id']);

            $stock = ProductStock::where('product_id', $product->id)
                ->where('quantity', '>', 0)
                ->first() ?? ProductStock::where('product_id', $product->id)->first();

            if (!$stock) {
                return redirect()->back()->with('error', 'Товар временно недоступен.');
            }

            $productPrice = $stock->price;

            $cart = (object)[
                'total' => $productPrice * $instantData['quantity'],
                'items' => collect([
                    (object)[
                        'product_id'   => $product->id,
                        'product'      => $product,
                        'quantity'     => $instantData['quantity'],
                        'price'        => $productPrice,
                        'total'        => $productPrice * $instantData['quantity'],
                        'warehouse_id' => $stock->warehouse_id
                    ]
                ])
            ];
            $isInstant = true;
        } else {
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart || $cart->items()->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Корзина пуста');
            }
            $isInstant = false;
        }

        // Группируем суммы заказа по партнёрам
        $partnerOrderTotals = [];
        foreach ($cart->items as $item) {
            $pId = $item->product->partner_id;
            $partnerOrderTotals[$pId] = ($partnerOrderTotals[$pId] ?? 0) + ($item->price * $item->quantity);
        }

        // 2. ИЩЕМ СКИДКИ ПАРТНЕРОВ (UserDiscount)
        $availableDiscounts = [];
        $userDiscounts = UserDiscount::with(['share.partner'])
            ->where('user_id', $user->id)
            ->where('status', UserDiscountEnum::ACTIVE->value)
            ->where(function($query) {
                $query->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            })
            ->get();

        foreach ($userDiscounts as $uDiscount) {
            $partner = $uDiscount->share?->partner ?? null;
            if ($partner && isset($partnerOrderTotals[$partner->id])) {
                $discountAmount = 0;
                $partnerSubtotal = $partnerOrderTotals[$partner->id];

                if ($uDiscount->percent > 0) {
                    $discountAmount = ($partnerSubtotal * $uDiscount->percent) / 100;
                } else {
                    $discountAmount = $uDiscount->amount;
                }

                $discountAmount = min($partnerSubtotal, $discountAmount);

                if ($discountAmount > 0) {
                    $availableDiscounts[] = [
                        'discount_id'  => $uDiscount->id,
                        'partner_name' => $partner->name,
                        'partner_id'   => $partner->id,
                        'title'        => $uDiscount->share->title ?? "Купон на скидку",
                        'calculated_amount' => $discountAmount
                    ];
                }
            }
        }

        // 3. РАССЧИТЫВАЕМ БОНУСЫ И КЭШБЭК (Из таблицы транзакций)
        $partnerCashbacks = [];
        $globalBalance = $user->balance;

        $cashbackTransactions = Transaction::where('user_id', $user->id)
            ->where('type', 'cashback')
            ->get();

        $partnerBalances = [];
        foreach ($cashbackTransactions as $tx) {
            $pId = $tx->data['partner_id'] ?? null;
            if ($pId) {
                $partnerBalances[$pId] = ($partnerBalances[$pId] ?? 0) + $tx->amount;
            }
        }

        foreach ($partnerOrderTotals as $partnerId => $orderTotal) {
            $hasCashback = $partnerBalances[$partnerId] ?? 0;
            if ($hasCashback > 0) {
                $partnerName = Product::where('partner_id', $partnerId)->first()?->partner?->name ?? 'Партнер';
                $maxSpendable = min($orderTotal, $hasCashback);

                $partnerCashbacks[] = [
                    'partner_id'   => $partnerId,
                    'partner_name' => $partnerName,
                    'balance'      => $hasCashback,
                    'max_spendable'=> $maxSpendable
                ];

                $globalBalance -= $hasCashback;
            }
        }

        $globalBalance = max(0, $globalBalance);

        $gifts = $this->partnerGiftService->getEligiblePrizes($cart->total);
        $discount = 0;
        $finalTotal = $cart->total;
        $ttpPublicId = env('TIPTOPPAY_PUBLIC_ID');

        return view('checkout.index', compact(
            'cart',
            'ttpPublicId',
            'discount',
            'finalTotal',
            'gifts',
            'isInstant',
            'availableDiscounts',
            'partnerCashbacks',
            'globalBalance'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $checkoutItems = collect();
            $cartTotal = 0;

            if (session()->has('instant_checkout')) {
                $instantData = session()->get('instant_checkout');
                $product = Product::findOrFail($instantData['product_id']);

                $stock = ProductStock::where('product_id', $product->id)
                    ->where('quantity', '>', 0)
                    ->first() ?? ProductStock::where('product_id', $product->id)->first();

                if (!$stock) {
                    throw new \Exception('Товар недоступен на складе');
                }

                $cartTotal = $stock->price * $instantData['quantity'];
                $checkoutItems->push((object)[
                    'product_id'   => $product->id,
                    'quantity'     => $instantData['quantity'],
                    'price'        => $stock->price,
                    'total'        => $cartTotal,
                    'warehouse_id' => $stock->warehouse_id
                ]);
            } else {
                $cart = Cart::where('user_id', $user->id)->first();

                if (!$cart || $cart->items->isEmpty()) {
                    return redirect()->route('cart.index');
                }

                $cartTotal = $cart->total;
                $checkoutItems = $cart->items;
            }

            // Массив цен товаров по партнерам для контроля ограничений списания
            $partnerSubtotals = [];
            foreach ($checkoutItems as $item) {
                $prod = Product::find($item->product_id);
                $partnerSubtotals[$prod->partner_id] = ($partnerSubtotals[$prod->partner_id] ?? 0) + ($item->price * $item->quantity);
            }

            $totalDiscountAmount = 0;

            // 1. ПРИМЕНЕНИЕ КУПОНОВ (UserDiscount)
            $appliedDiscountIds = $request->input('applied_discounts', []);
            if (!empty($appliedDiscountIds)) {
                $discounts = UserDiscount::whereIn('id', $appliedDiscountIds)
                    ->where('user_id', $user->id)
                    ->where('status', UserDiscountEnum::ACTIVE->value)
                    ->get();

                foreach ($discounts as $d) {
                    $prodSample = Product::whereIn('id', $checkoutItems->pluck('product_id'))->where('partner_id', $d->share?->partner_id)->first();
                    if ($prodSample) {
                        $val = $d->percent > 0 ? ($partnerSubtotals[$prodSample->partner_id] * $d->percent) / 100 : $d->amount;
                        $val = min($partnerSubtotals[$prodSample->partner_id], $val);

                        $totalDiscountAmount += $val;
                        $partnerSubtotals[$prodSample->partner_id] -= $val; // Снижаем остаток для последующих расчетов бонусов
                        $d->update(['status' => UserDiscountEnum::USED->value]);
                    }
                }
            }

            // 2. ПРИМЕНЕНИЕ ЦЕЛЕВЫХ БОНУСОВ ПАРТНЕРОВ (Кэшбэк)
            $usePartnerCashbacks = $request->input('use_partner_cashbacks', []);
            $totalCashbackSpent = 0;

            if (!empty($usePartnerCashbacks)) {
                foreach ($usePartnerCashbacks as $pId) {
                    if (isset($partnerSubtotals[$pId]) && $partnerSubtotals[$pId] > 0) {
                        // Рассчитываем сумму кэшбэка из транзакций
                        $availableCashback = Transaction::where('user_id', $user->id)
                            ->where('type', 'cashback')
                            ->get()
                            ->filter(function($tx) use ($pId) {
                                return ($tx->data['partner_id'] ?? null) == $pId;
                            })
                            ->sum('amount');

                        if ($availableCashback > 0) {
                            $canSpend = min($partnerSubtotals[$pId], $availableCashback, $user->balance);

                            if ($canSpend > 0) {
                                $balanceBefore = $user->balance;
                                $user->decrement('balance', $canSpend);
                                $totalCashbackSpent += $canSpend;
                                $partnerSubtotals[$pId] -= $canSpend;

                                Transaction::create([
                                    'user_id' => $user->id,
                                    'amount' => -$canSpend,
                                    'type' => 'withdrawal',
                                    'balance_before' => $balanceBefore,
                                    'balance_after' => $user->balance,
                                    'description' => "Списание целевого кэшбэка партнера #{$pId}",
                                    'source_type' => Order::class,
                                    'data' => ['partner_id' => $pId]
                                ]);
                            }
                        }
                    }
                }
            }

            // 3. ПРИМЕНЕНИЕ ГЛОБАЛЬНОГО СВОБОДНОГО БАЛАНСА
            $spentFromGlobalBalance = 0;
            $remainingPayAmount = max(0, $cartTotal - $totalDiscountAmount - $totalCashbackSpent);

            if ($request->input('use_global_balance') == 1 && $remainingPayAmount > 0 && $user->balance > 0) {
                $spentFromGlobalBalance = min($remainingPayAmount, $user->balance);

                $balanceBefore = $user->balance;
                $user->decrement('balance', $spentFromGlobalBalance);

                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => -$spentFromGlobalBalance,
                    'type' => 'withdrawal',
                    'balance_before' => $balanceBefore,
                    'balance_after' => $user->balance,
                    'description' => "Оплата со свободного баланса профиля",
                    'source_type' => Order::class,
                ]);
            }

            $finalCardPayAmount = max(0, $remainingPayAmount - $spentFromGlobalBalance);

            // Создаём заказ
            $order = Order::create([
                'user_id'          => $user->id,
                'user_discount_id' => !empty($appliedDiscountIds) ? $appliedDiscountIds[0] : null,
                'subtotal'         => $cartTotal,
                'discount'         => $totalDiscountAmount + $totalCashbackSpent + $spentFromGlobalBalance,
                'total'            => $finalCardPayAmount,
                'status'           => OrderEnum::PENDING->value,
                'payment_method'   => $finalCardPayAmount > 0 ? 'card' : 'balance',
                'shipping_address' => $request->address ?? 'Самовывоз',
            ]);

            // Привязываем сгенерированные транзакции списания средств к ID заказа
            Transaction::where('user_id', $user->id)
                ->where('source_type', Order::class)
                ->whereNull('source_id')
                ->update(['source_id' => $order->id]);

            foreach ($checkoutItems as $item) {
                $currentWarehouseId = $item->warehouse_id ?? 1;

                ProductStock::where(['product_id' => $item->product_id, 'warehouse_id' => $currentWarehouseId])
                    ->decrement('quantity', $item->quantity);

                $product = Product::find($item->product_id);

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'partner_id'   => $product->partner_id,
                    'warehouse_id' => $currentWarehouseId,
                    'product_name' => $product->name,
                    'product_sku'  => $product->sku,
                    'quantity'     => $item->quantity,
                    'price'        => $item->price,
                    'total'        => $item->total,
                ]);
            }

            // 4. ОБРАБОТКА ОПЛАТЫ
            if ($finalCardPayAmount > 0) {
                $paymentResponse = $this->tipTopPayService->payment([
                    'amount'     => $order->total,
                    'orderId'    => $order->id,
                    'cryptogram' => $request->get('cryptogram'),
                ]);

                if (!isset($paymentResponse['Success']) || !$paymentResponse['Success']) {
                    $model = $paymentResponse['Model'] ?? null;
                    if ($model && ($model['Status'] ?? '') === 'AwaitingAuthentication') {
                        $mdId = $model['TransactionId'] ?? ($model['MD'] ?? $model['AcsTransactionId'] ?? null);

                        $order->update([
                            'data' => array_merge($order->data ?? [], [
                                'transaction_id' => $mdId
                            ])
                        ]);

                        DB::commit();
                        return response()->json([
                            'status'         => '3ds_required',
                            'acs_url'        => $model['AcsUrl'],
                            'pareq'          => $model['PaReq'],
                            'transaction_id' => $mdId,
                            'order_id'       => $order->id
                        ]);
                    }
                    throw new \Exception('Платеж отклонен банком');
                }

                $this->finalizeOrder($order, $paymentResponse['Model']);
            } else {
                // Полная оплата баллами/купонами (0 ₸ по банковской карте)
                $this->finalizeOrder($order, [
                    'TransactionId' => 'BONUS_INTERNAL_' . time(),
                    'Status' => 'Completed'
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Заказ успешно оплачен!'
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    private function finalizeOrder(Order $order, $paymentModel, $isPreorder = false)
    {
        $order->update([
            'meta' => json_encode($paymentModel, JSON_UNESCAPED_UNICODE),
            'status' => ($isPreorder) ? OrderEnum::PREORDER->value  : OrderEnum::PAID->value,
            'transaction_id' => $paymentModel['TransactionId'] ?? $order->transaction_id
        ]);

        $buyer = $order->user;
        $referrer = $buyer->referrer;

        foreach ($order->items as $item) {
            $product = $item->product;
            $partner = $product->partner;

            $bankFee = $item->total * (self::BANK_FEE_PERCENT / 100);
            $partnerIncome = $item->total - $bankFee;

            if ($partner) {
                $partner->changeBalance(
                    $partnerIncome,
                    TransactionEnum::SALE_INCOME,
                    $buyer,
                    "Продажа (без комиссии): {$product->title}",
                    ['bank_payment_id' => $paymentModel['TransactionId'] ?? null]
                );
            }

            if ($partner && $partner->shares()->exists()) {
                $buyer->givePrize($partner->shares, $order);
            }

            $platformPromotionService = app(\App\Services\PlatformPromotionService::class);
            $platformPromotionService->checkAndGiveGifts($buyer, 'purchase', $order);
        }

        if (session()->has('instant_checkout')) {
            session()->forget('instant_checkout');
        } else {
            $buyer->cart?->items()->delete();
            $buyer->cart?->delete();
        }
    }

    public function handle3DS(Request $request)
    {
        $transactionId = $request->input('MD') ?? $request->input('TransactionId');
        $pares = $request->input('PaRes');

        \Log::info('3DS Callback Data', $request->all());

        $result = (new TipTopPayService())->confirm3DS($transactionId, $pares);

        if ($result['Success'] && $result['Model']['Status'] === 'Completed') {
            DB::beginTransaction();
            try {
                $order = Order::where('data->transaction_id', $transactionId)->first();

                if (!$order) {
                    \Log::error("Order not found for transaction: " . $transactionId);
                    return response('<!DOCTYPE html><html><head><script>window.top.location.href = "/checkout/success";</script></head></html>');
                }

                if ($order->status !== OrderEnum::PAID->value) {
                    $this->finalizeOrder($order, $result['Model']);
                }

                DB::commit();
                return response('<!DOCTYPE html><html><head><script>window.top.location.href = "/checkout/success";</script></head></html>');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("3DS Finalize Error: " . $e->getMessage());
                return response('Error', 500);
            }
        }

        \Log::error("3DS Confirmation Failed for Trans: " . $transactionId, $result);
        return response('FAILED', 200);
    }

    public function success()
    {
        $user = Auth::user();

        $rawGifts = \App\Models\UserGift::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->latest()
            ->get();

        $gifts = $rawGifts->groupBy('name')->map(function ($group) {
            $firstGift = $group->first();

            $ticketNumbers = [];
            foreach ($group as $gift) {
                $giftData = is_array($gift->data) ? $gift->data : json_decode($gift->data, true);
                if (!empty($giftData['ticket_number'])) {
                    $ticketNumbers[] = $giftData['ticket_number'];
                }
            }

            $firstGift->quantity_count = $group->count();
            $firstGift->all_tickets = $ticketNumbers;

            return $firstGift;
        });

        return view('checkout.success', compact('gifts'));
    }

    public function instant(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        session()->put('instant_checkout', [
            'product_id' => $request->product_id,
            'quantity' => (int) $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('checkout.index')
        ]);
    }
}
