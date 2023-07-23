<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCalories extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_calorie_need',
        'user_daily_calorie_intake',
        'date',
        'user_carbs_intake',
        'user_protien_intake',
        'user_iron_intake',
        'user_dietaryfiber_intake',
        'user_sugar_intake',
        'user_calcium_intake',
        'user_magnesium_intake',
        'user_potassium_intake',
        'user_sodium_intake',
        'user_vitamin_c_intake',
        'user_vitamin_d_intake',
        'user_vitamin_b6_intake',
        'user_vitamin_b12_intake',
        'user_cholesterol_intake',
        'user_fats_intake'
    ];
}
