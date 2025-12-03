<?php

namespace Database\Seeders;

use App\Enums\ReportType;
use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (ReportType::count()) {
            ReportType::truncate();
        }

        foreach (ReportType::cases() as $type) {
            ReportType::create(['name' => $type->value]);
        }
    }
}
