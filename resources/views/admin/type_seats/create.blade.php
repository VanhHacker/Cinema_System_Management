@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-plus text-cgv me-2"></i>Thêm Loại ghế mới</h4>

    <form action="{{ route('admin.type_seats.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Tên loại ghế <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="VD: Ghế Thường, Ghế VIP, Sweetbox..." required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Mức giá cơ bản (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" name="basePrice" class="form-control" placeholder="VD: 50000" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả thêm</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu lại</button>
        <a href="{{ route('admin.type_seats.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection
