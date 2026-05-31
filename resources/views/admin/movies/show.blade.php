@extends('admin.layouts.app')

@section('title', 'Chi tiết phim')

@section('content')

    <div class="card-box">

        <h4 class="fw-bold mb-3">🎬 {{ $movie->title }}</h4>

        <p><strong>⏱ Thời lượng:</strong> {{ $movie->duration }} phút</p>
        <p><strong>📅 Ngày chiếu:</strong> {{ $movie->release_date }}</p>
        <p><strong>📌 Trạng thái:</strong>
            @if($movie->status == 'showing')
                <span class="badge badge-active">Đang chiếu</span>
            @else
                <span class="badge badge-inactive">Sắp chiếu</span>
            @endif
        </p>

        <hr>

        <p>{{ $movie->description }}</p>

        <a href="{{ route('movies.index') }}" class="btn btn-light border rounded-pill px-4 mt-3">
            ← Quay lại
        </a>

    </div>

@endsection
