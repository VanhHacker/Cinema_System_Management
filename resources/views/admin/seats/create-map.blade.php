@extends('admin.layouts.app')

@section('title', 'Tạo sơ đồ ghế')

@section('content')
    <div class="card-box">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold">⚙️ Tạo sơ đồ ghế</h4>
                <p class="text-muted mb-0">
                    Thiết lập số hàng và cột cho phòng: {{ $room->room_name }}
                </p>
            </div>

            {{-- NÚT QUAY LẠI --}}
            <a href="{{ route('admin.rooms.seats', $room->id) }}"
               class="btn btn-secondary mb-3">
                ← Quay lại
            </a>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('admin.rooms.seats.storeMap', $room->id) }}">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <label class="fw-semibold">Số hàng</label>
                    <input type="number" id="rows" name="rows"
                           class="form-control mb-3" value="5" min="1" max="20">
                </div>

                <div class="col-md-4">
                    <label class="fw-semibold">Số cột</label>
                    <input type="number" id="cols" name="cols"
                           class="form-control mb-3" value="8" min="1" max="20">
                </div>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="button" class="btn btn-primary" onclick="previewMap()">
                    👁️ Xem trước
                </button>

                <button class="btn btn-danger">
                    💾 Tạo sơ đồ
                </button>
            </div>
        </form>

        {{-- PREVIEW --}}
        <div id="preview" class="mt-5 text-center"></div>

    </div>

    <style>
        .seat-preview {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .vip {
            background: gold !important;
        }
    </style>

    <script>
        function previewMap() {
            let rows = document.getElementById('rows').value;
            let cols = document.getElementById('cols').value;

            let html = `
        <div class="mb-3">
            <div style="width:300px;height:10px;background:#ccc;margin:auto;border-radius:10px"></div>
            <small>Màn hình</small>
        </div>
    `;

            for (let r = 1; r <= rows; r++) {
                html += `<div style="display:flex;justify-content:center;gap:5px;margin-bottom:5px;">`;

                for (let c = 1; c <= cols; c++) {
                    let seat = String.fromCharCode(64 + r) + c;

                    html += `
                <div class="seat-preview ${r <= 2 ? 'vip' : ''}">
                    ${seat}
                </div>
            `;
                }

                html += `</div>`;
            }

            document.getElementById('preview').innerHTML = html;
        }
    </script>
@endsection
