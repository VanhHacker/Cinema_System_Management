@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-pen text-cgv me-2"></i>Cập nhật Loại ghế</h4>

    <form action="{{ route('admin.type_seats.update', $typeSeat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-bold">Tên loại ghế <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $typeSeat->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Mức giá cơ bản (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" name="basePrice" class="form-control" value="{{ $typeSeat->basePrice }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Mô tả thêm</label>
            <textarea name="description" class="form-control" rows="3">{{ $typeSeat->description }}</textarea>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật</button>
        <a href="{{ route('admin.type_seats.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection
