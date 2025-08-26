<?php

namespace Database\Seeders;

use App\Enums\ArticleType;
use App\Models\Article;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Image;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Article::truncate();
        Image::truncate();

        // truncate article_tag table
        DB::table('article_tag')->truncate();

        $id = 1;

        $uncategorizedId = 5;

        //seeder for news
        $datas = DB::connection('second_db')->table('news')->orderBy('news_id')->get();
        
        foreach ($datas as $data) {
            $article = [
                'id' => $id++,
                'article_type_id' => ArticleType::News->value,
                'category_id' => Category::where('name->id', DB::connection('second_db')->table('category')->where('category_id', $data->category_id)->where('type', 'news')->pluck('id_name')->first())->pluck('id')->first() ?? $uncategorizedId,
                'author_id' => Employee::where('username', $data->admin_id)->pluck('id')->first() ?? null,
                'image' => null,
                'title' => [
                    'en' => $data->en_title,
                    'id' => $data->id_title
                ],
                'summary' => [
                    'en' => $data->en_summary,
                    'id' => $data->id_summary
                ],
                'content' => [
                    'en' => $data->en_content,
                    'id' => $data->id_content
                ],
                'hit' => $data->hit,
                'is_active' => $data->is_active == 'Y',
                'published_at' => $data->time_stamp,
            ];
            $url = env('URL_BEFORE').'filebox/news/'.$data->image;
            $contents = @file_get_contents($url);
            if($contents !== false && $data->image){
                Storage::put($article['image'] = config('services.disk.article.image').'/'.$data->image, $contents);
            }
            Article::create($article);

            //insert article_tag table from news_tag table
            $newsTags = DB::connection('second_db')->table('news_tag')->where('news_id', $data->news_id)->get();
            foreach ($newsTags as $newsTag) {
                if($tagId = Tag::where('name->id', (DB::connection('second_db')->table('tag')->where('tag_id', $newsTag->tag_id)->pluck('id_name')->first()))->pluck('id')->first()){
                    DB::table('article_tag')->insert([
                        'article_id' => $article['id'],
                        'tag_id' => $tagId
                    ]);
                }
            }
        }

        //seeder for blog

        $datas = DB::connection('second_db')->table('blog')->orderBy('blog_id')->get();

        foreach ($datas as $data) {
            $article = [
                'id' => $id++,
                'article_type_id' => ArticleType::News->value,
                'category_id' => Category::where('name->id', DB::connection('second_db')->table('category')->where('category_id', $data->category_id)->where('type', 'blog')->pluck('id_name')->first())->pluck('id')->first() ?? $uncategorizedId,
                'author_id' => Employee::where('username', $data->admin_id)->pluck('id')->first() ?? null,
                'image' => null,
                'title' => [
                    'en' => $data->en_title,
                    'id' => $data->id_title
                ],
                'summary' => [
                    'en' => $data->en_summary,
                    'id' => $data->id_summary
                ],
                'content' => [
                    'en' => $data->en_content,
                    'id' => $data->id_content
                ],
                'hit' => $data->hit,
                'is_active' => $data->is_active == 'Y',
                'published_at' => $data->time_stamp,
            ];
            $url = env('URL_BEFORE').'filebox/blog/'.$data->image;
            $contents = @file_get_contents($url);
            if($contents !== false && $data->image){
                Storage::put($article['image'] = config('services.disk.article.image').'/'.$data->image, $contents);
            }
            Article::create($article);

            //insert article_tag table from blog_tag table
            $blogTags = DB::connection('second_db')->table('blog_tag')->where('blog_id', $data->blog_id)->get();
            foreach ($blogTags as $blogTag) {
                if($tagId = Tag::where('name->id', (DB::connection('second_db')->table('tag')->where('tag_id', $blogTag->tag_id)->pluck('id_name')->first()))->pluck('id')->first()){
                    DB::table('article_tag')->insert([
                        'article_id' => $article['id'],
                        'tag_id' => $tagId
                    ]);
                }
            }
        }

        //seeder for gallery
        $datas = DB::connection('second_db')->table('gallery')->orderBy('gallery_id')->get();

        foreach ($datas as $data) {
            $article = [
                'id' => $id++,
                'article_type_id' => ArticleType::Gallery->value,
                'category_id' => Category::where('name->id', DB::connection('second_db')->table('category')->where('category_id', $data->category_id)->where('type', 'gallery')->pluck('id_name')->first())->pluck('id')->first() ?? $uncategorizedId,
                'author_id' => Employee::where('username', $data->admin_id)->pluck('id')->first() ?? null,
                'image' => null,
                'title' => [
                    'en' => $data->en_title,
                    'id' => $data->id_title
                ],
                'summary' => [
                    'en' => $data->en_description,
                    'id' => $data->id_description
                ],
                'content' => [
                    'en' => $data->en_description,
                    'id' => $data->id_description
                ],
                'hit' => $data->hit,
                'is_active' => $data->is_active == 'Y',
                'published_at' => $data->time_stamp,
            ];

            $slide = DB::connection('second_db')->table('slide')->where('type', 'gallery')->where('type_id', $data->gallery_id)->first();

            $url = env('URL_BEFORE').'filebox/gallery/'.$slide->image;
            $contents = @file_get_contents($url);
            if($contents !== false && $slide->image){
                Storage::put($article['image'] = config('services.disk.article.image').'/'.$slide->image, $contents);
            }

            Article::create($article);

            //insert article_tag table from gallery_tag table
            $galleryTags = DB::connection('second_db')->table('gallery_tag')->where('gallery_id', $data->gallery_id)->get();
            foreach ($galleryTags as $galleryTag) {
                if($tagId = Tag::where('name->id', (DB::connection('second_db')->table('tag')->where('tag_id', $galleryTag->tag_id)->pluck('id_name')->first()))->pluck('id')->first()){
                    DB::table('article_tag')->insert([
                        'article_id' => $article['id'],
                        'tag_id' => $tagId
                    ]);
                }
            }

            // insert image from slide table
            $galleryImages = DB::connection('second_db')->table('slide')->where('type', 'gallery')->where('type_id', $data->gallery_id)->get();
            foreach ($galleryImages as $k => $galleryImage) {
                $imageUrl = env('URL_BEFORE').'filebox/gallery/'.$galleryImage->image;
                $imageContents = @file_get_contents($imageUrl);
                if($imageContents !== false && $galleryImage->image){
                    Storage::put(config('services.disk.article.slide').'/'.$galleryImage->image, $imageContents);
                    Image::create([
                        'article_id' => $article['id'],
                        'path' => config('services.disk.article.slide').'/'.$galleryImage->image,
                        'description' => [
                            'en' => $galleryImage->en_description,
                            'id' => $galleryImage->id_description
                        ],
                        // 'sort' => $k + 1
                    ]);
                }
            }
        }

        //seeder for page
        $datas = DB::connection('second_db')->table('page')->orderBy('page_id')->get();

        foreach ($datas as $data) {
            $article = [
                'id' => $id++,
                'article_type_id' => ArticleType::Page->value,
                'category_id' => null,
                'author_id' => Employee::where('username', $data->admin_id)->pluck('id')->first() ?? null,
                'image' => null,
                'title' => [
                    'en' => $data->en_title,
                    'id' => $data->id_title
                ],
                'summary' => [
                    'en' => $data->en_summary,
                    'id' => $data->id_summary
                ],
                'content' => [
                    'en' => $data->en_content,
                    'id' => $data->id_content
                ],
                'hit' => $data->hit,
                'is_active' => $data->is_active == 'Y',
                'published_at' => $data->time_stamp,
            ];

            Article::create($article);
        }

        //seeder for information

        $datas = DB::connection('second_db')->table('information')->selectRaw('information.*, category.core_id')->join('category', 'category.category_id', '=', 'information.category_id')->orderBy('information_id')->get();

        foreach ($datas as $data) {
            $article = [
                'id' => $id++,
                'article_type_id' => ArticleType::Information->value,
                'category_id' => Category::where('name->id', DB::connection('second_db')->table('category')->where('category_id', $data->category_id)->pluck('id_name')->first())->where('parent_id', $data->core_id * -1)->pluck('id')->first() ?? $data->core_id,
                'author_id' => null,
                'image' => null,
                'title' => [
                    'en' => $data->en_title,
                    'id' => $data->id_title
                ],
                'summary' => [
                    'en' => $data->en_summary,
                    'id' => $data->id_summary
                ],
                'content' => [
                    'en' => $data->en_content,
                    'id' => $data->id_content
                ],
                'hit' => 0,
                'is_active' => $data->is_active == 'Y',
                'published_at' => $data->time_stamp,
                'sort' => $data->sort,
                'year' => $data->year,
            ];
            $file = $data->file;
            if(!empty($file)){
                $article['files'] = [];
                $origs = [];
                // ($file);
                $url = env('URL_BEFORE').'filebox/information/'.$file;
                $contents = @file_get_contents($url);
                if($contents !== false){
                    Storage::put($file_path = config('services.disk.article.file').'/'.$file, $contents);
                    $article['files'][] = $file_path;
                    $origs[] = [
                        $file_path  => $file
                    ];
                }
                $article['original_files'] = json_encode($origs);
            }
            Article::create($article);
        }

        $pdo = DB::getPdo();
        $pdo->beginTransaction();
        $statement = $pdo->prepare("SELECT setval('articles_id_seq', (SELECT MAX(id) FROM articles))");
        $statement->execute();
        $pdo->commit();
    }
}
