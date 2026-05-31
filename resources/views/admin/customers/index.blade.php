@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-users text-cgv me-2"></i>Danh sách Khách hàng</h4>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary"><i class="fa-solid fa-user-plus me-1"></i> Thêm Khách hàng</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Username</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customers as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td class="fw-bold">{{ $item->name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->email }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $item->user_name }}</span></td>
                    <td>
                    <span class="badge {{ $item->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $item->status ? 'Kích hoạt' : 'Khóa' }}
                    </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.customers.edit', $item->id) }}" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-user-pen"></i></a>
                        <form action="{{ route('admin.customers.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa vĩnh viễn khách hàng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-user-minus"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
