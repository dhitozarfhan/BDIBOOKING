<?php

namespace Database\Seeders;

use App\Enums\NavigationType as EnumsNavigationType;
use App\Models\NavigationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavigationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NavigationType::truncate();
        
        $datas = [
            [
                'id'    => EnumsNavigationType::Header->value,
                'name'  => 'Header'
            ],
            [
                'id'    => EnumsNavigationType::Footer->value,
                'name'  => 'Footer'
            ]
        ];
        
        NavigationType::insert($datas);

    }
}
