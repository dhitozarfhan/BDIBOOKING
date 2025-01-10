<?php

namespace App\Http\Controllers;

use App\Models\Core;
use App\Models\Information;
use Illuminate\Support\Str;

class InformationController extends Controller
{
    public function home()
    {
        $cores = Core::all();
        return view('information.home', compact('cores'));
    }

    public function show ($id, $slug)
    {
        $post = Information::where('information_id', $id)->firstOrFail();
        $generatedSlug = Str::slug($post->id_title);

        if ($generatedSlug !== $slug){
            return redirect()->route('information.post', ['id' => $id, 'slug' => $generatedSlug]);
        }

        return view('information.post', compact('post'));
    }

    public function showCore($slug)
    {
        $cores = Core::all();
        $core = Core::where('slug', $slug)->firstOrFail();
        $informations = Information::where('is_active', 'Y')
            ->whereHas('category', function ($query) use ($core) {
                $query->where('core_id', $core->core_id);
            })
            ->with('category')
            ->orderBy('sort', 'asc')
            ->get()
            ->groupBy('category_id');
        return view('information.core', compact('cores', 'core', 'informations'));
    }
}
