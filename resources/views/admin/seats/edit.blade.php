@extends('admin.layouts.app')

@section('title', 'Sửa ghế')

@section('content')
    <div class="card-box">
        <h4 class="mb-4">✏️ Sửa ghế</h4>

        <form action="{{ route('admin.rooms.seats.update', [$room->id, $seat->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- ROOM (CHỈ HIỂN THỊ, KHÔNG CHO SỬA) --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phòng</label>
                    <input type="text" class="form-control" value="{{ $room->room_name }}" disabled>
                </div>

                {{-- SEAT NUMBER --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mã ghế</label>
                    <input
                            type="text"
                            name="seat_number"
                            value="{{ old('seat_number', $seat->seat_number) }}"
                            class="form-control"
                    >
                    @error('seat_number')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- ROW --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hàng</label>
                    <input
                            type="number"
                            name="row"
                            value="{{ old('row', $seat->row) }}"
                            class="form-control"
                    >
                    @error('row')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- COLUMN --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Cột</label>
                    <input
                            type="number"
                            name="column"
                            value="{{ old('column', $seat->column) }}"
                            class="form-control"
                    >
                    @error('column')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- TYPE --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Loại ghế</label>
                    <select name="type" class="form-control">
                        <option value="normal" {{ $seat->type == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="vip" {{ $seat->type == 'vip' ? 'selected' : '' }}>VIP</option>
                    </select>
                </div>

                {{-- STATUS --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $seat->is_active ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ !$seat->is_active ? 'selected' : '' }}>Tắt</option>
                    </select>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('admin.rooms.seats', $room->id) }}" class="btn btn-secondary">
                    ← Quay lại
                </a>
                <button class="btn btn-primary">
                    🔄 Cập nhật
                </button>
            </div>

        </form>
    </div>
@endsection
