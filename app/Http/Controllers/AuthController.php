<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
        $password = rand(1000,9999);


        $message = "Регистрация прошло успешно. Ваш SMS-пароль: $password";
        $balance = Smsc::get_balance();

        if (User::exists($phone)) {
            return redirect()->back();
        } else {
            if ((int) $balance > 50) {
                Smsc::send_sms($phone, $message);
                $user_type = (isset($data['partner']) && $data['partner'] == 'yes') ? 'partner' : 'user';
                $user = User::create([
                    'phone' => $phone, 'password' => bcrypt($password), 'user_type' => $user_type
                ]);

                $user->create_profile();

                Auth::login($user);

                $this->applyReferral($user);

                return redirect()->route('home');
            } else {
                return redirect()->back();
            }
        }
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
        } else {
            return redirect()->back();
        }
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
        $code = session('ref_code') ?? Cookie::get('ref_code');

        if (!$code) return;

        // Запрет самореферала и партнеров
        if ($code == $user->id || $user->user_type == 'partner') return;

        // Если клиент уже привязан — выходим
        if (Referral::where('client_id', $user->id)->exists()) return;

        if (is_numeric($code)) {
            // Логика для прямых реферальных ссылок (по ID агента)
            $agent = User::find($code);
            if ($agent && $agent->user_type !== 'partner') {
                Referral::create([
                    'agent_id'  => $agent->id,
                    'client_id' => $user->id,
                    'source'    => 'link',
                ]);
            }
        } else {
            // Логика для ПРОМОКОДОВ (например, SPRING34)

            // 1. Парсим код: отделяем текст от ID агента
            // ([A-ZА-Я0-9]+) — буквы и цифры промокода, (\d+) — ID агента в конце
            if (preg_match('/^([A-ZА-Я0-9]+?)(\d+)$/u', strtoupper($code), $matches)) {
                $baseTitle = $matches[1]; // SPRING
                $agentId   = $matches[2]; // 34

                // 2. Ищем агента, чтобы узнать, к какому партнеру он привязан
                $agent = User::find($agentId);

                if ($agent) {
                    // 3. Ищем акцию именно этого партнера с таким названием
                    // Это защитит, если два партнера создали одинаковый код "SPRING"
                    $share = Share::where('title', $baseTitle)
                        ->where('user_id', $agent->partner_id) // Связь агента с его партнером
                        ->where('type', 'promocode')
                        ->first();

                    // 4. Создаем запись с привязкой к конкретной акции (share_id)
                    Referral::create([
                        'agent_id'   => $agent->id,
                        'client_id'  => $user->id,
                        'share_id'   => $share ? $share->id : null, // Сохраняем ID акции для расчета %
                        'promo_code' => $code,
                        'source'     => 'promo',
                    ]);
                }
            }
        }

        // Очищаем данные
        session()->forget('ref_code');
        Cookie::queue(Cookie::forget('ref_code'));
    }
}
