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

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true)->latest();
    }

    /**
     * Аксессор для среднего рейтинга.
     * Поддерживает как eager loading (withAvg), так и обычный вызов.
     */
    public function getApprovedReviewsAvgRatingAttribute(): float
    {
        // Если мы использовали ->withAvg('reviews', 'rating') в контроллере,
        // Laravel автоматически запишет результат в атрибут 'reviews_avg_rating'
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            return round((float) $this->attributes['reviews_avg_rating'], 1);
        }

        // Фолбек, если жадная загрузка не была вызвана (например, на детальной странице)
        return round((float) $this->reviews()->avg('rating'), 1) ?: 0.0;
    }

    /**
     * Аксессор для количества отзывов.
     * Поддерживает как eager loading (withCount), так и обычный вызов.
     */
    public function getApprovedReviewsCountAttribute(): int
    {
        // Если мы использовали ->withCount('reviews') в контроллере,
        // Laravel запишет результат в 'reviews_count'
        if (array_key_exists('reviews_count', $this->attributes)) {
            return (int) $this->attributes['reviews_count'];
        }

        // Фолбек на случай одиночного вызова
        return $this->reviews()->count();
    }

// Быстрый подсчет среднего рейтинга
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?? 0;
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

    /**
     * Проверяет, покупал ли указанный пользователь этот товар и оплачен ли заказ
     */
    public function isPurchasedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return OrderItem::where('product_id', $this->id)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereIn('status', [
                        \App\Enums\OrderEnum::PAID->value ?? 'paid',
                        \App\Enums\OrderEnum::COMPLETED->value ?? 'completed',
                    ]);
            })
            ->exists();
    }

}
