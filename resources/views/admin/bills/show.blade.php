@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-receipt text-cgv me-2"></i>Chi tiết Hóa đơn #INV{{ sprintf('%05d', $bill->id) }}</h4>
        <div>
            <button onclick="window.print()" class="btn btn-outline-dark me-2"><i class="fa-solid fa-print me-1"></i> In hóa đơn</button>
            <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row mb-4 border-bottom pb-3">
                <div class="col-sm-6">
                    <h5 class="fw-bold text-cgv mb-3">Thông tin Khách hàng</h5>
                    <p class="mb-1"><strong>Họ tên:</strong> {{ $bill->customer->name ?? 'Khách mua tại quầy' }}</p>
                    <p class="mb-1"><strong>Số điện thoại:</strong> {{ $bill->customer->phone ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $bill->customer->email ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <h5 class="fw-bold text-muted mb-3">Thông tin Giao dịch</h5>
                    <p class="mb-1"><strong>Ngày lập:</strong> {{ $bill->created_at->format('d/m/Y H:i:s') }}</p>
                    <p class="mb-1"><strong>Nhân viên xử lý:</strong> {{ $bill->staff->name ?? 'Hệ thống tự động (Web/App)' }}</p>
                    <p class="mb-1"><strong>Phương thức TT:</strong> <span class="badge bg-dark">{{ $bill->paymentMethod->name ?? 'N/A' }}</span></p>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Chi tiết Vé đã mua</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light text-center">
                    <tr>
                        <th>STT</th>
                        <th>Phim chiếu</th>
                        <th>Suất chiếu</th>
                        <th>Vị trí (Phòng & Ghế)</th>
                        <th>Trạng thái vé</th>
                        <th>Đơn giá</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bill->tickets as $index => $ticket)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold text-primary">{{ $ticket->showtime->movie->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($ticket->showtime->start_time)->format('H:i d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $ticket->seat->room->room_name ?? 'N/A' }}</span>
                                <span class="badge bg-danger">Ghế {{ $ticket->seat->seat_number ?? 'N/A' }}</span>
                            </td>
                            <td class="text-center">
                                <!-- 👉 ĐÃ SỬA: Kiểm tra trạng thái bằng số thay vì chữ -->
                                @if($ticket->status == 1)
                                    <span class="badge bg-success">Đã đặt</span>
                                @elseif($ticket->status == 2)
                                    <span class="badge bg-secondary">Đã sử dụng</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($ticket->price, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5" class="text-end fw-bold fs-5 text-uppercase">Tổng tiền thanh toán:</td>
                        <td class="text-end fw-bold fs-4 text-danger">{{ number_format($bill->total, 0, ',', '.') }} ₫</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .card, .card * {
                visibility: visible;
            }
            .card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .btn, .sidebar, .navbar-top {
                display: none !important;
            }
        }
    </style>
@endsection