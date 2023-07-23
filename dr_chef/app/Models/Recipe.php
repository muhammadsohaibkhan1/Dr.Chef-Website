<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'chef_id',
        'category_id',
        'recipe_name',
        'recipe_image',
        'recipe_video',
        'recipe_cooking_time',
        'recipe_servings',
        'recipe_ingredients',
        'recipe_instructions',
        'recipe_user_type',
        'recipe_likes',
        'recipe_reports',
    ];

}
