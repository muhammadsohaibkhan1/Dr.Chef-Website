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
        Schema::create('dietitians', function (Blueprint $table) {
            $table->id('dietitian_id');
            $table->string('dietitian_full_name');
            $table->string('dietitian_username');
            $table->string('dietitian_email')->unique();
            $table->string('dietitian_phone_number')->unique();
            $table->string('dietitian_password');
            $table->string('dietitian_profile_pic')->nullable();
            $table->string('dietitian_certificate');
            $table->string('verification_status');
            $table->integer('dietitian_likes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dietitians');
    }
};
