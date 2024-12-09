<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index($categoryId = null, $categorySlug = null)
    {
        if ($categoryId) {
            $category = Category::findOrFail($categoryId);

            $blog = Blog::where('category_id', $categoryId)
                ->orderBy('time_stamp', 'desc')
                ->paginate(10)
                ->through(function($item) {
                    $item->formatted_date = Carbon::parse($item->time_stamp)->translatedFormat('d F Y');
                    $item->time_stamp = Carbon::parse($item->time_stamp);
                    return $item;
                });
            $category_name = $category->id_name;
        }
        else {
            $blog = Blog::with('category')
            ->orderBy('time_stamp', 'desc')
            ->paginate(10)
            ->through(function ($item) {
                $item->formatted_date = Carbon::parse($item->time_stamp)->translatedFormat('d F Y');
                $item->time_stamp = Carbon::parse($item->time_stamp);
                return $item;
            });
            $category_name = null;
        }
        $category = Category::has('blog')->withCount('blog')->get();
        $archive = DB::table('blog')
            ->selectRaw('EXTRACT(YEAR FROM time_stamp) as year, EXTRACT(MONTH FROM time_stamp) as month')
            ->groupByRaw('EXTRACT(YEAR FROM time_stamp), EXTRACT(MONTH FROM time_stamp)')
            ->orderByRaw('EXTRACT(YEAR FROM time_stamp) DESC, EXTRACT(MONTH FROM time_stamp) DESC')
            ->get();

        return view('blog.index', compact('blog', 'archive', 'category', 'category_name'));
    }

    public function show($year, $month, Category $category, Blog $blog, $title)
    {
        $blog->incrementHit();
        $formatted_date = Carbon::parse($blog->time_stamp)->translatedFormat('d F Y');
        $category = Category::has('blog')->withCount('blog')->get();
        $archive = DB::table('blog')
            ->selectRaw('EXTRACT(YEAR FROM time_stamp) as year, EXTRACT(MONTH FROM time_stamp) as month')
            ->groupByRaw('EXTRACT(YEAR FROM time_stamp), EXTRACT(MONTH FROM time_stamp)')
            ->orderByRaw('EXTRACT(YEAR FROM time_stamp) DESC, EXTRACT(MONTH FROM time_stamp) DESC')
            ->get();

        return view('blog.post', compact('blog', 'formatted_date', 'archive', 'category'));
    }
}
