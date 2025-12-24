<?php

namespace Database\Seeders;

use App\Enums\PermissionType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (PermissionType::cases() as $permission) {
            Permission::firstOrCreate(['name' => $permission->value]);
        }
    }
}