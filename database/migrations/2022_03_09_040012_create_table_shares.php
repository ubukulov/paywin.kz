<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', [
                'share', 'discount', 'cashback', 'promocode'
            ]);
            $table->string('title');
            $table->integer('cnt')->nullable();
            $table->enum('promo', [
                'none', 'discount', 'money'
            ]);
            $table->integer('size')->nullable();
            $table->integer('from_order')->nullable();
            $table->integer('to_order')->nullable();
            $table->integer('c_winning')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('shares');
    }
}
