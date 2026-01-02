<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slideshow;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    /**
     * Get active slideshows
     */
    public function index()
    {
        $slideshows = Slideshow::where('is_active', true)
            ->orderBy('sort')
            ->get();

        $locale = app()->getLocale();

        return response()->json([
            'success' => true,
            'data' => $slideshows->map(function($slideshow) use ($locale) {
                return [
                    'id' => $slideshow->id,
                    'title' => $slideshow->getTranslation('title', $locale),
                    'image' => $slideshow->image ? asset('storage/' . $slideshow->image) : null,
                    'link' => $slideshow->link,
                    'order' => $slideshow->sort,
                ];
            }),
        ]);
    }
}
