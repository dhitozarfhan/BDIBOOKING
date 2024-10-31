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
        $newsImage = 'storage/news/thumbnails';
        $blogImage = 'storage/blog/thumbnails';

        $news = News::with('category')->get()->map(function($item) {
            $item->formatted_date = Carbon::parse($item->time_stamp)->format('d M Y');
            return $item;
        });
        $blog = Blog::with('category')->get()->map(function($item) {
            $item->formatted_date = Carbon::parse($item->time_stamp)->format('d M Y');
            return $item;
        });

        return view('home', [
            // 'featuredPosts' => Post::published()->featured()->latest('published_at')->take(9)->get(),
            'slideshows' => $slideshows,
            'news' => $news,
            'blog' => $blog,
            'newsImage' => $newsImage,
            'blogImage' => $blogImage,
        ]);
    }
}
