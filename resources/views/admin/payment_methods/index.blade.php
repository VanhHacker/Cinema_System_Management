@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0 text-dark"><i class="fa-solid fa-credit-card text-cgv me-2"></i>Phương thức Thanh toán</h4>
        <a href="{{ route('admin.payment_methods.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Thêm mới</a>
    </div>

    <table class="table table-hover table-bordered align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Tên phương thức</th>
            <th>Mô tả</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        @foreach($paymentMethods as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td class="fw-bold">{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>
                    @if($item->status)
                        <span class="badge bg-success">Hoạt động</span>
                    @else
                        <span class="badge bg-danger">Khóa</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.payment_methods.edit', $item->id) }}" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('admin.payment_methods.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
