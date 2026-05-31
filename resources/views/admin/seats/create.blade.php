@extends('admin.layouts.app')

@section('title', 'Thêm ghế')

@section('content')

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            
            {{-- HEADER --}}
            <div class="mb-4">
                <h4 class="fw-bold text-dark"><i class="fa-solid fa-chair text-cgv me-2"></i> Thêm ghế - Phòng: {{ $room->room_name }}</h4>
                <p class="text-muted">Tạo thêm ghế lẻ vào trong sơ đồ phòng chiếu</p>
            </div>

            {{-- ALERT --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3 shadow-sm">
                    <strong><i class="fa-solid fa-triangle-exclamation me-2"></i> Lỗi dữ liệu:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form action="{{ route('rooms.seats.store', $room->id) }}" method="POST">
                @csrf

                <div class="row">

                    {{-- SEAT NUMBER --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Số ghế <span class="text-danger">*</span></label>
                        <input type="text"
                               name="seat_number"
                               class="form-control"
                               placeholder="VD: A1, B2..."
                               value="{{ old('seat_number') }}"
                               required>
                    </div>

                    {{-- TYPE SEAT (Lấy tự động từ Database) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Loại ghế <span class="text-danger">*</span></label>
                        <select name="type_seat_id" class="form-select" required>
                            <option value="">-- Chọn loại ghế --</option>
                            @foreach($typeSeats as $type)
                                <option value="{{ $type->id }}" {{ old('type_seat_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ROW (Ký tự A, B, C...) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Hàng (Ký tự) <span class="text-danger">*</span></label>
                        <input type="text"
                               name="rows"
                               class="form-control text-uppercase"
                               maxlength="2"
                               placeholder="VD: A, B, C..."
                               value="{{ old('rows') }}"
                               required>
                    </div>

                    {{-- COLUMN (Số thứ tự) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Cột (Số thứ tự) <span class="text-danger">*</span></label>
                        <input type="number"
                               name="columns"
                               class="form-control"
                               min="1"
                               placeholder="VD: 1, 2, 3..."
                               value="{{ old('columns') }}"
                               required>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Bảo trì / Hỏng</option>
                        </select>
                    </div>

                </div>

                <hr>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('rooms.seats', $room->id) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu ghế
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection