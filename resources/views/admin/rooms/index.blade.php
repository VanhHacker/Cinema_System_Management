@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-door-closed text-danger me-2"></i>Danh sách Phòng chiếu</h4>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-danger fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Thêm Phòng mới
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.rooms.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search_name" class="form-control border-start-0 ps-0" placeholder="Nhập tên phòng..." value="{{ request('search_name') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="cinema_id" class="form-select">
                            <option value="">-- Tất cả Rạp chiếu --</option>
                            @foreach($cinemas as $cinema)
                                <option value="{{ $cinema->id }}" {{ request('cinema_id') == $cinema->id ? 'selected' : '' }}>
                                    {{ $cinema->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Bảo trì / Đóng cửa</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-danger w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                        @if(request()->anyFilled(['search_name', 'cinema_id', 'status']))
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary" title="Xóa bộ lọc"><i class="fa-solid fa-rotate-right"></i></a>
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
                        <th width="60">ID</th>
                        <th class="text-start">Tên Phòng</th>
                        <th class="text-start">Thuộc Rạp</th>
                        <th>Số lượng ghế</th>
                        <th>Trạng thái</th>
                        <th width="140">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td class="text-muted">{{ $room->id }}</td>
                            <td class="text-start fw-bold text-dark">{{ $room->room_name }}</td>
                            <td class="text-start">
                                <i class="fa-solid fa-location-dot text-danger me-1"></i> 
                                <span class="fw-bold">{{ $room->cinema->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                {{ $room->seats_count ?? '0' }} ghế
                            </td>
                            <td>
                                @if($room->status == 1)
                                    <span class="badge bg-success">Đang hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Bảo trì</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-sm btn-outline-info" title="Sơ đồ ghế"><i class="fa-solid fa-layer-group"></i></a>
                                    
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-door-open fa-3x mb-3 d-block text-light"></i>
                                Không tìm thấy phòng chiếu nào phù hợp với bộ lọc!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $rooms->links('pagination::bootstrap-5') }}
    </div>
@endsection