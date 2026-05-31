@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark">
            <i class="fa-solid fa-calendar-day text-cgv me-2"></i>
            Chi tiết doanh thu ngày <span class="text-danger fw-bold">{{ $formattedDate }}</span>
        </h4>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại Tổng quan
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                    <tr>
                        <th width="80">Mã Đơn</th>
                        <th class="text-start">Khách hàng</th>
                        <th>Thời gian đặt</th>
                        <th>Phương thức thanh toán</th>
                        <th>Tổng tiền (VNĐ)</th>
                        <th width="120">Chi tiết</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($bills as $bill)
                        <tr>
                            <td class="text-muted fw-bold">#INV{{ str_pad($bill->id, 5, '0', STR_PAD_LEFT) }}</td>
                            
                            <td class="text-start fw-bold text-dark">
                                {{ $bill->customer->name ?? $bill->customer->full_name ?? 'Khách vãng lai' }}
                            </td>
                            
                            <td>{{ \Carbon\Carbon::parse($bill->created_at)->format('H:i:s') }}</td>
                            
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $bill->paymentMethod->name ?? 'Tại quầy' }}
                                </span>
                            </td>
                            
                            <td class="text-danger fw-bold">
                                {{ number_format($bill->total, 0, ',', '.') }} ₫
                            </td>
                            
                            <td>
                                <a href="{{ route('admin.bills.show', $bill->id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết hóa đơn">
                                    <i class="fa-solid fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt fa-3x mb-3 d-block text-light"></i>
                                Không có giao dịch nào được thực hiện trong ngày này!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white text-end py-3">
            <h5 class="m-0">Tổng doanh thu ngày: <span class="text-danger fw-bold">{{ number_format($bills->sum('total'), 0, ',', '.') }} ₫</span></h5>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $bills->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection