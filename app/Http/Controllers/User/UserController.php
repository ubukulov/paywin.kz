<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Prize;
use App\Models\Share;
use App\Models\User;
use App\Models\UserBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Referral;

class UserController extends Controller
{
    public function cabinet()
    {
        $user_balance = UserBalance::where(['user_id' => Auth::user()->id, 'status' => 'waiting'])->orderBy('id', 'DESC')->first();
        /*if($user_balance) {
            $request = [
                'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
                'pg_order_id' => $user_balance->id,
                'pg_salt' => env('PAYBOX_MERCHANT_SECRET'),
            ];

            //generate a signature and add it to the array
            ksort($request); //sort alphabetically
            array_unshift($request, 'get_status2.php');
            array_push($request, env('PAYBOX_MERCHANT_SECRET')); //add your secret key (you can take it in your personal cabinet on paybox system)

            $request['pg_sig'] = md5(implode(';', $request)); // signature

            unset($request[0], $request[1]);

            $apiUrl = env('PAYBOX_URL') . "get_status2.php";
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', $apiUrl, [
                'headers' => [
                    'Content-type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => $request
            ]);
            $response = $response->getBody()->getContents();
            $responseXml = simplexml_load_string($response);
            if((string)$responseXml->pg_status == 'ok') {
                $user_balance->status = 'ok';
                $user_balance->pg_payment_id = (int) $responseXml->pg_payment_id;
                $user_balance->updated_at = Carbon::now();
                $user_balance->save();
            }
        }*/

        $user = Auth::user();
        $user_profile = Auth::user()->profile;
        $prize = Prize::where(['user_id' => $user->id, 'status' => 'waiting'])->first();
        return view('user.home', compact('user_profile', 'user', 'prize'));
    }

