<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleType;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Get all articles with filters
     */
    public function index(Request $request)
    {
        $query = Article::with(['category', 'author', 'articleType'])
            ->published()
            ->orderBy('published_at', 'desc');

        // Filter by article type
        if ($request->has('type')) {
            $articleType = ArticleType::where('slug', $request->type)->first();
            if ($articleType) {
                $query->where('article_type_id', $articleType->id);
            }
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by title
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title->id', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $articles = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $articles->currentPage(),
                'data' => $articles->items(),
                'total' => $articles->total(),
                'per_page' => $articles->perPage(),
                'last_page' => $articles->lastPage(),
            ],
        ]);
    }

    /**
     * Get article detail
     */
    public function show($id)
    {
        $article = Article::with(['category', 'author', 'tags', 'images', 'articleType'])
            ->published()
            ->findOrFail($id);

        // Increment hit counter
        $article->incrementHit();

        $locale = app()->getLocale();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $article->id,
                'title' => $article->getTranslation('title', $locale),
                'slug' => $article->slug,
                'summary' => $article->getTranslation('summary', $locale),
                'content' => $article->getTranslation('content', $locale),
                'image' => $article->image ? asset('storage/' . $article->image) : null,
                'category' => $article->category ? [
                    'id' => $article->category->id,
                    'name' => $article->category->getTranslation('name', $locale),
                ] : null,
                'author' => $article->author ? [
                    'id' => $article->author->id,
                    'name' => $article->author->name,
                    'image' => $article->author->image ? asset('storage/' . $article->author->image) : null,
                ] : null,
                'tags' => $article->tags->map(function($tag) use ($locale) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->getTranslation('name', $locale),
                    ];
                }),
                'images' => $article->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->path),
                        'caption' => $image->caption,
                    ];
                }),
                'published_at' => $article->published_at,
                'hit' => $article->hit,
            ],
        ]);
    }

    /**
     * Get article by slug
     */
    public function showBySlug($slug)
    {
        $id = Article::idFromSlug($slug);
        
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found',
            ], 404);
        }

        return $this->show($id);
    }
}
