<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Может пополняется самим пользователям
        // с помощью кэшбека
        // с помощью промокода

        Schema::create('user_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pg_payment_id')->nullable();
            $table->enum('type', [
                'payment', 'cashback', 'promocode'
            ]);
            $table->bigInteger('amount');
            $table->enum('status', [
                'waiting', 'ok', 'error'
            ]);

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
        Schema::dropIfExists('users_balances');
    }
}
