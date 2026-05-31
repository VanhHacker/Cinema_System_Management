@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-plus text-danger me-2"></i>Thêm Phòng chiếu mới</h4>

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rooms.store') }}" method="POST" id="roomForm">
        @csrf
        <input type="hidden" name="seat_map_data" id="seat_map_data">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tên phòng <span class="text-danger">*</span></label>
                        <input type="text" name="room_name" class="form-control" placeholder="VD: Cinema 01" value="{{ old('room_name') }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Thuộc rạp chiếu <span class="text-danger">*</span></label>
                        <select name="cinema_id" class="form-select" required>
                            <option value="">-- Chọn rạp chiếu --</option>
                            @foreach($cinemas as $cinema)
                                <option value="{{ $cinema->id }}" {{ old('cinema_id') == $cinema->id ? 'selected' : '' }}>
                                    {{ $cinema->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Số ghế tối đa / Hàng <span class="text-danger">*</span></label>
                        <input type="number" id="max_columns_input" name="max_columns" class="form-control" value="{{ old('max_columns', 12) }}" required>
                        <small class="text-muted">Cột ngang của sơ đồ</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="1">Đang hoạt động</option>
                            <option value="0">Tạm ngưng</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-danger mb-4"><i class="fa-solid fa-couch me-2"></i>Thiết lập Chỉ tiêu (Quota) *</h6>
                
                <div id="quota-rows-container">
                    <div class="row mb-3 quota-row align-items-center">
                        <div class="col-md-5">
                            <select name="type_seats[]" class="form-select select-type-seat" required>
                                <option value="">-- Chọn loại ghế --</option>
                                @foreach($typeSeats as $type)
                                    <option value="{{ $type->id }}" data-name="{{ $type->name }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="number" name="quantities[]" class="form-control input-quantity" placeholder="Nhập số lượng (VD: 50)" required min="1">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success w-100 btn-add-row fw-bold"><i class="fa-solid fa-plus me-1"></i> Thêm</button>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end border-top pt-3">
                    <button type="button" class="btn btn-warning fw-bold text-dark" id="btnGenerateMap">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Áp dụng & Mở Bản đồ Tương tác
                    </button>
                </div>
            </div>
        </div>

        <div id="interactiveMapSection" class="card border-0 shadow-sm mb-4 d-none">
            <div class="card-body p-4">
                <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-map me-2"></i>Bản đồ tương tác (Kéo thả để vẽ ghế)</h5>
                
                <div class="d-flex flex-wrap align-items-center gap-2 p-3 border rounded mb-4 bg-light" id="mapToolbar">
                    <span class="fw-bold me-2">Công cụ:</span>
                    <button type="button" class="btn bg-white border-dark text-dark fw-bold tool-btn active" data-tool="eraser">
                        <i class="fa-solid fa-eraser me-1"></i> Cục Tẩy (Xóa)
                    </button>
                </div>

                <p class="text-center text-muted fst-italic mb-4">Mẹo: Nhấn giữ chuột và kéo để vẽ nhanh nhiều ghế. Dùng Cục tẩy để xóa khoảng trống.</p>

                <div class="text-center mb-5">
                    <div class="d-inline-block text-white fw-bold shadow-sm" style="width: 80%; max-width: 600px; padding: 15px; border-radius: 50px; background-color: #6c757d; letter-spacing: 5px; font-size: 1.1rem;">
                        MÀN HÌNH CHIẾU
                    </div>
                </div>

                <div class="d-flex justify-content-center overflow-auto pb-4">
                    <div id="mapGrid" style="user-select: none;"></div>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <button type="submit" class="btn btn-danger px-4 fw-bold"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu Phòng & Sinh Sơ đồ</button>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary px-4">Hủy bỏ</a>
        </div>
    </form>
</div>

<style>
    .seat-cell {
        width: 38px; height: 38px;
        border: 2px solid #ced4da;
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: bold; color: #adb5bd;
        cursor: pointer; background-color: #f8f9fa;
        transition: all 0.2s ease-in-out; 
    }
    
    .seat-cell:hover { border-color: #6c757d; color: #6c757d; }
    .seat-cell.placed { color: #fff; border-color: transparent; }
    
    .seat-cell.sweetbox-seat {
        width: 82px !important; 
        border-radius: 10px;
    }

    .tool-btn { border-width: 2px; transition: 0.2s; }
    .tool-btn.active { transform: scale(1.05); box-shadow: 0 4px 10px rgba(0,0,0,0.15); border-color: #343a40 !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('quota-rows-container');
        const maxTypeSeats = {{ count($typeSeats) }}; 

        // --- 1. CHỐNG TRÙNG LẶP ---
        function updateDropdownOptions() {
            const allSelects = container.querySelectorAll('select[name="type_seats[]"]');
            const selectedValues = [];
            allSelects.forEach(select => { if (select.value !== "") selectedValues.push(select.value); });
            allSelects.forEach(select => {
                const options = select.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === "") return;
                    if (selectedValues.includes(option.value) && option.value !== select.value) option.style.display = 'none';
                    else option.style.display = ''; 
                });
            });
        }
        updateDropdownOptions(); 
        container.addEventListener('change', (e) => { if (e.target.tagName === 'SELECT') updateDropdownOptions(); });
        container.addEventListener('click', function(e) {
            if (e.target.closest('.btn-add-row')) {
                const currentRows = container.querySelectorAll('.quota-row').length;
                if (currentRows >= maxTypeSeats) return alert('Bạn đã thêm tối đa tất cả các loại ghế hiện có!');
                const newRow = container.querySelector('.quota-row').cloneNode(true);
                newRow.querySelector('select').value = ''; newRow.querySelector('input').value = '';
                newRow.querySelector('.col-md-2').innerHTML = '<button type="button" class="btn btn-danger w-100 btn-remove-row fw-bold"><i class="fa-solid fa-trash me-1"></i> Xóa</button>';
                container.appendChild(newRow); updateDropdownOptions(); 
            }
            if (e.target.closest('.btn-remove-row')) {
                e.target.closest('.quota-row').remove(); updateDropdownOptions(); 
            }
        });

        // --- 2. BẢN ĐỒ TƯƠNG TÁC ---
        const btnGenerateMap = document.getElementById('btnGenerateMap');
        const interactiveMapSection = document.getElementById('interactiveMapSection');
        const mapToolbar = document.getElementById('mapToolbar');
        const mapGrid = document.getElementById('mapGrid');
        const maxColumnsInput = document.getElementById('max_columns_input');
        const seatMapDataInput = document.getElementById('seat_map_data');
        const form = document.getElementById('roomForm');

        let currentTool = 'eraser';
        let quotasData = {}; 
        let isDrawing = false;

        const colors = ['#007bff', '#dc3545', '#ffc107', '#28a745', '#fd7e14', '#6f42c1'];

        function getRowName(index) {
            let letters = '';
            while (index >= 0) { letters = String.fromCharCode((index % 26) + 65) + letters; index = Math.floor(index / 26) - 1; }
            return letters;
        }

        
        function updateSeatNumbers() {
            const rows = document.querySelectorAll('#mapGrid > .d-flex');
            rows.forEach(rowDiv => {
                let seatIndex = 1;
                const cells = rowDiv.querySelectorAll('.seat-cell');
                cells.forEach(cell => {
                    if (cell.classList.contains('placed')) {
                        const rowChar = cell.dataset.row;
                        const seqNum = rowChar + seatIndex;
                        cell.innerText = seqNum;
                        cell.dataset.seatNum = seqNum;
                        seatIndex++;
                    } else {
                        // Trống thì xóa số để không lộn xộn
                        const gridPos = cell.dataset.row + cell.dataset.col;
                        cell.innerText = gridPos;
                        cell.dataset.seatNum = gridPos;
                    }
                });
            });
        }

        btnGenerateMap.addEventListener('click', function() {
            const maxCols = parseInt(maxColumnsInput.value);
            if (!maxCols || maxCols < 1) return alert('Vui lòng nhập Số ghế tối đa / Hàng!');

            const selects = document.querySelectorAll('.select-type-seat');
            const quantities = document.querySelectorAll('.input-quantity');
            
            quotasData = {};
            let totalSeats = 0;
            let colorIndex = 0;

            Array.from(mapToolbar.children).forEach(child => {
                if(!child.classList.contains('fw-bold') && !child.dataset.tool) child.remove();
            });

            selects.forEach((select, idx) => {
                const typeId = select.value;
                const typeName = select.options[select.selectedIndex]?.dataset.name;
                const limit = parseInt(quantities[idx].value);

                if (typeId && limit > 0) {
                    totalSeats += limit;
                    let color = colors[colorIndex % colors.length];
                    
                    if(typeName.toLowerCase().includes('thường')) color = '#6c757d'; 
                    if(typeName.toLowerCase().includes('vip')) color = '#dc3545'; 
                    if(typeName.toLowerCase().includes('sweetbox')) color = '#ffc107'; 

                    quotasData[typeId] = { id: typeId, name: typeName, limit: limit, placed: 0, color: color };
                    
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    const textClass = color === '#ffc107' ? 'text-dark' : 'text-white';
                    btn.className = `btn ${textClass} fw-bold tool-btn`;
                    btn.style.backgroundColor = color;
                    btn.dataset.tool = typeId;
                    btn.innerHTML = `${typeName} <span class="badge bg-white text-dark ms-1 rounded-pill shadow-sm" id="badge_${typeId}">0/${limit}</span>`;
                    mapToolbar.appendChild(btn);
                    colorIndex++;
                }
            });

            if (totalSeats === 0) return alert('Vui lòng chọn ít nhất 1 loại ghế!');

            const numRows = Math.ceil(totalSeats / maxCols) + 3;

            mapGrid.innerHTML = '';
            for (let r = 0; r < numRows; r++) {
                const rowChar = getRowName(r);
                const rowDiv = document.createElement('div');
                rowDiv.className = 'd-flex align-items-center justify-content-center mb-2 gap-2';

                rowDiv.innerHTML += `<div class="fw-bold me-2 text-dark" style="width: 20px; font-size: 14px;">${rowChar}</div>`;

                for (let c = 1; c <= maxCols; c++) {
                    const seatDiv = document.createElement('div');
                    seatDiv.className = 'seat-cell';
                    seatDiv.dataset.row = rowChar;
                    seatDiv.dataset.col = c; 
                    seatDiv.dataset.seatNum = rowChar + c;
                    seatDiv.innerText = rowChar + c;
                    rowDiv.appendChild(seatDiv);
                }

                rowDiv.innerHTML += `<div class="fw-bold ms-2 text-dark" style="width: 20px; font-size: 14px;">${rowChar}</div>`;
                mapGrid.appendChild(rowDiv);
            }

            // Đánh số lại toàn bộ ghế sau khi tạo lưới
            updateSeatNumbers();

            interactiveMapSection.classList.remove('d-none');
            interactiveMapSection.scrollIntoView({ behavior: 'smooth' });
        });

        mapToolbar.addEventListener('click', function(e) {
            const btn = e.target.closest('.tool-btn');
            if (btn) {
                document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('active', 'border-dark'));
                btn.classList.add('active', 'border-dark');
                currentTool = btn.dataset.tool;
            }
        });

        function paintSeat(cell) {
            if (!cell.classList.contains('seat-cell')) return;

            const previousType = cell.dataset.typeId;

            if (currentTool === 'eraser') {
                if (previousType) {
                    quotasData[previousType].placed--;
                    document.getElementById(`badge_${previousType}`).innerText = `${quotasData[previousType].placed}/${quotasData[previousType].limit}`;
                }
                cell.className = 'seat-cell'; 
                cell.style.backgroundColor = '';
                cell.style.color = '';
                cell.style.border = '';
                delete cell.dataset.typeId;
                
                // Đánh số lại ngay khi xóa ghế
                updateSeatNumbers();
                return;
            }

            const quota = quotasData[currentTool];
            
            if (previousType === currentTool) return;

            if (quota.placed >= quota.limit) {
                if (!isDrawing) alert(`Đã vẽ đủ số lượng ghế ${quota.name} (${quota.limit})!`);
                return;
            }

            if (previousType) {
                quotasData[previousType].placed--;
                document.getElementById(`badge_${previousType}`).innerText = `${quotasData[previousType].placed}/${quotasData[previousType].limit}`;
            }

            cell.className = 'seat-cell placed'; 
            cell.dataset.typeId = quota.id;

            if(quota.name.toLowerCase().includes('sweetbox')) {
                cell.classList.add('sweetbox-seat'); 
                cell.style.backgroundColor = '#ffc107'; 
                cell.style.border = '2px solid #d39e00'; 
                cell.style.color = '#212529'; 
            } else {
                cell.style.backgroundColor = quota.color;
                cell.style.color = 'white';
                cell.style.border = '2px solid ' + quota.color;
            }

            quota.placed++;
            document.getElementById(`badge_${quota.id}`).innerText = `${quota.placed}/${quota.limit}`;
            
            // Đánh số lại ngay khi thêm ghế mới
            updateSeatNumbers();
        }

        mapGrid.addEventListener('mousedown', (e) => { isDrawing = true; paintSeat(e.target); });
        mapGrid.addEventListener('mouseover', (e) => { if (isDrawing) paintSeat(e.target); });
        window.addEventListener('mouseup', () => { isDrawing = false; });

        // --- 3. GÓI TỌA ĐỘ VÀ KIỂM TRA QUOTA NGẶT NGHÈO TRƯỚC KHI SUBMIT ---
        form.addEventListener('submit', function(e) {
            const placedSeats = document.querySelectorAll('.seat-cell.placed');
            
            // 1. Kiểm tra xem bản đồ đã mở và có được vẽ chưa
            if (!interactiveMapSection.classList.contains('d-none')) {
                if (placedSeats.length === 0) {
                    e.preventDefault();
                    alert('Vui lòng vẽ sơ đồ ghế trên bản đồ trước khi lưu!');
                    return;
                }

                // 2. KIỂM TRA NGẶT NGHÈO: Đã vẽ đủ số lượng ghế theo Quota chưa?
                let isQuotaMet = true;
                let missingQuotaMsg = "Hệ thống từ chối lưu! Bạn chưa vẽ đủ số lượng ghế đã cấu hình:\n\n";

                for (const key in quotasData) {
                    if (quotasData[key].placed < quotasData[key].limit) {
                        isQuotaMet = false;
                        missingQuotaMsg += `- ${quotasData[key].name}: Mới vẽ ${quotasData[key].placed} / ${quotasData[key].limit} ghế.\n`;
                    }
                }

                // Nếu chưa vẽ đủ -> Chặn lưu và hiện thông báo lỗi chi tiết
                if (!isQuotaMet) {
                    e.preventDefault();
                    alert(missingQuotaMsg + "\nVui lòng dùng cọ vẽ nốt số ghế còn thiếu trên bản đồ!");
                    return;
                }

                // 3. Nếu mọi thứ OK -> Tiến hành gói tọa độ
                let mapData = [];
                placedSeats.forEach(cell => {
                    mapData.push({
                        seat_number: cell.dataset.seatNum, // Tên ghế tịnh tiến
                        grid_col: cell.dataset.col,        // Tọa độ vật lý
                        rows: cell.dataset.row,
                        type_seat_id: cell.dataset.typeId
                    });
                });

                seatMapDataInput.value = JSON.stringify(mapData);
            }
        });
    });
</script>
@endsection