<?php

namespace Database\Seeders;

use App\Enums\ResponseStatus;
use App\Models\ResponseStatus;
use Illuminate\Database\Seeder;

class ResponseStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (ResponseStatus::count()) {
            ResponseStatus::truncate();
        }

        foreach (ResponseStatus::cases() as $status) {
            // 'id' akan diisi otomatis oleh database
            // 'name' akan diisi dengan value dari enum (contoh: 'initiation')
            ResponseStatus::create(['name' => $status->value]);
        }
    }
}