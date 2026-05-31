@extends('client.layouts.app')

@section('content')
<style>
    /* 1. Container bao quanh: Sử dụng Flexbox để các viên ghế tự xếp hàng */
    .seat-badge-container {
        display: flex;
        flex-wrap: wrap; /* Cho phép rớt dòng khi quá dài */
        gap: 6px; /* Khoảng cách đều giữa các ghế */
        max-width: 100%; /* Không vượt quá khung cha */
        margin-top: 8px; /* Khoảng cách với chữ "Ghế:" */
    }

    /* 2. Style cho từng "viên ghế" (như biểu tượng ghế thu nhỏ) */
    .individual-seat-badge {
        background-color: #333; /* Màu nền đen xám */
        color: #fff;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: bold;
        text-align: center;
        min-width: 38px; /* Đảm bảo các ghế số 1 chữ số hay Sweetbox dài đều nhìn cân đối */
        border: 1px solid #444; /* Viền nhẹ tạo khối */
        white-space: nowrap; /* Không cho ngắt tên ghế (vd: Sweetbox 1) */
        transition: 0.2s;
    }
    
    /* Hiệu ứng nhẹ khi hover */
    .individual-seat-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* MÀU SẮC RIÊNG CHO TỪNG LOẠI GHẾ */
    .seat-normal { background-color: #6c757d !important; border-color: #5c636a !important; }
    .seat-vip { background-color: #e71a0f !important; border-color: #c9160d !important; }
    .seat-sweetbox { background-color: #ffc107 !important; border-color: #d39e00 !important; color: #000 !important; width: 60px; }

    /* Layout tổng thể */
    .history-container { background-color: #fdfcf0; padding-bottom: 60px; min-height: 80vh; }
    
    .page-title-box { 
        background-color: #232221; 
        padding: 30px 0; 
        border-bottom: 4px solid #e71a0f; 
        text-align: center; 
        margin-bottom: 40px; 
    }
    .page-title-box h2 { color: #fff; text-transform: uppercase; font-weight: bold; margin: 0; letter-spacing: 1px; }

    /* Thẻ vé (Ticket Card) */
    .ticket-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #eaeaea;
        transition: transform 0.2s ease;
    }
    .ticket-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }

    @media (min-width: 768px) {
        .ticket-card { flex-direction: row; }
    }

    .ticket-poster {
        width: 100%;
        height: 250px;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .ticket-poster { width: 160px; height: auto; }
    }
    .ticket-poster img { width: 100%; height: 100%; object-fit: cover; }

    .ticket-info {
        padding: 25px;
        flex-grow: 1;
        border-bottom: 2px dashed #ddd;
    }
    @media (min-width: 768px) {
        .ticket-info { border-bottom: none; border-right: 2px dashed #ddd; }
    }
    .ticket-movie-title {
        font-size: 22px;
        font-weight: bold;
        text-transform: uppercase;
        color: #222;
        margin-bottom: 15px;
    }
    .ticket-detail-item { margin-bottom: 8px; color: #444; font-size: 15px; }
    .ticket-detail-item strong { color: #111; min-width: 90px; display: inline-block; }

    .ticket-summary {
        width: 100%;
        padding: 25px;
        background-color: #fafafa;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .ticket-summary { width: 260px; }
    }
    .ticket-price {
        font-size: 26px;
        font-weight: bold;
        color: #e71a0f;
        margin-bottom: 10px;
    }
    
    .barcode-box {
        background: #fff;
        border: 1px solid #ccc;
        padding: 10px;
        width: 100%;
        margin-top: 15px;
        border-radius: 4px;
    }
    .barcode-line { 
        height: 35px; 
        background: repeating-linear-gradient(90deg, #222, #222 2px, #fff 2px, #fff 4px, #222 4px, #222 7px, #fff 7px, #fff 10px); 
    }
</style>

<div class="history-container">
    <div class="page-title-box shadow">
        <h2>Lịch sử giao dịch</h2>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card border-0 shadow-sm mb-4 bg-white">
                    <div class="card-body p-3">
                        <form action="{{ route('client.history') }}" method="GET">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-film text-muted"></i></span>
                                        <input type="text" name="search_movie" class="form-control border-start-0 ps-0" placeholder="Tên phim (VD: Trợ lý nhà xác...)" value="{{ request('search_movie') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="cinema_id" class="form-select text-secondary">
                                        <option value="">-- Tất cả rạp chiếu --</option>
                                        @foreach($cinemas as $cinema)
                                            <option value="{{ $cinema->id }}" {{ request('cinema_id') == $cinema->id ? 'selected' : '' }}>
                                                {{ $cinema->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <input type="date" name="date" class="form-control text-secondary" value="{{ request('date') }}" title="Ngày chiếu phim">
                                </div>

                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-danger w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                                    @if(request()->anyFilled(['search_movie', 'cinema_id', 'date']))
                                        <a href="{{ route('client.history') }}" class="btn btn-secondary" title="Xóa bộ lọc"><i class="fa-solid fa-rotate-right"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @forelse($bills as $bill)
                    @php
                        $firstTicket = $bill->tickets->first();
                        $showtime = $firstTicket ? $firstTicket->showtime : null;
                        $movie = $showtime ? $showtime->movie : null;
                        $room = $firstTicket ? $firstTicket->seat->room : null;
                        $cinema = $room ? $room->cinema : null;

                        $posterUrl = 'https://www.cgv.vn/media/catalog/product/placeholder/default/cgv-default-poster.jpg';
                        if ($movie && !empty($movie->poster)) {
                            $posterUrl = Str::startsWith($movie->poster, ['http://', 'https://']) ? $movie->poster : asset('storage/' . $movie->poster);
                        }
                    @endphp

                    @if($movie)
                    <div class="ticket-card">
                        <div class="ticket-poster">
                            <img src="{{ $posterUrl }}" alt="{{ $movie->name }}" onerror="this.src='https://www.cgv.vn/media/catalog/product/placeholder/default/cgv-default-poster.jpg'">
                        </div>
                        
                        <div class="ticket-info">
                            <div class="ticket-movie-title">{{ $movie->name }}</div>
                            <div class="ticket-detail-item">
                                <i class="fa-solid fa-location-dot text-danger me-2"></i><strong>Rạp:</strong> {{ $cinema->name ?? 'CGV Cinemas' }}
                            </div>
                            <div class="ticket-detail-item">
                                <i class="fa-solid fa-door-open text-secondary me-2"></i><strong>Phòng:</strong> {{ $room->room_name ?? 'N/A' }}
                            </div>
                            <div class="ticket-detail-item">
                                <i class="fa-regular fa-calendar-days text-primary me-2"></i><strong>Suất chiếu:</strong> 
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i | d/m/Y') }}</span>
                            </div>
                            
                            <div class="ticket-detail-item mt-3">
                                <i class="fa-solid fa-couch text-success me-2"></i><strong>Ghế ngồi:</strong> 
                                
                                <div class="seat-badge-container">
                                    @forelse($bill->tickets as $ticket)
                                        @php
                                            $typeName = strtolower($ticket->seat->typeSeat->name ?? '');
                                            $seatClass = 'seat-normal'; 
                                            
                                            if (str_contains($typeName, 'vip')) {
                                                $seatClass = 'seat-vip'; 
                                            } elseif (str_contains($typeName, 'sweetbox') || str_contains($typeName, 'đôi')) {
                                                $seatClass = 'seat-sweetbox'; 
                                            }
                                        @endphp
                                        <span class="individual-seat-badge {{ $seatClass }} shadow-sm">
                                            {{ $ticket->seat->seat_number }}
                                        </span>
                                    @empty
                                        <small class="text-muted fst-italic">(Chưa xác định ghế)</small>
                                    @endforelse
                                </div>
                                
                                <div class="mt-2" style="font-size: 11px; opacity: 0.8;">
                                    <span class="d-inline-block me-2"><span style="display:inline-block; width:10px; height:10px; background:#6c757d; border-radius:2px; margin-right:3px;"></span>Thường</span>
                                    <span class="d-inline-block me-2"><span style="display:inline-block; width:10px; height:10px; background:#e71a0f; border-radius:2px; margin-right:3px;"></span>VIP</span>
                                    <span class="d-inline-block"><span style="display:inline-block; width:10px; height:10px; background:#ffc107; border-radius:2px; margin-right:3px;"></span>Đôi</span>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-summary">
                            <div class="text-muted mb-1 fs-6">Mã giao dịch</div>
                            <div class="fw-bold fs-5 mb-2 text-dark">#INV{{ sprintf('%05d', $bill->id) }}</div>
                            
                            <div class="text-muted mb-1 fs-6 mt-2">Tổng thanh toán</div>
                            <div class="ticket-price">{{ number_format($bill->total, 0, ',', '.') }} ₫</div>
                            
                            @php
                                $ticketStatus = $firstTicket->status ?? 1;
                            @endphp

                            @if($ticketStatus == 0)
                                <span class="badge bg-danger px-3 py-2 mb-2"><i class="fa-solid fa-xmark-circle me-1"></i> Đã hủy</span>
                            @elseif($ticketStatus == 1)
                                <span class="badge bg-success px-3 py-2 mb-2"><i class="fa-solid fa-check-circle me-1"></i> Thành công</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 mb-2">Chờ xử lý</span>
                            @endif

                            <div class="barcode-box">
                                <div class="barcode-line"></div>
                                <small class="text-muted mt-2 d-block fw-bold">Mua lúc: {{ $bill->created_at->format('H:i d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                    @endif

                @empty
                    <div class="text-center py-5 bg-white rounded shadow-sm border">
                        <img src="https://cdn-icons-png.flaticon.com/512/7466/7466140.png" width="100" class="opacity-50 mb-4 mt-3" alt="Empty">
                        <h4 class="text-dark fw-bold">Bạn chưa có lịch sử đặt vé nào phù hợp!</h4>
                        <p class="text-muted mb-4">Thử thay đổi bộ lọc hoặc chọn cho mình một bộ phim yêu thích và trải nghiệm ngay nhé.</p>
                        <a href="/" class="btn btn-danger px-4 py-2 fw-bold text-uppercase"><i class="fa-solid fa-ticket me-2"></i> Đặt vé ngay</a>
                    </div>
                @endforelse

                @if(method_exists($bills, 'hasPages') && $bills->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $bills->links('pagination::bootstrap-5') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection