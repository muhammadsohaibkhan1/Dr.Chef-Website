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
        Schema::create('recipe_nutrients', function (Blueprint $table) {
            $table->id('recipe_nutrients_id');
            $table->unsignedBigInteger('recipe_id');
            $table->foreign('recipe_id')->references('recipe_id')->on('recipes');
            $table->integer('recipe_calories');
            $table->float('recipe_carbs')->nullable();
            $table->float('recipe_protien')->nullable();
            $table->float('recipe_iron')->nullable();
            $table->float('recipe_dietaryfiber')->nullable();
            $table->float('recipe_sugar')->nullable();
            $table->float('recipe_calcium')->nullable();
            $table->float('recipe_magnesium')->nullable();
            $table->float('recipe_potassium')->nullable();
            $table->float('recipe_sodium')->nullable();
            $table->float('recipe_vitamin_c')->nullable();
            $table->float('recipe_vitamin_d')->nullable();
            $table->float('recipe_vitamin_b6')->nullable();
            $table->float('recipe_vitamin_b12')->nullable();
            $table->float('recipe_cholesterol')->nullable();
            $table->float('recipe_fats')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_nutrients');
    }
};
