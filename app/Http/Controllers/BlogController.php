<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blog = Blog::latest()->get();
        $imagePath = 'public\storage\blog\thumbnails';

        return view('home', compact('blog', 'imagePath'));
    }
}
