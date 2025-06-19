<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ArchiveController extends Controller
{
    public function posted($year, $month)
    {
        $newsQuery = News::query();
        if ($year) {
            $newsQuery->whereYear('time_stamp', $year);
        }
        if ($month) {
            $newsQuery->whereMonth('time_stamp', $month);
        }
        $news = $newsQuery->with('category')->get()->map(function($item) {
            $item->formatted_date = Carbon::parse($item->time_stamp)->translatedFormat('d F Y');
            $item->time_stamp = Carbon::parse($item->time_stamp);
            $item->type = 'news';
            return $item;
        });

        $blogQuery = Blog::query();
        if ($year) {
            $blogQuery->whereYear('time_stamp', $year);
        }
        if ($month) {
            $blogQuery->whereMonth('time_stamp', $month);
        }
        $blog = $blogQuery->with('category')->get()->map(function($item) {
            $item->formatted_date = Carbon::parse($item->time_stamp)->translatedFormat('d F Y');
            $item->time_stamp = Carbon::parse($item->time_stamp);
            $item->type = 'blog';
            return $item;
        });

        $posts = $news->concat($blog)->sortByDesc('time_stamp');

        $newsArchive = DB::table('news')
            ->selectRaw('EXTRACT(YEAR FROM time_stamp) as year, EXTRACT(MONTH FROM time_stamp) as month')
            ->groupByRaw('EXTRACT(YEAR FROM time_stamp), EXTRACT(MONTH FROM time_stamp)');

        $blogArchive = DB::table('blog')
            ->selectRaw('EXTRACT(YEAR FROM time_stamp) as year, EXTRACT(MONTH FROM time_stamp) as month')
            ->groupByRaw('EXTRACT(YEAR FROM time_stamp), EXTRACT(MONTH FROM time_stamp)');

        $archive = $newsArchive->union($blogArchive)->orderByRaw('year DESC, month DESC')->get();

        $currentDate = Carbon::create($year, $month);
        $prevDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();

        $maxDateNews = News::max('time_stamp');
        $maxDateBlog = Blog::max('time_stamp');
        $maxDate = Carbon::parse(max($maxDateNews, $maxDateBlog));
        $nextDisabled = $nextDate->greaterThan($maxDate);

        return view('archive.posted', [
            'posts' => $posts,
            'year' => $year,
            'month' => $month,
            'archive' => $archive,
            'currentDate' => $currentDate,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'nextDisabled' => $nextDisabled
        ]);
    }
}
