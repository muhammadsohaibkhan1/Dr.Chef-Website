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
        Schema::create('diet_plans', function (Blueprint $table) {
            $table->id('diet_plan_id');
            $table->unsignedBigInteger('dietitian_id');
            $table->foreign('dietitian_id')->references('dietitian_id')->on('dietitians');
            $table->integer('diet_plan_duration');
            $table->integer('diet_plan_likes');
            $table->string('diet_plan_type');
            $table->string('diet_plan_user_type');
            $table->integer('diet_plan_weight_goal');
            $table->text('diet_plan_meals');
            $table->integer('diet_plan_reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diet_plans');
    }
};
