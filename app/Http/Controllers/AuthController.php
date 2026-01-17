<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Smsc;
use Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Referral;

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

                $code = session('ref_code') ?? Cookie::get('ref_code');

                if ($code) {

                    // ❌ запрет самореферала
                    if ($code != $user->id) {

                        // ❌ если клиент уже привязан
                        if (!Referral::where('client_id', $user->id)->exists()) {

                            // /ref/12
                            if (is_numeric($code)) {

                                $agent = User::find($code);

                                if ($agent) {
                                    Referral::create([
                                        'agent_id'  => $agent->id,
                                        'client_id' => $user->id,
                                        'source'    => 'link',
                                    ]);
                                }

                            }
                            // /ref/AGENT12
                            else {

                                $promo = Referral::where('promo_code', $code)->first();

                                if ($promo) {
                                    Referral::create([
                                        'agent_id'   => $promo->agent_id,
                                        'client_id'  => $user->id,
                                        'promo_code' => $promo->promo_code,
                                        'source'     => 'promo',
                                    ]);
                                }
                            }
                        }
                    }

                    // очищаем
                    session()->forget('ref_code');
                    Cookie::queue(Cookie::forget('ref_code'));
                }

                Auth::login($user);

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
}
