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
        Schema::create('platform_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('partner_id')->nullable();
            $table->foreignId('agent_id')->nullable();

            $table->decimal('gross_amount', 12, 2);       // Вся сумма позиции (100%)
            $table->decimal('bank_fee_amount', 12, 2);    // Комиссия эквайринга (3%)
            $table->decimal('agent_fee_amount', 12, 2);   // Выплата агенту (4.9%)
            $table->decimal('platform_net_amount', 12, 2);// Чистый доход платформы (2.1%)

            $table->string('type')->default('referral_commission');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_earnings');
    }
};
