<?php

namespace Database\Seeders\apk;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [];



        // CLASSROOMS
        $classrooms = ['Borobudur', 'Prambanan', 'Mendut', 'Boko'];
        foreach ($classrooms as $room) {
            $rooms[] = [
                'property_type_id' => 1,
                'name' => 'Ruang Kelas ' . $room,
                'description' => 'Ruang Kelas ' . $room . ' di Gedung Utama BDI Yogyakarta.',
                'capacity' => 30,
                'price' => 500000,
                'total_rooms' => 1,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // KAMAR VIP
        $VIPRooms = ['101','102','103','104','129','131','133','135'];
        foreach ($VIPRooms as $room) {
            $rooms[] = [
                'property_type_id' => 3,
                'name' => 'Room '.$room,
                'description' => 'Kamar Type VIP - Kapasitas 1 Orang dengan fasilitas lengkap.',
                'capacity' => 1,
                'price' => 250000,
                'total_rooms' => 1,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // KAMAR BIASA 2 BED
        $bed2Rooms = [
            '105','106','107','137','139','141',
            '201','202','203','204','207','208','205','206'
        ];
        foreach ($bed2Rooms as $room) {
            $rooms[] = [
                'property_type_id' => 4,
                'name' => 'Room '.$room,
                'description' => 'Kamar Bed 2 - Kapasitas 2 Orang.',
                'capacity' => 2,
                'price' => 150000,
                'total_rooms' => 1,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // KAMAR BIASA 3 BED
        $bed3Rooms = [
            '108','109','110','111','112','113','114','115',
            '116','117','118','119','120','121','122','123',
            '124','125','126','127',
            '128','130','132','134','136','138','140'
        ];
        foreach ($bed3Rooms as $room) {
            $rooms[] = [
                'property_type_id' => 5,
                'name' => 'Room '.$room,
                'description' => 'Bed 3 - Kapasitas 3 Orang.',
                'capacity' => 3,
                'price' => 100000,
                'total_rooms' => 1,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE "propertiesAPK" CASCADE');
        DB::table('propertiesAPK')->insert($rooms);
    }
}