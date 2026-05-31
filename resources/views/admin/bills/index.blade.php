@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-file-invoice-dollar text-danger me-2"></i>Quản lý Hóa đơn</h4>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.bills.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-hashtag text-muted"></i></span>
                            <input type="text" name="search_code" class="form-control border-start-0 ps-0" placeholder="Mã HĐ..." value="{{ request('search_code') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                            <input type="text" name="search_customer" class="form-control border-start-0 ps-0" placeholder="Tên hoặc SĐT khách..." value="{{ request('search_customer') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="payment_method_id" class="form-select">
                            <option value="">-- Cổng thanh toán --</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}" {{ request('payment_method_id') == $method->id ? 'selected' : '' }}>
                                    {{ $method->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-danger w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                        @if(request()->anyFilled(['search_code', 'search_customer', 'payment_method_id', 'date']))
                            <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary" title="Xóa bộ lọc"><i class="fa-solid fa-rotate-right"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Mã HĐ</th>
                        <th>Khách hàng</th>
                        <th>Người tạo (Staff)</th>
                        <th>Thanh toán qua</th>
                        <th>Tổng tiền</th>
                        <th>Thời gian giao dịch</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($bills as $item)
                        <tr>
                            <td class="fw-bold text-dark">#INV{{ sprintf('%05d', $item->id) }}</td>
                            <td class="text-start">
                                <div class="fw-bold text-primary">{{ $item->customer->name ?? 'Khách vãng lai' }}</div>
                                <small class="text-muted">{{ $item->customer->phone ?? '' }}</small>
                            </td>
                            <td>
                                @if($item->staff)
                                    <span class="badge bg-secondary"><i class="fa-solid fa-user-tie me-1"></i>{{ $item->staff->name }}</span>
                                @else
                                    <span class="badge bg-info text-dark"><i class="fa-solid fa-globe me-1"></i>Khách tự đặt Web/App</span>
                                @endif
                            </td>
                            <td><span class="badge bg-dark">{{ $item->paymentMethod->name ?? 'N/A' }}</span></td>
                            <td class="text-danger fw-bold fs-5">{{ number_format($item->total, 0, ',', '.') }} ₫</td>
                            <td>{{ $item->created_at->format('H:i | d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.bills.show', $item->id) }}" class="btn btn-sm btn-danger fw-bold" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye me-1"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt fa-3x mb-3 d-block text-light"></i>
                                Không tìm thấy hóa đơn nào phù hợp với bộ lọc!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $bills->links('pagination::bootstrap-5') }}
    </div>
@endsection