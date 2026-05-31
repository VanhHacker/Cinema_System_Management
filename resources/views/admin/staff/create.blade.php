@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-user-plus text-cgv me-2"></i>Thêm Nhân viên mới</h4>

    <form action="{{ route('admin.staff.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Nguyễn Văn A" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="staff@gmail.com" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="user_name" class="form-control" placeholder="nv_a" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Thuộc rạp chiếu <span class="text-danger">*</span></label>
                <select name="cinema_id" class="form-select" required>
                    <option value="">-- Chọn chi nhánh rạp --</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="1">Đang làm việc</option>
                    <option value="0">Nghỉ việc</option>
                </select>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu nhân viên</button>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection
