@extends('admin.layouts.app')

@section('title', 'Seat Detail')

@section('page-title', '👁️ Chi tiết ghế')

@section('content')

    <div class="card-box">

        <div class="row">

            <div class="col-md-6 mb-4">
                <label class="text-muted">Phòng</label>
                <div class="fw-bold fs-5">
                    {{ $seat->room->room_name ?? '' }}
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <label class="text-muted">Mã ghế</label>
                <div class="fw-bold fs-5">
                    {{ $seat->seat_number }}
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <label class="text-muted">Hàng</label>
                <div class="fw-bold">{{ $seat->row }}</div>
            </div>

            <div class="col-md-6 mb-4">
                <label class="text-muted">Cột</label>
                <div class="fw-bold">{{ $seat->column }}</div>
            </div>

            <div class="col-md-6 mb-4">
                <label class="text-muted">Loại ghế</label>
                <div>
                    @if($seat->type == 'vip')
                        <span class="badge bg-warning text-dark">VIP</span>
                    @else
                        <span class="badge bg-secondary">Normal</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <label class="text-muted">Trạng thái</label>
                <div>
                    @if($seat->is_active)
                        <span class="badge badge-active">Hoạt động</span>
                    @else
                        <span class="badge badge-inactive">Đã tắt</span>
                    @endif
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('seats.edit', $seat->id) }}"
               class="btn btn-primary px-4">
                ✏️ Sửa
            </a>

            <a href="{{ route('seats.index') }}"
               class="btn btn-light border px-4">
                ← Quay lại
            </a>
        </div>

    </div>

@endsection
