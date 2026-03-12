<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [];

        // BED 1 (5 kamar)
        $bed1Rooms = ['101','102','103','104','135'];

        foreach ($bed1Rooms as $room) {
            $rooms[] = [
                'property_type_id' => 4,
                'name' => 'Room '.$room,
                'description' => 'Kamar Bed 1 - kapasitas 1 orang',
                'capacity' => 1,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // BED 2 (17 kamar)
        $bed2Rooms = [
            '105','106','107','129','131','133','137','139','141',
            '201','202','203','204','207','208','205','206'
        ];

        foreach ($bed2Rooms as $room) {
            $rooms[] = [
                'property_type_id' => 5,
                'name' => 'Room '.$room,
                'description' => 'Kamar Bed 2 - kapasitas 2 orang',
                'capacity' => 2,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // BED 3 (27 kamar)
        $bed3Rooms = [
            '108','109','110','111','112','113','114','115',
            '116','117','118','119','120','121','122','123',
            '124','125','126','127',
            '128','130','132','134','136','138','140'
        ];

        foreach ($bed3Rooms as $room) {
            $rooms[] = [
                'property_type_id' => 6,
                'name' => 'Room '.$room,
                'description' => 'Kamar Bed 3 - kapasitas 3 orang',
                'capacity' => 3,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('properties')->insert($rooms);
    }
}