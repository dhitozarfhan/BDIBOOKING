<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        City::truncate();
        Schema::enableForeignKeyConstraints();

        $jsonPath = database_path('data/cities.json');
        $cities = json_decode(file_get_contents($jsonPath), true);

        // Build province code -> id map
        $provinceMap = Province::pluck('id', 'code')->toArray();

        $inserted = 0;
        foreach ($cities as $city) {
            $provinceId = $provinceMap[$city['province_id']] ?? null;

            if (!$provinceId) {
                $this->command->warn("Province code {$city['province_id']} not found, skipping {$city['name']}");
                continue;
            }

            // Determine type from name prefix (KABUPATEN or KOTA)
            $rawName = $city['name'];
            if (str_starts_with($rawName, 'KABUPATEN ')) {
                $type = 'kabupaten';
                $name = ucwords(strtolower(substr($rawName, 10)));
            } elseif (str_starts_with($rawName, 'KOTA ')) {
                $type = 'kota';
                $name = ucwords(strtolower(substr($rawName, 5)));
            } else {
                $type = 'kabupaten';
                $name = ucwords(strtolower($rawName));
            }

            City::create([
                'province_id' => $provinceId,
                'code' => $city['id'],
                'name' => $name,
                'type' => $type,
            ]);
            $inserted++;
        }

        $this->command->info("Seeded {$inserted} cities/regencies.");
    }
}
