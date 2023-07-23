<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chef extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable =  [
        'chef_full_name',
        'chef_username',
        'chef_email',
        'chef_password',
        'chef_profile_pic',
        'chef_likes'
    ];
}
