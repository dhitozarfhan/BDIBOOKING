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
                    'description' => $slideshow->getTranslation('description', $locale),
                    'image' => $slideshow->image ? asset('storage/' . $slideshow->image) : null,
                    'image_url' => $slideshow->getSlideImage(), // Using the newly added method
                    'link' => $slideshow->link,
                    'path' => $slideshow->path,
                    'target_blank' => $slideshow->target_blank,
                    'order' => $slideshow->sort,
                    'link_type_id' => $slideshow->link_type_id,
                    'article_id' => $slideshow->article_id,
                ];
            }),
        ]);
    }
}
