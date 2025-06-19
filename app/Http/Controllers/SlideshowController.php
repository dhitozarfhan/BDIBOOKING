<?php

namespace App\Http\Controllers;

use App\Models\Slideshow;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    public function index() {
        $slideshows = Slideshow::all();
        return view('components.carousel', compact('slideshows'));
    }
}
