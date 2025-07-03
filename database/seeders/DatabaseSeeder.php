<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            EducationSeeder::class,
            EmployeeStatusSeeder::class,
            GenderSeeder::class,
            RankSeeder::class,
            ReligionSeeder::class,
            PositionTypeSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            
            ArticleTypeSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
