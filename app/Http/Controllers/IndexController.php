<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Prize;
use App\Models\Share;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class IndexController extends BaseController
{
    public function home()
    {
        $categories = Category::all();
        return view('home',  compact('categories'));
    }

    public function prizes()
    {
        $prizes = Prize::where(['user_id' => Auth::user()->id])
            ->with('user', 'payment', 'share')
            ->get();
        return view('prizes', compact('prizes'));
    }

    public function paymentPage($slug, $id)
    {
        return view('payment2', compact('slug', 'id'));
    }

    public function payment(Request $request)
    {
        $pg_merchant_id = 511867;
        $salt = "y7crxXTrz6SXKPNd";
        $amount = $request->input('sum');
        $partner_id = $request->input('partner_id');

        $payment = Payment::where(['user_id' => Auth::user()->id, 'partner_id' => $partner_id, 'amount' => $amount, 'pg_status' => 'waiting'])->first();
        if(!$payment) {
            $payment = new Payment();
            $payment->user_id = Auth::user()->id;
            $payment->partner_id = $partner_id;
            $payment->amount = $amount;
            $payment->pg_status = 'waiting';
            $payment->save();
        }


        $request = [
            'pg_merchant_id'=> $pg_merchant_id,
            'pg_amount' => $amount,
            'pg_salt' => $salt,
            'pg_order_id' => $payment->id,
            'pg_description' => 'Описание заказа',
            'pg_success_url' => 'https://paywin.kz/payment/success',
            'pg_failure_url' => 'https://paywin.kz/payment/error',
        ];

        //$request['pg_testing_mode'] = 1; //add this parameter to request for testing payments

        //if you pass any of your parameters, which you want to get back after the payment, then add them. For example:
//        $request['client_name'] = $post['first_name'];
//        $request['office_id'] = $post['office_id'];
        // $request['client_address'] = 'Earth Planet';

        //generate a signature and add it to the array
        ksort($request); //sort alphabetically
        array_unshift($request, 'payment.php');
        array_push($request, $salt); //add your secret key (you can take it in your personal cabinet on paybox system)


        $request['pg_sig'] = md5(implode(';', $request));

        unset($request[0], $request[1]);

        $query = http_build_query($request);

        //redirect a customer to payment page
        header('Location:https://api.paybox.money/payment.php?'.$query);
        exit();
    }

    public function paymentSuccess(Request $request)
    {
        $payment_id = $request->input('pg_order_id');
        $payment = Payment::findOrFail($payment_id);

        if($payment->pg_status != 'ok') {
            $payment->pg_status = 'ok';
            $payment->pg_payment_id = $request->input('pg_payment_id');
            $payment->updated_at = Carbon::now();
            $payment->save();
        }

        if (!User::isPrize(Auth::user()->id, $payment->id)) {
            $partner = $payment->partner;
            $shares = $partner->shares;

            if (count($shares) != 0) {
                foreach($shares->shuffle() as $share) {
                    if(($share->from_order >= $payment->amount) && ($payment->amount <= $share->to_order)) {
                        $prize = new Prize();
                        $prize->payment_id = $payment->id;
                        $prize->user_id = $payment->user_id;
                        $prize->share_id = $share->id;
                        $prize->cnt = 1;
                        $prize->status = 'got';
                        $prize->save();

                        $share->cnt--;
                        $share->save();
                        break;
                    }
                }
            }
        }

        $prize = $payment->prize;
        $share = $prize->share;

        /*$pg_merchant_id = 511867;
        $secret_key = "y7crxXTrz6SXKPNd";
        $pg_payment_id = 572103783;
        $salt = \Str::random(15);
        $pg_api_url = "https://api.paybox.money/get_status2.php";

        $request = [
            'pg_merchant_id'=> $pg_merchant_id,
            'pg_payment_id' => $pg_payment_id,
            'pg_salt' => $salt,
        ];

        //generate a signature and add it to the array
        ksort($request); //sort alphabetically
        array_unshift($request, 'get_status2.php');
        array_push($request, $secret_key); //add your secret key (you can take it in your personal cabinet on paybox system)

        $request['pg_sig'] = md5(implode(';', $request)); // signature

        unset($request[0], $request[1]);


        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $pg_api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_HTTP_VERSION => CURLOPT_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ]
            ]
        );
        $res = curl_exec($curl);
        curl_close($curl);
        var_dump($res);*/
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

    public function notGivenPrize()
    {
        return view('not_given_prize');
    }
}
