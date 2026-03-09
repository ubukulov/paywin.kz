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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            $table->string('path'); // путь к файлу
            $table->string('disk')->default('public'); // local, s3, etc.

            $table->boolean('main')->default(false)->index(); // Быстрый поиск главной картинки
            $table->integer('position')->default(0); // Для сортировки в галерее

            $table->string('mime_type')->nullable(); // image/webp, image/jpeg
            $table->unsignedBigInteger('size')->nullable(); // размер в байтах

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
