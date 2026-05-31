<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký Admin | CGV Cinemas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .register-card { width: 100%; max-width: 450px; border: none; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .register-header { background-color: #333; color: white; border-radius: 12px 12px 0 0; padding: 25px; text-align: center; }
        .btn-dark-cgv { background-color: #333; color: white; font-weight: bold; border: none; }
        .btn-dark-cgv:hover { background-color: #000; color: white; }
    </style>
</head>
<body>

<div class="card register-card">
    <div class="register-header">
        <h3 class="mb-0 fw-bold"><i class="fa-solid fa-user-plus"></i> ĐĂNG KÝ ADMIN</h3>
        <p class="mb-0 opacity-75 small">Tạo tài khoản quản trị viên mới</p>
    </div>
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.register.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold small">Họ và tên</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="VD: Nguyễn Văn A" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small">Email làm việc</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@cgv.vn" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold small">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold small">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
            </div>
            <button type="submit" class="btn btn-dark-cgv w-100 py-2">TẠO TÀI KHOẢN</button>
        </form>
    </div>
    <div class="card-footer bg-white border-0 text-center pb-4 small">
        Đã có tài khoản? <a href="{{ route('admin.login') }}" class="text-danger fw-bold text-decoration-none">Đăng nhập tại đây</a>
    </div>
</div>

</body>
</html>