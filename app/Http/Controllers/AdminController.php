<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import thư viện Hash để mã hóa mật khẩu

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('id', 'desc')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255|unique:admins,user_name',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'status' => 'boolean'
        ]);

        // Tạo mảng dữ liệu để lưu, mã hóa mật khẩu trước khi thêm vào DB
        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        Admin::create($data);

        return redirect()->route('admin.admins.index')->with('success', 'Thêm Quản trị viên thành công!');
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'user_name' => 'required|string|max:255|unique:admins,user_name,' . $admin->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'status' => 'boolean',
            'password' => 'nullable|string|min:6' // Cho phép null, nếu nhập thì mới đổi pass
        ]);

        $data = $request->except('password'); // Lấy tất cả ngoại trừ pass

        // Nếu người dùng có nhập mật khẩu mới thì mới mã hóa và cập nhật
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'Cập nhật Quản trị viên thành công!');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Đã xóa Quản trị viên!');
    }
}
