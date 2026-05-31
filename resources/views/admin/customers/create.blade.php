@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-user-plus text-cgv me-2"></i>Đăng ký Khách hàng mới</h4>

    <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="user_name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái tài khoản</label>
                <select name="status" class="form-select">
                    <option value="1">Kích hoạt</option>
                    <option value="0">Khóa</option>
                </select>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-check me-1"></i> Hoàn tất</button>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary px-4">Quay lại</a>
    </form>
@endsection
