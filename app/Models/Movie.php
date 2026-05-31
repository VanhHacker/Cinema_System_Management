<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'duration', 'language', 'release_date', 'status', 'poster'
    ];

    // Quan hệ nhiều - nhiều với Thể loại
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_movie');
    }

    // 1 Phim có nhiều Suất chiếu
    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
