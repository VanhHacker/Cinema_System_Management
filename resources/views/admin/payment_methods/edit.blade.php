@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-pen text-cgv me-2"></i>Cập nhật Phương thức Thanh toán</h4>

    <form action="{{ route('admin.payment_methods.update', $paymentMethod->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-bold">Tên phương thức <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $paymentMethod->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả thêm</label>
            <textarea name="description" class="form-control" rows="3">{{ $paymentMethod->description }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="1" {{ $paymentMethod->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ $paymentMethod->status == 0 ? 'selected' : '' }}>Khóa tạm thời</option>
            </select>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật</button>
        <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection
