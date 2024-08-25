<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profile';

    protected $fillable = [
        'user_id', 'company', 'logo', 'phone', 'address', 'email', 'site', 'work_time', 'description',
        'vk', 'telegram', 'instagram', 'facebook', 'full_name', 'sex', 'birth_date', 'bank_name',
        'bank_account', 'card_number', 'percent', 'agreement'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getLastNumberOfCard()
    {
        return (is_null($this->card_number)) ? 9981 : substr($this->card_number,strlen($this->card_number)-4);
    }

    public function getAgreementUrl()
    {
        return env('APP_URL') . $this->agreement;
    }
}
