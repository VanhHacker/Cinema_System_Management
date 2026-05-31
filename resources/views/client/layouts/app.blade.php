<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CGV Cinemas - Trải nghiệm điện ảnh đỉnh cao</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --cgv-red: #E71A0F;
            --dark-bg: #111111;
            --card-bg: #222222;
        }

        body {
            background-color: var(--dark-bg);
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* NAVBAR (Trắng - Đỏ giống CGV) */
        .navbar {
            background-color: #f8f8f8;
            border-bottom: 2px solid var(--cgv-red);
            padding: 15px 0;
        }
        .navbar-brand {
            color: var(--cgv-red) !important;
            font-weight: 900;
            font-size: 2rem;
            letter-spacing: 2px;
        }
        .nav-link {
            color: #333 !important;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 10px;
            transition: 0.3s;
        }
        .nav-link:hover {
            color: var(--cgv-red) !important;
        }
        .btn-cgv {
            background-color: var(--cgv-red);
            color: white;
            font-weight: bold;
            border: none;
        }
        .btn-cgv:hover {
            background-color: #c9160d;
            color: white;
        }

        /* Tùy chỉnh Menu Dropdown Khách hàng */
        .customer-dropdown .dropdown-toggle::after {
            vertical-align: middle;
        }
        .customer-dropdown .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 10px 0;
            margin-top: 15px;
        }
        .customer-dropdown .dropdown-item {
            padding: 10px 20px;
            font-weight: 600;
            transition: 0.2s;
        }
        .customer-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--cgv-red) !important;
        }

        /* FOOTER */
        .footer {
            background-color: #222;
            padding: 40px 0 20px;
            margin-top: 50px;
            border-top: 3px solid var(--cgv-red);
        }
        .footer-title {
            color: var(--cgv-red);
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .footer ul li {
            margin-bottom: 10px;
        }
        .footer ul li a {
            color: #aaa;
            text-decoration: none;
            transition: 0.3s;
        }
        .footer ul li a:hover {
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="fa-solid fa-film"></i> CGV</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Lịch chiếu</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('client.cinemasList') }}">Rạp chiếu</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Khuyến mãi</a></li>
            </ul>
            
            <div class="d-flex align-items-center">
                @if(Auth::guard('customer')->check())
                    <div class="nav-item dropdown customer-dropdown">
                        <a class="nav-link dropdown-toggle text-dark fw-bold pe-0" href="#" id="customerMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user fs-5 align-middle me-1" style="color: var(--cgv-red)"></i> 
                            <span class="align-middle">Xin chào, {{ Auth::guard('customer')->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customerMenu">
                            <li>
                                <a class="dropdown-item text-secondary" href="{{ route('client.history') }}">
                                    <i class="fa-solid fa-clock-rotate-left me-2"></i> Lịch sử đặt vé
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('client.logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('client.login') }}" class="btn btn-outline-danger me-2 fw-bold">Đăng nhập</a>
                    <a href="{{ route('client.register') }}" class="btn btn-cgv fw-bold">Đăng ký</a>
                @endif
            </div>
        </div>
    </div>
</nav>

<div class="main-content min-vh-100">
    @yield('content')
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h4 class="footer-title">CGV Việt Nam</h4>
                <p class="text-muted">Trải nghiệm điện ảnh chất lượng nhất với hệ thống rạp chiếu chuẩn quốc tế.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h4 class="footer-title">Điều khoản sử dụng</h4>
                <ul class="list-unstyled">
                    <li><a href="#">Điều khoản chung</a></li>
                    <li><a href="#">Chính sách thanh toán</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h4 class="footer-title">Chăm sóc khách hàng</h4>
                <ul class="list-unstyled">
                    <li><a href="#">Hotline: 1900 1234</a></li>
                    <li><a href="#">Giờ làm việc: 8:00 - 22:00</a></li>
                    <li><a href="#">Email: support@cgv.vn</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="text-center text-muted">
            <small>&copy; 2026 CGV Clone Project. Thiết kế dành cho mục đích học tập.</small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>