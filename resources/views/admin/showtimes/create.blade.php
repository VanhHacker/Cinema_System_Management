@extends('admin.layouts.app')

@section('content')
    <style>
        /* CSS cho Xem trước Bản đồ ghế */
        .screen-container { display: flex; justify-content: center; margin-bottom: 20px; }
        .screen-curve { width: 70%; max-width: 500px; height: 30px; background: linear-gradient(to bottom, #6c757d, #343a40); border-radius: 5px 5px 50% 50% / 5px 5px 100% 100%; box-shadow: 0 5px 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 12px; letter-spacing: 2px; }
        .seat-cell { width: 30px; height: 30px; border: 2px solid #ccc; border-radius: 5px 5px 8px 8px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold; color: #fff; margin: 0 2px; transition: 0.2s; }
        .seat-cell:hover { transform: scale(1.1); box-shadow: 0 0 5px rgba(0,0,0,0.3); }
        .normal { background: #6c757d; border-color: #495057; }
        .vip { background: #dc3545; border-color: #bd2130; }
        .sweetbox { background: #ffc107; border-color: #d39e00; color: #000; width: 65px; } 
        .maintenance { background: #343a40; border-color: #212529; color: #adb5bd; text-decoration: line-through; }
    </style>

    <h4 class="mb-4 text-dark"><i class="fa-solid fa-calendar-plus text-cgv me-2"></i>Tạo Suất chiếu mới</h4>

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <h6 class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i>Hệ thống từ chối xếp lịch:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.showtimes.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Chọn Phim <span class="text-danger">*</span></label>
                <select name="movie_id" id="movie_id" class="form-select" required>
                    <option value="">-- Chọn phim đang chiếu --</option>
                    @foreach($movies as $movie)
                        <option value="{{ $movie->id }}" data-duration="{{ $movie->duration }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                            {{ $movie->name }} ({{ $movie->duration }} phút)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Chọn Rạp <span class="text-danger">*</span></label>
                <select name="cinema_id" id="cinema_id" class="form-select" required>
                    <option value="">-- Chọn rạp chiếu --</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}" {{ old('cinema_id') == $cinema->id ? 'selected' : '' }}>
                            {{ $cinema->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Chọn Phòng <span class="text-danger">*</span></label>
                <select name="room_id" id="room_id" class="form-select" required disabled>
                    <option value="">-- Vui lòng chọn rạp trước --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-cinema="{{ $room->cinema_id }}" class="d-none">
                            {{ $room->room_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Thời gian bắt đầu <span class="text-danger">*</span></label>
                <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d\TH:i') }}" required>
                <small class="text-muted">Bắt buộc phải chọn lịch chiếu từ ngày mai trở đi.</small>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Thời gian kết thúc </label>
                <input type="datetime-local" name="end_time" id="end_time" class="form-control bg-light text-danger fw-bold" value="{{ old('end_time') }}" readonly tabindex="-1">
                <small class="text-muted fst-italic">Hệ thống tự tính: Thời lượng phim + 60 phút dọn dẹp.</small>
            </div>

            <div id="seatMapPreview" class="col-md-12 mt-3 mb-3 d-none">
                <label class="form-label fw-bold text-primary"><i class="fa-solid fa-map"></i> Xem trước Sơ đồ phòng chiếu</label>
                <div class="card border-0 shadow-sm border-primary" style="border-style: dashed !important; border-width: 2px !important;">
                    <div class="card-body bg-light rounded text-center overflow-auto" style="max-height: 400px;">
                        <div class="screen-container">
                            <div class="screen-curve">Màn hình chiếu</div>
                        </div>
                        <div id="previewMapContent"></div>
                        
                        <div class="d-flex justify-content-center gap-3 pt-3 mt-3 border-top border-secondary flex-wrap text-dark" style="font-size: 12px;">
                            <div class="d-flex align-items-center"><div class="seat-cell normal" style="width:20px;height:20px;margin-right:5px;"></div> Thường</div>
                            <div class="d-flex align-items-center"><div class="seat-cell vip" style="width:20px;height:20px;margin-right:5px;"></div> VIP</div>
                            <div class="d-flex align-items-center"><div class="seat-cell sweetbox" style="width:40px;height:20px;margin-right:5px;"></div> Đôi</div>
                            <div class="d-flex align-items-center"><div class="seat-cell maintenance" style="width:20px;height:20px;margin-right:5px;"></div> Hỏng</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu suất chiếu</button>
        <a href="{{ route('admin.showtimes.index') }}" class="btn btn-secondary px-4">Hủy bỏ</a>
    </form>

    <script>
        // Truyền dữ liệu toàn bộ các phòng kèm ghế vào JS
        const roomsData = @json($rooms);

        document.addEventListener('DOMContentLoaded', function () {
            
            const cinemaSelect = document.getElementById('cinema_id');
            const roomSelect = document.getElementById('room_id');
            const oldRoomId = "{{ old('room_id') }}"; 
            const allRoomOptions = Array.from(roomSelect.options).filter(opt => opt.value !== "");

            function filterRooms() {
                const selectedCinemaId = cinemaSelect.value;
                roomSelect.innerHTML = '<option value="">-- Chọn phòng chiếu --</option>';

                if (selectedCinemaId) {
                    roomSelect.disabled = false; 
                    allRoomOptions.forEach(option => {
                        if (option.getAttribute('data-cinema') === selectedCinemaId) {
                            if(option.value === oldRoomId) option.selected = true;
                            option.classList.remove('d-none');
                            roomSelect.appendChild(option.cloneNode(true));
                        }
                    });
                } else {
                    roomSelect.innerHTML = '<option value="">-- Vui lòng chọn rạp trước --</option>';
                    roomSelect.disabled = true; 
                }
                renderMapPreview(); // Cập nhật lại bản đồ mỗi khi danh sách phòng bị filter
            }

            cinemaSelect.addEventListener('change', filterRooms);
            if(cinemaSelect.value) filterRooms();


            // Tính năng render (vẽ) bản đồ ghế
            function renderMapPreview() {
                const roomId = roomSelect.value;
                const previewContainer = document.getElementById('seatMapPreview');
                const mapDiv = document.getElementById('previewMapContent');

                if (roomId) {
                    const room = roomsData.find(r => r.id == parseInt(roomId));
                    
                    if (room && room.seats && room.seats.length > 0) {
                        const rows = {};
                        room.seats.forEach(seat => {
                            const rowLetter = seat.seat_number.charAt(0);
                            if (!rows[rowLetter]) rows[rowLetter] = [];
                            rows[rowLetter].push(seat);
                        });

                        let html = '';
                        Object.keys(rows).sort().forEach(rowLetter => {
                            html += `<div class="d-flex justify-content-center align-items-center mb-2">
                                        <div class="text-dark fw-bold me-2" style="width: 20px;">${rowLetter}</div>`;
                            
                            rows[rowLetter].forEach(seat => {
                                let typeObj = seat.type_seat || seat.typeSeat;
                                let typeName = typeObj ? typeObj.name.toLowerCase() : '';
                                let seatClass = 'normal';
                                
                                if (seat.status == 0) seatClass = 'maintenance';
                                else if (typeName.includes('vip')) seatClass = 'vip';
                                else if (typeName.includes('sweetbox') || typeName.includes('đôi')) seatClass = 'sweetbox';
                                
                                html += `<div class="seat-cell ${seatClass}" style="cursor:default;" title="${seat.seat_number}">${seat.seat_number}</div>`;
                            });
                            
                            html += `<div class="text-dark fw-bold ms-2" style="width: 20px;">${rowLetter}</div></div>`;
                        });

                        mapDiv.innerHTML = html;
                        previewContainer.classList.remove('d-none');
                    } else {
                        mapDiv.innerHTML = '<p class="text-danger fw-bold mt-3"><i class="fa-solid fa-triangle-exclamation"></i> Phòng này chưa được cấu hình Sơ đồ ghế!</p>';
                        previewContainer.classList.remove('d-none');
                    }
                } else {
                    previewContainer.classList.add('d-none'); // Ẩn bản đồ nếu chưa chọn phòng
                }
            }

            // Lắng nghe sự kiện đổi phòng chiếu
            roomSelect.addEventListener('change', renderMapPreview);
            // Gọi chạy ngay nếu đã có phòng (trường hợp load lại lỗi validate)
            if(roomSelect.value) renderMapPreview();


            // TÍNH NĂNG: TỰ ĐỘNG TÍNH THỜI GIAN KẾT THÚC (+ 60 PHÚT)
            const movieSelect = document.getElementById('movie_id');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');

            function calculateEndTime() {
                const selectedOption = movieSelect.options[movieSelect.selectedIndex];
                const startTimeValue = startTimeInput.value;

                if (selectedOption && selectedOption.value !== "" && startTimeValue) {
                    const duration = parseInt(selectedOption.getAttribute('data-duration'), 10); 
                    if (!isNaN(duration)) {
                        const startDate = new Date(startTimeValue);
                        startDate.setMinutes(startDate.getMinutes() + duration + 60);

                        const year = startDate.getFullYear();
                        const month = String(startDate.getMonth() + 1).padStart(2, '0');
                        const day = String(startDate.getDate()).padStart(2, '0');
                        const hours = String(startDate.getHours()).padStart(2, '0');
                        const minutes = String(startDate.getMinutes()).padStart(2, '0');

                        endTimeInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
                    }
                } else {
                    endTimeInput.value = "";
                }
            }

            movieSelect.addEventListener('change', calculateEndTime);
            startTimeInput.addEventListener('change', calculateEndTime);
        });
    </script>
@endsection