<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\AdminAuth;
use Illuminate\Http\Request; // Thêm dòng này để xử lý Request

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Khai báo Alias middleware của bạn (giữ nguyên)
        $middleware->alias([
            'admin.auth' => AdminAuth::class,
        ]);

        // CẤU HÌNH ĐIỀU HƯỚNG KHI CHƯA ĐĂNG NHẬP (Fix lỗi Route login not defined)
        $middleware->redirectGuestsTo(function (Request $request) {
            // Nếu khách đang cố vào link có chữ /admin/ (hoặc chính xác là /admin)
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            // Còn lại thì đẩy về trang đăng nhập của khách hàng
            return route('client.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();