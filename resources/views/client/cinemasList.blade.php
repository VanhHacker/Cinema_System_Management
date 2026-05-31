@extends('client.layouts.app')

@section('content')
<style>
    /* Custom CSS cho danh sách Rạp chiếu kiểu Dark Theme (CGV Style) */
    body {
        background-color: #fdfcf0; /* Màu nền sáng nhẹ đặc trưng bên ngoài trang khách */
    }
    
    .cinema-wrapper {
        background-color: #232221; /* Nền xám đen */
        border: 4px solid #363636;
        padding: 40px 40px;
        margin-top: 40px;
        margin-bottom: 60px;
        border-radius: 8px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }
    
    .cinema-title {
        color: #fdfcf0;
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 26px;
        margin-bottom: 30px;
        border-bottom: 2px solid #363636;
        padding-bottom: 20px;
        letter-spacing: 2px;
    }
    
    .cinema-item {
        display: block;
        color: #cccccc;
        text-decoration: none;
        padding: 10px 15px;
        margin-bottom: 12px;
        border-radius: 6px;
        font-size: 15px;
        transition: all 0.2s ease-in-out;
    }
    
    .cinema-item:hover {
        background-color: #e71a0f; /* Màu đỏ CGV */
        color: #ffffff;
        font-weight: bold;
        transform: translateX(5px); /* Hiệu ứng trượt nhẹ sang phải khi di chuột */
    }
</style>

<div class="container">
    <div class="cinema-wrapper">
        <h2 class="cinema-title">Hệ thống Rạp chiếu</h2>
        
        <div class="row">
            @forelse($cinemas as $cinema)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="{{ route('client.showtimes', $cinema->id) }}" class="cinema-item">
                        {{ $cinema->name }}
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-white py-5">
                    <i class="fa-solid fa-film fs-1 mb-3 text-muted"></i>
                    <p class="fs-5">Hiện tại hệ thống chưa có rạp chiếu nào.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection