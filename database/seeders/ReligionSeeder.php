<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Religion::count()) Religion::truncate();

        $datas = [
            ['name' =>'Islam', 'sort' => 1],
            ['name' =>'Katolik', 'sort' => 2],
            ['name' =>'Kristen', 'sort' => 3],
            ['name' =>'Hindu', 'sort' => 4],
            ['name' =>'Budha', 'sort' => 5],
            ['name' =>'Kepercayaan Lainnya', 'sort' => 6],
            ['name' =>'Konghucu', 'sort' => 7],
        ];
        
        foreach ($datas as $data) {
            Religion::create($data);
        }
    }
}
