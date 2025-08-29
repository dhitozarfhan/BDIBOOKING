<?php

namespace Database\Seeders;

use App\Enums\EmployeeStatus as Status;
use App\Models\EmployeeStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        if (EmployeeStatus::count()) EmployeeStatus::truncate();

        $datas = [

            ['id' => Status::CPNS->value, 'description' => 'CPNS'],
            ['id' => Status::PNS->value, 'description' => 'PNS'],
            ['id' => Status::Pensiun->value, 'description' => 'Pensiun'],
            ['id' => Status::PPPK->value, 'description' => 'PPPK'],
            ['id' => Status::Mutation->value, 'description' => 'Mutasi'],
            ['id' => Status::NonPNS->value, 'description' => 'Tenaga Kontrak/PPNPN']
        ];

        foreach ($datas as $data) {
            EmployeeStatus::create($data);
        }
    }
}
