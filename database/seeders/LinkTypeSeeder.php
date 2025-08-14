<?php

namespace Database\Seeders;

use App\Enums\LinkType as EnumsLinkType;
use App\Models\LinkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LinkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LinkType::truncate();

        $datas = [
            ['id' => EnumsLinkType::Article->value, 'name' => 'Artikel'],
            ['id' => EnumsLinkType::Internal->value, 'name' => 'Internal'],
            ['id' => EnumsLinkType::External->value, 'name' => 'Eksternal'],
            ['id' => EnumsLinkType::Module->value, 'name' => 'Modul'],
            ['id' => EnumsLinkType::Empty->value, 'name' => 'Kosong'],
        ];

        LinkType::insert($datas);
    }
}
