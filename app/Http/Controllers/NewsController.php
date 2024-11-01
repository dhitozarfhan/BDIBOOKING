<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $blog = News::latest()->get();

        return view('home', compact('blog'));
    }
}
