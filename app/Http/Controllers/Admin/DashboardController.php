<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Showtime;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. CÁC CON SỐ TỔNG QUAN Ở TRÊN CÙNG
        $totalTickets = Ticket::where('status', 1)->count(); // Vé đã thanh toán
        $totalRevenue = Bill::sum('total'); // Tổng doanh thu
        $totalCustomers = Customer::count(); // Số lượng khách
        $todayShowtimes = Showtime::whereDate('start_time', Carbon::today())->count(); // Suất chiếu hôm nay

        // ========================================================
        // 2. THỐNG KÊ DOANH THU THEO NGÀY (TRONG THÁNG HIỆN TẠI)
        // ========================================================
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth; 

        $dailyLabels = [];
        $dailyDataArray = array_fill(1, $daysInMonth, 0);

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dailyLabels[] = $i . '/' . $currentMonth; 
        }

        $dailyRevenues = Bill::select(DB::raw('DAY(created_at) as day'), DB::raw('SUM(total) as revenue'))
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('day')
            ->get();

        foreach ($dailyRevenues as $rev) {
            $dailyDataArray[$rev->day] = $rev->revenue;
        }
        $dailyData = array_values($dailyDataArray); 

        // ========================================================
        // 3. THỐNG KÊ DOANH THU THEO THÁNG (TRONG NĂM HIỆN TẠI)
        // ========================================================
        $monthlyLabels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        $monthlyDataArray = array_fill(1, 12, 0);

        $monthlyRevenues = Bill::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as revenue'))
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get();

        foreach ($monthlyRevenues as $rev) {
            $monthlyDataArray[$rev->month] = $rev->revenue;
        }
        $monthlyData = array_values($monthlyDataArray);


        // ========================================================
        // 4. TOP 5 PHIM BÁN CHẠY NHẤT (TRONG THÁNG HIỆN TẠI)
        // ========================================================
        $topMovies = \App\Models\Ticket::join('showtimes', 'tickets.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->whereMonth('tickets.created_at', $currentMonth) // Lọc theo tháng hiện tại
            ->whereYear('tickets.created_at', $currentYear)   // Lọc theo năm hiện tại
            // ->where('tickets.status', 1) // Tạm thời bỏ comment để chắc chắn lấy được dữ liệu, sau khi ổn định bạn có thể mở lại
            // 👉 ĐÃ SỬA: Thay movies.title thành movies.name ở select và groupBy
            ->select('movies.id', 'movies.name', DB::raw('COUNT(tickets.id) as total_tickets'))
            ->groupBy('movies.id', 'movies.name')
            ->orderByDesc('total_tickets')
            ->limit(5)
            ->get();


        // Gom TẤT CẢ biến lại và chỉ sử dụng 1 lệnh return view duy nhất ở cuối hàm
        return view('admin.dashboard', compact(
            'totalTickets', 'totalRevenue', 'totalCustomers', 'todayShowtimes', 
            'dailyLabels', 'dailyData', 'monthlyLabels', 'monthlyData', 
            'currentMonth', 'currentYear', 'topMovies'
        ));
    }

    public function dailyDetails(Request $request)
    {
        $date = $request->query('date');
        
        if (!$date) {
            return redirect()->route('admin.dashboard')->with('error', 'Không tìm thấy dữ liệu ngày!');
        }
        
        $bills = \App\Models\Bill::with(['paymentMethod', 'customer']) 
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $formattedDate = \Carbon\Carbon::parse($date)->format('d/m/Y');

        return view('admin.dashboard.daily_details', compact('bills', 'formattedDate'));
    }

    public function monthlyDetails(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        
        if (!$month || !$year) {
            return redirect()->route('admin.dashboard')->with('error', 'Không tìm thấy dữ liệu tháng!');
        }

        $bills = \App\Models\Bill::with(['paymentMethod', 'customer']) 
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $bills->appends(['month' => $month, 'year' => $year]);

        return view('admin.monthly_details', compact('bills', 'month', 'year'));
    }
}