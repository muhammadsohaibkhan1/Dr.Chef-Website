<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable =  [
        'dietitian_id',
        'diet_plan_duration',
        'diet_plan_likes',
        'diet_plan_type',
        'diet_plan_user_type',
        'diet_plan_weight_goal',
        'diet_plan_meals',
        'diet_plan_reports',
    ];
}
