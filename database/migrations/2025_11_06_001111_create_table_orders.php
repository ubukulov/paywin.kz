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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('partner_id')->constrained('users'); // Кто продавец
            $table->foreignId('warehouse_id')->nullable()->constrained('partner_warehouses'); // С какой точки

            // Реферальная система
            $table->foreignId('agent_id')->nullable()->constrained('users');
            $table->foreignId('user_discount_id')->nullable()->constrained('user_discounts'); // Какую скидку применил

            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Расширенные статусы
            $table->string('status')->default('pending')->index();

            $table->string('payment_method')->nullable();
            $table->string('shipping_method')->nullable();
            $table->text('shipping_address')->nullable();

            $table->json('data')->nullable(); // Здесь можно хранить коммент к заказу или данные курьера
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
