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
        Category::truncate();

        $id = 1;
        
        $datas = DB::connection('second_db')->table('core')->orderBy('sort')->get();

        foreach ($datas as $data) {
            Category::create([
                'id' => $data->core_id * -1,
                'category_type_id' => CategoryType::InformationType->value,
                'parent_id' => null,
                'name' => [
                    'en' => $data->en_name,
                    'id' => $data->id_name,
                ],
                'sort' => $data->sort,
                'is_root' => true,
                'is_active' => true
            ]);

            Category::create([
                'id' => $id,
                'category_type_id' => CategoryType::Information->value,
                'parent_id' => $id * -1,
                'name' => [
                    'en' => 'Uncategorized',
                    'id' => 'Belum Terkategorikan',
                ],
                'sort' => 1,
                'is_root' => true,
                'is_active' => true
            ]);
            $id++;
        }

        $datas = DB::connection('second_db')->table('category')
        ->whereIn('type', ['news', 'gallery', 'blog', 'information'])
        ->groupBy('id_name')->orderBy('category_id')->get();

        foreach ($datas as $data) {
            if($data->type == 'information' && $data->id_name == 'Belum Terkategorikan') {
                continue;
            }
            Category::create([
                'id' => $id++,
                'category_type_id' => $data->type == 'information' ? CategoryType::Information->value : CategoryType::Article->value,
                'parent_id' => $data->type == 'information' ? $data->core_id * -1 : null,
                'name' => [
                    'en' => $data->en_name,
                    'id' => $data->id_name,
                ],
                'sort' => $data->sort,
                'is_root' => $data->is_root == 'Y',
                'is_active' => $data->is_active == 'Y',
            ]);
        }

        $pdo = DB::getPdo();
        $pdo->beginTransaction();
        $statement = $pdo->prepare("SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories))");
        $statement->execute();
        $pdo->commit();

    }
}
