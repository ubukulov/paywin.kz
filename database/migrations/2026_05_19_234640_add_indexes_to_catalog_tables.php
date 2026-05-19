<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Индексы для таблицы товаров
        Schema::table('products', function (Blueprint $table) {
            $table->index('product_category_id'); // Очень важно для whereIn()
            $table->index('is_active');
        });

        // Индексы для таблицы категорий
        Schema::table('product_categories', function (Blueprint $table) {
            $table->index('slug');
            $table->index('parent_id');
        });

        // Индексы для отзывов (Критично для withAvg и withCount)
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['product_category_id']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['parent_id']);
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['is_approved']);
        });
    }
};
