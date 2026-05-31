<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị Rạp Chiếu Phim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Khai báo bảng màu CGV */
        :root {
            --cgv-red: #E71A0F;
            --cgv-dark: #222222;
            --cgv-light-bg: #f4f6f9;
        }

        body {
            background-color: var(--cgv-light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* --- THIẾT KẾ SIDEBAR --- */
        .sidebar {
            height: 100vh;
            background-color: var(--cgv-dark);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        /* Header của Sidebar (Logo) */
        .sidebar-header {
            background-color: var(--cgv-red);
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        /* Cho phép click vào logo */
        .sidebar-header a {
            color: white;
            text-decoration: none;
            display: block;
        }

        /* Tiêu đề từng cụm Menu */
        .sidebar-category {
            font-size: 0.75rem;
            color: #888;
            text-transform: uppercase;
            font-weight: bold;
            padding: 15px 20px 5px;
            letter-spacing: 1px;
        }

        /* Link Menu */
        .sidebar a.nav-link {
            color: #d1d1d1;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar a.nav-link:hover, .sidebar a.nav-link.active {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
            border-left: 4px solid var(--cgv-red);
        }

        .sidebar a.nav-link i {
            width: 30px;
            font-size: 1.1rem;
        }

        /* --- THIẾT KẾ KHU VỰC NỘI DUNG CHÍNH --- */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Topbar (Thanh điều hướng trên cùng) */
        .navbar-top {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 15px 25px;
            margin-bottom: 25px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Ghi đè màu nút bấm Bootstrap thành màu Đỏ CGV */
        .btn-primary {
            background-color: var(--cgv-red);
            border-color: var(--cgv-red);
        }

        .btn-primary:hover {
            background-color: #c9160d;
            border-color: #c9160d;
        }

        .text-cgv {
            color: var(--cgv-red);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}">
            <i class="fa-solid fa-film"></i> CGV ADMIN
        </a>
    </div>

    <div class="sidebar-category">Tổng quan</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link">
        <i class="fa-solid fa-gauge-high"></i> Dashboard
    </a>

    <div class="sidebar-category">Hệ thống</div>
    <a href="{{ route('admin.admins.index') }}" class="nav-link"><i class="fa-solid fa-user-shield"></i> Quản trị viên</a>
    <a href="{{ route('admin.staff.index') }}" class="nav-link"><i class="fa-solid fa-user-tie"></i> Nhân viên</a>
    <a href="{{ route('admin.customers.index') }}" class="nav-link"><i class="fa-solid fa-users"></i> Khách hàng</a>

    <div class="sidebar-category">Rạp & Phim</div>
    <a href="{{ route('admin.cinemas.index') }}" class="nav-link"><i class="fa-solid fa-building"></i> Rạp chiếu</a>
    <a href="{{ route('admin.rooms.index') }}" class="nav-link"><i class="fa-solid fa-door-open"></i> Phòng chiếu</a>
    <a href="{{ route('admin.movies.index') }}" class="nav-link"><i class="fa-solid fa-video"></i> Phim</a>
    <a href="{{ route('admin.showtimes.index') }}" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Lịch chiếu</a>

    <div class="sidebar-category">Danh mục</div>
    <a href="{{ route('admin.categories.index') }}" class="nav-link"><i class="fa-solid fa-tags"></i> Thể loại</a>
    <a href="{{ route('admin.type_seats.index') }}" class="nav-link"><i class="fa-solid fa-chair"></i> Loại ghế</a>
    <a href="{{ route('admin.payment_methods.index') }}" class="nav-link"><i class="fa-solid fa-credit-card"></i> Thanh toán</a>

    <div class="sidebar-category">Giao dịch</div>
    <a href="{{ route('admin.bills.index') }}" class="nav-link"><i class="fa-solid fa-file-invoice-dollar"></i> Hóa đơn</a>
    <a href="{{ route('admin.tickets.index') }}" class="nav-link"><i class="fa-solid fa-ticket"></i> Vé</a>
</div>

<div class="main-content">

    <div class="navbar-top">
        <h5 class="m-0 text-secondary"><i class="fa-solid fa-bars me-2"></i> Bảng điều khiển</h5>
        <div class="d-flex align-items-center">
            <span class="me-4 fw-bold text-dark">
                <i class="fa-solid fa-circle-user text-cgv fs-5 align-middle me-1"></i> 
                {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
            </span>
            
            <a href="{{ route('admin.logout') }}" 
               class="btn btn-sm btn-outline-danger" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @yield('content')
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>