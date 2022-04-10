<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns2ToUserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profile', function (Blueprint $table) {
            $table->string('vk')->after('description')->nullable();
            $table->string('telegram')->after('vk')->nullable();
            $table->string('instagram')->after('telegram')->nullable();
            $table->string('facebook')->after('instagram')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profile', function (Blueprint $table) {
            $table->dropColumn([
                'vk', 'telegram', 'instagram', 'facebook'
            ]);
        });
    }
}
