<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profile';

    protected $fillable = [
        'user_id', 'company', 'logo', 'phone', 'address', 'email', 'site', 'work_time', 'description',
        'vk', 'telegram', 'instagram', 'facebook', 'full_name', 'sex', 'birth_date'
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
}
