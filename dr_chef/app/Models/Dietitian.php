<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dietitian extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable =  [
        'dietitian_full_name',
        'dietitian_username',
        'dietitian_email',
        'dietitian_phone_number',
        'dietitian_password',
        'dietitian_profile_pic',
        'dietitian_certificate',
        'verification_status',
        'dietitian_likes'
    ];
}
