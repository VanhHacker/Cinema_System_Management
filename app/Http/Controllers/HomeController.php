<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 8 bộ phim mới nhất Đang chiếu (status = 1)
        $movies = Movie::where('status', 1)
            ->orderBy('release_date', 'desc')
            ->take(8)
            ->get();

        return view('client.home', compact('movies'));
    }

    


}
