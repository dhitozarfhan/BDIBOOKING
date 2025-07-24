<?php

namespace Database\Seeders;

use App\Enums\CategoryType as EnumsCategoryType;
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
        CategoryType::truncate();

        $datas = [
            ['id' => EnumsCategoryType::Article->value, 'name' => 'Artikel'],
            ['id' => EnumsCategoryType::InformationType->value, 'name' => 'Jenis Informasi'],
            ['id' => EnumsCategoryType::Information->value, 'name' => 'Informasi'],
        ];

        CategoryType::insert($datas);
    }
}
