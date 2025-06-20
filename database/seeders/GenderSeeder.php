<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        if (Gender::count()) Gender::truncate();

        $datas = [
            ['code' => 'L', 'type' => 'Laki-laki', 'image' => 'images/user/man.jpg'],
            ['code' => 'P', 'type' => 'Perempuan', 'image' => 'images/user/woman.jpg'],
        ];

        foreach ($datas as $data) {
            Gender::create($data);
        }
    }
}
