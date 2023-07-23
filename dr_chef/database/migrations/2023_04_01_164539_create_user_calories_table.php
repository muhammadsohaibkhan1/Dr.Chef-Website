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
        Schema::create('user_calories', function (Blueprint $table) {
            $table->id('user_calories_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->integer('user_calorie_need');
            $table->integer('user_daily_calorie_intake');
            $table->date('date');
            $table->float('user_carbs_intake')->nullable();
            $table->float('user_protien_intake')->nullable();
            $table->float('user_iron_intake')->nullable();
            $table->float('user_dietaryfiber_intake')->nullable();
            $table->float('user_sugar_intake')->nullable();
            $table->float('user_calcium_intake')->nullable();
            $table->float('user_magnesium_intake')->nullable();
            $table->float('user_potassium_intake')->nullable();
            $table->float('user_sodium_intake')->nullable();
            $table->float('user_vitamin_c_intake')->nullable();
            $table->float('user_vitamin_d_intake')->nullable();
            $table->float('user_vitamin_b6_intake')->nullable();
            $table->float('user_vitamin_b12_intake')->nullable();
            $table->float('user_cholesterol_intake')->nullable();
            $table->float('user_fats_intake')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_calories');
    }
};
