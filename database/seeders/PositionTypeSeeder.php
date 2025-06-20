<?php

namespace Database\Seeders;

use App\Models\PositionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (PositionType::count()) PositionType::truncate();

        $datas = [
            ['name' => 'Pejabat Struktural'],
            ['name' => 'Pejabat Fungsional'],
            ['name' => 'Pelaksana'],
        ];

        foreach ($datas as $data) {
            PositionType::create($data);
        }
    }
}
