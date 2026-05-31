@extends('admin.layouts.app')

@section('content')
<style>
    .screen-container {
        display: flex;
        justify-content: center;
        margin-bottom: 40px;
        margin-top: 10px;
    }
    .screen-curve {
        width: 80%;
        max-width: 700px;
        height: 45px;
        background: linear-gradient(to bottom, #6c757d, #343a40);
        border-radius: 5px 5px 50% 50% / 5px 5px 100% 100%;
        box-shadow: 0 10px 15px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 14px;
    }

    .seat-map-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow-x: auto;
        padding-bottom: 20px;
        user-select: none; /* Chống bôi đen text khi kéo chuột */
    }

    .seat-cell {
        width: 35px; height: 35px;
        border: 2px solid #ccc;
        border-radius: 5px 5px 10px 10px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: bold; color: #fff;
        transition: transform 0.1s, box-shadow 0.2s;
        margin: 0 2px;
    }
    
    .seat-cell:hover { transform: scale(1.1); box-shadow: 0 0 10px rgba(0,0,0,0.2); }
    
    .normal { background: #6c757d; border-color: #495057; }
    .vip { background: #dc3545; border-color: #bd2130; }
    .sweetbox { background: #ffc107; border-color: #d39e00; color: #000; width: 75px; } 
    .maintenance { background: #343a40; border-color: #212529; color: #adb5bd; text-decoration: line-through; }
    
    /* Hiệu ứng khi bấm chọn */
    .seat-cell.selected {
        border-color: #00ff00 !important;
        box-shadow: 0 0 15px #00ff00;
        transform: scale(1.1);
        color: #fff;
    }
    .sweetbox.selected { color: #000; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="m-0 text-dark"><i class="fa-solid fa-layer-group text-cgv me-2"></i>Quản lý Sơ đồ ghế: {{ $room->room_name }}</h4>
    <div>
        <button class="btn btn-outline-primary btn-sm me-2" onclick="selectAllSeats()">Chọn tất cả</button>
        <button class="btn btn-outline-secondary btn-sm me-2" onclick="deselectAll()">Bỏ chọn</button>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
    </div>
</div>

<div class="card border-0 shadow-sm position-relative mb-5 pb-5">
    <div class="card-body bg-light rounded p-5 text-center overflow-auto" style="min-height: 600px;">
        
        <div class="text-center text-muted mb-3"><small><i>Mẹo: Nhấn giữ chuột và kéo để chọn nhanh (hoặc bỏ chọn) nhiều ghế cùng lúc.</i></small></div>

        <div class="screen-container">
            <div class="screen-curve">Màn hình chiếu</div>
        </div>

        <div class="seat-map-container" id="seatContainer">
            @foreach($seatRows as $rowLetter => $rowSeats)
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <div class="text-dark fw-bold me-3" style="width: 25px;">{{ $rowLetter }}</div>
                    
                    @foreach($rowSeats as $seat)
                        @php
                            $typeName = isset($seat->typeSeat) ? mb_strtolower($seat->typeSeat->name, 'UTF-8') : '';
                            $seatClass = 'normal'; 

                            if ($seat->status == 0) {
                                $seatClass = 'maintenance'; 
                            } elseif (str_contains($typeName, 'vip')) {
                                $seatClass = 'vip';
                            } elseif (str_contains($typeName, 'sweetbox') || str_contains($typeName, 'đôi')) {
                                $seatClass = 'sweetbox';
                            }
                        @endphp

                        <div class="seat-cell {{ $seatClass }}"
                             data-id="{{ $seat->id }}"
                             onmousedown="startSelect(this)"
                             onmouseenter="dragSelect(this)">
                            {{ $seat->seat_number }}
                        </div>
                    @endforeach
                    
                    <div class="text-dark fw-bold ms-3" style="width: 25px;">{{ $rowLetter }}</div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center gap-3 gap-md-4 pt-4 border-top border-secondary flex-wrap text-dark mt-4">
            <div class="d-flex align-items-center"><div class="seat-cell normal" style="margin-right:8px;cursor:default;"></div> Ghế Thường</div>
            <div class="d-flex align-items-center"><div class="seat-cell vip" style="margin-right:8px;cursor:default;"></div> Ghế VIP</div>
            <div class="d-flex align-items-center"><div class="seat-cell sweetbox" style="margin-right:8px;cursor:default;"></div> Ghế Đôi (Sweetbox)</div>
            <div class="d-flex align-items-center"><div class="seat-cell maintenance" style="margin-right:8px;cursor:default;"></div> Bảo trì / Hỏng</div>
            <div class="d-flex align-items-center"><div class="seat-cell normal selected" style="margin-right:8px;cursor:default;"></div> Đang chọn</div>
        </div>

    </div>

    <div id="bulkActions" class="position-fixed bottom-0 start-50 translate-middle-x mb-4 d-none" style="z-index: 1050;">
        <div class="card shadow-lg border-danger" style="min-width: 500px; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
            <div class="card-body d-flex align-items-center justify-content-between py-2">
                <div class="me-3 text-dark fw-bold">
                    Đang chọn: <span id="selectedCount" class="text-danger fs-5">0</span> ghế
                </div>
                <form action="{{ route('admin.seats.bulkUpdate') }}" method="POST" class="d-flex gap-2 align-items-center m-0">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="seat_ids" id="seatIdsInput">

                    <select name="type_seat_id" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">-- Đổi loại ghế --</option>
                        @foreach($typeSeats as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="form-select form-select-sm" style="width: 150px;">
                        <option value="">-- Trạng thái --</option>
                        <option value="1">Bình thường</option>
                        <option value="0">Bảo trì/Hỏng</option>
                    </select>

                    <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedSeats = [];
    let isDragging = false;
    let dragMode = 'select'; // Cờ xác định là đang kéo để Chọn hay Bỏ chọn

    // Bắt sự kiện chuột toàn cục để biết khi nào đang kéo
    document.addEventListener('mousedown', () => { isDragging = true; });
    document.addEventListener('mouseup', () => { isDragging = false; });

    // Hàm gọi khi nhấn chuột XUỐNG vào 1 ghế
    function startSelect(el) {
        const id = el.getAttribute('data-id');
        
        // Nếu click vào ghế ĐÃ chọn -> Bật chế độ "Bỏ chọn" (Deselect)
        if (el.classList.contains('selected')) {
            dragMode = 'deselect';
            el.classList.remove('selected');
            selectedSeats = selectedSeats.filter(s => s !== id);
        } else {
            // Nếu click vào ghế CHƯA chọn -> Bật chế độ "Chọn" (Select)
            dragMode = 'select';
            el.classList.add('selected');
            if(!selectedSeats.includes(id)) selectedSeats.push(id);
        }
        updateUI();
    }

    // Hàm gọi khi RÊ CHUỘT ngang qua 1 ghế
    function dragSelect(el) {
        if (!isDragging) return; // Nếu không nhấn giữ chuột thì bỏ qua
        
        const id = el.getAttribute('data-id');
        
        if (dragMode === 'select' && !el.classList.contains('selected')) {
            el.classList.add('selected');
            if(!selectedSeats.includes(id)) selectedSeats.push(id);
        } else if (dragMode === 'deselect' && el.classList.contains('selected')) {
            el.classList.remove('selected');
            selectedSeats = selectedSeats.filter(s => s !== id);
        }
        updateUI();
    }

    function selectAllSeats() {
        const allSeats = document.querySelectorAll('.seat-cell');
        selectedSeats = [];
        allSeats.forEach(el => {
            el.classList.add('selected');
            selectedSeats.push(el.getAttribute('data-id'));
        });
        updateUI();
    }

    function deselectAll() {
        document.querySelectorAll('.seat-cell').forEach(el => el.classList.remove('selected'));
        selectedSeats = [];
        updateUI();
    }

    function updateUI() {
        const bulkBar = document.getElementById('bulkActions');
        const countLabel = document.getElementById('selectedCount');
        const inputIds = document.getElementById('seatIdsInput');

        if (selectedSeats.length > 0) {
            bulkBar.classList.remove('d-none');
            countLabel.innerText = selectedSeats.length;
            inputIds.value = selectedSeats.join(',');
        } else {
            bulkBar.classList.add('d-none');
        }
    }
</script>
@endsection