<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Prize;
use Illuminate\Http\Request;
use Auth;
use Str;

class UserController extends Controller
{
    public function cabinet()
    {
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
        return view('user.earn');
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
}
