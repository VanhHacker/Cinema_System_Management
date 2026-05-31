<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Cinema; 
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để xem lịch sử đặt vé!');
        }

        $customerId = Auth::guard('customer')->id();
        
        // 2. Lấy danh sách rạp để đổ vào Dropdown lọc
        $cinemas = Cinema::all();

        // 3. Khởi tạo Query lấy Hóa đơn
        $query = Bill::with([
                'tickets.showtime.movie', 
                'tickets.seat.room.cinema'
            ])
            ->where('customer_id', $customerId);

        // --- XỬ LÝ BỘ LỌC ---

        // Lọc theo Tên phim
        if ($request->filled('search_movie')) {
            $query->whereHas('tickets.showtime.movie', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_movie . '%');
            });
        }

        // Lọc theo Cụm rạp
        if ($request->filled('cinema_id')) {
            $query->whereHas('tickets.seat.room', function ($q) use ($request) {
                $q->where('cinema_id', $request->cinema_id);
            });
        }

        // Lọc theo Ngày chiếu (Lấy theo ngày của suất chiếu, không phải ngày mua vé)
        if ($request->filled('date')) {
            $query->whereHas('tickets.showtime', function ($q) use ($request) {
                $q->whereDate('start_time', $request->date);
            });
        }

        // 4. Lấy dữ liệu & Phân trang (appends để giữ nguyên bộ lọc khi sang trang 2)
        $bills = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());

        return view('client.history', compact('bills', 'cinemas'));
    }
}