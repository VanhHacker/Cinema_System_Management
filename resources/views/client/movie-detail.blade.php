@extends('client.layouts.app')

@section('content')
    <div class="card-box">
        <h3>{{ $movie->title }}</h3>

        <p>{{ $movie->description }}</p>

        <h5 class="mt-4">📅 Suất chiếu</h5>

        @foreach($movie->showtimes as $showtime)
            @php
                // Lấy thời gian hiện tại và thời gian chiếu
                $now = \Carbon\Carbon::now();
                $startTime = \Carbon\Carbon::parse($showtime->start_time);
                
                // Kiểm tra: Nếu Hiện tại + 45 phút >= Giờ chiếu -> Khóa
                $isClosed = $now->copy()->addMinutes(45)->gte($startTime);
            @endphp

            @if($isClosed)
                <button class="btn btn-outline-secondary mb-2 disabled opacity-50" style="cursor: not-allowed;" title="Đã đóng bán vé">
                    {{ $startTime->format('H:i') }} (Đã đóng)
                </button>
            @else
                <a href="{{ route('booking.create', $showtime->id) }}" class="btn btn-outline-primary mb-2">
                    {{ $startTime->format('H:i') }}
                </a>
            @endif
        @endforeach
    </div>
@endsection