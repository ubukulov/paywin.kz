<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerProfile extends Model
{
    protected $table = 'partner_profiles';
    protected $fillable = [
        'partner_id',
        'category_id',
        'company',
        'logo',
        'phone',
        'address',
        'email',
        'site',
        'work_time',
        'description',
        'bank_name',
        'bank_account',
        'vk',
        'telegram',
        'instagram',
        'facebook',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
