<?php

namespace Database\Seeders;

use App\Models\Navigation;
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

            PermissionSeeder::class,
            RoleDefaultSeeder::class,
            
            LinkTypeSeeder::class,
            NavigationTypeSeeder::class,

            CategoryTypeSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ArticleTypeSeeder::class,

            ResponseStatusSeeder::class,
            ArticleSeeder::class,
            NavigationSeeder::class,
        ]);
    }
}
