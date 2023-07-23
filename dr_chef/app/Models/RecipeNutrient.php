<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeNutrient extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'recipe_calories',
        'recipe_carbs',
        'recipe_protien',
        'recipe_iron',
        'recipe_dietaryfiber',
        'recipe_sugar',
        'recipe_calcium',
        'recipe_magnesium',
        'recipe_potassium',
        'recipe_sodium',
        'recipe_vitamin_c',
        'recipe_vitamin_d',
        'recipe_vitamin_b6',
        'recipe_vitamin_b12',
        'recipe_cholesterol',
        'recipe_fats',
    ];
}
