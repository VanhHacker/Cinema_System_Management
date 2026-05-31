<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
   public function index(Request $request)
    {
        // Khởi tạo query
        $query = \App\Models\Cinema::query();

        // XỬ LÝ TÌM KIẾM ĐA NĂNG
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Đã sửa thành contact_info cho chuẩn với Model của bạn
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhere('contact_info', 'like', '%' . $search . '%');
            });
        }

        // Lấy dữ liệu, sắp xếp mới nhất lên đầu và phân trang (10 rạp/trang)
        $cinemas = $query->orderBy('id', 'desc')->paginate(6)->appends($request->all());

        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function create()
    {
        return view('admin.cinemas.create');
    }

    public function store(Request $request)
    {
        // 1. KHI THÊM MỚI: Bắt buộc Tên rạp và Thông tin liên hệ không được trùng với bất kỳ ai
        $request->validate([
            'name' => 'required|string|max:255|unique:cinemas,name',
            'contact_info' => 'required|string|unique:cinemas,contact_info', 
            'address' => 'required|string',
        ], [
            'name.unique' => 'Tên rạp này đã tồn tại trong hệ thống. Vui lòng chọn tên khác!',
            'name.required' => 'Vui lòng nhập tên rạp.',
            'contact_info.unique' => 'Thông tin liên hệ này (SĐT/Email) đã tồn tại. Vui lòng nhập thông tin khác!',
            'contact_info.required' => 'Vui lòng nhập thông tin liên hệ.'
        ]);

        Cinema::create($request->only(['name', 'contact_info', 'address']));
        
        return redirect()->route('admin.cinemas.index')->with('success', 'Thêm rạp chiếu mới thành công!');
    }

    public function edit(Cinema $cinema)
    {
        return view('admin.cinemas.edit', compact('cinema'));
    }

    public function update(Request $request, Cinema $cinema)
    {
        // 2. KHI CẬP NHẬT: Kiểm tra trùng lặp nhưng "BỎ QUA" rạp đang được sửa (dựa vào $cinema->id)
        $request->validate([
            'name' => 'required|string|max:255|unique:cinemas,name,' . $cinema->id,
            'contact_info' => 'required|string|unique:cinemas,contact_info,' . $cinema->id, 
            'address' => 'required|string',
        ], [
            'name.unique' => 'Tên rạp này đã bị trùng với một rạp khác!',
            'name.required' => 'Vui lòng nhập tên rạp.',
            'contact_info.unique' => 'Thông tin liên hệ này (SĐT/Email) đã tồn tại ở một rạp khác!',
            'contact_info.required' => 'Vui lòng nhập thông tin liên hệ.'
        ]);

        $cinema->update($request->only(['name', 'contact_info', 'address']));
        
        return redirect()->route('admin.cinemas.index')->with('success', 'Cập nhật thông tin rạp thành công!');
    }

    public function destroy(Cinema $cinema)
    {
        $cinema->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Đã xóa rạp chiếu thành công!');
    }
}   