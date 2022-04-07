<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Str;

class UserController extends Controller
{
    public function cabinet()
    {
        $user_profile = Auth::user()->profile;
        return view('user.home', compact('user_profile'));
    }

    public function addMyCard()
    {
        $pg_merchant_id = 511867;
        $secret_key = "y7crxXTrz6SXKPNd";
        $salt = Str::random(15);
        $pg_api_url = "https://api.paybox.money/v1/merchant/$pg_merchant_id/cardstorage/add";

        $request = [
            'pg_merchant_id'=> $pg_merchant_id,
            'pg_user_id' => Auth::user()->id,
            'pg_post_link' => 'https://paywin.kz/user/add-my-card/result',
            'pg_back_link' => 'https://paywin.kz/user',
            'pg_salt' => $salt,
        ];

        //generate a signature and add it to the array
        ksort($request); //sort alphabetically
        array_unshift($request, 'add');
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
        echo $res;
    }

    public function addMyCardResult()
    {
        dd("OK");
    }

    public function getMyCards()
    {
        $pg_merchant_id = '511867';
        $pg_user_id = (string) Auth::user()->id;
        $secret_key = "y7crxXTrz6SXKPNd";
        $salt = Str::random(15);
        $pg_api_url = "https://api.paybox.money/v1/merchant/$pg_merchant_id/cardstorage/list";

        $request = [
            'pg_merchant_id'=> $pg_merchant_id,
            'pg_user_id' => $pg_user_id,
            'pg_salt' => $salt,
        ];

        //generate a signature and add it to the array
        ksort($request); //sort alphabetically
        array_unshift($request, 'list');
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
        echo $res;
    }

    public function earn()
    {
        return view('user.earn');
    }

    public function history()
    {
        return view('user.history');
    }

    public function settings()
    {
        return view('user.settings');
    }
}
