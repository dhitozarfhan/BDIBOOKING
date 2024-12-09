<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index($categoryId = null, $categorySlug = null)
    {
        if ($categoryId) {
            $category = Category::findOrFail($categoryId);

            $news = News::where('category_id', $categoryId)
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
            $news = News::with('category')
            ->orderBy('time_stamp', 'desc')
            ->paginate(10)
            ->through(function($item) {
                $item->formatted_date = Carbon::parse($item->time_stamp)->translatedFormat('d F Y');
                $item->time_stamp = Carbon::parse($item->time_stamp);
                return $item;
            });
            $category_name = null;
        }
        $category = Category::has('news')->withCount('news')->get();
        $archive = DB::table('news')
            ->selectRaw('EXTRACT(YEAR FROM time_stamp) as year, EXTRACT(MONTH FROM time_stamp) as month')
            ->groupByRaw('EXTRACT(YEAR FROM time_stamp), EXTRACT(MONTH FROM time_stamp)')
            ->orderByRaw('EXTRACT(YEAR FROM time_stamp) DESC, EXTRACT(MONTH FROM time_stamp) DESC')
            ->get();

            return view('news.index', compact('news', 'archive', 'category', 'category_name'));
    }

    public function show($year, $month, Category $category, News $news, $title)
    {
        $news->incrementHit();
        $formatted_date = Carbon::parse($news->time_stamp)->translatedFormat('d F Y');
        $category = Category::has('news')->withCount('news')->get();
        $archive = DB::table('news')
            ->selectRaw('EXTRACT(YEAR FROM time_stamp) as year, EXTRACT(MONTH FROM time_stamp) as month')
            ->groupByRaw('EXTRACT(YEAR FROM time_stamp), EXTRACT(MONTH FROM time_stamp)')
            ->orderByRaw('EXTRACT(YEAR FROM time_stamp) DESC, EXTRACT(MONTH FROM time_stamp) DESC')
            ->get();

        return view('news.post', compact('news', 'formatted_date', 'archive', 'category'));
    }
}
