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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('subtotal', 12, 2);   // сумма товаров без скидок
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2); // итоговая сумма после скидок и доставки
            $table->enum('status', [
                'pending',
                'paid',
                'cancelled',
            ])->default('pending');
            $table->string('payment_method')->nullable(); // например: 'card', 'cash', 'paypal'
            $table->string('shipping_method')->nullable(); // например: 'courier', 'pickup'
            $table->string('shipping_address')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
