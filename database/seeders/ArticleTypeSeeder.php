<?php

namespace Database\Seeders;

use App\Enums\ArticleType as EnumsArticleType;
use App\Models\ArticleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(ArticleType::count()){
            ArticleType::truncate();
        }

        $datas = [
            ['id' => EnumsArticleType::News->value, 'name' => 'Berita', 'slug' => 'news'],
            ['id' => EnumsArticleType::Blog->value, 'name' => 'Blog', 'slug' => 'blog'],
            ['id' => EnumsArticleType::Gallery->value, 'name' => 'Galeri', 'slug' => 'gallery'],
            ['id' => EnumsArticleType::Page->value, 'name' => 'Laman', 'slug' => 'page'],
            ['id' => EnumsArticleType::Information->value, 'name' => 'Informasi', 'slug' => 'information']
        ];
        
        ArticleType::insert($datas);
    }
}
