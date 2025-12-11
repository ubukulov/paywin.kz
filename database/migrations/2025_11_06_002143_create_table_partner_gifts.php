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
        Schema::create('partner_gifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['physical','discount','cashback', 'promocode'])->default('discount');
            $table->string('partner')->nullable();
            $table->json('payload')->nullable(); // данные (код купона и т.п.)
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->foreign('partner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_gifts');
    }
};
