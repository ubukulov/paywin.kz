<?php

namespace App\Http\Controllers;

use App\Enums\TransactionEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Smsc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Referral;
use App\Models\Share;

class AuthController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function register()
    {
        return view('register');
    }

    public function login()
    {
        return view('login');
    }

    public function registration(Request $request)
    {
        $data = $request->all();
        $phone = $this->phoneConvert($data['phone']);

        if ((int) Smsc::get_balance() <= 50) {
            return redirect()->back()->withErrors(['sms' => 'Технические работы на сервере SMS. Попробуйте позже.']);
        }

        $password = rand(1000, 9999);

        return DB::transaction(function () use ($phone, $password, $request) {
            $user_type = ($request->partner == 'yes') ? 'partner' : 'user';

            $user = User::create([
                'phone' => $phone,
                'password' => Hash::make($password),
                'user_type' => $user_type
            ]);

            $user->createProfile();

            // Отправка SMS
            Smsc::send_sms($phone, "Регистрация прошло успешно. Ваш SMS-пароль: $password");

            Auth::login($user);
            $this->applyReferral($user);

            return redirect()->route('home');
        });
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function authenticate(Request $request)
    {
        if(Auth::attempt(['phone' => $this->phoneConvert($request->input('phone')), 'password' => $request->input('password')])) {
            $this->applyReferral(Auth::user());
            return redirect()->route('home');
        }

        return redirect()->back()->withErrors(['login' => 'Неверный логин или пароль']);
    }

    public function phoneConvert($phone)
    {
        $phone = substr($phone,3);
        return "7".str_replace("-","", $phone);
    }

    /**
     * Привязка реферального кода к пользователю
     */
    private function applyReferral($user)
    {
        $agentId = Cookie::get('ref_agent_id');
        $promoCode = Cookie::get('ref_promo_code') ?? request()->code;

        if (!$agentId && !$promoCode) return;

        // 1. Ищем акцию (если есть промокод)
        $share = null;
        if ($promoCode) {
            $share = Share::where('title', strtoupper($promoCode))
                ->where('type', 'promocode')
                ->active()
                ->first();

            // Если агент не указан в ссылке, но код верный —
            // владельцем "трафика" считаем партнера (автора акции)
            if ($share && !$agentId) {
                $agentId = $share->partner_id;
            }
        }

        if (!$agentId) return;

        $agent = User::find($agentId);
        if (!$agent) return;

        // --- ЛОГИКА РАЗДЕЛЕНИЯ ---

        // Если агент — ПАРТНЕР
        if ($agent->user_type === 'partner') {
            // Запись в referrals НЕ создаем (как ты и просил)
            // Но проверяем, положен ли бонус пользователю
            $this->processDirectPartnerBonus($user, $share, $promoCode);
        }

        // Если агент — ОБЫЧНЫЙ ПОЛЬЗОВАТЕЛЬ (Агент)
        else {
            // Создаем реферальную связь (агент будет получать % с покупок)
            $referral = Referral::create([
                'agent_id'           => $agent->id,
                'user_id'            => $user->id,
                'share_id'           => $share ? $share->id : null,
                'percent' => $share->data['agent_percent'] ?? 5,
            ]);

            // Начисляем бонус пользователю
            $this->processDirectPartnerBonus($user, $share, $promoCode, $referral);
        }

        // Очистка кук
        Cookie::queue(Cookie::forget('ref_agent_id'));
        Cookie::queue(Cookie::forget('ref_promo_code'));
    }

    private function processDirectPartnerBonus($user, $share, $code, $referral = null): void
    {
        if (!$share || !isset($share->data['size'])) return;

        $bonusAmount = (float) $share->data['size'];
        $limit = (int) ($share->count ?? 0);

        // 1. Быстрая проверка вне транзакции (чтобы не грузить БД зря)
        if ($limit > 0 && $share->used_count >= $limit) return;

        $partner = $share->partner;
        if (!$partner) return;

        // 2. Основная логика в транзакции
        DB::transaction(function () use ($user, &$share, $partner, $bonusAmount, $code, $referral, $limit) {

            // Блокируем строку акции для обновления (SELECT ... FOR UPDATE)
            // Это гарантирует, что мы не превысим лимит при 100 одновременных запросах
            $lockedShare = Share::where('id', $share->id)->lockForUpdate()->first();

            if ($limit > 0 && $lockedShare->used_count >= $limit) {
                throw new \Exception("Лимит промокодов исчерпан в момент обработки");
            }

            // Начисляем пользователю
            $user->changeBalance(
                $bonusAmount,
                TransactionEnum::PROMOCODE,
                $referral ?? $lockedShare,
                "Бонус по промокоду " . ($code ?? 'акции')
            );

            // Списываем у партнера
            $partner->changeBalance(
                -$bonusAmount,
                TransactionEnum::PROMO_PAYOUT,
                $user,
                "Выплата бонуса новому клиенту ({$user->phone})"
            );

            // Увеличиваем счетчик
            $lockedShare->increment('used_count');
        });
    }
}
