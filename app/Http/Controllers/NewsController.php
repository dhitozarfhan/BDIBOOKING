<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $blog = News::latest()->get();
        $imagePath = 'public\storage\news\thumbnails';

        return view('home', compact('blog', 'imagePath'));
    }
}
