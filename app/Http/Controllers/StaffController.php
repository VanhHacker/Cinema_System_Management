<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Cinema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        // Load kèm thông tin rạp chiếu để hiển thị tên rạp trên bảng danh sách
        $staffs = Staff::with('cinema')->orderBy('id', 'desc')->get();
        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        // Lấy danh sách rạp để hiển thị trong thẻ <select>
        $cinemas = Cinema::all();
        return view('admin.staff.create', compact('cinemas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255|unique:staff,user_name',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255', // Cột name chúng ta đã chốt thêm vào DB
            'email' => 'required|email|unique:staff,email',
            'cinema_id' => 'required|exists:cinemas,id', // Phải chọn 1 rạp có thực
            'status' => 'boolean'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password); // Mã hóa mật khẩu

        Staff::create($data);

        return redirect()->route('admin.staff.index')->with('success', 'Thêm Nhân viên thành công!');
    }

    public function edit(Staff $staff)
    {
        $cinemas = Cinema::all();
        return view('admin.staff.edit', compact('staff', 'cinemas'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'user_name' => 'required|string|max:255|unique:staff,user_name,' . $staff->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'cinema_id' => 'required|exists:cinemas,id',
            'status' => 'boolean',
            'password' => 'nullable|string|min:6'
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', 'Cập nhật Nhân viên thành công!');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Đã xóa Nhân viên!');
    }
}
