@extends('client.layouts.auth')

@section('title', 'Đăng ký')

@section('content')
    <h3 class="text-center mb-4">📝 Đăng ký</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('client.register.post') }}">
        @csrf

        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button class="btn btn-success w-100">Đăng ký</button>
    </form>

    <p class="text-center mt-3">
        Đã có tài khoản?
        <a href="{{ route('client.login') }}">Đăng nhập</a>
    </p>
@endsection
