<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Hiển thị danh sách thể loại (Đã tích hợp Tìm kiếm & Phân trang)
    public function index(Request $request)
    {
        $query = Category::query();

        // Xử lý tìm kiếm theo tên thể loại
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // Sắp xếp mới nhất lên đầu và phân trang (ví dụ: 7 thể loại/trang)
        $categories = $query->orderBy('id', 'desc')->paginate(7)->appends($request->all());

        return view('admin.categories.index', compact('categories'));
    }

    // Hiển thị form thêm thể loại
    public function create()
    {
        return view('admin.categories.create');
    }

    // Xử lý lưu thể loại mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Vui lòng nhập tên thể loại',
            'name.unique' => 'Thể loại này đã tồn tại'
        ]);

        Category::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Thêm thể loại thành công!');
    }

    // Hiển thị form sửa thể loại
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Xử lý cập nhật thể loại
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thể loại thành công!');
    }

    // Xử lý xóa thể loại
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa thể loại!');
    }
}