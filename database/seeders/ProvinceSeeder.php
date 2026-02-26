<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Province::truncate();
        Schema::enableForeignKeyConstraints();

        $jsonPath = database_path('data/provinces.json');
        $provinces = json_decode(file_get_contents($jsonPath), true);

        foreach ($provinces as $province) {
            Province::create([
                'code' => $province['id'],
                'name' => ucwords(strtolower($province['name'])),
            ]);
        }

        $this->command->info('Seeded ' . Province::count() . ' provinces.');
    }
}
