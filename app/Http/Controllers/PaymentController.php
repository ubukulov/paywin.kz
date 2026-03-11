<?php

namespace App\Http\Controllers;

use App\Enums\TransactionEnum;
use App\Models\Transaction;
use App\Models\UserDiscount;
use App\Models\UserGift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\PartnerGiftService;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected PartnerGiftService $partnerGiftService;

    // Внедряем сервис через конструктор
    public function __construct(PartnerGiftService $partnerGiftService)
    {
        $this->partnerGiftService = $partnerGiftService;
    }

    public function paymentWithBalance(Request $request): \Illuminate\Http\JsonResponse
    {
        $amount = (float) $request->input('amount');
        $partnerId = $request->input('partner_id');
        $user = Auth::user();
        $partner = User::findOrFail($partnerId);

        // ВАЖНО: Считаем баланс именно для этого партнера
        $availableBalance = $user->getBalanceForPartner($partnerId);

        DB::beginTransaction();
        try {

            if ($availableBalance < $amount) {
                throw new \Exception("У вас недостаточно бонусов для оплаты в {$partner->partnerProfile?->company}. Доступно: {$availableBalance} ₸");
            }

            // 1. Списываем у пользователя
            $user->changeBalance(
                -$amount,
                TransactionEnum::ADJUSTMENT,
                $partner,
                "Оплата услуг партнера {$partner->partnerProfile?->company}",
            );

            // 2. Начисляем партнеру
            $partner->changeBalance(
                $amount,
                TransactionEnum::SALE_INCOME,
                $user,
                "Продажа услуг клиенту {$user->phone}",
            );

            // 3. Получаем объект транзакции (триггер для приза)
            $transaction = $user->transactions()->latest()->first();

            // 4. Розыгрыш приза
            $wonPrize = $this->partnerGiftService->checkAndAssignPrize(
                $user,
                $partner,
                $amount,
                $transaction
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Оплата прошла успешно',
                'payment' => $transaction,
                'share' => $wonPrize,
                'prize' => $wonPrize ? [
                    'title' => $wonPrize->title,
                    'type' => $wonPrize->type
                ] : null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function paymentSuccess()
    {
        $user = Auth::user();

        // 1. Находим последнюю транзакцию списания (оплаты) пользователя
        // Используем ADJUSTMENT, так как именно этот тип мы указывали при оплате с баланса
        $transaction = Transaction::where('user_id', $user->id)
            ->where('amount', '<', 0)
            ->where('type', TransactionEnum::ADJUSTMENT->value)
            ->latest()
            ->first();

        if (!$transaction) {
            return redirect()->route('home');
        }

        // 2. Ищем приз, который ссылается на эту транзакцию как на источник (source_id)
        $prize = null;
        $share = null;

        // Сначала проверяем подарок
        $gift = UserGift::where('source_id', $transaction->id)
            ->where('source_type', Transaction::class)
            ->first();

        if ($gift) {
            $prize = $gift;
            $share = $gift->share;
        } else {
            // Если нет подарка, проверяем скидку
            $discount = UserDiscount::where('source_id', $transaction->id)
                ->where('source_type', Transaction::class)
                ->first();

            if ($discount) {
                $prize = $discount;
                $share = $discount->share;
            }
        }

        // 3. Если приза в таблицах нет, проверяем, не был ли это кешбэк
        // (кешбэк — это отдельная транзакция, созданная в ту же секунду)
        if (!$prize) {
            $cashback = Transaction::where('user_id', $user->id)
                ->where('type', TransactionEnum::CASHBACK->value)
                ->where('created_at', '>=', $transaction->created_at)
                ->first();

            if ($cashback) {
                $prize = $cashback;
                $share = $cashback->source; // В методе applyCashback мы передавали $share в source
            }
        }

        // Передаем в вьюху 'payment', так как в шаблоне ожидается эта переменная для суммы
        $payment = $transaction;
        // Важно: в шаблоне сумма будет отрицательной (-100), поэтому используем abs() в Blade
        $payment->amount = abs($transaction->amount);

        return view('thanks', compact('payment', 'prize', 'share'));
    }
}
