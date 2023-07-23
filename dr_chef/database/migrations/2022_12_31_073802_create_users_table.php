<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_full_name');
            $table->string('user_username');
            $table->string('user_email')->unique();
            $table->string('user_password');
            $table->string('user_profile_pic')->nullable();
            $table->integer('user_age');
            $table->float('user_height');
            $table->float('user_weight');
            $table->string('user_activity');
            $table->string('user_disease');
            $table->string('user_gender');
            $table->string('user_weight_goal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
