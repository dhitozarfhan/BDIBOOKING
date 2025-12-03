<?php

namespace Database\Seeders;

use App\Enums\ReportType as EnumsReportType;
use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReportType::truncate();

        $datas = [];
        foreach (EnumsReportType::cases() as $case) {
            $datas[] = ['name' => $case->name];
        }

        ReportType::insert($datas);
    }
}