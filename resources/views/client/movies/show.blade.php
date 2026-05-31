@extends('client.layouts.app')

@section('content')
    <style>
        /* Bọc nền đen cho riêng khu vực chi tiết phim để không ảnh hưởng Header/Footer */
        .movie-detail-wrapper { 
            background-color: #1a1a1a; 
            color: #fff; 
            font-family: 'Segoe UI', sans-serif; 
            padding-top: 50px; 
            padding-bottom: 50px;
        }
        .movie-poster { border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); object-fit: cover; width: 100%; }
        .btn-book { background-color: #E71A0F; color: white; font-weight: bold; padding: 12px 30px; font-size: 1.1rem; border-radius: 8px; border: none; transition: 0.3s; }
        .btn-book:hover { background-color: #c9160d; transform: translateY(-2px); }
        .movie-info-title { color: #aaa; font-weight: 600; width: 120px; display: inline-block; }
        html { scroll-behavior: smooth; }
    </style>

    <div class="movie-detail-wrapper">
        <div class="container mb-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    @php
                        // Tối ưu hóa việc load ảnh giống như đã làm ở các trang khác
                        $posterUrl = 'https://www.cgv.vn/media/catalog/product/placeholder/default/cgv-default-poster.jpg';
                        if (!empty($movie->poster)) {
                            $posterUrl = Str::startsWith($movie->poster, ['http://', 'https://']) 
                                        ? $movie->poster 
                                        : asset('storage/' . $movie->poster);
                        }
                    @endphp
                    <img src="{{ $posterUrl }}" 
                         alt="{{ $movie->name }}" 
                         class="movie-poster img-fluid" 
                         onerror="this.src='https://www.cgv.vn/media/catalog/product/placeholder/default/cgv-default-poster.jpg'">
                </div>

                <div class="col-md-8">
                    <h1 class="fw-bold text-uppercase mb-3">{{ $movie->name }}</h1>
                    
                    <div class="d-flex align-items-center mb-4">
                        <span class="badge bg-warning text-dark me-2 fs-6"><i class="fa-solid fa-star"></i> 8.5/10</span>
                        <span class="badge bg-secondary me-2 fs-6">{{ $movie->duration ?? 120 }} Phút</span>
                        <span class="badge bg-danger fs-6">{{ $movie->rating ?? 'T13' }}</span> 
                    </div>

                    <p class="mb-2"><span class="movie-info-title">Đạo diễn:</span> {{ $movie->director ?? 'Đang cập nhật' }}</p>
                    <p class="mb-2"><span class="movie-info-title">Diễn viên:</span> {{ $movie->cast ?? 'Đang cập nhật' }}</p>
                    <p class="mb-2"><span class="movie-info-title">Thể loại:</span> {{ $movie->genre ?? 'Hành động, Viễn tưởng' }}</p>
                    <p class="mb-4"><span class="movie-info-title">Khởi chiếu:</span> {{ date('d/m/Y', strtotime($movie->release_date)) }}</p>

                    <h5 class="fw-bold border-bottom border-secondary pb-2 mb-3">Nội dung phim</h5>
                    <p class="text-light opacity-75" style="line-height: 1.8;">
                        {{ $movie->description ?? 'Đang cập nhật nội dung chi tiết cho bộ phim này...' }}
                    </p>

                    <div class="mt-5 d-flex gap-3">
                        @if(isset($movie->trailer_url))
                            <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-outline-light d-flex align-items-center px-4" style="border-radius: 8px;">
                                <i class="fa-brands fa-youtube text-danger me-2"></i> Xem Trailer
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-5 pt-4" id="showtimes">
                <div class="col-12">
                    <h4 class="fw-bold border-bottom border-secondary pb-3 mb-4 text-uppercase">
                        <i class="fa-solid fa-calendar-check text-danger me-2"></i>Lịch chiếu phim
                    </h4>

                    @if(isset($showtimes) && $showtimes->isEmpty())
                        <div class="alert alert-dark text-center opacity-75 py-4">
                            <i class="fa-solid fa-face-frown-open fa-2x mb-2 d-block"></i>
                            Hiện chưa có lịch chiếu cho bộ phim này trong thời gian tới.
                        </div>
                    @elseif(isset($showtimes))
                        @foreach($showtimes as $date => $times)
                            <div class="mb-4 bg-dark p-4 rounded shadow-sm" style="border: 1px solid #333;">
                                <h5 class="text-warning fw-bold mb-3">
                                    <i class="fa-solid fa-calendar-day me-2"></i> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                </h5>
                                
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($times as $show)
                                        <a href="{{ route('client.book.seats', $show->id) }}" class="btn btn-outline-light d-flex flex-column align-items-center justify-content-center px-4 py-2" style="border-radius: 8px; min-width: 100px; transition: 0.2s;">
                                          <span class="fw-bold fs-5 mb-1">{{ \Carbon\Carbon::parse($show->start_time)->format('H:i') }}</span>
                                          <span style="font-size: 0.75rem; color: #ccc;">{{ $show->room->room_name ?? 'Phòng chiếu' }}</span>
                                       </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection