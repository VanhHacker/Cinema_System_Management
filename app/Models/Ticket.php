<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'seat_id',
        'showtime_id',
        'price',   
        'status',    
    ];

    // Vé thuộc về 1 Suất chiếu
    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    // Vé áp dụng cho 1 Ghế
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    // Vé nằm trong 1 Hóa đơn
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
