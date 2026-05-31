<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShowtimeController extends Controller
{
    // 1. Hiển thị danh sách Lịch chiếu
    public function index(Request $request)
    {
        $cinemas = Cinema::all();
        $query = Showtime::with(['movie', 'room.cinema']);

        if ($request->filled('search_movie')) {
            $query->whereHas('movie', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_movie . '%');
            });
        }

        if ($request->filled('cinema_id')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('cinema_id', $request->cinema_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        $showtimes = $query->orderBy('start_time', 'desc')->paginate(15)->appends($request->all());

        return view('admin.showtimes.index', compact('showtimes', 'cinemas'));
    }

    // 2. Hiển thị form Thêm mới
    public function create()
    {
        $movies = Movie::where('status', 1)->get(); 
        $cinemas = Cinema::all(); 
        
        // Chỉ lấy các phòng Đang hoạt động (status = 1) để đưa ra Form
        $rooms = Room::with(['seats.typeSeat'])->where('status', 1)->get();     

        return view('admin.showtimes.create', compact('movies', 'cinemas', 'rooms'));
    }

    // 3. Xử lý lưu Lịch chiếu mới (Chống trùng giờ + 1h dọn dẹp + Chống xếp phòng hỏng)
    public function store(Request $request)
    {
        $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date',
        ]);

        // Chặn cứng ở Backend phòng trường hợp cố tình hack/lỗi tab
        $room = Room::find($request->room_id);
        if ($room && $room->status == 0) {
            return back()->withInput()->with('error', 'Lỗi: Phòng chiếu [' . $room->room_name . '] hiện đang bảo trì. Không thể xếp lịch!');
        }

        $movie = Movie::findOrFail($request->movie_id);
        
        $newStartTime = Carbon::parse($request->start_time);
        $newEndTime = $newStartTime->copy()->addMinutes($movie->duration); 
        $newEndWithCleanup = $newEndTime->copy()->addMinutes(60); 

        $existingShowtimes = Showtime::with('movie')
            ->where('room_id', $request->room_id)
            ->whereBetween('start_time', [
                $newStartTime->copy()->subDay(), 
                $newStartTime->copy()->addDay()
            ])->get();

        foreach ($existingShowtimes as $oldShow) {
            $oldStart = Carbon::parse($oldShow->start_time);
            $oldEnd = Carbon::parse($oldShow->end_time ?? $oldStart->copy()->addMinutes($oldShow->movie->duration)); 
            $oldEndWithCleanup = $oldEnd->copy()->addMinutes(60); 

            if ($newStartTime < $oldEndWithCleanup && $newEndWithCleanup > $oldStart) {
                $timeStart = $oldStart->format('H:i d/m/Y');
                $timeEnd = $oldEndWithCleanup->format('H:i d/m/Y');
                
                return back()->withInput()->withErrors([
                    'overlap' => "🚨 XUNG ĐỘT: Phòng đang bận chiếu phim '{$oldShow->movie->name}' từ {$timeStart} đến {$timeEnd} (Đã bao gồm 1 tiếng dọn dẹp). Vui lòng chọn giờ khác!"
                ]);
            }
        }

        Showtime::create([
            'movie_id' => $request->movie_id,
            'room_id'  => $request->room_id,
            'start_time' => $newStartTime,
            'end_time' => $newEndTime, 
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Xếp lịch chiếu thành công!');
    }

    // 4. Hiển thị form Cập nhật
    public function edit(Showtime $showtime)
    {
        $movies = Movie::all();
        $cinemas = Cinema::all(); 

        //  Khi sửa, cũng chỉ cho phép đổi sang các phòng Đang hoạt động
        $rooms = Room::with(['cinema', 'seats.typeSeat'])->where('status', 1)->get();

        return view('admin.showtimes.edit', compact('showtime', 'movies', 'cinemas', 'rooms'));
    }

    // 5. Xử lý cập nhật Lịch chiếu (Đồng bộ bảo vệ Lớp 2)
    public function update(Request $request, $id)
    {
        $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date',
        ]);

        // Chặn cứng ở Backend nếu cập nhật vào phòng đang bảo trì
        $room = Room::find($request->room_id);
        if ($room && $room->status == 0) {
            return back()->withInput()->with('error', 'Lỗi: Phòng chiếu [' . $room->room_name . '] hiện đang bảo trì. Không thể xếp lịch!');
        }

        $showtime = Showtime::findOrFail($id);
        $movie = Movie::findOrFail($request->movie_id);

        $newStartTime = Carbon::parse($request->start_time);
        $newEndTime = $newStartTime->copy()->addMinutes($movie->duration); 
        $newEndWithCleanup = $newEndTime->copy()->addMinutes(60); 

        $existingShowtimes = Showtime::with('movie')
            ->where('room_id', $request->room_id)
            ->where('id', '!=', $id) 
            ->whereBetween('start_time', [
                $newStartTime->copy()->subDay(), 
                $newStartTime->copy()->addDay()
            ])->get();

        foreach ($existingShowtimes as $oldShow) {
            $oldStart = Carbon::parse($oldShow->start_time);
            $oldEnd = Carbon::parse($oldShow->end_time ?? $oldStart->copy()->addMinutes($oldShow->movie->duration)); 
            $oldEndWithCleanup = $oldEnd->copy()->addMinutes(60); 

            if ($newStartTime < $oldEndWithCleanup && $newEndWithCleanup > $oldStart) {
                $timeStart = $oldStart->format('H:i d/m/Y');
                $timeEnd = $oldEndWithCleanup->format('H:i d/m/Y');
                
                return back()->withInput()->withErrors([
                    'overlap' => "🚨 XUNG ĐỘT: Phòng đang bận chiếu phim '{$oldShow->movie->name}' từ {$timeStart} đến {$timeEnd} (Đã bao gồm 1 tiếng dọn dẹp). Vui lòng chọn giờ khác!"
                ]);
            }
        }

        $showtime->update([
            'movie_id' => $request->movie_id,
            'room_id'  => $request->room_id,
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
            'status' => $request->status ?? $showtime->status
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Đã cập nhật suất chiếu thành công!');
    }

    // 6. Xóa lịch chiếu
    public function destroy(Showtime $showtime)
    {
        $showtime->delete();
        return redirect()->route('admin.showtimes.index')->with('success', 'Đã hủy lịch chiếu!');
    }
}