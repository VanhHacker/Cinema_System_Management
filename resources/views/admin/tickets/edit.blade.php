@extends('admin.layouts.app')

@section('content')
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-pen-to-square text-cgv me-2"></i>Xử lý Vé #TK{{ sprintf('%05d', $ticket->id) }}</h4>

    <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Khách hàng</label>
                <input type="text" class="form-control bg-light" value="{{ $ticket->bill->customer->name ?? 'Khách vãng lai' }}" disabled>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Mã Hóa đơn</label>
                <input type="text" class="form-control bg-light" value="#{{ $ticket->bill_id }}" disabled>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Phim chiếu</label>
                <input type="text" class="form-control bg-light" value="{{ $ticket->showtime->movie->name ?? 'N/A' }}" disabled>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Giờ chiếu</label>
                <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::parse($ticket->showtime->start_time)->format('H:i | d/m/Y') }}" disabled>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Vị trí (Phòng & Ghế)</label>
                <input type="text" class="form-control bg-light" value="{{ $ticket->seat->room->room_name ?? 'N/A' }} - Ghế: {{ $ticket->seat->seat_number ?? 'N/A' }}" disabled>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Giá vé</label>
                <input type="text" class="form-control bg-light text-danger fw-bold" value="{{ number_format($ticket->price, 0, ',', '.') }} ₫" disabled>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold text-primary">Cập nhật Trạng thái vé <span class="text-danger">*</span></label>
                <select name="status" class="form-select border-primary" required>
                    <option value="booked" {{ $ticket->status == 'booked' ? 'selected' : '' }}>Đã đặt (Booked)</option>
                    <option value="completed" {{ $ticket->status == 'completed' ? 'selected' : '' }}>Đã sử dụng / Xem xong (Completed)</option>
                    <option value="cancelled" {{ $ticket->status == 'cancelled' ? 'selected' : '' }}>Khách hủy vé (Cancelled)</option>
                </select>
                <small class="text-muted italic">Việc hủy vé sẽ đánh dấu ghế này có thể được bán lại cho người khác.</small>
            </div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu trạng thái</button>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary px-4">Quay lại</a>
    </form>
@endsection
