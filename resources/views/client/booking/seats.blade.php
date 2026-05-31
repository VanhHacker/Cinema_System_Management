<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn ghế | {{ $showtime->movie->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; padding-bottom: 100px; }
        .screen-curve { width: 80%; height: 50px; background: linear-gradient(to bottom, #555, #111); border-radius: 50% / 100% 100% 0 0; box-shadow: 0 10px 20px rgba(255,255,255,0.1); }
        .seat { width: 35px; height: 35px; border-radius: 5px 5px 10px 10px; cursor: pointer; transition: 0.2s; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; font-weight: bold; margin: 3px; border: 1px solid rgba(255,255,255,0.2); }
        .seat:hover:not(.booked):not(.maintenance) { transform: scale(1.1); box-shadow: 0 0 10px rgba(255,255,255,0.5); }
        .available { background-color: #444; color: white; } 
        .vip { background-color: #E71A0F; color: white; border-color: #ff4d4d; } 
        .sweetbox { background-color: #ffc107; color: #000; width: 76px; border-color: #ffca2c; } 
        .selected { background-color: #00ff00 !important; color: #000; border-color: #00ff00; box-shadow: 0 0 15px #00ff00; } 
        .booked { background-color: #222; color: #555; cursor: not-allowed; border-color: #111; } 
        .maintenance { background-color: #555; color: #222; cursor: not-allowed; position: relative; } 
        .maintenance::after { content: 'X'; position: absolute; color: #111; font-size: 1.2rem; }
        .booking-footer { background: #222; border-top: 2px solid #E71A0F; z-index: 1000; }
        .seat-row { display: flex; align-items: center; justify-content: center; margin-bottom: 2px; }
    </style>
</head>
<body>

    <div class="container mt-3">
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <strong><i class="fa-solid fa-triangle-exclamation me-2"></i> Lỗi rồi:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger fw-bold shadow-sm">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            </div>
        @endif
    </div>  

    <div class="bg-dark py-3 mb-4 shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('client.movies.show', $showtime->movie_id) }}" class="text-white me-3 fs-4"><i class="fa-solid fa-arrow-left"></i></a>
                <div>
                    <h5 class="mb-0 fw-bold text-uppercase">{{ $showtime->movie->name }}</h5>
                    <small class="text-warning">
                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i - d/m/Y') }} | {{ $showtime->room->room_name }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5 pb-5">
        <div class="d-flex justify-content-center mb-5">
            <div class="screen-curve text-center pt-2 text-white-50 letter-spacing-2">MÀN HÌNH CHÍNH</div>
        </div>

        <div class="d-flex flex-column align-items-center mb-5">
            @foreach($seatRows as $rowLetter => $rowSeats)
                <div class="seat-row">
                    <div class="fw-bold me-3 text-white-50 text-center" style="width: 25px;">{{ $rowLetter }}</div>
                    
                    @foreach($rowSeats as $seat)
                        @php
                            $typeName = strtolower($seat->typeSeat->name ?? '');
                            $seatClass = 'available'; 
                            $price = $seat->typeSeat->basePrice ?? 0;

                            if($seat->status == 0) $seatClass = 'maintenance';
                            elseif(in_array($seat->id, $bookedSeats)) $seatClass = 'booked';
                            elseif(str_contains($typeName, 'vip')) $seatClass = 'vip';
                            elseif(str_contains($typeName, 'đôi') || str_contains($typeName, 'sweetbox')) $seatClass = 'sweetbox';
                        @endphp

                        <div class="seat {{ $seatClass }}" 
                             data-id="{{ $seat->id }}" 
                             data-price="{{ $price }}" 
                             data-number="{{ $seat->seat_number }}"
                             onclick="selectSeat(this)">
                            {{ $seat->seat_number }}
                        </div>
                    @endforeach
                    
                    <div class="fw-bold ms-3 text-white-50 text-center" style="width: 25px;">{{ $rowLetter }}</div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center gap-3 gap-md-4 mt-5 pt-3 border-top border-secondary flex-wrap shadow p-3 bg-dark rounded">
            <div class="d-flex align-items-center"><div class="seat available" style="width:20px;height:20px;margin-right:8px;cursor:default;"></div> Ghế Thường</div>
            <div class="d-flex align-items-center"><div class="seat vip" style="width:20px;height:20px;margin-right:8px;cursor:default;"></div> Ghế VIP</div>
            <div class="d-flex align-items-center"><div class="seat sweetbox" style="width:40px;height:20px;margin-right:8px;cursor:default;"></div> Ghế Đôi</div>
            <div class="d-flex align-items-center"><div class="seat selected" style="width:20px;height:20px;margin-right:8px;cursor:default;"></div> Đang chọn</div>
            <div class="d-flex align-items-center"><div class="seat booked" style="width:20px;height:20px;margin-right:8px;cursor:default;"></div> Đã bán</div>
        </div>
    </div>

    <div class="fixed-bottom booking-footer p-3 shadow-lg">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <div class="text-white-50 small mb-1">Ghế đang chọn:</div>
                <div id="selectedSeatsText" class="fw-bold text-warning fs-5">Chưa chọn ghế</div>
            </div>
            <div class="text-end me-4">
                <div class="text-white-50 small mb-1">Tổng tiền:</div>
                <div id="totalPriceText" class="fw-bold text-danger fs-4">0đ</div>
            </div>
            <button class="btn btn-danger btn-lg px-5 fw-bold" onclick="openPaymentModal()" id="btnCheckout" disabled>TIẾP TỤC</button>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-dark">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-uppercase"><i class="fa-solid fa-cart-shopping me-2 text-danger"></i>Xác nhận đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form id="checkoutForm" action="{{ route('client.book.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                    <input type="hidden" name="seat_ids" id="seatIdsInput">

                    <div class="modal-body p-4">
                        <div class="mb-3 pb-3 border-bottom">
                            <p class="mb-1 text-muted">Phim: <strong class="text-dark">{{ $showtime->movie->name }}</strong></p>
                            <p class="mb-1 text-muted">Ghế đã chọn: <strong id="modalSeatList" class="text-danger fs-5"></strong></p>
                            <p class="mb-0 text-muted fs-5">Tổng tiền: <strong id="modalTotalPrice" class="text-danger fs-4"></strong></p>
                        </div>
                        
                        <label class="form-label fw-bold">Chọn phương thức thanh toán <span class="text-danger">*</span></label>
                        <select name="payment_method_id" class="form-select form-select-lg" required>
                            <option value="">-- Vui lòng chọn --</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quay lại</button>
                        <button type="submit" class="btn btn-danger fw-bold px-4">Xác nhận thanh toán</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedSeats = [];
        let totalPrice = 0;

        function selectSeat(element) {
            if(element.classList.contains('booked') || element.classList.contains('maintenance')) return;

            const seatId = element.getAttribute('data-id');
            const seatNumber = element.getAttribute('data-number');
            const price = parseInt(element.getAttribute('data-price'));

            if(element.classList.contains('selected')) {
                element.classList.remove('selected');
                selectedSeats = selectedSeats.filter(s => s.id !== seatId);
                totalPrice -= price;
            } else {
                element.classList.add('selected');
                selectedSeats.push({ id: seatId, number: seatNumber });
                totalPrice += price;
            }

            updateFooter();
        }

        function updateFooter() {
            const seatsText = document.getElementById('selectedSeatsText');
            const priceText = document.getElementById('totalPriceText');
            const btn = document.getElementById('btnCheckout');

            if(selectedSeats.length > 0) {
                seatsText.innerText = selectedSeats.map(s => s.number).join(', ');
                priceText.innerText = totalPrice.toLocaleString('vi-VN') + 'đ';
                btn.disabled = false;
            } else {
                seatsText.innerText = 'Chưa chọn ghế';
                priceText.innerText = '0đ';
                btn.disabled = true;
            }
        }

        function openPaymentModal() {
            if(selectedSeats.length === 0) return;
            
            // Đổ dữ liệu vào input ẩn (Không cần input ngầm total_vnpay nữa vì Backend tự tính)
            document.getElementById('seatIdsInput').value = selectedSeats.map(s => s.id).join(',');
            
            // Đổ dữ liệu hiển thị ra Modal
            document.getElementById('modalSeatList').innerText = selectedSeats.map(s => s.number).join(', ');
            document.getElementById('modalTotalPrice').innerText = totalPrice.toLocaleString('vi-VN') + 'đ';
            
            // Gọi Bootstrap Modal hiện lên
            var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();
        }
    </script>
</body>
</html>