<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cviebrock\EloquentSluggable\Sluggable;

class ProductCategory extends Model
{
    use Sluggable;

    protected $table = 'product_categories';
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function parent() : BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children() : HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
