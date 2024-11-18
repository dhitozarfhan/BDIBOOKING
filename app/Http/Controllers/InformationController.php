<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Support\Str;

class InformationController extends Controller
{
    public function show ($id, $slug)
    {
        $post = Information::where('information_id', $id)->firstOrFail();
        $generatedSlug = Str::slug($post->id_title);

        if ($generatedSlug !== $slug){
            return redirect()->route('information.post', ['id' => $id, 'slug' => $generatedSlug]);
        }

        return view('information.post', compact('post'));
    }
}
