<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    // 1. Khai báo các cột được phép thêm dữ liệu (Tránh lỗi Mass Assignment)
    protected $fillable = [
        'seat_number',
        'description',
        'rows',
        'columns',
        'room_id',
        'type_seat_id',
        'status'
    ];

    // 2. Mối quan hệ: Một ghế (Seat) thuộc về một phòng chiếu (Room)
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // 3. Mối quan hệ: Một ghế (Seat) sẽ thuộc về một loại ghế (TypeSeat)
    // Cực kỳ quan trọng để lấy tên loại ghế và giá tiền hiển thị ra sơ đồ
    public function typeSeat()
    {
        return $this->belongsTo(TypeSeat::class, 'type_seat_id');
    }
}
