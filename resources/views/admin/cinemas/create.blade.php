@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-plus text-cgv me-2"></i>Thêm Rạp chiếu mới</h4>

    <form action="{{ route('admin.cinemas.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
               <label class="form-label fw-bold">Tên rạp <span class="text-danger">*</span></label>
               <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="VD: CGV Vincom Bà Triệu" required>
    
            @error('name')
        <div class="invalid-feedback fw-bold">
            <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
        </div>
            @enderror
        </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Thông tin liên hệ <span class="text-danger">*</span></label>
                <input type="text" name="contact_info" class="form-control @error('contact_info') is-invalid @enderror" value="{{ old('contact_info') }}" placeholder="Số điện thoại hoặc Email" required>
                
                @error('contact_info')
                    <div class="invalid-feedback fw-bold">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu lại</button>
        <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection