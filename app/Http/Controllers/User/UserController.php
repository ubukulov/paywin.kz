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
        $user = Auth::user();
        $user_profile = Auth::user()->profile;
        return view('user.home', compact('user_profile', 'user'));
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

        /*$curl = curl_init($pg_api_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $response = curl_exec($curl);
        curl_close($curl);
        dd($response->pg_redirect_url);
        $redirect_url = strstr($response, 'https');
        $redirect_url = strstr($redirect_url, 'ok', true);
        $redirect_url = strstr($redirect_url, '</', true);
        header('Location: ' . $redirect_url);
        exit();*/

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

        /*$curl = curl_init();
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
        dd($res);*/

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $pg_api_url, [
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => $request
        ]);
        $response = $response->getBody()->getContents();
        $responseXml = simplexml_load_string($response);
        foreach($responseXml->card as $card) {
            dd($card);
        }
        dd($responseXml->card[1]);
        $pg_card_hash = (string) $responseXml->pg_card_hash;
        dd($pg_card_hash);
    }

    public function removeMyCard()
    {
        $pg_merchant_id = 511867;
        $secret_key = "y7crxXTrz6SXKPNd";
        $salt = Str::random(15);
        $pg_card_id = 41620811;
        $pg_api_url = "https://api.paybox.money/v1/merchant/$pg_merchant_id/cardstorage/remove";

        $request = [
            'pg_merchant_id'=> $pg_merchant_id,
            'pg_user_id' => Auth::user()->id,
            'pg_card_id' => $pg_card_id,
            'pg_salt' => $salt,
        ];

        //generate a signature and add it to the array
        ksort($request); //sort alphabetically
        array_unshift($request, 'remove');
        array_push($request, $secret_key); //add your secret key (you can take it in your personal cabinet on paybox system)

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
        return view('user.history');
    }

    public function settings()
    {
        $user = Auth::user();
        $user_profile = $user->profile;
        return view('user.settings', compact('user_profile'));
    }
}
