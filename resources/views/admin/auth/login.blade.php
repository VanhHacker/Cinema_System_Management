<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin | CGV Cinemas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .login-card { width: 100%; max-width: 400px; border: none; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .login-header { background-color: #E71A0F; color: white; border-radius: 12px 12px 0 0; padding: 30px; text-align: center; }
        .btn-cgv { background-color: #E71A0F; color: white; font-weight: bold; border: none; transition: 0.3s; }
        .btn-cgv:hover { background-color: #c9160d; color: white; }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="login-header">
        <h3 class="mb-0 fw-bold"><i class="fa-solid fa-user-shield"></i> CGV ADMIN</h3>
        <p class="mb-0 opacity-75">Hệ thống quản trị rạp phim</p>
    </div>
    <div class="card-body p-4">
        @if(session('error'))
            <div class="alert alert-danger p-2 small text-center"><i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small">Email Quản trị</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@cgv.vn" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-cgv w-100 py-2 shadow-sm">ĐĂNG NHẬP</button>
        </form>
    </div>
    <div class="card-footer bg-white border-0 text-center pb-4 small">
        Chưa có tài khoản quản trị? <a href="{{ route('admin.register') }}" class="text-danger fw-bold text-decoration-none">Đăng ký ngay</a>
    </div>
</div>

</body>
</html>