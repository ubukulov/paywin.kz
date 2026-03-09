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
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);

            // Статусы: pending (ожидание), processing (в работе), completed (выплачено), rejected (отклонено)
            $table->string('status')->default('pending')->index();

            // Снапшот реквизитов (чтобы знать, куда именно ушли деньги, если профиль изменится)
            $table->json('data')->nullable();

            $table->text('comment')->nullable(); // Причина отказа
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
