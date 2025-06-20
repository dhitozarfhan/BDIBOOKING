<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Education::count()) Education::truncate();

        $datas = [
            ['name' =>'SD', 'description' => 'SD', 'intranet_id' => 1, 'bnsp_id' => 1, 'grade' => 1],
            ['name' =>'SMP', 'description' => 'SMP', 'intranet_id' => 2, 'bnsp_id' => 2, 'grade' => 2],
            ['name' =>'SMA', 'description' => 'SMA / Sederajat', 'intranet_id' => 3, 'bnsp_id' => 3, 'grade' => 3],
            ['name' =>'SMK', 'description' => 'SMK', 'intranet_id' => 3, 'bnsp_id' => 3, 'grade' => 3],
            ['name' =>'D1', 'description' => 'Diploma 1', 'intranet_id' => 8, 'bnsp_id' => 10, 'grade' => 4],
            ['name' =>'D2', 'description' => 'Diploma 2', 'intranet_id' => 9, 'bnsp_id' => 4, 'grade' => 5],
            ['name' =>'D3', 'description' => 'Diploma 3', 'intranet_id' => 4, 'bnsp_id' => 5, 'grade' => 6],
            ['name' =>'D4', 'description' => 'Diploma 4', 'intranet_id' => 5, 'bnsp_id' => 6, 'grade' => 7],
            ['name' =>'S1', 'description' => 'Sarjana', 'intranet_id' => 5, 'bnsp_id' => 7, 'grade' => 7],
            ['name' =>'S2', 'description' => 'Pasca Sarjana', 'intranet_id' => 6, 'bnsp_id' => 8, 'grade' => 8],
            ['name' =>'S3', 'description' => 'Doktoral', 'intranet_id' => 7, 'bnsp_id' => 9, 'grade' => 9],
        ];

        foreach ($datas as $data) {
            Education::create($data);
        }
    }
}
