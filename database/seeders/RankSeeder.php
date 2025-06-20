<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Rank::count()) Rank::truncate();

        $datas = [
            ['id'=> 1, 'code' =>'1a', 'name' => 'Ia', 'label' => 'Juru Muda'],
            ['id'=> 2, 'code' =>'1b', 'name' => 'Ib', 'label' => 'Juru Muda Tk. I'],
            ['id'=> 3, 'code' =>'1c', 'name' => 'Ic', 'label' => 'Juru'],
            ['id'=> 4, 'code' =>'1d', 'name' => 'Id', 'label' => 'Juru Tk. I'],
            ['id'=> 5, 'code' =>'2a', 'name' => 'IIa', 'label' => 'Pengatur Muda'],
            ['id'=> 6, 'code' =>'2b', 'name' => 'IIb', 'label' => 'Pengatur Muda Tk. I'],
            ['id'=> 7, 'code' =>'2c', 'name' => 'IIc', 'label' => 'Pengatur'],
            ['id'=> 8, 'code' =>'2d', 'name' => 'IId', 'label' => 'Pengatur Tk. I'],
            ['id'=> 9, 'code' =>'3a', 'name' => 'IIIa', 'label' => 'Penata Muda'],
            ['id'=> 10, 'code' =>'3b', 'name' => 'IIIb', 'label' => 'Penata Muda Tk. I'],
            ['id'=> 11, 'code' =>'3c', 'name' => 'IIIc', 'label' => 'Penata'],
            ['id'=> 12, 'code' =>'3d', 'name' => 'IIId', 'label' => 'Penata Tk. I'],
            ['id'=> 13, 'code' =>'4a', 'name' => 'IVa', 'label' => 'Pembina'],
            ['id'=> 14, 'code' =>'4b', 'name' => 'IVb', 'label' => 'Pembina Tk. I'],
            ['id'=> 15, 'code' =>'4c', 'name' => 'IVc', 'label' => 'Pembina Utama Muda'],
            ['id'=> 16, 'code' =>'4d', 'name' => 'IVd', 'label' => 'Pembina Utama Madya'],
            ['id'=> 17, 'code' =>'4e', 'name' => 'IVe', 'label' => 'Pembina Utama'],
            ['id'=> 18, 'code' => '1', 'name' => 'I', 'label' => 'Golongan I'],
            ['id'=> 19, 'code' => '2', 'name' => 'II', 'label' => 'Golongan II'],
            ['id'=> 20, 'code' => '3', 'name' => 'III', 'label' => 'Golongan III'],
            ['id'=> 21, 'code' => '4', 'name' => 'IV', 'label' => 'Golongan IV'],
            ['id'=> 22, 'code' => '5', 'name' => 'V', 'label' => 'Golongan V'],
            ['id'=> 23, 'code' => '6', 'name' => 'VI', 'label' => 'Golongan VI'],
            ['id'=> 24, 'code' => '7', 'name' => 'VII', 'label' => 'Golongan VII'],
            ['id'=> 25, 'code' => '8', 'name' => 'VIII', 'label' => 'Golongan VIII'],
            ['id'=> 26, 'code' => '9', 'name' => 'IX', 'label' => 'Golongan IX'],
            ['id'=> 27, 'code' => '10', 'name' => 'X', 'label' => 'Golongan X'],
            ['id'=> 28, 'code' => '11', 'name' => 'XI', 'label' => 'Golongan XI'],
            ['id'=> 29, 'code' => '12', 'name' => 'XII', 'label' => 'Golongan XII'],
            ['id'=> 30, 'code' => '13', 'name' => 'XIII', 'label' => 'Golongan XIII'],
            ['id'=> 31, 'code' => '14', 'name' => 'XIV', 'label' => 'Golongan XIV'],
            ['id'=> 32, 'code' => '15', 'name' => 'XV', 'label' => 'Golongan XV'],
            ['id'=> 33, 'code' => '16', 'name' => 'XVI', 'label' => 'Golongan XVI'],
            ['id'=> 34, 'code' => '17', 'name' => 'XVII', 'label' => 'Golongan XVII']
        ];

        foreach ($datas as $data) {
            Rank::create($data);
        }
    }
}
