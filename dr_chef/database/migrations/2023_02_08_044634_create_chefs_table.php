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
        Schema::create('chefs', function (Blueprint $table) {
            $table->id('chef_id');
            $table->string('chef_full_name');
            $table->string('chef_username');
            $table->string('chef_email')->unique();
            $table->string('chef_password');
            $table->string('chef_profile_pic')->nullable();
            $table->integer('chef_likes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chefs');
    }
};
