@extends('client.layouts.app')

@section('content')
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/9/8/980x448_8__2.png" class="d-block w-100" alt="Banner 1" style="height: 450px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/b/_/b_n_sao_c_a_980x448_1_.png" class="d-block w-100" alt="Banner 2" style="height: 450px; object-fit: cover;">
            </div>
            <div class="carousel-item">
                <img src="https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/9/8/980x448__1_.png" class="d-block w-100" alt="Banner 3" style="height: 450px; object-fit: cover;">
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <div class="container mt-5">
        <div class="d-flex justify-content-center align-items-center mb-4">
            <h2 class="text-uppercase fw-bold text-center" style="border-bottom: 3px solid var(--cgv-red); padding-bottom: 10px;">Phim Đang Chiếu</h2>
        </div>

        <div class="row">
            @foreach($movies as $movie)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 border-0 bg-transparent text-white movie-card">
                        
                        <div class="position-relative overflow-hidden" style="border-radius: 8px;">
                            @if(Str::startsWith($movie->poster, ['http://', 'https://']))
                                <img src="{{ $movie->poster }}" class="card-img-top w-100" alt="{{ $movie->name }}" style="height: 380px; object-fit: cover; transition: transform 0.4s ease;">
                            @else
                                <img src="{{ asset('storage/' . $movie->poster) }}" class="card-img-top w-100" alt="{{ $movie->name }}" style="height: 380px; object-fit: cover; transition: transform 0.4s ease;">
                            @endif

                            <div class="overlay d-flex flex-column justify-content-center align-items-center">
                                <a href="{{ route('client.movies.show', $movie->id) }}#showtimes" class="btn btn-danger w-75 mb-3 fw-bold py-2 shadow">
                                  <i class="fa-solid fa-ticket me-1"></i> Mua Vé
                                </a>
                                
                                <a href="{{ route('client.movies.show', $movie->id) }}" class="btn btn-outline-light w-75 fw-bold py-2 shadow">
                                    <i class="fa-solid fa-circle-info me-1"></i> Chi tiết
                                </a>
                            </div>
                        </div>

                        <div class="card-body px-0 mt-2">
                            <h5 class="card-title fw-bold text-truncate" title="{{ $movie->name }}">{{ $movie->name }}</h5>

                            <p class="card-text mb-1" style="color: #cccccc; font-size: 0.9rem;">
                                <i class="fa-regular fa-clock me-1"></i> {{ $movie->duration }} phút
                            </p>
                            <p class="card-text" style="color: #cccccc; font-size: 0.9rem;">
                                <i class="fa-solid fa-calendar-days me-1"></i> Khởi chiếu: {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        /* CSS cho lớp phủ bóng đen trên Poster */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Nền đen mờ 70% */
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            border-radius: 8px; /* Bo góc trùng với poster */
        }

        /* Hiệu ứng hiển thị nút khi hover vào Poster phim */
        .movie-card:hover .overlay {
            opacity: 1; /* Hiện lớp phủ và nút */
        }

        /* Hiệu ứng zoom nhẹ ảnh poster khi hover */
        .movie-card:hover .card-img-top {
            transform: scale(1.08); 
        }
    </style>
@endsection