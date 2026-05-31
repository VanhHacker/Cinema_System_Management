@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-plus text-cgv me-2"></i>Thêm Phim mới</h4>

    <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên phim <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Thời lượng (Phút) <span class="text-danger">*</span></label>
                <input type="number" name="duration" class="form-control" value="{{ old('duration') }}" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Ngày khởi chiếu <span class="text-danger">*</span></label>
                <input type="date" name="release_date" class="form-control" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ngôn ngữ <span class="text-danger">*</span></label>
                <input type="text" name="language" class="form-control" value="{{ old('language') }}" placeholder="VD: Tiếng Anh - Phụ đề Tiếng Việt" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ảnh Poster</label>
                <input type="file" name="poster" class="form-control" accept="image/*">

                @if(isset($movie) && $movie->poster)
                    <div class="mt-2">
                        <p class="mb-1 text-muted"><small>Ảnh hiện tại:</small></p>
                        <img src="{{ asset('storage/' . $movie->poster) }}" alt="Poster" style="height: 100px; border-radius: 5px;">
                    </div>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Đang chiếu</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ngừng chiếu</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Thể loại phim <span class="text-danger">*</span></label>
                <div class="border rounded p-3 bg-light">
                    <div class="row">
                        @foreach($categories as $category)
                            <div class="col-md-3 col-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="category_ids[]" value="{{ $category->id }}" id="cat_{{ $category->id }}"
                                        {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <small class="text-muted">Có thể chọn nhiều thể loại cho 1 bộ phim.</small>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu bộ phim</button>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Hủy bỏ</a>
    </form>
@endsection
