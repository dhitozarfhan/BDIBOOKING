<?php

namespace Database\Seeders\apk;

use Illuminate\Database\Seeder;
use App\Models\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE "property_typesAPK" CASCADE');

        $types = [
            ['id' => 1, 'name' => 'Ruang Kelas', 'description' => 'Borobudur, Prambanan, Mendut, Boko'],
            ['id' => 2, 'name' => 'Meeting Room', 'description' => 'Ruang Rapat'],
            ['id' => 3, 'name' => 'Kamar VIP', 'description' => 'Kamar Type Vip'],
            ['id' => 4, 'name' => 'Kamar Inap 2 Bed', 'description' => 'Kamar Tidur 2 Orang'],
            ['id' => 5, 'name' => 'Kamar Inap 3 Bed ', 'description' => 'Kamar Tidur 3 Orang'],
        ];

        foreach ($types as $type) {
            \App\Models\apk\PropertyTypeAPK::create($type);
        }
    }
}
