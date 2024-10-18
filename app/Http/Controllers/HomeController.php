<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Slideshow;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $slideshows = Slideshow::all();

        return view('home', [
            'featuredPosts' => Post::published()->featured()->latest('published_at')->take(9)->get(),
            'slideshows' => $slideshows
        ]);
    }
}
