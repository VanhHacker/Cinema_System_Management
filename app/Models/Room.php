<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name', 'number_of_seats', 'status', 'cinema_id'
    ];

    // Phòng chiếu thuộc về 1 Rạp
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    // Phòng chiếu có nhiều Ghế
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    // Phòng chiếu có nhiều Suất chiếu
    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
