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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Кому принадлежат деньги
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Сумма: положительная (приход) или отрицательная (расход/вывод)
            $table->decimal('amount', 15, 2);

            // Тип операции (Enum или String)
            // referral_income, cashback, sale_income, withdrawal, refund, adjustment
            $table->string('type')->index();

            // Слепок баланса (КРИТИЧЕСКИ ВАЖНО для аудита)
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);

            // Описание для пользователя на понятном языке
            $table->string('description')->nullable();

            // Полиморфная связь: к чему относится транзакция?
            // Например: к Order (покупка), к PayoutRequest (вывод) или к Referral (бонус)
            $table->nullableMorphs('source');

            // Дополнительные данные (например, ID банковского перевода)
            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
