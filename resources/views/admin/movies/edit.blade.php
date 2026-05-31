@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-pen text-cgv me-2"></i>Cập nhật Phim</h4>

    @php
        $currentCategories = $movie->categories->pluck('id')->toArray();
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded-3 mb-4">
            <strong class="text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Hệ thống từ chối cập nhật vì:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tên phim <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $movie->name) }}" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Thời lượng (Phút) <span class="text-danger">*</span></label>
                <input type="number" name="duration" class="form-control" value="{{ old('duration', $movie->duration) }}" required>
            </div>

            @php
                $minDate = \Carbon\Carbon::parse($movie->release_date)->isPast() 
                            ? \Carbon\Carbon::parse($movie->release_date)->format('Y-m-d') 
                            : \Carbon\Carbon::tomorrow()->format('Y-m-d');
            @endphp
            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Ngày khởi chiếu <span class="text-danger">*</span></label>
                <input type="date" name="release_date" class="form-control" value="{{ old('release_date', $movie->release_date) }}" min="{{ $minDate }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ngôn ngữ <span class="text-danger">*</span></label>
                <input type="text" name="language" class="form-control" value="{{ old('language', $movie->language) }}" required>
            </div>

             <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ảnh Poster</label>
                <input type="file" name="poster" class="form-control" accept="image/*">

                @if(isset($movie) && $movie->poster)
                    <div class="mt-2 shadow-sm d-inline-block p-1 bg-white border rounded">
                        <p class="mb-1 text-muted"><small>Ảnh hiện tại:</small></p>
                        @if(Str::startsWith($movie->poster, ['http://', 'https://']))
                            <img src="{{ $movie->poster }}" alt="Poster" style="height: 120px; object-fit: cover; border-radius: 4px;">
                        @else
                            <img src="{{ asset('storage/' . $movie->poster) }}" alt="Poster" style="height: 120px; object-fit: cover; border-radius: 4px;">
                        @endif
                    </div>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="1" {{ old('status', $movie->status) == 1 ? 'selected' : '' }}>Đang chiếu</option>
                    <option value="0" {{ old('status', $movie->status) == 0 ? 'selected' : '' }}>Ngừng chiếu</option>
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
                                        {{ in_array($category->id, old('category_ids', $currentCategories)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Cập nhật</button>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection