<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseBootstrapSeeder extends Seeder
{
    public function run(): void
    {
        // First run PermissionSeeder to ensure permissions exist
        $this->call(PermissionSeeder::class);

        // Create an Admin Employee for login
        $admin = Employee::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@rsvroom.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'can_edited' => false,
            ]
        );

        // Give all permissions to admin
        $admin->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Create a default Customer
        Customer::updateOrCreate(
            ['email' => 'customer@rsvroom.com'],
            [
                'name' => 'Default Customer',
                'phone' => '08123456789',
                'password' => Hash::make('password'),
            ]
        );
    }
}
