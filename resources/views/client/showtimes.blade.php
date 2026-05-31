@extends('client.layouts.app')

@section('content')
<style>
    .cinema-detail-container { background-color: #fdfcf0; padding-bottom: 50px; min-height: 80vh; }
    
    /* Header Rạp */
    .cinema-header {
        background-color: #232221;
        padding: 40px 0;
        text-align: center;
        border-bottom: 4px solid #e71a0f;
    }
    
    /* Thanh Ribbon Tabs đỏ đặc trưng */
    .cinema-tabs-nav {
        display: flex;
        justify-content: center;
        background: url('https://www.cgv.vn/skin/frontend/cgv/default/images/bg-cgv/bg-header-home.jpg') repeat-x;
        padding: 10px 0;
        margin-bottom: 30px;
    }
    .nav-tabs-custom { border: none; gap: 15px; }
    .nav-tabs-custom .nav-link {
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
        border: none;
        padding: 10px 40px;
        font-size: 16px;
        background: transparent;
        transition: 0.3s;
    }
    .nav-tabs-custom .nav-link.active {
        background-color: #e71a0f;
        color: #fff;
        border-radius: 5px;
    }
    .nav-tabs-custom .nav-link:not(.active):hover { color: #ffc107; }

    /* Lịch chiếu theo phim */
    .movie-row { border-bottom: 2px dashed #ccc; padding: 30px 0; }
    .movie-row:last-child { border-bottom: none; }
    .movie-poster { width: 100%; max-width: 180px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover; height: 260px; }
    .movie-title { font-weight: bold; text-transform: uppercase; color: #222; font-size: 22px; }
    
    .showtime-box {
        display: inline-block;
        border: 2px solid #ccc;
        padding: 8px 20px;
        margin-right: 15px;
        margin-bottom: 15px;
        color: #333;
        text-decoration: none;
        font-weight: bold;
        background: #fff;
        font-size: 16px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .showtime-box:hover { 
        background: #e71a0f; 
        color: #fff; 
        border-color: #e71a0f; 
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(231,26,15,0.3);
    }

    /* Bảng giá vé */
    .price-table-wrapper { max-width: 700px; margin: 0 auto; }

    /* =========================================
       CSS CHO THANH TRƯỢT NGÀY (DATE SLIDER) 
       ========================================= */
    .date-slider-wrapper {
        display: flex;
        align-items: center;
        border-top: 2px solid #222;
        border-bottom: 2px solid #222;
        padding: 10px 0;
        margin-bottom: 30px;
        background-color: transparent;
    }
    .date-scroll-track {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        flex-grow: 1;
        padding: 0 10px;
    }
    .date-scroll-track::-webkit-scrollbar { display: none; /* Ẩn thanh cuộn mặc định */ }
    
    .date-item {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #777;
        padding: 5px 15px;
        border: 2px solid transparent;
        min-width: 90px;
        border-radius: 5px;
        transition: 0.2s;
    }
    .date-item:hover { color: #222; background-color: #f1f1f1; }
    .date-item.active { border-color: #222; color: #222; background-color: #fff; }
    
    .date-info {
        display: flex;
        flex-direction: column;
        font-size: 13px;
        line-height: 1.2;
        margin-right: 8px;
        text-transform: uppercase;
        text-align: center;
    }
    .date-number { font-size: 34px; font-weight: bold; line-height: 1; }
    
    .nav-arrow {
        background: #000; color: #fff; border: none;
        width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: 0.2s;
        flex-shrink: 0;
    }
    .nav-arrow:hover { background: #e71a0f; }
</style>

@php
    $selectedDate = request('date', now()->format('Y-m-d'));
    $dates = [];
    for ($i = 0; $i < 14; $i++) {
        $dates[] = now()->addDays($i);
    }
@endphp

<div class="cinema-detail-container">
    
    <div class="cinema-header shadow">
        <div class="container">
            <h1 class="fw-bold text-uppercase text-white mb-0">{{ $cinema->name }}</h1>
            <p class="text-light mt-2 mb-0">
                <i class="fa-solid fa-location-dot text-danger me-2"></i> 
                {{ $cinema->address ?? 'Đang cập nhật địa chỉ...' }}
            </p>
        </div>
    </div>

    <div class="cinema-tabs-nav shadow-sm">
        <ul class="nav nav-tabs nav-tabs-custom" id="cinemaTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button">Lịch chiếu</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="price-tab" data-bs-toggle="tab" data-bs-target="#price" type="button">Bảng Giá vé</button>
            </li>
        </ul>
    </div>

    <div class="container">
        <div class="tab-content" id="cinemaTabContent">
            
            <div class="tab-pane fade show active" id="schedule" role="tabpanel">
                
                <div class="date-slider-wrapper">
                    <button class="nav-arrow shadow" onclick="scrollDates('left')"><i class="fa-solid fa-chevron-left"></i></button>
                    
                    <div class="date-scroll-track" id="dateTrack">
                        @foreach($dates as $d)
                            @php
                                $isDateSelected = $selectedDate == $d->format('Y-m-d');
                            @endphp
                            <a href="{{ request()->url() }}?date={{ $d->format('Y-m-d') }}" class="date-item {{ $isDateSelected ? 'active shadow-sm' : '' }}">
                                <div class="date-info">
                                    <span class="month">{{ $d->format('m') }}</span>
                                    <span class="day">{{ $d->format('D') }}</span>
                                </div>
                                <div class="date-number">{{ $d->format('d') }}</div>
                            </a>
                        @endforeach
                    </div>

                    <button class="nav-arrow shadow" onclick="scrollDates('right')"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <div class="bg-white p-4 rounded shadow-sm">
                    @forelse($showtimes as $movieId => $items)
                        @php 
                            $movie = $items->first()->movie; 
                            $posterUrl = 'https://www.cgv.vn/media/catalog/product/placeholder/default/cgv-default-poster.jpg';
                            if (!empty($movie->poster)) {
                                $posterUrl = Str::startsWith($movie->poster, ['http://', 'https://']) 
                                            ? $movie->poster 
                                            : asset('storage/' . $movie->poster);
                            }
                        @endphp
                        
                        <div class="row movie-row">
                            <div class="col-md-3 text-center mb-3 mb-md-0">
                                <img src="{{ $posterUrl }}" 
                                     class="movie-poster shadow" 
                                     alt="{{ $movie->name }}"
                                     onerror="this.src='https://www.cgv.vn/media/catalog/product/placeholder/default/cgv-default-poster.jpg'">
                            </div>
                            <div class="col-md-9">
                                <h4 class="movie-title">
                                    {{ $movie->name }} 
                                    @if($movie->rating)
                                        <span class="badge bg-warning text-dark ms-2 align-middle fs-6">{{ $movie->rating }}</span>
                                    @endif
                                </h4>
                                <p class="text-muted mb-4 fw-bold"><i class="fa-solid fa-closed-captioning me-1"></i> 2D Phụ Đề Tiếng Việt</p>
                                
                                <div class="showtimes-list mt-3">
                                    @foreach($items as $st)
                                        <a href="{{ route('client.book.seats', $st->id) }}" class="showtime-box">
                                          {{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7466/7466140.png" width="80" class="opacity-50 mb-3" alt="Empty">
                            <h5 class="text-muted fw-bold">Rạp hiện chưa có suất chiếu nào trong ngày này. Vui lòng chọn ngày khác!</h5>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="price" role="tabpanel">
                <div class="price-table-wrapper mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header text-center fw-bold py-3 bg-dark text-white fs-5">
                            <i class="fa-solid fa-tags me-2 text-warning"></i>BẢNG GIÁ VÉ NIÊM YẾT
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-bordered mb-0 text-center align-middle">
                                <thead class="table-light text-uppercase">
                                    <tr>
                                        <th class="py-3 text-dark">Loại ghế</th>
                                        <th class="py-3 text-dark">Giá vé (VNĐ)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($typeSeats as $type)
                                        <tr>
                                            <td class="fw-bold py-3 fs-6">{{ $type->name }}</td>
                                            <td class="text-danger fw-bold fs-5 py-3">
                                                {{ number_format($type->basePrice) }} đ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="alert alert-warning mt-4 border-0 shadow-sm">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        <strong>Lưu ý:</strong> Giá vé trên là giá vé cơ sở. Giá vé thực tế có thể thay đổi tùy theo định dạng phim (3D, IMAX), các ngày Lễ, Tết hoặc các chương trình khuyến mãi đang diễn ra.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // JS mượt mà cho 2 nút cuộn ngày
    function scrollDates(direction) {
        const track = document.getElementById('dateTrack');
        const scrollAmount = 300; // Mỗi lần cuộn 300px
        if(direction === 'left') {
            track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
</script>
@endsection