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
        Schema::create('partner_gift_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_gift_id');
            $table->decimal('min_order_total', 12, 2)->default(0); // порог
            $table->decimal('max_order_total', 12, 2)->nullable(); // опционально
            $table->decimal('chance', 5, 2)->default(100); // процент (0-100). 100=гарантированно
            $table->integer('max_per_user')->nullable();
            $table->timestamps();

            $table->foreign('partner_gift_id')->references('id')->on('partner_gifts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_gift_rules');
    }
};
