<?php
$article = \App\Models\Article::whereNotNull('image')->latest('id')->first();
if ($article) {
    echo "Image path: " . $article->image . "\n";
    echo "Storage::exists() is: " . (\Illuminate\Support\Facades\Storage::exists($article->image) ? "TRUE" : "FALSE") . "\n";
    echo "Storage::disk('public')->exists() is: " . (\Illuminate\Support\Facades\Storage::disk('public')->exists($article->image) ? "TRUE" : "FALSE") . "\n";
} else {
    echo "No articles with image found.\n";
}
