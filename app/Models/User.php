<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;
use Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'user_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function exists($phone)
    {
        $user = User::where(['phone' => $phone])->first();
        return ($user) ? true : false;
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class)->whereNotNull('category_id');
    }

    public function address()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function images()
    {
        return $this->hasMany(UserImage::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class)->where('cnt', '>', 0);
    }

    public function create_profile()
    {
        UserProfile::create([
            'user_id' => $this->id
        ]);
    }

    public static function isPrize($user_id, $payment_id)
    {
        $prize = Prize::where(['user_id' => $user_id, 'payment_id' => $payment_id])->first();
        return ($prize) ? true : false;
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

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $pg_api_url, [
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => $request
        ]);
        $response = $response->getBody()->getContents();
        $responseXml = simplexml_load_string($response);
        $cards = [];
        foreach($responseXml->card as $card) {
            $cards[] = [
                'id' => (int) $card->pg_card_id,
                'number' => (string) $card->pg_card_hash
            ];
        }

        return $cards;
    }
}
