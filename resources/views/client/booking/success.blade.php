<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vé điện tử | Thanh toán thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .ticket-card { background: #fff; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); overflow: hidden; position: relative; border-top: 5px solid #E71A0F; }
        .dashed-line { border-top: 2px dashed #ddd; margin: 20px 0; position: relative; }
        .dashed-line::before, .dashed-line::after { content: ''; position: absolute; width: 30px; height: 30px; background: #f4f6f9; border-radius: 50%; top: -16px; }
        .dashed-line::before { left: -35px; } .dashed-line::after { right: -35px; }
        .qr-code { width: 120px; height: 120px; background: #eee; margin: 0 auto; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            @if(session('success'))
                <div class="alert alert-success text-center mb-4"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
            @endif

            <div class="ticket-card p-4">
                @php 
                    $firstTicket = $bill->tickets->first(); 
                    $showtime = $firstTicket->showtime;
                @endphp

                <div class="text-center mb-4">
                    <h3 class="fw-bold text-uppercase mb-1">{{ $showtime->movie->name }}</h3>
                    <p class="text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i> Rạp chiếu {{ $showtime->room->cinema->name ?? 'CGV' }}</p>
                </div>

                <div class="row text-center mb-3">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block text-uppercase">Ngày chiếu</small>
                        <span class="fw-bold fs-5">{{ \Carbon\Carbon::parse($showtime->start_time)->format('d/m/Y') }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase">Giờ chiếu</small>
                        <span class="fw-bold fs-5 text-danger">{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                    </div>
                </div>

                <div class="row text-center mb-2">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block text-uppercase">Phòng chiếu</small>
                        <span class="fw-bold fs-5">{{ $showtime->room->room_name }}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase">Ghế của bạn</small>
                        <span class="fw-bold fs-5 text-success">
                            @foreach($bill->tickets as $ticket)
                                {{ $ticket->seat->seat_number }}@if(!$loop->last), @endif
                            @endforeach
                        </span>
                    </div>
                </div>

                <div class="dashed-line"></div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="text-muted fw-bold">Tổng thanh toán:</span>
                    <span class="fw-bold fs-4 text-danger">{{ number_format($bill->total, 0, ',', '.') }}đ</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="text-muted fw-bold">Phương thức:</span>
                    <span class="fw-bold">{{ $bill->paymentMethod->name ?? 'Không xác định' }}</span>
                </div>

                <div class="text-center mb-3">
                    <div class="qr-code">
                        <i class="fa-solid fa-qrcode fa-4x text-muted"></i>
                    </div>
                    <p class="small text-muted mt-2 mb-0">Mã hóa đơn: #BILL-{{ str_pad($bill->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <small>Vui lòng đưa mã này cho nhân viên soát vé</small>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 8px;"><i class="fa-solid fa-house me-2"></i>Về trang chủ</a>
            </div>

        </div>
    </div>
</div>

</body>
</html>