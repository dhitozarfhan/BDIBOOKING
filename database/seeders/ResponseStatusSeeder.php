<?php

namespace Database\Seeders;

use App\Enums\ResponseStatus as EnumsResponseStatus;
use App\Models\ResponseStatus;
use Illuminate\Database\Seeder;

class ResponseStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResponseStatus::truncate();

        $datas = [
            ['id' => EnumsResponseStatus::Initiation->value, 'name' => 'Initiation'],
            ['id' => EnumsResponseStatus::Process->value, 'name' => 'Process'],
            ['id' => EnumsResponseStatus::Disposition->value, 'name' => 'Disposition'],
            ['id' => EnumsResponseStatus::Termination->value, 'name' => 'Termination'],
        ];

        ResponseStatus::insert($datas);
    }
}
