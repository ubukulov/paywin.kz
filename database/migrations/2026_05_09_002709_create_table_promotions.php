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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->enum('scope', ['all', 'partners', 'products']); // Охват: все, только партнеры или товары
            $table->enum('reward_type', ['guaranteed', 'raffle']); // Награда: гарантированная или билет на розыгрыш
            $table->json('prizes')->nullable(); // Список призов (если гарантированные)
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
