@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-user-pen text-cgv me-2"></i>Cập nhật thông tin Nhân viên</h4>

    <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST">
        @csrf
        @method('PUT') <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $staff->name }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ $staff->email }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="user_name" class="form-control" value="{{ $staff->user_name }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Mật khẩu mới (Bỏ trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới nếu muốn đổi">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Thuộc rạp chiếu <span class="text-danger">*</span></label>
                <select name="cinema_id" class="form-select" required>
                    <option value="">-- Chọn chi nhánh rạp --</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}" {{ $staff->cinema_id == $cinema->id ? 'selected' : '' }}>
                            {{ $cinema->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="1" {{ $staff->status == 1 ? 'selected' : '' }}>Đang làm việc</option>
                    <option value="0" {{ $staff->status == 0 ? 'selected' : '' }}>Nghỉ việc</option>
                </select>
            </div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật</button>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
