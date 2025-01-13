<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $page->incrementHit();
        $formatted_date = Carbon::parse($page->time_stamp)->translatedFormat('d F Y');
        $page->id_content = html_entity_decode($page->id_content);
        $archive = DB::table('news')
            ->selectRaw('EXTRACT(YEAR FROM time_stamp) as year, EXTRACT(MONTH FROM time_stamp) as month')
            ->groupByRaw('EXTRACT(YEAR FROM time_stamp), EXTRACT(MONTH FROM time_stamp)')
            ->orderByRaw('EXTRACT(YEAR FROM time_stamp) DESC, EXTRACT(MONTH FROM time_stamp) DESC')
            ->get();

        return view('page.post', compact('page', 'formatted_date', 'archive'));
    }
}
