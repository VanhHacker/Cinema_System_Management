<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Hiển thị form Đăng nhập
    public function showLogin()
    {
        return view('client.auth.login');
    }

    // 2. Xử lý Đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Sử dụng guard 'customer' để kiểm tra đăng nhập
        $credentials = $request->only('email', 'password');

        // Thêm điều kiện status = 1 (Tài khoản phải đang hoạt động)
        $credentials['status'] = 1;

        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        return back()->with('error', 'Email, mật khẩu không đúng hoặc tài khoản đã bị khóa!');
    }

    // 3. Hiển thị form Đăng ký
    public function showRegister()
    {
        return view('client.auth.register');
    }

    // 4. Xử lý Đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:customers,email',
            'user_name' => 'required|string|unique:customers,user_name',
            'password' => 'required|string|min:6|confirmed', // Cần thêm ô nhập lại mật khẩu (password_confirmation)
        ], [
            'email.unique' => 'Email này đã được đăng ký!',
            'user_name.unique' => 'Tên đăng nhập này đã có người sử dụng!',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp!'
        ]);

        // Tạo khách hàng mới (mặc định status = 1)
        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'user_name' => $request->user_name,
            'password' => Hash::make($request->password),
            'status' => 1
        ]);

        // Tự động đăng nhập luôn sau khi đăng ký xong
        Auth::guard('customer')->login($customer);

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // 5. Xử lý Đăng xuất
    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('home')->with('success', 'Bạn đã đăng xuất!');
    }
}
