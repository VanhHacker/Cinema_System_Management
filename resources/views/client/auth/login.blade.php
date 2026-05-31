@extends('client.layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card bg-dark text-white border-secondary shadow">
                    <div class="card-header border-secondary text-center py-3">
                        <h4 class="mb-0 text-uppercase fw-bold" style="color: var(--cgv-red)">Đăng Nhập</h4>
                    </div>
                    <div class="card-body p-4">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('client.login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email đăng nhập</label>
                                <input type="email" name="email" class="form-control bg-secondary text-white border-0" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mật khẩu</label>
                                <input type="password" name="password" class="form-control bg-secondary text-white border-0" required>
                            </div>
                            <button type="submit" class="btn btn-cgv w-100 py-2 fw-bold text-uppercase">Đăng Nhập</button>
                        </form>
                        <div class="mt-3 text-center">
                            <span class="text-muted">Chưa có tài khoản?</span>
                            <a href="{{ route('client.register') }}" style="color: var(--cgv-red); text-decoration: none;">Đăng ký ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
