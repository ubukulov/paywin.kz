<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'path', 'main', 'position'
    ];

    protected $casts = [
        'main' => 'boolean'
    ];

    protected $appends = [
        'url'
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    protected static function booted(): void
    {
        static::saved(function (self $image) {
            if ($image->main) {
                static::where('product_id', $image->product_id)
                    ->where('id', '!=', $image->id)
                    ->update(['main' => false]);
            }
        });
    }
}
