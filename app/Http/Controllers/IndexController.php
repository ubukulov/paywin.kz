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
        $shares = Share::where('cnt', '>', 0)
            ->with('user')
            ->get();

        $prizes = Prize::where(['user_id' => Auth::user()->id])
            ->with('user', 'share', 'payment')
            ->get();

        return view('prizes', compact('shares', 'prizes'));
    }

    public function paymentPage($slug, $id)
    {
        $partner = User::findOrFail($id);
        $user = User::findOrFail(Auth::user()->id);
        return view('payment2', compact('slug', 'id', 'partner', 'user'));
    }

    public function payment(Request $request)
    {
        $amount = $request->input('sum');
        $partner_id = $request->input('partner_id');
        $pg_card_id = $request->input('card_id');

        $payment = Payment::where(['user_id' => Auth::user()->id, 'partner_id' => $partner_id, 'amount' => $amount, 'pg_status' => 'waiting'])->first();
        if(!$payment) {
            $payment = new Payment();
            $payment->user_id = Auth::user()->id;
            $payment->partner_id = $partner_id;
            $payment->amount = $amount;
            $payment->pg_status = 'waiting';
            $payment->save();
        }

//        if($request->has('card_id')) {
//            $apiUrl = env('PAYBOX_URL')."v1/merchant/".env('PAYBOX_MERCHANT_ID')."/card/init";
//            $request = [
//                'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
//                'pg_amount' => $amount,
//                'pg_order_id' => $payment->id,
//                'pg_user_id' => $payment->user_id,
//                'pg_card_id' => $pg_card_id,
//                'pg_description' => 'Описание платежа',
//                'pg_salt' => env('PAYBOX_MERCHANT_SECRET'),
//                'pg_success_url_method' => 'POST',
//                'pg_failure_url_method' => 'GET',
//            ];
//            //generate a signature and add it to the array
//            ksort($request); //sort alphabetically
//            array_unshift($request, 'init');
//            array_push($request, env('PAYBOX_MERCHANT_SECRET')); //add your secret key (you can take it in your personal cabinet on paybox system)
//            $request['pg_sig'] = md5(implode(';', $request)); // signature
//            unset($request[0], $request[1]);
//
//            $client = new \GuzzleHttp\Client();
//            $response = $client->request('POST', $apiUrl, [
//                'headers' => [
//                    'Content-type' => 'application/x-www-form-urlencoded'
//                ],
//                'form_params' => $request
//            ]);
//            $response = $response->getBody()->getContents();
//            $responseXml = simplexml_load_string($response);
//
//            if ((string)$responseXml->pg_status == 'new') {
//                $pg_payment_id = (int) $responseXml->pg_payment_id;
//                $request = [
//                    'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
//                    'pg_payment_id' => $pg_payment_id,
//                    'pg_salt' => env('PAYBOX_MERCHANT_SECRET'),
//                ];
//                //generate a signature and add it to the array
//                ksort($request); //sort alphabetically
//                array_unshift($request, 'pay');
//                array_push($request, env('PAYBOX_MERCHANT_SECRET')); //add your secret key (you can take it in your personal cabinet on paybox system)
//                $request['pg_sig'] = md5(implode(';', $request)); // signature
//                unset($request[0], $request[1]);
//
//                $payUrl = env('PAYBOX_URL') . "v1/merchant/".env('PAYBOX_MERCHANT_ID')."/card/pay";
//
//                /*$response = $client->request('POST', $payUrl, [
//                    'headers' => [
//                        'Content-type' => 'application/x-www-form-urlencoded'
//                    ],
//                    'form_params' => $request
//                ]);
//                $response = $response->getBody()->getContents();
//                dd($response);*/
//
//                $query = http_build_query($request);
//
//                //redirect a customer to payment page
//                header("Location: $payUrl?".$query);
//                exit();
//            }
//
//
//            /*$redirect_url = (string) $responseXml->pg_redirect_url;
//            header('Location: ' . $redirect_url);
//            exit();*/
//
//        } else {
            $request = [
                'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
                'pg_amount' => $amount,
                'pg_salt' => env('PAYBOX_MERCHANT_SECRET'),
                'pg_order_id' => $payment->id,
                'pg_description' => 'Описание заказа',
                'pg_success_url' => 'https://paywin.kz/success/payment',
                'pg_failure_url' => 'https://paywin.kz/error/payment',
                'pg_success_url_method' => 'GET',
                'pg_failure_url_method' => 'GET',
            ];

            //$request['pg_testing_mode'] = 1; //add this parameter to request for testing payments

            //if you pass any of your parameters, which you want to get back after the payment, then add them. For example:
//        $request['client_name'] = $post['first_name'];
//        $request['office_id'] = $post['office_id'];
            // $request['client_address'] = 'Earth Planet';

            //generate a signature and add it to the array
            ksort($request); //sort alphabetically
            array_unshift($request, 'payment.php');
            array_push($request, env('PAYBOX_MERCHANT_SECRET')); //add your secret key (you can take it in your personal cabinet on paybox system)


            $request['pg_sig'] = md5(implode(';', $request));

            unset($request[0], $request[1]);

            $query = http_build_query($request);

            //redirect a customer to payment page
            header('Location: '.env('PAYBOX_URL').'payment.php?'.$query);
            exit();
//        }
    }

    public function paymentSuccess()
    {
        $payment = Payment::where(['user_id' => Auth::user()->id, 'pg_status' => 'waiting'])->orderBy('id', 'DESC')->first();
        if($payment) {
            $request = [
                'pg_merchant_id'=> env('PAYBOX_MERCHANT_ID'),
                'pg_order_id' => $payment->id,
                'pg_salt' => env('PAYBOX_MERCHANT_SECRET'),
            ];
        }


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
            $payment->pg_status = 'ok';
            $payment->pg_payment_id = (int) $responseXml->pg_payment_id;
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
        if($prize) {
            $share = Share::findOrFail($prize->share_id);
        } else {
            $share = null;
        }


        return view('thanks', compact('payment', 'prize', 'share'));
//        return view('thanks2');
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

    public function howItWorks()
    {
        return view('how_it_works');
    }
}
