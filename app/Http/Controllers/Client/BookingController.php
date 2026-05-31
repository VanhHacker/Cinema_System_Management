<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Ticket;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Hiển thị giao diện chọn ghế
     */
    public function seatSelection($showtime_id)
    {
        $showtime = Showtime::with(['movie', 'room'])->findOrFail($showtime_id);
        
        // Ngăn user gõ link trực tiếp vào url
        $now = \Carbon\Carbon::now();
        $startTime = \Carbon\Carbon::parse($showtime->start_time);
        
        if ($now->copy()->addMinutes(45)->gte($startTime)) {
            return redirect()->back()->with('error', 'Suất chiếu này đã đóng bán vé do sắp đến giờ chiếu. Vui lòng chọn suất khác!');
        }

        $seats = Seat::with('typeSeat')->where('room_id', $showtime->room_id)->get();
        $seatRows = $seats->groupBy('rows');
        $bookedSeats = Ticket::where('showtime_id', $showtime_id)->pluck('seat_id')->toArray(); 
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        return view('client.booking.seats', compact('showtime', 'seatRows', 'bookedSeats', 'paymentMethods'));
    }

    /**
     * Xử lý lưu hóa đơn và vé khi khách bấm Thanh toán
     */
    public function store(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập!');
        }

        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids' => 'required|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        // Ngăn trường hợp khách treo máy ở trang chọn ghế 1 tiếng rồi mới bấm thanh toán
        $showtime = Showtime::findOrFail($request->showtime_id);
        $now = \Carbon\Carbon::now();
        $startTime = \Carbon\Carbon::parse($showtime->start_time);

        if ($now->copy()->addMinutes(45)->gte($startTime)) {
            return redirect()->route('home')->with('error', 'Giao dịch thất bại: Đã quá hạn thời gian mua vé cho suất chiếu này!');
        }

        $seatIds = explode(',', $request->seat_ids);

        // Kiểm tra ghế có bị nẫng tay trên không
        if (Ticket::where('showtime_id', $request->showtime_id)->whereIn('seat_id', $seatIds)->exists()) {
            return back()->with('error', 'Ghế đã có người đặt, vui lòng chọn ghế khác!');
        }

        $seats = Seat::with('typeSeat')->whereIn('id', $seatIds)->get();
        $totalPrice = $seats->sum(fn($seat) => $seat->typeSeat->basePrice ?? 0);

        // Nhận diện phương thức thanh toán
        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        $isVnPay = str_contains(strtolower($paymentMethod->name ?? ''), 'vnpay');

        try {
            DB::beginTransaction();

            $bill = Bill::create([
                'customer_id' => Auth::guard('customer')->id(),
                'total' => $totalPrice, 
                'payment_method_id' => $request->payment_method_id,
            ]);

            foreach ($seats as $seat) {
                Ticket::create([
                    'bill_id' => $bill->id,
                    'seat_id' => $seat->id,
                    'showtime_id' => $request->showtime_id,
                    'price' => $seat->typeSeat->basePrice ?? 0, 
                    'status' => $isVnPay ? 0 : 1, 
                ]);
            }

            DB::commit();

            // Nếu là VNPay, đẩy dữ liệu sang CheckOut Controller
            if ($isVnPay) {
                return app(\App\Http\Controllers\CheckOut::class)->vnpay_payment($request, $bill->id, $totalPrice);
            }

            // Nếu Tiền mặt/Tại quầy -> Báo thành công luôn
            return redirect()->route('client.book.success', $bill->id)->with('success', 'Thanh toán thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị trang Vé điện tử (Thành công)
     */
    public function success($bill_id)
    {
        $bill = Bill::with([
            'paymentMethod', 
            'tickets.seat', 
            'tickets.showtime.movie', 
            'tickets.showtime.room'
        ])->findOrFail($bill_id);
        
        if ($bill->customer_id !== Auth::guard('customer')->id()) {
            abort(403, 'BẠN KHÔNG CÓ QUYỀN XEM HÓA ĐƠN NÀY.');
        }

        return view('client.booking.success', compact('bill'));
    }
}