@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-video text-danger me-2"></i>Danh sách Phim</h4>
        <a href="{{ route('admin.movies.create') }}" class="btn btn-danger fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Thêm Phim mới
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.movies.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search_name" class="form-control border-start-0 ps-0" placeholder="Nhập tên phim..." value="{{ request('search_name') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="category_id" class="form-select">
                            <option value="">-- Tất cả Thể loại --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang chiếu</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ngừng chiếu</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="release_date" class="form-control" value="{{ request('release_date') }}" title="Ngày khởi chiếu">
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-danger w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                        @if(request()->anyFilled(['search_name', 'category_id', 'status', 'release_date']))
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary" title="Xóa bộ lọc"><i class="fa-solid fa-rotate-right"></i></a>
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
                        <th width="80">Poster</th>
                        <th class="text-start">Tên Phim</th>
                        <th width="300">Thể loại</th>
                        <th>Thời lượng</th>
                        <th>Khởi chiếu</th>
                        <th>Trạng thái</th>
                        <th width="120">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($movies as $item)
                        <tr>
                            <td>
                                @php
                                    $posterUrl = 'https://www.cgv.vn/media/catalog/product/placeholder/default/cgv-default-poster.jpg';
                                    if (!empty($item->poster)) {
                                        $posterUrl = Str::startsWith($item->poster, ['http://', 'https://']) ? $item->poster : asset('storage/' . $item->poster);
                                    }
                                @endphp
                                <img src="{{ $posterUrl }}" alt="Poster" class="img-thumbnail" style="width: 55px; height: 75px; object-fit: cover;">
                            </td>
                            <td class="text-start fw-bold text-dark">{{ $item->name }}</td>
                            <td>
                                @forelse($item->categories as $cat)
                                    <span class="badge bg-info text-dark mb-1">{{ $cat->name }}</span>
                                @empty
                                    <span class="text-muted small">Chưa có</span>
                                @endforelse
                            </td>
                            <td>{{ $item->duration }} phút</td>
                            <td>{{ \Carbon\Carbon::parse($item->release_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->status == 1)
                                    <span class="badge bg-success">Đang chiếu</span>
                                @else
                                    <span class="badge bg-secondary">Ngừng chiếu</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.movies.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('admin.movies.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phim này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-film fa-3x mb-3 d-block text-light"></i>
                                Không tìm thấy phim nào phù hợp với bộ lọc!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $movies->links('pagination::bootstrap-5') }}
    </div>
@endsection