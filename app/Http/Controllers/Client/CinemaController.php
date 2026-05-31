<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\TypeSeat;
use App\Models\Showtime;
use Carbon\Carbon; // Nhớ thêm thư viện Carbon để xử lý ngày giờ

class CinemaController extends Controller
{
    // 1. Hàm hiển thị danh sách tất cả các rạp 
    public function index()
    {
        $cinemas = Cinema::all(); 
        return view('client.cinemasList', compact('cinemas'));
    }

    // 2. Hàm hiển thị chi tiết 1 rạp (Lịch chiếu & Giá vé)
    // Khai báo thêm (Request $request) để bắt tham số trên URL
    public function show(Request $request, $id)
    {
        $cinema = Cinema::findOrFail($id);
        $typeSeats = TypeSeat::all();

        // 1. Lấy ngày người dùng đang chọn trên thanh trượt (Mặc định nếu không có thì là Hôm nay)
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        // 2. Khởi tạo query lấy lịch chiếu của rạp
        $query = Showtime::with('movie')
            ->whereHas('room', function($q) use ($id) {
                $q->where('cinema_id', $id);
            })
            ->whereDate('start_time', $selectedDate) 
            ->orderBy('start_time', 'asc');

        // 3. LOGIC NÂNG CAO: Nếu ngày đang xem là ngày hôm nay, chỉ lấy suất chiếu từ giờ phút hiện tại trở đi (ẩn các suất đã qua)
        if ($selectedDate === now()->format('Y-m-d')) {
            $query->where('start_time', '>=', now());
        }

        // 4. Lấy dữ liệu và nhóm theo ID Phim
        $showtimes = $query->get()->groupBy('movie_id');

        
        return view('client.showtimes', compact('cinema', 'typeSeats', 'showtimes', 'selectedDate'));
    }
}