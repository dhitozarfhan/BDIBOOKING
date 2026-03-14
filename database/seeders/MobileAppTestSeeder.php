<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Party;
use App\Models\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MobileAppTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Test Customer
        $party = Party::firstOrCreate(
            ['type' => Party::TYPE_INDIVIDUAL],
            ['company_name' => 'Individual Test']
        );

        Customer::updateOrCreate(
            ['email' => 'test-customer@email.com'],
            [
                'name' => 'Test Customer APK',
                'phone' => '08123456789',
                'password' => Hash::make('password123'),
                'party_id' => $party->id,
            ]
        );

        // 2. Create a Test Employee (NIP Login)
        $gender = Gender::first();
        Employee::updateOrCreate(
            ['nip' => '123456789012345678'], // Valid 18 digit NIP
            [
                'username' => '1234567890',
                'name' => 'Test Employee APK',
                'email' => 'test-employee@email.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'gender_id' => $gender ? $gender->id : null,
                'can_edited' => true,
            ]
        );
        
        $this->command->info('Mobile app test accounts created:');
        $this->command->info('Customer: test-customer@email.com / password123');
        $this->command->info('Employee: 1234567890 (NIP) / password123');
    }
}
