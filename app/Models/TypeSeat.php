<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'basePrice', 'description'
    ];

    // Loại ghế này áp dụng cho nhiều Ghế
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
