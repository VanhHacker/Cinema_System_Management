@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-building text-danger me-2"></i>Danh sách Rạp chiếu</h4>
        <a href="{{ route('admin.cinemas.create') }}" class="btn btn-danger fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Thêm Rạp mới
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.cinemas.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Nhập tên rạp, địa chỉ hoặc thông tin liên hệ..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-danger w-100 fw-bold"><i class="fa-solid fa-magnifying-glass me-1"></i> Tìm kiếm</button>
                        
                        @if(request()->filled('search'))
                            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary" title="Xóa tìm kiếm"><i class="fa-solid fa-rotate-right"></i></a>
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
                        <th class="text-start">Tên Rạp</th>
                        <th class="text-start">Địa chỉ</th>
                        <th>Liên hệ</th>
                        <th width="120">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cinemas as $cinema)
                        <tr>
                            <td class="text-muted">{{ $cinema->id }}</td>
                            <td class="text-start fw-bold text-dark">{{ $cinema->name }}</td>
                            <td class="text-start">{{ $cinema->address }}</td>
                            
                            <td>{{ $cinema->contact_info }}</td> 
                            
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('admin.cinemas.destroy', $cinema->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa rạp chiếu này? Hệ thống có thể xóa các phòng chiếu liên quan!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-building fa-3x mb-3 d-block text-light"></i>
                                Không tìm thấy rạp chiếu nào phù hợp!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $cinemas->links('pagination::bootstrap-5') }}
    </div>
@endsection