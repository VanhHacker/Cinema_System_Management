@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-pen text-cgv me-2"></i>Cập nhật Rạp chiếu</h4>

    <form action="{{ route('admin.cinemas.update', $cinema->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên rạp <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $cinema->name }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Thông tin liên hệ</label>
                <input type="text" name="contact_info" class="form-control" value="{{ $cinema->contact_info }}">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                <input type="text" name="address" class="form-control" value="{{ $cinema->address }}" required>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật</button>
        <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection
