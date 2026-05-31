<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time', 'end_time', 'movie_id', 'room_id'
    ];

    // Suất chiếu chiếu 1 Phim
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Suất chiếu diễn ra tại 1 Phòng
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // 1 Suất chiếu bán nhiều Vé
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
