@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-user-pen text-cgv me-2"></i>Cập nhật thông tin Khách hàng</h4>

    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="user_name" class="form-control" value="{{ $customer->user_name }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Mật khẩu mới (Bỏ trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới để đổi">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái tài khoản</label>
                <select name="status" class="form-select">
                    <option value="1" {{ $customer->status == 1 ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ $customer->status == 0 ? 'selected' : '' }}>Khóa</option>
                </select>
            </div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-check me-1"></i> Lưu thay đổi</button>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary px-4">Quay lại</a>
    </form>
@endsection
