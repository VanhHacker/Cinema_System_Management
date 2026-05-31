<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'contact_info', 'rating'
    ];

    // 1 Rạp có nhiều Phòng chiếu
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // 1 Rạp có nhiều Nhân viên
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
