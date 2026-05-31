<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'user_name', 'password', 'name', 'email', 'status'
    ];

    protected $hidden = [
        'password',
    ];
}
