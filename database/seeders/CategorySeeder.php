<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Category::count()){
            Category::truncate();
        }

        $datas = DB::connection('second_db')->table('category')->orderBy('category_id')->get();

        foreach ($datas as $data) {
            Category::create([
                'id' => $data->category_id,
                'core_id' => $data->core_id,
                'category_type_id' => CategoryType::{ucfirst($data->type)}->value,
                'name' => [
                    'en' => $data->en_name,
                    'id' => $data->id_name,
                ],
                'sort' => $data->sort,
                'is_root' => $data->is_root,
                'is_active' => $data->is_active,
            ]);
        }

        $pdo = DB::getPdo();
        $pdo->beginTransaction();
        $statement = $pdo->prepare("SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories))");
        $statement->execute();
        $pdo->commit();

    }
}
