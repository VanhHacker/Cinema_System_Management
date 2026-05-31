<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLERS: CLIENT ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\MovieController as ClientMovieController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Client\CinemaController as ClientCinemaController;
use App\Http\Controllers\Client\ShowtimeController as ClientGlobalShowtimeController; // 👉 ĐÃ THÊM: Controller lịch chiếu NCC
// Controller xử lý VNPay
use App\Http\Controllers\CheckOut; 

// --- CONTROLLERS: ADMIN ---
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TypeSeatController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Client\HistoryController;

/*
|--------------------------------------------------------------------------
| NHÓM ROUTE CLIENT (KHÁCH HÀNG)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. CÁC ROUTE CÔNG KHAI (Không cần đăng nhập)
// ==========================================

// Trang chủ & Xem phim
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/phim/{id}', [ClientMovieController::class, 'show'])->name('client.movies.show');
Route::get('/rap-chieu', [ClientCinemaController::class, 'index'])->name('client.cinemasList'); 
Route::get('/rap-chieu/{id}', [ClientCinemaController::class, 'show'])->name('client.showtimes'); 

// Route Lịch chiếu tổng hợp (Chuẩn bộ lọc nhiều rạp)
Route::get('/lich-chieu', [ClientGlobalShowtimeController::class, 'index'])->name('client.showtimes.global');

// Luồng Xác thực Khách hàng
Route::controller(AuthController::class)->group(function () {
    
    // Dùng guest:customer để chặn khách ĐÃ đăng nhập vào lại form Đăng nhập
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', 'showLogin')->name('client.login');
        Route::post('/login', 'login')->name('client.login.post');
        Route::get('/register', 'showRegister')->name('client.register');
        Route::post('/register', 'register')->name('client.register.post');
    });

    // Dùng auth:customer để bắt buộc phải ĐĂNG NHẬP mới thấy nút Đăng xuất
    Route::post('/logout', 'logout')->name('client.logout')->middleware('auth:customer');
});


// ==========================================
// 2. LUỒNG ĐẶT VÉ & THANH TOÁN & TÀI KHOẢN (Yêu cầu đăng nhập)
// ==========================================

// Bọc toàn bộ luồng yêu cầu user ĐÃ ĐĂNG NHẬP bằng auth:customer
Route::middleware('auth:customer')->group(function () {
    
    Route::get('/lich-su-dat-ve', [HistoryController::class, 'index'])->name('client.history');

    // Luồng đặt vé
    Route::get('/dat-ve/{showtime}', [BookingController::class, 'seatSelection'])->name('client.book.seats');
    Route::post('/dat-ve/thanh-toan', [BookingController::class, 'store'])->name('client.book.store');
    Route::get('/dat-ve/thanh-cong/{bill}', [BookingController::class, 'success'])->name('client.book.success');

    // Luồng thanh toán VNPAY
    // 1. Route đẩy khách sang VNPay (bắt buộc phải là POST để nhận form)
    Route::post('/thanh-toan-vnpay', [CheckOut::class, 'vnpay_payment'])->name('vnpay.payment');
    // 2. Route đón khách từ VNPay trở về (bắt buộc là GET)
    Route::get('/returnvnpay', [CheckOut::class, 'return_vnpay'])->name('vnpay.return');
    
});


/*
|--------------------------------------------------------------------------
| NHÓM ROUTE DÀNH CHO ADMIN PANEL
|--------------------------------------------------------------------------
*/
// Tên nhóm đã có sẵn chữ 'admin.'
Route::prefix('admin')->name('admin.')->group(function () {

    // --- 1. ROUTE CÔNG KHAI (AUTH ADMIN) ---
    Route::controller(AdminAuthController::class)->group(function () {
        // Dùng guest:admin để Admin đang làm việc không vô tình quay lại form login
        Route::middleware('guest:admin')->group(function () {
            Route::get('/login', 'showLogin')->name('login');
            Route::post('/login', 'login')->name('login.post');
            Route::get('/register', 'showRegister')->name('register');
            Route::post('/register', 'register')->name('register.post');
        });
    });

    // --- 2. ROUTE BẢO MẬT (YÊU CẦU ĐĂNG NHẬP BẢNG ADMINS) ---
    Route::middleware('auth:admin')->group(function () {
        
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']); 
        
        
        Route::get('/dashboard/daily-details', [DashboardController::class, 'dailyDetails'])->name('daily_details');
        Route::get('/dashboard/monthly-details', [DashboardController::class, 'monthlyDetails'])->name('monthly_details');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Quản lý Người dùng
        Route::resource('admins', AdminController::class);
        Route::resource('staff', StaffController::class);
        Route::resource('customers', CustomerController::class);

        // Danh mục & Cấu hình
        Route::resource('categories', CategoryController::class);
        Route::resource('payment_methods', PaymentMethodController::class);
        Route::resource('type_seats', TypeSeatController::class);

        // Cơ sở vật chất (Rạp & Phòng)
        Route::resource('cinemas', CinemaController::class);
        Route::resource('rooms', RoomController::class);

        // Quản lý Ghế
        Route::controller(SeatController::class)->group(function () {
            Route::put('/seats/bulk-update', 'bulkUpdateSeats')->name('seats.bulkUpdate');
            Route::put('/seats/{id}', 'updateSeat')->name('seats.update');
        });

        // Phim và Lịch chiếu
        Route::resource('movies', MovieController::class);
        Route::resource('showtimes', ShowtimeController::class);

        // Giao dịch
        Route::resource('bills', BillController::class)->only(['index', 'show']);
        Route::resource('tickets', TicketController::class);
    });
});
