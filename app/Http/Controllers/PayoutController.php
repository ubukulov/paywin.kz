<?php

namespace App\Http\Controllers;

use App\Enums\PayoutEnum;
use App\Enums\TransactionEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PayoutRequest;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    public function index()
    {
        $requests = Auth::user()->payoutRequests()->latest()->get();
        return view('payouts.index', compact('requests'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $profile = ($user->user_type === 'user') ? $user->userProfile : $user->partnerProfile;

        if (!$profile || empty($profile->bank_account)) {
            return redirect()->back()->withErrors([
                'bank_account' => 'У вас не заполнены реквизиты для вывода в профиле.'
            ]);
        }

        $request->validate([
            'amount' => "required|numeric|min:1000|max:{$user->balance}",
        ], [
            'amount.required' => 'Введите сумму.',
            'amount.numeric'  => 'Сумма должна быть числом.',
            'amount.min'      => 'Минимальная сумма вывода — 1000 ₸.',
            'amount.max'      => 'Недостаточно средств. Ваш баланс: ' . number_format($user->balance, 0, '.', ' ') . ' ₸.',
        ]);

        return DB::transaction(function () use ($user, $request, $profile) {
            // 2. Создаем саму заявку
            $payout = PayoutRequest::create([
                'user_id' => $user->id,
                'amount'  => $request->amount,
                'status'  => PayoutEnum::PENDING->value,
                'data'    => [
                    'bank_account' => $profile->bank_account,
                ]
            ]);

            $user->changeBalance(
                -$request->amount,
                TransactionEnum::WITHDRAWAL, // Предположим, такой тип есть в Enum
                $payout,
                "Заявка на вывод №{$payout->id}"
            );

            return redirect()->back()->with('success', 'Заявка принята, средства заморожены.');
        });
    }

    public function reject(PayoutRequest $payout)
    {
        $payout->update(['status' => 'rejected']);

        // Возвращаем деньги пользователю через ту же систему транзакций
        $payout->user->changeBalance(
            $payout->amount,
            TransactionEnum::REFUND, // Тип "Возврат средств"
            $payout,
            "Возврат по отклоненной заявке №{$payout->id}"
        );
    }
}
