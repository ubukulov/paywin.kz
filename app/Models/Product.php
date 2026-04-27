<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'product_category_id',
        'sku',
        'name',
        'slug',
        'description',
        'is_active',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function warehouses()
    {
        return $this->belongsToMany(PartnerWarehouse::class, 'product_stocks', 'product_id', 'warehouse_id')
            ->withPivot('price', 'old_price', 'quantity') // указываем все поля из таблицы product_stocks
            ->withTimestamps();
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function mainImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('main', true)->withDefault([
            'path' => 'defaults/no-image.png' // Картинка-заглушка
        ]);
    }

    // Пример метода в модели ProductImage
    public function setAsMain()
    {
        self::where('product_id', $this->product_id)->update(['main' => false]);
        $this->update(['main' => true]);
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
