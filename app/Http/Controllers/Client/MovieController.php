<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Hiển thị trang chi tiết của 1 bộ phim kèm Lịch chiếu
     */
    public function show($id)
    {
        $movie = Movie::findOrFail($id);

        // Lấy lịch chiếu từ thời điểm hiện tại trở đi (không hiển thị suất chiếu trong quá khứ)
        $showtimes = Showtime::with(['room'])
            ->where('movie_id', $id)
            ->where('start_time', '>=', now()) // Lấy suất chiếu từ bây giờ
            ->orderBy('start_time', 'asc')
            ->get()
            ->groupBy(function ($showtime) {
                // Tách lấy phần "Ngày" (Y-m-d) từ cột start_time để làm nhóm
                return Carbon::parse($showtime->start_time)->format('Y-m-d');
            });

        return view('client.movies.show', compact('movie', 'showtimes'));
    }
}