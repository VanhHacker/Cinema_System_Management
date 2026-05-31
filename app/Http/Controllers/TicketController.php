<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // 1. Hiển thị danh sách vé (Lấy từ luồng khách đặt)
    public function index(Request $request)
    {
        // Khởi tạo query và load sẵn các quan hệ lồng nhau để tối ưu tốc độ (Tránh N+1 Query)
        $query = \App\Models\Ticket::with(['bill.customer', 'showtime.movie', 'seat.room']);

        // --- BỘ LỌC TÌM KIẾM ---

        // 1. Tìm theo Mã Vé hoặc Mã Hóa đơn
        if ($request->filled('search_code')) {
            $cleanCode = str_replace(['#TK', '#INV'], '', $request->search_code);
            $cleanCode = ltrim($cleanCode, '0'); // Xóa số 0 ở đầu
            
            $query->where(function($q) use ($cleanCode) {
                $q->where('id', 'like', '%' . $cleanCode . '%')
                  ->orWhere('bill_id', 'like', '%' . $cleanCode . '%');
            });
        }

        // 2. Tìm theo Khách hàng (Qua quan hệ với bảng Bill -> Customer)
        if ($request->filled('search_customer')) {
            $query->whereHas('bill.customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_customer . '%')
                  ->orWhere('phone', 'like', '%' . $request->search_customer . '%');
            });
        }

        // 3. Lọc theo Trạng thái vé
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Lọc theo Ngày chiếu (Qua quan hệ Showtime)
        if ($request->filled('date')) {
            $query->whereHas('showtime', function ($q) use ($request) {
                $q->whereDate('start_time', $request->date);
            });
        }

        // Lấy dữ liệu, sắp xếp mới nhất lên đầu và phân trang
        $tickets = $query->orderBy('id', 'desc')->paginate(15)->appends($request->all());

        return view('admin.tickets.index', compact('tickets'));
    }

    
    // Việc store() sẽ được xử lý ở API hoặc Frontend khi khách hàng thanh toán Bill.

    // 2. Hiển thị form Cập nhật (Chỉ để đổi trạng thái vé)
    public function edit(Ticket $ticket)
    {
        // Load kèm các thông tin liên quan để hiển thị (Read-only) trên form
        $ticket->load(['bill.customer', 'showtime.movie', 'seat.room']);
        return view('admin.tickets.edit', compact('ticket'));
    }

    // 3. Xử lý cập nhật (Chủ yếu là Hủy vé)
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|string|max:50'
        ]);

        $ticket->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.tickets.index')->with('success', 'Đã cập nhật trạng thái vé!');
    }

    // 4. Xóa vé (Dành cho Admin dọn rác dữ liệu, nếu không thích có thể bỏ hàm này)
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Đã xóa vé khỏi hệ thống!');
    }
}
