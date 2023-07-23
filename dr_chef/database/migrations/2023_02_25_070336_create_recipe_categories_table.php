<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\RecipeCategory;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name');
        });

        $data = [
            [
                'category_name' => 'Breakfast',
            ],
            [
                'category_name' => 'Lunch',
            ],
            [
                'category_name' => 'Dinner',
            ],
            [
                'category_name' => 'Seafood',
            ],
            [
                'category_name' => 'Salad',
            ],
            [
                'category_name' => 'Soup',
            ],
            [
                'category_name' => 'Vegetarian',
            ],
            [
                'category_name' => 'Fast Food',
            ],
            [
                'category_name' => 'Dessert',
            ],
            [
                'category_name' => 'Drink',
            ],
        ];
        RecipeCategory::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_categories');
    }
};
