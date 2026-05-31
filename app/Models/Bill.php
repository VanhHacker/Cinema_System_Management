<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',       // ID khách hàng
        'total',      // Tổng tiền (bạn check lại xem DB bạn đặt là total_amount hay total_price nhé)
        'payment_method_id', // Phương thức thanh toán
       
    ];

    // Hóa đơn của 1 Khách hàng
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Hóa đơn thanh toán bằng 1 Phương thức
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Hóa đơn được xử lý bởi 1 Nhân viên (có thể null nếu đặt online)
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    // 1 Hóa đơn bao gồm nhiều Vé
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
