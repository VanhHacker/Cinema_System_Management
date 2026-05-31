@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-user-pen text-cgv me-2"></i>Cập nhật Quản trị viên</h4>

    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="user_name" class="form-control" value="{{ old('user_name', $admin->user_name) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Mật khẩu mới (Bỏ trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control" placeholder="Chỉ nhập khi muốn đổi mật khẩu">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="1" {{ old('status', $admin->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status', $admin->status) == 0 ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật</button>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
