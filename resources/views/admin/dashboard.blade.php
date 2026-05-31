@extends('admin.layouts.app')

@section('content')
<style>
    /* CSS TẠO KHỐI THỐNG KÊ (SMALL-BOX) KIỂU ADMINLTE */
    .small-box {
        border-radius: 0.5rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        display: block;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        color: #fff !important;
    }
    .small-box .inner {
        padding: 20px;
    }
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
        z-index: 3;
        position: relative;
    }
    .small-box p {
        font-size: 1.1rem;
        z-index: 3;
        position: relative;
        margin-bottom: 0;
    }
    .small-box .icon {
        color: rgba(0,0,0,.15);
        z-index: 0;
        position: absolute;
        right: 15px;
        top: 15px;
        transition: transform .3s linear;
    }
    .small-box .icon i {
        font-size: 70px;
    }
    .small-box:hover .icon {
        transform: scale(1.15);
    }
    .small-box .small-box-footer {
        background-color: rgba(0,0,0,.1);
        color: rgba(255,255,255,.8);
        display: block;
        padding: 5px 0;
        position: relative;
        text-align: center;
        text-decoration: none;
        z-index: 10;
        transition: background-color .3s;
    }
    .small-box .small-box-footer:hover {
        background-color: rgba(0,0,0,.15);
        color: #fff;
    }
    
    /* Bảng màu chuẩn */
    .bg-info { background-color: #17a2b8 !important; }
    .bg-success { background-color: #28a745 !important; }
    .bg-warning { background-color: #ffc107 !important; color: #1f2d3d !important; }
    .bg-warning .small-box-footer { color: rgba(0,0,0,.6); }
    .bg-warning .small-box-footer:hover { color: #000; }
    .bg-danger { background-color: #dc3545 !important; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-chart-pie text-danger me-2"></i>Tổng quan hệ thống</h4>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalTickets ?? 0) }}</h3>
                    <p>Vé Đã Bán</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <a href="{{ route('admin.tickets.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fa-solid fa-circle-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}<sup style="font-size: 20px">đ</sup></h3>
                    <p>Tổng Doanh Thu</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <a href="{{ route('admin.bills.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fa-solid fa-circle-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalCustomers ?? 0) }}</h3>
                    <p>Khách Hàng Đăng Ký</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <a href="{{ route('admin.customers.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fa-solid fa-circle-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($todayShowtimes ?? 0) }}</h3>
                    <p>Suất Chiếu Hôm Nay</p>
                </div>
                <div class="icon">
                    <i class="fa-solid fa-video"></i>
                </div>
                <a href="{{ route('admin.showtimes.index') }}" class="small-box-footer">
                    Xem chi tiết <i class="fa-solid fa-circle-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom fw-bold py-3">
                    <i class="fa-solid fa-calendar-day text-danger me-1"></i> Doanh thu theo ngày (Tháng {{ $currentMonth ?? date('m') }}/{{ $currentYear ?? date('Y') }})
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom fw-bold py-3">
                    <i class="fa-solid fa-calendar-days text-success me-1"></i> Doanh thu các tháng (Năm {{ $currentYear ?? date('Y') }})
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom fw-bold py-3 d-flex align-items-center">
                    <i class="fa-solid fa-trophy text-warning me-2 fs-5"></i>
                    <span class="fs-5 text-dark">Top 5 Phim Bán Chạy Nhất (Tháng {{ $currentMonth ?? date('m') }}/{{ $currentYear ?? date('Y') }})</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="100" class="py-3">Xếp hạng</th>
                                    <th class="text-start py-3">Tên phim</th>
                                    <th width="200" class="py-3">Số lượng vé đã bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topMovies ?? [] as $index => $movie)
                                    <tr>
                                        <td>
                                            @if($index == 0) <span class="fs-3">🥇</span>
                                            @elseif($index == 1) <span class="fs-3">🥈</span>
                                            @elseif($index == 2) <span class="fs-3">🥉</span>
                                            @else <span class="fw-bold text-muted fs-5">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="text-start fw-bold text-dark fs-6">{{ $movie->name }}</td>
                                        <td>
                                            <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill shadow-sm">
                                                {{ number_format($movie->total_tickets) }} vé
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-muted py-5">
                                            <i class="fa-solid fa-film fa-2x mb-2 text-light d-block"></i>
                                            Chưa có dữ liệu bán vé nào được ghi nhận trong tháng này.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dailyLabels = @json($dailyLabels ?? []);
        const dailyData = @json($dailyData ?? []);
        
        const monthlyLabels = @json($monthlyLabels ?? []);
        const monthlyData = @json($monthlyData ?? []);

        // 1. VẼ BIỂU ĐỒ THEO NGÀY
        const ctxDaily = document.getElementById('dailyChart');
        if (ctxDaily && dailyLabels.length > 0) {
            new Chart(ctxDaily.getContext('2d'), {
                type: 'line', 
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: dailyData,
                        backgroundColor: 'rgba(220, 53, 69, 0.2)', 
                        borderColor: 'rgba(220, 53, 69, 1)', 
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(220, 53, 69, 1)',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(220, 53, 69, 1)',
                        pointRadius: 4, 
                        pointHoverRadius: 6, 
                        fill: true, 
                        tension: 0.4 
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' ₫';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const day = index + 1; 
                            const month = {{ $currentMonth ?? date('m') }};
                            const year = {{ $currentYear ?? date('Y') }};
                            const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                            
                            // Route chuẩn khớp với web.php
                            window.location.href = `{{ route('admin.daily_details') }}?date=${dateStr}`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                    }
                }
            });
        }

       // 2. VẼ BIỂU ĐỒ THEO THÁNG
        const ctxMonthly = document.getElementById('monthlyChart');
        if (ctxMonthly && monthlyLabels.length > 0) {
            new Chart(ctxMonthly.getContext('2d'), {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: monthlyData,
                        backgroundColor: 'rgba(25, 135, 84, 0.2)', 
                        borderColor: 'rgba(25, 135, 84, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(25, 135, 84, 1)',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(25, 135, 84, 1)',
                        pointRadius: 4, 
                        pointHoverRadius: 6, 
                        fill: true,
                        tension: 0.4 
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' ₫';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const month = index + 1; 
                            const year = {{ $currentYear ?? date('Y') }};
                            
                            // Route chuẩn khớp với web.php
                            window.location.href = `{{ route('admin.monthly_details') }}?month=${month}&year=${year}`;
                        }
                    },
                    onHover: (event, chartElement) => {
                        event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                    }
                }
            });
        }
    });
</script>
@endsection