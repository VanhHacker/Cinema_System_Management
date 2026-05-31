<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory;

    // Mình đã thêm cột 'name' vào fillable như chúng ta vừa chốt ở phần trước
    protected $fillable = [
        'user_name', 'password', 'name', 'email', 'status', 'cinema_id'
    ];

    protected $hidden = [
        'password',
    ];

    // Nhân viên thuộc về 1 Rạp
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    // Nhân viên có thể tạo nhiều Hóa đơn
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
