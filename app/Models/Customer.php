<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'user_name', 'password', 'name', 'email', 'phone', 'status'
    ];

    protected $hidden = [
        'password',
    ];

    // Khách hàng có nhiều Hóa đơn
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
