@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-plus text-cgv me-2"></i>Thêm Phương thức Thanh toán</h4>

    <form action="{{ route('admin.payment_methods.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Tên phương thức <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="VD: Momo, ZaloPay, Tiền mặt..." required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả thêm</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="1">Hoạt động</option>
                <option value="0">Khóa tạm thời</option>
            </select>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu lại</button>
        <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection
