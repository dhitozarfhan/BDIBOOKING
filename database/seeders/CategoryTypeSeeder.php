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
            ['id' => EnumsCategoryType::News->value, 'name' => 'Berita'],
            ['id' => EnumsCategoryType::Blog->value, 'name' => 'Blog'],
            ['id' => EnumsCategoryType::Gallery->value, 'name' => 'Galeri'],
            ['id' => EnumsCategoryType::Event->value, 'name' => 'Acara'],
            ['id' => EnumsCategoryType::Information->value, 'name' => 'Informasi'],
            ['id' => EnumsCategoryType::Question->value, 'name' => 'Pertanyaan Masyarakat'],
            ['id' => EnumsCategoryType::Request->value, 'name' => 'PPID'],
            ['id' => EnumsCategoryType::Wbs->value, 'name' => 'WBS'],
            ['id' => EnumsCategoryType::Service->value, 'name' => 'Layanan'],
        ];
        
        CategoryType::insert($datas);
    }
}
