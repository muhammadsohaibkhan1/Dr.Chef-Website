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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id('recipe_id');
            $table->unsignedBigInteger('chef_id');
            $table->foreign('chef_id')->references('chef_id')->on('chefs');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('category_id')->on('recipe_categories');
            $table->string('recipe_name');
            $table->string('recipe_image');
            $table->text('recipe_ingredients');
            $table->text('recipe_instructions');
            $table->string('recipe_video');
            $table->integer('recipe_servings');
            $table->float('recipe_cooking_time');
            $table->string('recipe_user_type');
            $table->integer('recipe_likes');
            $table->integer('recipe_reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
};
