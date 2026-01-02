<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryType;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index(Request $request)
    {
        $query = Category::withCount('articles');

        // Filter by category type
        if ($request->has('type')) {
            $categoryType = CategoryType::where('name', $request->type)->first();
            if ($categoryType) {
                $query->where('category_type_id', $categoryType->id);
            }
        }

        $categories = $query->where('is_active', true)
            ->orderBy('sort')
            ->get();

        $locale = app()->getLocale();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function($category) use ($locale) {
                return [
                    'id' => $category->id,
                    'name' => $category->getTranslation('name', $locale),
                    'slug' => $category->slug,
                    'articles_count' => $category->articles_count,
                ];
            }),
        ]);
    }

    /**
     * Get category detail
     */
    public function show($id)
    {
        $category = Category::withCount('articles')->findOrFail($id);
        $locale = app()->getLocale();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => $category->getTranslation('name', $locale),
                'slug' => $category->slug,
                'description' => $category->getTranslation('description', $locale),
                'articles_count' => $category->articles_count,
            ],
        ]);
    }
}
