<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'sku', 'name', 'slug', 'description', 'active', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function mainImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductImage::class)->where('main', true);
    }

    protected static function booted(): void
    {
        static::saving(function ($product) {
            // Если slug не указан вручную — генерируем из name
            if (empty($product->slug) && !empty($product->name)) {
                $product->slug = Str::slug($product->name);

                // Проверяем, что slug уникален
                $originalSlug = $product->slug;
                $counter = 1;

                while (static::where('slug', $product->slug)->where('id', '!=', $product->id)->exists()) {
                    $product->slug = $originalSlug . '-' . $counter++;
                }
            }
        });
    }

}
