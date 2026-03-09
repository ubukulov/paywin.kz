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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');

            // Снапшоты данных
            $table->string('product_name'); // Чтобы имя не поменялось в истории
            $table->string('product_sku')->nullable();

            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2); // Цена за 1 шт.
            $table->decimal('total', 12, 2); // Итого по позиции

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
