<?php

namespace App\Http\Controllers;

use App\Models\TypeSeat;
use Illuminate\Http\Request;

class TypeSeatController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query
        $query = \App\Models\TypeSeat::query();

        // 1. Xử lý thanh tìm kiếm
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // 2. SỬA LỖI Ở ĐÂY: Dùng paginate() thay vì get() hoặc all()
        // Lấy danh sách, sắp xếp mới nhất lên đầu và phân trang (ví dụ: 10 dòng/trang)
        $typeSeats = $query->orderBy('id', 'desc')->paginate(10)->appends($request->all());

        return view('admin.type_seats.index', compact('typeSeats'));
    }                              

    public function create()
    {
        return view('admin.type_seats.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:type_seats,name',
            'basePrice' => 'required|numeric|min:0', // Đảm bảo giá trị là số và không âm
            'description' => 'nullable|string'
        ], [
            'basePrice.required' => 'Vui lòng nhập giá cơ bản',
            'basePrice.numeric' => 'Giá phải là định dạng số'
        ]);

        TypeSeat::create($request->all());
        return redirect()->route('admin.type_seats.index')->with('success', 'Thêm loại ghế thành công!');
    }

    public function edit(TypeSeat $typeSeat)
    {
        return view('admin.type_seats.edit', compact('typeSeat'));
    }

    public function update(Request $request, TypeSeat $typeSeat)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:type_seats,name,' . $typeSeat->id,
            'basePrice' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $typeSeat->update($request->all());
        return redirect()->route('admin.type_seats.index')->with('success', 'Cập nhật loại ghế thành công!');
    }

    public function destroy(TypeSeat $typeSeat)
    {
        $typeSeat->delete();
        return redirect()->route('admin.type_seats.index')->with('success', 'Đã xóa loại ghế!');
    }
}
