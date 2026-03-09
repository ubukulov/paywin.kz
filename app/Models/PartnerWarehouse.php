<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerWarehouse extends Model
{
    use HasFactory;

    protected $table = 'partner_warehouses';

    protected $fillable = [
        'partner_id',
        'city_id',
        'name',
        'address',
        'contacts'
    ];

    protected $casts = [
        'contacts' => 'array'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
