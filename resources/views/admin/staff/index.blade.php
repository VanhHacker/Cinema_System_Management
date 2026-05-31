@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-user-tie text-cgv me-2"></i>Quản lý Nhân viên</h4>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Thêm Nhân viên</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Username</th>
                <th>Email</th>
                <th>Rạp trực thuộc</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($staffs as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td class="fw-bold">{{ $item->name }}</td>
                    <td><span class="badge bg-secondary">{{ $item->user_name }}</span></td>
                    <td>{{ $item->email }}</td>
                    <td><i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $item->cinema->name }}</td>
                    <td>
                        {!! $item->status
                            ? '<span class="badge bg-success">Đang làm việc</span>'
                            : '<span class="badge bg-danger">Nghỉ việc</span>'
                        !!}
                    </td>
                    <td>
                        <a href="{{ route('admin.staff.edit', $item->id) }}" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('admin.staff.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa nhân viên này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
