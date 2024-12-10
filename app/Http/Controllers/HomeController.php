<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\News;
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

        $news = News::with('category')->latest('time_stamp')->take(6)->get()->map(function($item) {
            $item->formatted_date = Carbon::parse($item->time_stamp)->translatedFormat('l, d M Y');
            $item->time_stamp = Carbon::parse($item->time_stamp);
            $item->type = 'news';
            return $item;
        });
        $blog = Blog::with('category')->latest('time_stamp')->take(6)->get()->map(function($item) {
            $item->formatted_date = Carbon::parse($item->time_stamp)->translatedFormat('l, d M Y');
            $item->time_stamp = Carbon::parse($item->time_stamp);
            $item->type = 'blog';
            return $item;
        });

        $posts = $news->concat($blog)->sortByDesc('time_stamp');

        return view('home', [
            // 'featuredPosts' => Post::published()->featured()->latest('published_at')->take(9)->get(),
            'slideshows' => $slideshows,
            'posts' => $posts
        ]);
    }
}
