<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'status'
    ];

    // 1 Phương thức TT có thể dùng cho nhiều Hóa đơn
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
