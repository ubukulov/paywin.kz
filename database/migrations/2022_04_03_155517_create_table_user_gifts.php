<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserGifts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_gifts', function (Blueprint $table) {
            $table->id();
            // Владелец подарка
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Ссылка на акцию в таблице shares (где описаны условия и название подарка)
            $table->foreignId('share_id')->nullable()->constrained('shares');

            // Название подарка на момент получения (чтобы оно осталось в истории, если акция изменится)
            $table->string('name');

            // Статусы: available (можно получить), claimed (уже выдан), expired (протух)
            $table->string('status')->default('available')->index();

            // Срок, до которого нужно забрать подарок
            $table->timestamp('valid_until')->nullable();

            // Полиморфный источник (Order, Promo, Referral и т.д.)
            $table->nullableMorphs('source');

            // Дополнительные данные (например, код для получения на кассе или выбранный размер/цвет)
            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_gifts');
    }
}
