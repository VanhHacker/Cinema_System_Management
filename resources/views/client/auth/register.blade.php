@extends('client.layouts.app')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-dark text-white border-secondary shadow">
                    <div class="card-header border-secondary text-center py-3">
                        <h4 class="mb-0 text-uppercase fw-bold" style="color: var(--cgv-red)">Đăng Ký Thành Viên</h4>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('client.register.post') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Họ và tên</label>
                                    <input type="text" name="name" class="form-control bg-secondary text-white border-0" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control bg-secondary text-white border-0" value="{{ old('phone') }}" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control bg-secondary text-white border-0" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Tên đăng nhập (Username)</label>
                                    <input type="text" name="user_name" class="form-control bg-secondary text-white border-0" value="{{ old('user_name') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control bg-secondary text-white border-0" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Nhập lại Mật khẩu</label>
                                    <input type="password" name="password_confirmation" class="form-control bg-secondary text-white border-0" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-cgv w-100 py-2 fw-bold text-uppercase">Đăng Ký</button>
                        </form>
                        <div class="mt-3 text-center">
                            <span class="text-muted">Đã có tài khoản?</span>
                            <a href="{{ route('client.login') }}" style="color: var(--cgv-red); text-decoration: none;">Đăng nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
