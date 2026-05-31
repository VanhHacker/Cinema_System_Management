<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\TypeSeat;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; 

class RoomController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách rạp để đổ vào Dropdown lọc
        $cinemas = \App\Models\Cinema::all();

        // Thêm withCount('seats') để tự động đếm số lượng ghế của từng phòng
        $query = \App\Models\Room::with('cinema')->withCount('seats');

        // 1. Lọc theo Tên phòng 
        if ($request->filled('search_name')) {
            $query->where('room_name', 'like', '%' . $request->search_name . '%');
        }

        // 2. Lọc theo Rạp chiếu
        if ($request->filled('cinema_id')) {
            $query->where('cinema_id', $request->cinema_id);
        }

        // 3. Lọc theo Trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lấy dữ liệu, sắp xếp mới nhất lên đầu và phân trang 15 phòng/trang
        $rooms = $query->orderBy('id', 'desc')->paginate(15)->appends($request->all());

        return view('admin.rooms.index', compact('rooms', 'cinemas'));
    }

    public function create()
    {
        $cinemas = Cinema::all();
        $typeSeats = TypeSeat::all(); 
        return view('admin.rooms.create', compact('cinemas', 'typeSeats'));
    }

    // 1. Lưu thông tin phòng và đọc sơ đồ từ Bản đồ tương tác
    public function store(Request $request)
    {
        $maxColumns = $request->max_columns ?? $request->max_seats_per_row;

        // Kiểm tra trùng tên phòng trong cùng 1 rạp
        $request->validate([
            'room_name' => [
                'required',
                'string',
                Rule::unique('rooms')->where(function ($query) use ($request) {
                    return $query->where('cinema_id', $request->cinema_id);
                })
            ],
            'cinema_id' => 'required|exists:cinemas,id',
            'seat_map_data' => 'required' 
        ], [
            'room_name.unique' => 'Tên phòng này đã tồn tại trong rạp chiếu được chọn. Vui lòng nhập tên khác!'
        ]);

        try {
            DB::beginTransaction();

            $seatMapArray = json_decode($request->seat_map_data, true);
            $totalSeats = is_array($seatMapArray) ? count($seatMapArray) : 0;

            $room = Room::create([
                'room_name' => $request->room_name,
                'cinema_id' => $request->cinema_id,
                'max_columns' => $maxColumns,
                'status' => $request->status ?? 1,
                'number_of_seats' => $totalSeats,
            ]);

            $seatsToInsert = [];
            $now = now(); 

            if (is_array($seatMapArray)) {
                foreach ($seatMapArray as $seat) {
                    // 👉 ĐÃ SỬA: Bắt tọa độ cột lưới vật lý (grid_col) để bảo toàn khoảng trống
                    $columnNumber = isset($seat['grid_col']) ? (int) $seat['grid_col'] : (int) preg_replace('/[^0-9]/', '', $seat['seat_number']);
                    
                    $seatsToInsert[] = [
                        'room_id'      => $room->id,
                        'type_seat_id' => $seat['type_seat_id'], 
                        'seat_number'  => $seat['seat_number'], // Tên hiển thị (E1, E2)
                        'rows'         => $seat['rows'], 
                        'columns'      => $columnNumber,        // Tọa độ vật lý (11, 12)
                        'status'       => 1, 
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }
                Seat::insert($seatsToInsert);
            }

            DB::commit();
            return redirect()->route('admin.rooms.index')->with('success', 'Đã tạo phòng và lưu bản đồ ghế thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // 2. Hiển thị sơ đồ ghế trực quan
    public function show(Room $room)
    {
        $seats = Seat::where('room_id', $room->id)->get();
        $seatRows = $seats->groupBy('rows');

        $typeSeats = TypeSeat::all();

        return view('admin.rooms.seat_map', compact('room', 'seatRows', 'typeSeats'));
    }

    // 3. Xử lý khi Admin click đổi trạng thái/loại 1 cái ghế
    public function updateSeat(Request $request, $id)
    {
        $seat = Seat::findOrFail($id);
        $seat->update([
            'type_seat_id' => $request->type_seat_id,
            'status' => $request->status
        ]);

        return back()->with('success', 'Đã cập nhật ghế ' . $seat->seat_number);
    }

    // 4. Xử lý cập nhật hàng loạt nhiều ghế cùng lúc
    public function bulkUpdateSeats(Request $request)
    {
        $ids = explode(',', $request->seat_ids);
        $updateData = [];

        if ($request->type_seat_id != '') {
            $updateData['type_seat_id'] = $request->type_seat_id;
        }

        if ($request->status != '') {
            $updateData['status'] = $request->status;
        }

        if (!empty($updateData)) {
            Seat::whereIn('id', $ids)->update($updateData);
            return back()->with('success', 'Đã cập nhật ' . count($ids) . ' ghế thành công!');
        }

        return back()->with('error', 'Sự cố: Bạn chưa chọn Loại ghế hoặc Trạng thái để thay đổi!');
    }

    // 5. Hiển thị form sửa phòng chiếu
    public function edit(Room $room)
    {
        $cinemas = Cinema::all();
        $typeSeats = TypeSeat::all(); 
        
        // Lấy toàn bộ danh sách ghế hiện tại của phòng kèm theo Loại ghế
        $existingSeats = \App\Models\Seat::with('typeSeat')
            ->where('room_id', $room->id)
            ->get();

        return view('admin.rooms.edit', compact('room', 'cinemas', 'typeSeats', 'existingSeats'));
    }

    // 6. Cập nhật hàm Update: Cập nhật phòng và vẽ lại ghế nếu Admin có sửa bản đồ
    public function update(Request $request, Room $room)
    {
        $maxColumns = $request->max_columns ?? $request->max_seats_per_row;

        // Kiểm tra trùng tên phòng, nhưng BỎ QUA phòng hiện tại đang sửa
        $request->validate([
            'room_name' => [
                'required',
                'string',
                Rule::unique('rooms')->where(function ($query) use ($request) {
                    return $query->where('cinema_id', $request->cinema_id);
                })->ignore($room->id)
            ],
            'cinema_id' => 'required|exists:cinemas,id',
        ], [
            'room_name.unique' => 'Tên phòng này đã tồn tại trong rạp chiếu được chọn. Vui lòng nhập tên khác!'
        ]);

        try {
            DB::beginTransaction();

            // Dữ liệu cơ bản cập nhật
            $updateData = [
                'room_name' => $request->room_name,
                'cinema_id' => $request->cinema_id,
                'max_columns' => $maxColumns,
                'status' => $request->status ?? 1,
            ];

            // Nếu Admin có mở bản đồ lên vẽ lại và gửi tọa độ mới thì ta mới xóa ghế cũ đi và insert lại
            if ($request->has('seat_map_data') && !empty($request->seat_map_data)) {
                $seatMapArray = json_decode($request->seat_map_data, true);
                
                if (is_array($seatMapArray) && count($seatMapArray) > 0) {
                    $totalSeats = count($seatMapArray);
                    
                    // XÓA SẠCH toàn bộ ghế cũ thuộc phòng này
                    Seat::where('room_id', $room->id)->delete();

                    $seatsToInsert = [];
                    $now = now();

                    foreach ($seatMapArray as $seat) {
                        // 👉 ĐÃ SỬA: Bắt tọa độ cột lưới vật lý (grid_col) giống hàm store()
                        $columnNumber = isset($seat['grid_col']) ? (int) $seat['grid_col'] : (int) preg_replace('/[^0-9]/', '', $seat['seat_number']);
                        
                        $seatsToInsert[] = [
                            'room_id'      => $room->id,
                            'type_seat_id' => $seat['type_seat_id'],
                            'seat_number'  => $seat['seat_number'], // Tên hiển thị (E1, E2)
                            'rows'         => $seat['rows'],
                            'columns'      => $columnNumber,        // Tọa độ vật lý (11, 12)
                            'status'       => 1,
                            'created_at'   => $now,
                            'updated_at'   => $now,
                        ];
                    }

                    // Insert hàng loạt toàn bộ ghế vào DB
                    Seat::insert($seatsToInsert);
                    
                    // Cập nhật lại tổng số ghế mới vào bảng room
                    $updateData['number_of_seats'] = $totalSeats;
                }
            }

            // Lưu thông tin Update
            $room->update($updateData);

            DB::commit();
            return redirect()->route('admin.rooms.index')->with('success', 'Đã cập nhật thông tin và sơ đồ phòng chiếu thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // 7. Xử lý xóa phòng chiếu
    public function destroy(Room $room)
    {
        // Bắt buộc phải xóa ghế (dữ liệu con) trước khi xóa phòng (dữ liệu cha)
        Seat::where('room_id', $room->id)->delete();
        $room->delete();
        
        return redirect()->route('admin.rooms.index')->with('success', 'Đã xóa phòng chiếu và các ghế liên quan!');
    }
}