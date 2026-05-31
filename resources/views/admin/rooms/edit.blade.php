@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4 text-dark"><i class="fa-solid fa-pen-to-square text-danger me-2"></i>Cập nhật Phòng chiếu: {{ $room->room_name }}</h4>

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" id="roomForm">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="seat_map_data" id="seat_map_data">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tên phòng <span class="text-danger">*</span></label>
                        <input type="text" name="room_name" class="form-control" value="{{ old('room_name', $room->room_name) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Thuộc rạp chiếu <span class="text-danger">*</span></label>
                        <select name="cinema_id" class="form-select" required>
                            @foreach($cinemas as $cinema)
                                <option value="{{ $cinema->id }}" {{ old('cinema_id', $room->cinema_id) == $cinema->id ? 'selected' : '' }}>
                                    {{ $cinema->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Số ghế tối đa / Hàng <span class="text-danger">*</span></label>
                        <input type="number" id="max_columns_input" name="max_seats_per_row" class="form-control" value="{{ old('max_seats_per_row', $room->max_columns ?? $room->max_seats_per_row) }}" required>
                        <small class="text-muted">Cột ngang của sơ đồ</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', $room->status) == 1 ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="0" {{ old('status', $room->status) == 0 ? 'selected' : '' }}>Tạm ngưng</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        @php
            $existingQuotas = [];
            if($room->seats) {
                foreach($room->seats as $seat) {
                    if ($seat->typeSeat) {
                        $tId = $seat->typeSeat->id;
                        if(!isset($existingQuotas[$tId])) {
                            $existingQuotas[$tId] = [
                                'id' => $tId,
                                'count' => 0
                            ];
                        }
                        $existingQuotas[$tId]['count']++;
                    }
                }
            }
        @endphp

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-danger mb-4"><i class="fa-solid fa-couch me-2"></i>Thiết lập Chỉ tiêu (Quota) *</h6>
                
                <div id="quota-rows-container">
                    @if(count($existingQuotas) > 0)
                        @foreach($existingQuotas as $index => $data)
                            <div class="row mb-3 quota-row align-items-center">
                                <div class="col-md-5">
                                    <select name="type_seats[]" class="form-select select-type-seat" required>
                                        <option value="">-- Chọn loại ghế --</option>
                                        @foreach($typeSeats as $type)
                                            <option value="{{ $type->id }}" data-name="{{ $type->name }}" {{ $type->id == $data['id'] ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" name="quantities[]" class="form-control input-quantity" value="{{ $data['count'] }}" required min="1">
                                </div>
                                <div class="col-md-2">
                                    @if($loop->first)
                                        <button type="button" class="btn btn-success w-100 btn-add-row fw-bold"><i class="fa-solid fa-plus me-1"></i> Thêm</button>
                                    @else
                                        <button type="button" class="btn btn-danger w-100 btn-remove-row fw-bold"><i class="fa-solid fa-trash me-1"></i> Xóa</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
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
                    @endif
                </div>

                <div class="mt-4 text-end border-top pt-3">
                    <button type="button" class="btn btn-warning fw-bold text-dark" id="btnGenerateMap">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Áp dụng Quota & Mở Bản đồ Sửa Sơ đồ
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

                <p class="text-center text-muted fst-italic mb-4">Mẹo: Nhấn giữ chuột và kéo để vẽ nhanh. Sơ đồ dưới đây đã tải sẵn các ghế cũ của phòng.</p>

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
            <button type="submit" class="btn btn-danger px-4 fw-bold"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thay Đổi Của Phòng</button>
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
        const existingSeatsDB = @json($room->seats); 

        function getRowIndex(rowStr) {
            let idx = 0;
            for (let i = 0; i < rowStr.length; i++) {
                idx = idx * 26 + (rowStr.charCodeAt(i) - 64);
            }
            return idx - 1;
        }

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
                        cell.innerText = '';
                        cell.dataset.seatNum = '';
                    }
                });
            });
        }

        btnGenerateMap.addEventListener('click', function() {
            const maxCols = parseInt(maxColumnsInput.value);
            if (!maxCols || maxCols < 1) return alert('Vui lòng nhập Số ghế tối đa / Hàng!');

            // 👉 SỬA LỖI TRỐNG SƠ ĐỒ LẦN 2: Lấy dữ liệu ghế từ chính lưới hiện tại thay vì từ DB (nếu đã vẽ)
            let sourceSeatsByRow = {};
            if (mapGrid.innerHTML.trim() === '') {
                // Lần đầu mở: Lấy dữ liệu DB
                if (existingSeatsDB && existingSeatsDB.length > 0) {
                    let tempDB = [...existingSeatsDB].sort((a, b) => a.columns - b.columns);
                    tempDB.forEach(s => {
                        if (!sourceSeatsByRow[s.rows]) sourceSeatsByRow[s.rows] = [];
                        sourceSeatsByRow[s.rows].push(s.type_seat_id);
                    });
                }
            } else {
                // Các lần cập nhật sau: "Chụp ảnh" lại sơ đồ đang vẽ trên trình duyệt
                document.querySelectorAll('.seat-cell.placed').forEach(cell => {
                    const r = cell.dataset.row;
                    const tId = cell.dataset.typeId;
                    if (!sourceSeatsByRow[r]) sourceSeatsByRow[r] = [];
                    sourceSeatsByRow[r].push(tId);
                });
            }

            // 👉 SỬA LỖI DUPLICATE: Xóa sạch các nút loại ghế của lần trước, chỉ giữ lại Cục tẩy
            document.querySelectorAll('.tool-btn').forEach(btn => {
                if (btn.dataset.tool !== 'eraser') {
                    btn.remove();
                }
            });
            // Reset công cụ hiện tại về Cục Tẩy
            currentTool = 'eraser';
            const eraserBtn = document.querySelector('[data-tool="eraser"]');
            if (eraserBtn) {
                document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('active', 'border-dark'));
                eraserBtn.classList.add('active', 'border-dark');
            }

            const selects = document.querySelectorAll('.select-type-seat');
            const quantities = document.querySelectorAll('.input-quantity');
            
            quotasData = {};
            let totalSeats = 0;
            let colorIndex = 0;

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

            let numRows = Math.ceil(totalSeats / maxCols) + 3;
            let maxDbRow = 0;
            Object.keys(sourceSeatsByRow).forEach(rowChar => {
                let rIdx = getRowIndex(rowChar);
                if(rIdx > maxDbRow) maxDbRow = rIdx;
            });
            if (maxDbRow >= numRows) {
                numRows = maxDbRow + 2; 
            }

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
                    
                    const typeList = sourceSeatsByRow[rowChar];
                    let savedTypeId = null;
                    
                    if (typeList && typeList.length > 0) {
                        savedTypeId = typeList.shift(); 
                    }

                    if (savedTypeId && quotasData[savedTypeId] && quotasData[savedTypeId].placed < quotasData[savedTypeId].limit) {
                        const quota = quotasData[savedTypeId];
                        seatDiv.dataset.typeId = quota.id;
                        seatDiv.classList.add('placed');
                        
                        if(quota.name.toLowerCase().includes('sweetbox')) {
                            seatDiv.classList.add('sweetbox-seat'); 
                            seatDiv.style.backgroundColor = '#ffc107'; 
                            seatDiv.style.border = '2px solid #d39e00'; 
                            seatDiv.style.color = '#212529'; 
                        } else {
                            seatDiv.style.backgroundColor = quota.color;
                            seatDiv.style.color = 'white';
                            seatDiv.style.border = '2px solid ' + quota.color;
                        }
                        quota.placed++;
                    }

                    rowDiv.appendChild(seatDiv);
                }

                rowDiv.innerHTML += `<div class="fw-bold ms-2 text-dark" style="width: 20px; font-size: 14px;">${rowChar}</div>`;
                mapGrid.appendChild(rowDiv);
            }

            updateSeatNumbers();

            Object.values(quotasData).forEach(q => {
                const badge = document.getElementById(`badge_${q.id}`);
                if (badge) badge.innerText = `${q.placed}/${q.limit}`;
            });

            interactiveMapSection.classList.remove('d-none');
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
            
            updateSeatNumbers();
        }

        mapGrid.addEventListener('mousedown', (e) => { isDrawing = true; paintSeat(e.target); });
        mapGrid.addEventListener('mouseover', (e) => { if (isDrawing) paintSeat(e.target); });
        window.addEventListener('mouseup', () => { isDrawing = false; });

        form.addEventListener('submit', function(e) {
            const placedSeats = document.querySelectorAll('.seat-cell.placed');
            
            if (!interactiveMapSection.classList.contains('d-none')) {
                if (placedSeats.length === 0) {
                    e.preventDefault();
                    alert('Bạn đang sửa sơ đồ, vui lòng vẽ ít nhất 1 ghế hoặc tải lại trang nếu không muốn sửa!');
                    return;
                }

                let isQuotaMet = true;
                let missingQuotaMsg = "Hệ thống từ chối lưu! Bạn chưa vẽ đủ số lượng ghế đã cấu hình:\n\n";

                for (const key in quotasData) {
                    if (quotasData[key].placed < quotasData[key].limit) {
                        isQuotaMet = false;
                        missingQuotaMsg += `- ${quotasData[key].name}: Mới vẽ ${quotasData[key].placed} / ${quotasData[key].limit} ghế.\n`;
                    }
                }

                if (!isQuotaMet) {
                    e.preventDefault();
                    alert(missingQuotaMsg + "\nVui lòng dùng cọ vẽ nốt số ghế còn thiếu trên bản đồ!");
                    return;
                }

                let mapData = [];
                placedSeats.forEach(cell => {
                    mapData.push({
                        seat_number: cell.dataset.seatNum, 
                        grid_col: cell.dataset.col,
                        rows: cell.dataset.row,
                        type_seat_id: cell.dataset.typeId
                    });
                });
                seatMapDataInput.value = JSON.stringify(mapData);
            }
        });

        // Tự động generate biểu đồ lần đầu khi vào trang
        if (existingSeatsDB && existingSeatsDB.length > 0) {
            setTimeout(() => {
                btnGenerateMap.click();
            }, 100);
        }
    });
</script>
@endsection