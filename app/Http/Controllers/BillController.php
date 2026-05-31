<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\PaymentMethod; 
use Illuminate\Http\Request;

class BillController extends Controller
{
    // 1. Hiển thị danh sách Hóa đơn
    public function index(Request $request)
    {
        // 2. LẤY DANH SÁCH CỔNG THANH TOÁN TỪ DATABASE LÊN
        $paymentMethods = PaymentMethod::all();

        // Khởi tạo query và load kèm các quan hệ
        $query = Bill::with(['customer', 'paymentMethod', 'staff']);

        // --- XỬ LÝ BỘ LỌC ---

        // 1. Tìm theo Mã Hóa đơn
        if ($request->filled('search_code')) {
            $cleanCode = str_replace('#INV', '', $request->search_code);
            $cleanCode = ltrim($cleanCode, '0'); 
            $query->where('id', 'like', '%' . $cleanCode . '%');
        }

        // 2. Tìm theo Tên hoặc SĐT Khách hàng
        if ($request->filled('search_customer')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_customer . '%')
                  ->orWhere('phone', 'like', '%' . $request->search_customer . '%');
            });
        }

        // 3. Lọc theo Phương thức thanh toán
        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        // 4. Lọc theo Ngày giao dịch
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Thực thi query, sắp xếp và phân trang
        $bills = $query->orderBy('id', 'desc')->paginate(15)->appends($request->all());

        // 3. TRUYỀN CẢ 2 BIẾN $bills VÀ $paymentMethods SANG VIEW
        return view('admin.bills.index', compact('bills', 'paymentMethods'));
    }

    // 2. Xem chi tiết 1 Hóa đơn
    public function show($id)
    {
        $bill = Bill::with([
            'customer',
            'paymentMethod',
            'staff',
            'tickets.seat.room',
            'tickets.showtime.movie'
        ])->findOrFail($id);

        return view('admin.bills.show', compact('bill'));
    }
}