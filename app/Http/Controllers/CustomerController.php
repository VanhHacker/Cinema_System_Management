<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // Hiển thị danh sách khách hàng
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('admin.customers.index', compact('customers'));
    }

    // Hiển thị form thêm mới khách hàng
    public function create()
    {
        return view('admin.customers.create');
    }

    // Xử lý lưu khách hàng vào Database
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'email'     => 'required|email|unique:customers,email',
            'user_name' => 'required|string|max:255|unique:customers,user_name',
            'password'  => 'required|string|min:6',
            'status'    => 'boolean'
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
            'user_name.unique' => 'Tên đăng nhập này đã tồn tại.'
        ]);

        $data = $request->all();
        // Mã hóa mật khẩu trước khi lưu
        $data['password'] = Hash::make($request->password);

        Customer::create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Thêm Khách hàng thành công!');
    }

    // Hiển thị form chỉnh sửa khách hàng
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    // Xử lý cập nhật thông tin khách hàng
    public function update(Request $request, Customer $customer)
    {
        // Khi validate update, cần loại trừ ID của user hiện tại để không bị báo lỗi "Đã tồn tại"
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'email'     => 'required|email|unique:customers,email,' . $customer->id,
            'user_name' => 'required|string|max:255|unique:customers,user_name,' . $customer->id,
            'password'  => 'nullable|string|min:6', // Bỏ trống thì không đổi mật khẩu
            'status'    => 'boolean'
        ]);

        $data = $request->except('password');

        // Nếu admin có nhập mật khẩu mới thì tiến hành mã hóa và lưu lại
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật Khách hàng thành công!');
    }

    // Xử lý xóa khách hàng
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Đã xóa Khách hàng!');
    }
}
