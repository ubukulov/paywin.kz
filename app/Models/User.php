<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
}
