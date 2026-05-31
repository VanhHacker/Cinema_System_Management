<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\Movie;
use Carbon\Carbon;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy danh sách tất cả các rạp đang hoạt động
        $cinemas = Cinema::where('status', 1)->get();

        // 2. Xác định Rạp được chọn 
        // Lấy từ URL xuống, nếu không có thì mặc định lấy Rạp đầu tiên trong danh sách
        $selectedCinemaId = $request->query('cinema_id');
        if (!$selectedCinemaId && $cinemas->isNotEmpty()) {
            $selectedCinemaId = $cinemas->first()->id;
        }

        // 3. Xác định Ngày được chọn (Mặc định là ngày hôm nay)
        $selectedDate = $request->query('date', Carbon::today()->format('Y-m-d'));

        // 4. Tạo mảng 7 ngày liên tiếp để in ra các Nút (Tabs) trên giao diện
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = Carbon::today()->addDays($i);
        }

        // 5. Truy vấn: Lấy các PHIM có suất chiếu thỏa mãn Rạp và Ngày đã chọn
        $movies = collect(); // Mặc định là mảng rỗng
        
        if ($selectedCinemaId) {
            $movies = Movie::whereHas('showtimes', function($query) use ($selectedCinemaId, $selectedDate) {
                // Điều kiện 1: Phim phải có suất chiếu tại rạp này và trong ngày này
                $query->where('cinema_id', $selectedCinemaId)
                      ->whereDate('start_time', $selectedDate);
            })->with(['showtimes' => function($query) use ($selectedCinemaId, $selectedDate) {
                // Điều kiện 2: Khi load danh sách suất chiếu của phim đó ra, cũng phải lọc chuẩn Rạp & Ngày
                $query->where('cinema_id', $selectedCinemaId)
                      ->whereDate('start_time', $selectedDate)
                      ->orderBy('start_time', 'asc') // Sắp xếp giờ chiếu từ sáng đến tối
                      ->with('room'); // Load sẵn thông tin phòng chiếu để hiển thị
            }])->get();
        }

        // Trả toàn bộ dữ liệu ra file giao diện bạn đã tạo ở bước trước
        return view('client.showtimes', compact(
            'cinemas', 'selectedCinemaId', 'dates', 'selectedDate', 'movies'
        ));
    }
}