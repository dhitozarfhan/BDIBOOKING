<?php

namespace Database\Seeders;

use App\Enums\ArticleType;
use App\Enums\CategoryType;
use App\Models\Article;
use App\Models\ArticleType as ArticleTypeModel;
use App\Models\Category;
use App\Models\Navigation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        Article::truncate();
        Category::truncate();
        Navigation::truncate();
        ArticleTypeModel::truncate();
        DB::table('category_types')->truncate();
        DB::table('navigation_types')->truncate();
        DB::table('link_types')->truncate();
        DB::table('navigations')->truncate();
        
        // Buat Article Types
        ArticleTypeModel::insert([
            ['id' => ArticleType::News->value, 'name' => 'News', 'slug' => 'news', 'created_at' => now(), 'updated_at' => now()],
            ['id' => ArticleType::Gallery->value, 'name' => 'Gallery', 'slug' => 'gallery', 'created_at' => now(), 'updated_at' => now()],
            ['id' => ArticleType::Page->value, 'name' => 'Page', 'slug' => 'page', 'created_at' => now(), 'updated_at' => now()],
            ['id' => ArticleType::Information->value, 'name' => 'Information', 'slug' => 'information', 'created_at' => now(), 'updated_at' => now()]
        ]);
        
        // Buat Category Types
        DB::table('category_types')->insert([
            ['id' => CategoryType::Article->value, 'name' => 'Article'],
            ['id' => CategoryType::Information->value, 'name' => 'Information'],
            ['id' => CategoryType::InformationType->value, 'name' => 'Information Type'],
        ]);
        
        // Buat beberapa kategori
        Category::create([
            'id' => 1,
            'category_type_id' => CategoryType::Article->value,
            'parent_id' => null,
            'name' => ['en' => 'News', 'id' => 'Berita'],
            'sort' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Category::create([
            'id' => 2,
            'category_type_id' => CategoryType::Article->value,
            'parent_id' => null,
            'name' => ['en' => 'Gallery', 'id' => 'Galeri'],
            'sort' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Category::create([
            'id' => 3,
            'category_type_id' => CategoryType::Information->value,
            'parent_id' => null,
            'name' => ['en' => 'Information', 'id' => 'Informasi'],
            'sort' => 3,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Buat beberapa artikel contoh
        Article::create([
            'id' => 1,
            'title' => ['en' => 'Sample News', 'id' => 'Berita Contoh'],
            'summary' => ['en' => 'This is a sample news article', 'id' => 'Ini adalah contoh artikel berita'],
            'content' => ['en' => 'This is the full content of the sample news article', 'id' => 'Ini adalah konten lengkap dari contoh artikel berita'],
            'article_type_id' => ArticleType::News->value,
            'category_id' => 1,
            'author_id' => 1, // Default author
            'is_active' => true,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Article::create([
            'id' => 2,
            'title' => ['en' => 'Sample Gallery', 'id' => 'Galeri Contoh'],
            'summary' => ['en' => 'This is a sample gallery', 'id' => 'Ini adalah contoh galeri'],
            'content' => ['en' => 'This is the full content of the sample gallery', 'id' => 'Ini adalah konten lengkap dari contoh galeri'],
            'article_type_id' => ArticleType::Gallery->value,
            'category_id' => 2,
            'author_id' => 1, // Default author
            'is_active' => true,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Article::create([
            'id' => 3,
            'title' => ['en' => 'Sample Information', 'id' => 'Informasi Contoh'],
            'summary' => ['en' => 'This is a sample information page', 'id' => 'Ini adalah contoh halaman informasi'],
            'content' => ['en' => 'This is the full content of the sample information page', 'id' => 'Ini adalah konten lengkap dari contoh halaman informasi'],
            'article_type_id' => ArticleType::Information->value,
            'category_id' => 3,
            'author_id' => 1, // Default author
            'is_active' => true,
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Buat beberapa navigasi dasar
        DB::table('navigation_types')->insert([
            ['id' => 1, 'name' => 'Main Menu', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Footer Menu', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        DB::table('link_types')->insert([
            ['id' => 1, 'name' => 'Internal Link', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'External Link', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Article Link', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Empty', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Buat navigasi menggunakan metode tree karena ada field array dan nested set
        Navigation::insert([
            [
                'id' => 1,
                'parent_id' => null,
                'navigation_type_id' => 1,
                'link_type_id' => 4, // Empty
                'name' => ['en' => 'Home', 'id' => 'Beranda'],
                'path' => '/',
                'sort' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                '_lft' => 1,
                '_rgt' => 2,
                'depth' => 0
            ],
            [
                'id' => 2,
                'parent_id' => null,
                'navigation_type_id' => 1,
                'link_type_id' => 4, // Empty
                'name' => ['en' => 'News', 'id' => 'Berita'],
                'path' => '/news',
                'sort' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                '_lft' => 3,
                '_rgt' => 4,
                'depth' => 0
            ],
            [
                'id' => 3,
                'parent_id' => null,
                'navigation_type_id' => 1,
                'link_type_id' => 4, // Empty
                'name' => ['en' => 'Gallery', 'id' => 'Galeri'],
                'path' => '/gallery',
                'sort' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                '_lft' => 5,
                '_rgt' => 6,
                'depth' => 0
            ],
            [
                'id' => 4,
                'parent_id' => null,
                'navigation_type_id' => 1,
                'link_type_id' => 4, // Empty
                'name' => ['en' => 'Information', 'id' => 'Informasi'],
                'path' => '/information',
                'sort' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                '_lft' => 7,
                '_rgt' => 8,
                'depth' => 0
            ]
        ]);
    }
}