    public function addMyCard()
    {
        $salt = Str::random(15);
        $pg_api_url = env('PAYBOX_URL') . "v1/merchant/".env('PAYBOX_MERCHANT_ID')."/cardstorage/add";

        $request = [
            'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
            'pg_user_id' => Auth::user()->id,
            'pg_post_link' => 'https://paywin.kz/user',
            'pg_back_link' => 'https://paywin.kz/user',
            'pg_salt' => $salt,
        ];

        //generate a signature and add it to the array
        ksort($request); //sort alphabetically
        array_unshift($request, 'add');
        array_push($request, env('PAYBOX_MERCHANT_SECRET')); //add your secret key (you can take it in your personal cabinet on paybox system)

        $request['pg_sig'] = md5(implode(';', $request)); // signature
        unset($request[0], $request[1]);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $pg_api_url, [
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => $request
        ]);
        $response = $response->getBody()->getContents();
        $responseXml = simplexml_load_string($response);
        $redirect_url = (string) $responseXml->pg_redirect_url;
        header('Location: ' . $redirect_url);
        exit();
    }

    public function removeMyCard()
    {
        $salt = Str::random(15);
        $pg_card_id = 41620811;
        $pg_api_url = env('PAYBOX_URL') . "v1/merchant/". env('PAYBOX_MERCHANT_ID') ."/cardstorage/remove";

        $request = [
            'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
            'pg_user_id' => Auth::user()->id,
            'pg_card_id' => $pg_card_id,
            'pg_salt' => $salt,
        ];

        //generate a signature and add it to the array
        ksort($request); //sort alphabetically
        array_unshift($request, 'remove');
        array_push($request, env('PAYBOX_MERCHANT_SECRET')); //add your secret key (you can take it in your personal cabinet on paybox system)

        $request['pg_sig'] = md5(implode(';', $request)); // signature
        unset($request[0], $request[1]);

        $curl = curl_init($pg_api_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $response = curl_exec($curl);
        curl_close($curl);
        dd($response);
    }

    public function earn()
    {
        $promos = Share::actualPromocodes()->get();
        $myPromos = Referral::where('agent_id', auth()->id())
            ->where('source', 'promo')
            ->get()
            ->unique('promo_code');

        return view('user.earn', compact('promos', 'myPromos'));
    }

    public function history()
    {
        $payments = Payment::where(['user_id' => Auth::user()->id, 'pg_status' => 'ok'])->orderby('id', 'DESC')->get();
        return view('user.history', compact('payments'));
    }

    public function settings()
    {
        $user = Auth::user();
        $user_profile = $user->profile;
        return view('user.settings', compact('user_profile'));
    }

    public function balanceReplenishment(Request $request)
    {
        $amount = $request->input('amount');
        $user_balance = UserBalance::where(['user_id' => Auth::user()->id, 'amount' => $amount, 'status' => 'waiting'])->first();
        if(!$user_balance) {
            $user_balance = new UserBalance();
            $user_balance->user_id = Auth::user()->id;
            $user_balance->amount = $amount;
            $user_balance->type = 'payment';
            $user_balance->status = 'waiting';
            $user_balance->save();
        }

        $request = [
            'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
            'pg_amount' => $amount,
            'pg_salt' => env('PAYBOX_MERCHANT_SECRET'),
            'pg_order_id' => $user_balance->id,
            'pg_description' => 'Пополнение баланса',
            'pg_success_url' => 'https://paywin.kz/user',
            'pg_failure_url' => 'https://paywin.kz/user',
            'pg_success_url_method' => 'GET',
            'pg_failure_url_method' => 'GET',
        ];

        ksort($request); //sort alphabetically
        array_unshift($request, 'payment.php');
        array_push($request, env('PAYBOX_MERCHANT_SECRET')); //add your secret key (you can take it in your personal cabinet on paybox system)


        $request['pg_sig'] = md5(implode(';', $request));

        unset($request[0], $request[1]);

        $query = http_build_query($request);

        //redirect a customer to payment page
        header('Location: '.env('PAYBOX_URL').'payment.php?'.$query);
        exit();
    }

    public function promoActivate(Request $request)
    {
        $promoCode = strtoupper(trim($request->promo_code));

        $promoPartner = preg_replace('/[^A-Z]/', '', $promoCode);

        $user = auth()->user();

        // 1. Получаем ID агента из кода
        $agentId = (int) filter_var($promoCode, FILTER_SANITIZE_NUMBER_INT);

        if (!$agentId || $agentId === $user->id) {
            return redirect()
                ->back()
                ->withErrors(['message' => 'Некорректный промокод']);
        }

        // 2. Проверяем, существует ли агент
        $agent = User::find($agentId);

        if (!$agent || $agent->user_type !== 'user') {
            return redirect()
                ->back()
                ->withErrors(['message' => 'Агент не найден']);
        }

        // 3. Проверяем промокод в SHARES
        $share = Share::where('title', $promoPartner)
            ->where('from_date', '<=', now())
            ->where('to_date', '>=', now())
            ->first();

        if (!$share) {
            return redirect()
                ->back()
                ->withErrors(['message' => 'Промокод неактуален']);
        }

        // 4. Проверка — не активировал ли уже
        if (Referral::where('client_id', $user->id)->exists()) {
            return redirect()
                    ->back()
                    ->withErrors(['message' => 'Вы уже привязаны к агенту']);
        }

        // =========================
        // MONEY — активируем
        // =========================
        if ($share->promo === 'money') {

            DB::transaction(function () use ($share, $agentId, $user, $promoCode) {

                // фиксируем реферала
                Referral::create([
                    'agent_id'   => $agentId,
                    'client_id'  => $user->id,
                    'promo_code' => $promoCode,
                    'source'     => 'promo',
                ]);

                // начисляем бонус
                UserBalance::create([
                    'user_id'      => $user->id,
                    'promocode_id' => $share->id,
                    'type'         => 'promocode',
                    'amount'       => $share->size,
                    'status'       => 'ok',
                ]);
            });

            return redirect()->route('user.cabinet');
        }

        // =========================
        // DISCOUNT — не активируем
        // =========================
        if ($share->type === 'discount') {

            return redirect()
                ->back()
                ->withErrors(['message' => 'Промокод будет применён при оплате']);
        }

        return redirect()
            ->back()
            ->withErrors(['message' => 'Неизвестный тип промокода']);
    }
}
