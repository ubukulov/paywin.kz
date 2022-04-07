<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'cnt', 'promo', 'size', 'from_order', 'to_order', 'c_winning', 'from_date', 'to_date'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
