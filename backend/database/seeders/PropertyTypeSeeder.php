<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['id' => 1, 'name' => 'Meeting Room', 'description' => 'Ruang Rapat'],
            ['id' => 2, 'name' => 'Ballroom', 'description' => 'Ruang Serbaguna'],
            ['id' => 3, 'name' => 'Auditorium', 'description' => 'Ruang Pertunjukan'],
            ['id' => 4, 'name' => 'Bed 1', 'description' => 'Kamar Tidur 1 Orang'],
            ['id' => 5, 'name' => 'Bed 2', 'description' => 'Kamar Tidur 2 Orang'],
            ['id' => 6, 'name' => 'Bed 3', 'description' => 'Kamar Tidur 3 Orang'],
        ];

        foreach ($types as $type) {
            PropertyType::updateOrCreate(['id' => $type['id']], $type);
        }
    }
}
