<?php

namespace Database\Seeders;

use App\Enums\EmployeeStatusCode;
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

            ['code' => EmployeeStatusCode::CPNS->value, 'description' => 'CPNS'],
            ['code' => EmployeeStatusCode::PNS->value, 'description' => 'PNS'],
            ['code' => EmployeeStatusCode::Pensiun->value, 'description' => 'Pensiun'],
            ['code' => EmployeeStatusCode::PPPK->value, 'description' => 'PPPK'],
            ['code' => EmployeeStatusCode::NonPNS->value, 'description' => 'Tenaga Kontrak/PPNPN']
        ];

        foreach ($datas as $data) {
            EmployeeStatus::create($data);
        }
    }
}
