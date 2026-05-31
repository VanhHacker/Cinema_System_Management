@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-user-plus text-cgv me-2"></i>Thêm Quản trị viên mới</h4>

    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ví dụ: Quản trị hệ thống" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@cgv.vn" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" placeholder="admin_super" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu lại</button>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection
