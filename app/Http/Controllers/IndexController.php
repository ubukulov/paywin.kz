<?php

namespace App\Http\Controllers;

use App\Enums\TransactionEnum;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Partner;
use App\Models\Category;
use App\Models\Payment;
use App\Models\UserGift;
use App\Models\Share;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\UserDiscount;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends BaseController
{
    public function home()
    {
        $categories = Category::all();
        return view('home',  compact('categories'));
    }

    public function paymentPage($slug, $id)
    {
        $partner = User::findOrFail($id);
        $user = User::findOrFail(Auth::user()->id);
        return view('payment', compact('slug', 'id', 'partner', 'user'));
    }

    public function aboutUs()
    {
        return view('about');
    }

    public function payment(Request $request)
    {
        $amount = $request->input('amount');
        $partner_id = $request->input('partner_id');
        $transaction_id = $request->input('transaction_id');

        $user = Auth::user();
        $partner = User::findOrFail($partner_id);
        $new_amount = $amount;


        $payment = Payment::create([
            'user_id' => $user->id, 'partner_id' => $partner_id, 'amount' => $amount, 'pg_status' => 'ok', 'pg_payment_id' => $transaction_id
        ]);

        $user->givePrize($partner->shares, $payment);

        return redirect()->route('payment.success');
    }

    public function paymentWithBalance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'partner_id' => 'required|exists:users,id',
            'transaction_id' => 'nullable|string'
        ]);

        $amount = (float) $request->input('amount');
        $partnerId = $request->input('partner_id');

        $user = Auth::user();
        $partner = User::findOrFail($partnerId);

        try {
            return DB::transaction(function () use ($user, $partner, $amount) {

                // Проверка: хватает ли денег (на случай, если JS на фронте подменили)
                if ($user->balance < $amount) {
                    throw new \Exception('Недостаточно средств на балансе');
                }

                // 3. Списываем сумму с баланса пользователя
                $user->changeBalance(
                    -$amount,
                    TransactionEnum::ADJUSTMENT, // Тип "Корректировка/Оплата"
                    $partner,
                    "Оплата услуг партнера {$partner->partnerProfile?->company}",
                );

                // 4. Начисляем сумму в баланс партнера
                $partner->changeBalance(
                    $amount,
                    TransactionEnum::SALE_INCOME,
                    $user,
                    "Продажа услуг клиенту {$user->phone}",
                );

                return redirect()->route('payment.success');
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function paymentSuccess()
    {
        $user = Auth::user();

        $payment = Payment::where(['user_id' => $user->id, 'pg_status' => 'ok'])->orderBy('id', 'DESC')->first();

        $prize = $payment->prize;
        if($prize) {
            $share = Share::findOrFail($prize->share_id);
        } else {
            $share = null;
        }


        return view('thanks', compact('payment', 'prize', 'share'));
    }

    public function paymentError(Request $request)
    {
        $payment_id = $request->input('pg_order_id');
        $payment = Payment::findOrFail($payment_id);
        $payment->pg_status = 'error';
        $payment->save();

        dd("Ошибка во время оплаты", $request->all());
    }

    public function review()
    {
        return view('review');
    }

    public function notGivenPrize($id)
    {
        $partner = User::findOrFail($id);
        return view('not_given_prize');
    }

    public function howItWorks()
    {
        return view('how_it_works');
    }
}
