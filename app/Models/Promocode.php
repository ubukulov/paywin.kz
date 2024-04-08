<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $fillable = [
        'user_id', 'name', 'amount', 'start', 'end'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
