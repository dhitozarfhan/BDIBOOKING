<?php

namespace Database\Seeders;

use App\Enums\CategoryType as EnumsCategoryType;
use App\Models\Category;
use App\Models\CategoryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(CategoryType::count()){
            CategoryType::truncate();
        }

        $datas = [
            ['id' => EnumsCategoryType::News->value, 'name' => 'Berita', 'slug' => 'news'],
            ['id' => EnumsCategoryType::Blog->value, 'name' => 'Blog', 'slug' => 'blog'],
            ['id' => EnumsCategoryType::Gallery->value, 'name' => 'Galeri', 'slug' => 'gallery'],
            ['id' => EnumsCategoryType::Event->value, 'name' => 'Acara', 'slug' => 'event'],
            ['id' => EnumsCategoryType::Information->value, 'name' => 'Informasi', 'slug' => 'information'],
            ['id' => EnumsCategoryType::Question->value, 'name' => 'Pertanyaan Masyarakat', 'slug' => 'question'],
            ['id' => EnumsCategoryType::Request->value, 'name' => 'PPID', 'slug' => 'request'],
            // ['id' => EnumsCategoryType::Wbs->value, 'name' => 'WBS', 'slug' => 'wbs'],
            ['id' => EnumsCategoryType::Service->value, 'name' => 'Layanan', 'slug' => 'service'],
        ];
        
        CategoryType::insert($datas);
    }
}
