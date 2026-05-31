<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// 👉 ĐÃ SỬA: Import Model Admin thay vì User
use App\Models\Admin; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 1. Hiển thị Form Đăng nhập Admin
     */
    public function showLogin()
    {
        // 👉 ĐÃ SỬA: Dùng guard 'admin' để kiểm tra
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.rooms.index'); 
        }
        return view('admin.auth.login');
    }

    /**
     * 2. Xử lý Đăng nhập Admin
     */
    public function login(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Vui lòng nhập Email.',
            'email.email' => 'Định dạng Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.'
        ]);

        $credentials = $request->only('email', 'password');

        // 👉 ĐÃ SỬA: Sử dụng guard('admin') để trỏ vào bảng admins
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->route('admin.rooms.index')
                ->with('success', 'Chào mừng quay trở lại hệ thống quản trị!');
        }

        // Nếu thất bại, quay về kèm thông báo lỗi
        return back()->with('error', 'Email hoặc mật khẩu Quản trị viên không chính xác!');
    }

    /**
     * 3. Hiển thị Form Đăng ký Admin
     */
    public function showRegister()
    {
        return view('admin.auth.register');
    }

    /**
     * 4. Xử lý Đăng ký Admin mới
     */
    public function register(Request $request)
    {
        // Validate thông tin đăng ký
        $request->validate([
            'name' => 'required|string|max:255',
            // 👉 ĐÃ SỬA: unique:admins (Kiểm tra trùng email trong bảng admins)
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Họ tên không được để trống.',
            'email.unique' => 'Email này đã được sử dụng bởi một admin khác.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.'
        ]);

        // 👉 ĐÃ SỬA: Tạo tài khoản bằng Model Admin
        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Tạo tài khoản Admin thành công! Vui lòng đăng nhập.');
    }

    /**
     * 5. Xử lý Đăng xuất Admin
     */
    public function logout(Request $request)
    {
        // 👉 ĐÃ SỬA: Đăng xuất khỏi guard 'admin'
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Bạn đã đăng xuất khỏi hệ thống an toàn.');
    }
}