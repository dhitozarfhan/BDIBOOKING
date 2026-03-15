<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Employee;
use App\Enums\ArticleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalArticleRecoverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Articles are stored in public/article/images
        $disk = 'public';
        $directory = 'article/images';
        
        $files = Storage::disk($disk)->files($directory);
        
        if (empty($files)) {
            $this->command->warn("No files found in storage:disk('public')->files('$directory')");
            return;
        }

        $this->command->info("Found " . count($files) . " images. Restoring articles...");

        $admin = Employee::where('username', 'admin')->first();
        if (!$admin) {
            $admin = Employee::first();
        }

        // Ensure we have at least one Category for Articles
        $uncategorized = Category::where('category_type_id', \App\Enums\CategoryType::Article->value)->first();
        if (!$uncategorized) {
            $uncategorized = Category::create([
                'category_type_id' => \App\Enums\CategoryType::Article->value,
                'name' => [
                    'id' => 'Umum',
                    'en' => 'General'
                ],
                'is_active' => true,
                'is_root' => true,
                'sort' => 1
            ]);
        }

        foreach ($files as $file) {
            $filename = basename($file);
            
            // Skip hidden files
            if (str_starts_with($filename, '.')) continue;

            // Generate a readable title from filename
            $rawName = pathinfo($filename, PATHINFO_FILENAME);
            $cleanName = str_replace(['-', '_', '.'], ' ', $rawName);
            $title = ucwords($cleanName);

            // Check if article already exists for this image
            if (Article::where('image', 'article/images/' . $filename)->exists()) {
                continue;
            }

            Article::create([
                'article_type_id' => ArticleType::News->value,
                'category_id' => $uncategorized ? $uncategorized->id : null,
                'author_id' => $admin ? $admin->id : null,
                'image' => 'article/images/' . $filename,
                'title' => [
                    'id' => $title,
                    'en' => $title
                ],
                'summary' => [
                    'id' => 'Auto-restored article for ' . $filename,
                    'en' => 'Auto-restored article for ' . $filename
                ],
                'content' => [
                    'id' => '<p>This article was automatically restored from existing image assets.</p>',
                    'en' => '<p>This article was automatically restored from existing image assets.</p>'
                ],
                'is_active' => true,
                'published_at' => now(),
                'hit' => rand(10, 100),
            ]);
        }

        $this->command->info("Restoration complete.");
    }
}
