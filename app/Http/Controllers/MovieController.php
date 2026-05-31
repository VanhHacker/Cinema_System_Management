<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    // Hiển thị danh sách phim (Đã tích hợp Tìm kiếm & Lọc)
    public function index(Request $request)
    {
        // 1. Lấy danh sách thể loại để đổ vào Dropdown lọc trên View
        $categories = Category::all();

        // 2. Khởi tạo query và load sẵn quan hệ 'categories' để tránh lỗi N+1 Query
        $query = Movie::with('categories');

        // --- XỬ LÝ BỘ LỌC ---

        // Lọc theo Tên phim
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        // Lọc theo Thể loại (Xử lý Many-to-Many)
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Lọc theo Trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo Ngày khởi chiếu
        if ($request->filled('release_date')) {
            $query->whereDate('release_date', $request->release_date); 
        }

        // 3. Lấy dữ liệu, sắp xếp mới nhất lên đầu và phân trang
        $movies = $query->orderBy('id', 'desc')->paginate(6)->appends($request->all());

        return view('admin.movies.index', compact('movies', 'categories'));
    }
    
    // Hiển thị form thêm phim
    public function create()
    {
        // Lấy danh sách thể loại để hiển thị ra Checkbox/Select nhiều lựa chọn
        $categories = Category::all();
        return view('admin.movies.create', compact('categories'));
    }

    // Xử lý lưu phim vào DB
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'language' => 'required|string',
            'release_date' => 'required|date|after:today', 
            'category_ids' => 'required|array',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Kiểm tra file ảnh (tối đa 2MB)
        ], [
            'release_date.after' => 'Ngày khởi chiếu phải bắt đầu từ ngày mai trở đi!',
        ]);

        $data = $request->except('category_ids');

        // Xử lý upload ảnh
        if ($request->hasFile('poster')) {
            // Lưu ảnh vào thư mục 'storage/app/public/posters'
            $imagePath = $request->file('poster')->store('posters', 'public');
            $data['poster'] = $imagePath; // Gắn đường dẫn vào mảng data để lưu DB
        }

        $movie = Movie::create($data);
        $movie->categories()->attach($request->category_ids);

        return redirect()->route('admin.movies.index')->with('success', 'Thêm phim thành công!');
    }

    // Hiển thị form sửa phim
    public function edit(Movie $movie)
    {
        $categories = Category::all();
        return view('admin.movies.edit', compact('movie', 'categories'));
    }

    // Xử lý cập nhật phim
    public function update(Request $request, Movie $movie)
    {
        // Ghi chú: Ở đây giữ nguyên 'date' để không chặn việc lưu các phim có ngày khởi chiếu trong quá khứ
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'language' => 'required|string',
            'release_date' => 'required|date',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('category_ids');

        // Xử lý nếu người dùng có upload ảnh mới
        if ($request->hasFile('poster')) {
            // 1. Xóa ảnh cũ đi cho nhẹ server (nếu có)
            if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
                Storage::disk('public')->delete($movie->poster);
            }

            // 2. Lưu ảnh mới
            $imagePath = $request->file('poster')->store('posters', 'public');
            $data['poster'] = $imagePath;
        }

        $movie->update($data);

        if ($request->has('category_ids')) {
            $movie->categories()->sync($request->category_ids);
        }

        return redirect()->route('admin.movies.index')->with('success', 'Cập nhật phim thành công!');
    }

    // Xử lý xóa phim
    public function destroy(Movie $movie)
    {
        if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
            Storage::disk('public')->delete($movie->poster);
        }
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Đã xóa phim!');
    }
}