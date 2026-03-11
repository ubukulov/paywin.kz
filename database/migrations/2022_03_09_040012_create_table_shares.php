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
            $table->unsignedBigInteger('partner_id');
            $table->string('type');

            $table->string('title');
            $table->string('code')->nullable()->unique();

            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->integer('count')->default(0);
            $table->integer('used_count')->default(0);
            $table->json('data')->nullable();

            /*
            $table->enum('promo', [
                'none', 'discount', 'money', 'gift'
            ]);
            $table->integer('size')->nullable();
            $table->string('gift_title')->nullable();
            $table->decimal('agent_percent', 5.2)->default(0.00);;
            $table->integer('from_order')->nullable();
            $table->integer('to_order')->nullable();
            $table->integer('c_winning')->nullable();*/


            $table->foreign('partner_id')
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
