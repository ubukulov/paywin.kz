<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'avatar',
        'full_name',
        'sex',
        'birth_date',
        'vk',
        'telegram',
        'instagram',
        'facebook',
        'bank_name',
        'bank_account',
        'agreement'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAgreementUrl()
    {
        return env('APP_URL') . $this->agreement;
    }
}
