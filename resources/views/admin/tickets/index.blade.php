@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-ticket text-danger me-2"></i>Quản lý Vé đã xuất</h4>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.tickets.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-hashtag text-muted"></i></span>
                            <input type="text" name="search_code" class="form-control border-start-0 ps-0" placeholder="Mã Vé hoặc Mã HĐ..." value="{{ request('search_code') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                            <input type="text" name="search_customer" class="form-control border-start-0 ps-0" placeholder="Tên hoặc SĐT khách..." value="{{ request('search_customer') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}" title="Ngày chiếu">
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-danger w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                        @if(request()->anyFilled(['search_code', 'search_customer', 'status', 'date']))
                            <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary" title="Xóa bộ lọc"><i class="fa-solid fa-rotate-right"></i></a>
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
                        <th>Mã Vé</th>
                        <th>Khách hàng</th>
                        <th>Phim & Suất chiếu</th>
                        <th>Phòng & Ghế</th>
                        <th>Giá vé</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tickets as $item)
                        <tr>
                            <td class="fw-bold text-dark">#TK{{ sprintf('%05d', $item->id) }}</td>
                            <td>
                                <div class="fw-bold text-primary">{{ $item->bill->customer->name ?? 'Khách vãng lai' }}</div>
                                <small class="text-muted">Bill: #{{ $item->bill_id }}</small>
                            </td>
                            <td class="text-start">
                                <div class="fw-bold">{{ $item->showtime->movie->name ?? 'N/A' }}</div>
                                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($item->showtime->start_time)->format('H:i d/m/Y') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-dark">{{ $item->seat->room->room_name ?? 'N/A' }}</span>
                                <span class="badge bg-danger">Ghế: {{ $item->seat->seat_number ?? 'N/A' }}</span>
                            </td>
                            <td class="text-danger fw-bold">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                            <td>
                                @if($item->status == 1)
                                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Đã thanh toán</span>
                                @elseif($item->status == 0)
                                    <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i> Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">Không xác định</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.tickets.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Đổi trạng thái"><i class="fa-solid fa-pen-to-square"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-ticket fa-3x mb-3 d-block text-light"></i>
                                Không tìm thấy vé nào phù hợp với bộ lọc!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $tickets->links('pagination::bootstrap-5') }}
    </div>
@endsection