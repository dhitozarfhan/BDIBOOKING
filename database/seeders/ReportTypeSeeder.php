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
        // Mengosongkan tabel, sama seperti di contoh
        ReportType::truncate();

        // Membuat array data dengan struktur ['id' => ..., 'name' => ...], sama seperti di contoh
        $datas = [
            ['id' => EnumsReportType::GRATIFICATION->value, 'name' => 'GRATIFICATION'],
            ['id' => EnumsReportType::WBS->value, 'name' => 'WBS'],
            ['id' => EnumsReportType::PUBLIC_COMPLAINT->value, 'name' => 'PUBLIC_COMPLAINT'],
            ['id' => EnumsReportType::INFORMATION_REQUEST->value, 'name' => 'INFORMATION_REQUEST'],
        ];

        // Memasukkan data ke tabel, sama seperti di contoh
        ReportType::insert($datas);
    }
}