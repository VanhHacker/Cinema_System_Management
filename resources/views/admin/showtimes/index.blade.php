@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-calendar-days text-danger me-2"></i>Quản lý Lịch chiếu</h4>
        <a href="{{ route('admin.showtimes.create') }}" class="btn btn-danger"><i class="fa-solid fa-plus me-1"></i> Thêm Suất chiếu</a>
    </div>

    <!-- THANH TÌM KIẾM VÀ LỌC -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.showtimes.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <!-- Tìm theo tên phim -->
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search_movie" class="form-control border-start-0 ps-0" placeholder="Tìm tên phim (VD: Mario...)" value="{{ request('search_movie') }}">
                        </div>
                    </div>

                    <!-- Lọc theo Rạp -->
                    <div class="col-md-3">
                        <select name="cinema_id" class="form-select">
                            <option value="">-- Tất cả cụm rạp --</option>
                            @foreach($cinemas as $cinema)
                                <option value="{{ $cinema->id }}" {{ request('cinema_id') == $cinema->id ? 'selected' : '' }}>
                                    {{ $cinema->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lọc theo Ngày -->
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>

                    <!-- Nút Hành động -->
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-danger w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                        @if(request()->anyFilled(['search_movie', 'cinema_id', 'date']))
                            <a href="{{ route('admin.showtimes.index') }}" class="btn btn-secondary" title="Xóa bộ lọc"><i class="fa-solid fa-rotate-right"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BẢNG DANH SÁCH LỊCH CHIẾU -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">ID</th>
                        <th>Phim</th>
                        <th>Rạp & Phòng</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian kết thúc</th>
                        <th class="text-center" style="width: 120px;">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($showtimes as $item)
                        <tr>
                            <td class="text-center text-muted">{{ $item->id }}</td>
                            <td class="fw-bold text-primary">{{ $item->movie->name }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->room->cinema->name }}</div>
                                <small class="text-muted"><i class="fa-solid fa-door-open me-1"></i>{{ $item->room->room_name }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}</span>
                                <span class="ms-1 text-muted">{{ \Carbon\Carbon::parse($item->start_time)->format('d/m/Y') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</span>
                                <span class="ms-1 text-muted">{{ \Carbon\Carbon::parse($item->end_time)->format('d/m/Y') }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.showtimes.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa lịch chiếu"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.showtimes.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa suất chiếu này sẽ ảnh hưởng đến các vé đã đặt. Bạn chắc chắn chứ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa lịch chiếu"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>
                                Không tìm thấy suất chiếu nào phù hợp với bộ lọc!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PHÂN TRANG -->
    <div class="d-flex justify-content-end mt-4">
        {{ $showtimes->links('pagination::bootstrap-5') }}
    </div>
@endsection