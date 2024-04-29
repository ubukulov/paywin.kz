<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDiscount extends Model
{
    protected $table = 'user_discounts';

    protected $fillable = [
        'user_id', 'partner_id', 'size', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}
