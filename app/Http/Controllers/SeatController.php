<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    /**
     * Cập nhật nhiều ghế cùng lúc (Dùng cho thanh công cụ chọn hàng loạt)
     */
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

    /**
     * Cập nhật 1 ghế lẻ (Dự phòng nếu Admin mở Modal cập nhật riêng lẻ)
     */
    public function updateSeat(Request $request, $id)
    {
        $seat = Seat::findOrFail($id);
        
        $updateData = [];
        if ($request->has('type_seat_id')) $updateData['type_seat_id'] = $request->type_seat_id;
        if ($request->has('status')) $updateData['status'] = $request->status;

        $seat->update($updateData);

        return back()->with('success', 'Đã cập nhật ghế ' . $seat->seat_number);
    }
}