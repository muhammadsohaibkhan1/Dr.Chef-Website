<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable =  [
        'exercise_name',
        'exercise_image',
        'met_value'
    ];
}
