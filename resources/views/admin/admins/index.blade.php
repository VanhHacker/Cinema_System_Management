@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-user-shield text-cgv me-2"></i>Danh sách Quản trị viên</h4>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Thêm Admin</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Username</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($admins as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td class="fw-bold">{{ $item->name }}</td>
                    <td><span class="badge bg-dark">{{ $item->user_name }}</span></td>
                    <td>{{ $item->email }}</td>
                    <td>
                        @if($item->status)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Đã khóa</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.admins.edit', $item->id) }}" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('admin.admins.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa quyền Quản trị viên này?');">
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
