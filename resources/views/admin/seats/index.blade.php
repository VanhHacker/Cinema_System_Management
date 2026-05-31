@extends('admin.layouts.app')

@section('title', 'Quản lý ghế')

@section('content')

    <div class="card-box">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            {{-- LEFT --}}
            <div class="d-flex align-items-center gap-3">
                {{-- 🔙 BACK --}}
                <a href="{{ route('admin.rooms.index') }}"
                   class="btn btn-light border rounded-pill px-3 shadow-sm">
                    ⬅ Danh sách phòng
                </a>

                <div>
                    <h4 class="fw-bold mb-0">
                        🪑 Ghế - Phòng: {{ $room->room_name ?? '' }}
                    </h4>
                    <p class="text-muted mb-0">
                        Danh sách & sơ đồ ghế
                    </p>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="d-flex gap-2">

                {{-- THÊM GHẾ --}}
                <a href="{{ route('admin.rooms.seats.create', $room->id) }}"
                   class="btn btn-success rounded-pill px-3">
                    <i class="fa fa-plus"></i> Thêm ghế
                </a>

                {{-- TẠO SƠ ĐỒ --}}
                <a href="{{ route('admin.rooms.seats.createMap', $room->id) }}"
                   class="btn btn-warning rounded-pill px-3">
                    ⚙️ Tạo sơ đồ
                </a>

                {{-- TOGGLE VIEW --}}
                <div class="btn-group">
                    <button class="btn btn-outline-primary active" id="btnList">📋</button>
                    <button class="btn btn-outline-danger" id="btnMap">🪑</button>
                </div>

            </div>
        </div>

        {{-- ================= LIST ================= --}}
        <div id="listView">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Ghế</th>
                    <th>Hàng</th>
                    <th>Cột</th>
                    <th>Loại</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($seats as $seat)
                    <tr>
                        <td>{{ $seat->id }}</td>
                        <td class="fw-bold text-danger">{{ $seat->seat_number }}</td>
                        <td>{{ $seat->row }}</td>
                        <td>{{ $seat->column }}</td>
                        <td>
                        <span class="badge bg-secondary">
                            {{ strtoupper($seat->type) }}
                        </span>
                        </td>
                        <td>
                            @if($seat->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Tắt</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.rooms.seats.edit', [$room->id, $seat->id]) }}">
                                <i class="fa fa-pen text-primary me-2"></i>
                            </a>

                            <form action="{{ route('admin.rooms.seats.destroy', [$room->id, $seat->id]) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button style="border:none;background:none;">
                                    <i class="fa fa-trash text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- ================= MAP ================= --}}
        <div id="mapView" style="display:none">

            {{-- SCREEN --}}
            <div class="text-center mb-4">
                <div class="screen"></div>
                <small class="text-muted">Màn hình</small>
            </div>

            @php
                $grouped = $seats->sortBy(['row','column'])->groupBy('row');
            @endphp

            <div class="d-flex flex-column align-items-center gap-2">
                @foreach($grouped as $row => $rowSeats)
                    <div class="d-flex gap-2 align-items-center">

                        {{-- LABEL --}}
                        <span class="row-label">
                        {{ chr(64 + $row) }}
                    </span>

                        @foreach($rowSeats as $seat)
                            <div class="seat
                            {{ $seat->is_active ? 'active' : 'inactive' }}
                            {{ $seat->type == 'vip' ? 'vip' : '' }}"
                                 data-id="{{ $seat->id }}">
                                {{ $seat->column }}
                            </div>
                        @endforeach

                    </div>
                @endforeach
            </div>

        </div>

    </div>

    {{-- ================= STYLE ================= --}}
    <style>
        .screen {
            width: 300px;
            height: 12px;
            background: linear-gradient(to right, #ccc, #eee, #ccc);
            margin: auto;
            border-radius: 10px;
        }

        .row-label {
            width: 25px;
            font-weight: bold;
        }

        .seat {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .seat.active {
            background: #22c55e;
            color: white;
        }

        .seat.inactive {
            background: #e5e7eb;
        }

        .seat.vip {
            background: gold !important;
            color: black;
        }

        .seat:hover {
            transform: scale(1.1);
        }
    </style>

    {{-- ================= SCRIPT ================= --}}
    <script>

        const btnList = document.getElementById('btnList');
        const btnMap = document.getElementById('btnMap');
        const listView = document.getElementById('listView');
        const mapView = document.getElementById('mapView');

        btnList.onclick = () => {
            listView.style.display = 'block';
            mapView.style.display = 'none';
            btnList.classList.add('active');
            btnMap.classList.remove('active');
        };

        btnMap.onclick = () => {
            listView.style.display = 'none';
            mapView.style.display = 'block';
            btnMap.classList.add('active');
            btnList.classList.remove('active');
        };

        // AJAX toggle
        document.querySelectorAll('.seat').forEach(seat => {
            seat.addEventListener('click', function () {

                let id = this.dataset.id;

                fetch(`/seats/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(() => {
                        this.classList.toggle('active');
                        this.classList.toggle('inactive');
                    });

            });
        });

    </script>

@endsection
