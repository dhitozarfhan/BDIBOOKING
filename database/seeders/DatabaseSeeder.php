<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            CategoryTypeSeeder::class,
            CategorySeeder::class,

            EducationSeeder::class,
            EmployeeStatusSeeder::class,
            GenderSeeder::class,
            RankSeeder::class,
            ReligionSeeder::class,
            PositionTypeSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
