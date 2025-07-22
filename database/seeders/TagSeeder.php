<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Services\Ai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Tag::count()){
            Tag::truncate();
        }

        $id = 1;

        $datas = DB::connection('second_db')->table('tag')
        ->whereIn('type', ['news', 'gallery', 'blog'])
        ->groupBy('id_name')->orderBy('tag_id')->get();

        foreach ($datas as $data) {
            Tag::create([
                'id' => $id++,
                'name' => [
                    'id' => $data->id_name,
                    'en' => $data->en_name,
                    // 'en' => empty($data->en_name) ? $ai->translateToEnglish($data->id_name) : $data->en_name
                ],
                'is_active' => $data->is_active == 'Y',
            ]);
        }

        $pdo = DB::getPdo();
        $pdo->beginTransaction();
        $statement = $pdo->prepare("SELECT setval('tags_id_seq', (SELECT MAX(id) FROM tags))");
        $statement->execute();
        $pdo->commit();
    }
}
