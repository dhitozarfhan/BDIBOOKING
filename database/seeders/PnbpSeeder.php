<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Training;

class PnbpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            Training::create([
                'title' => 'Diklat Manajemen Proyek Dasar',
                'type' => Training::TYPE_PNBP,
                'description' => '<p>Pelatihan dasar manajemen proyek untuk pemula. Peserta akan mempelajari konsep dasar, perencanaan, dan eksekusi proyek.</p>',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(10),
                'location' => 'Gedung Diklat BDIYK, Yogyakarta',
                'price' => 500000, // Rp 500.000
                'quota' => 30,
                'is_published' => true,
            ]);
            
            Training::create([
                'title' => 'Workshop Analisis Data dengan Python',
                'type' => Training::TYPE_PNBP,
                'description' => '<p>Workshop intensif selama 2 hari untuk mempelajari analisis data menggunakan bahasa pemrograman Python.</p>',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(15),
                'location' => 'Online via Zoom',
                'price' => 750000, 
                'quota' => 50,
                'is_published' => true,
            ]);

            // 3-in-1 Examples
            Training::create([
                'title' => 'Operator Garmen',
                'type' => Training::TYPE_3IN1,
                'description' => '<p>Pelatihan 3-in-1 untuk operator garmen.</p>',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(30),
                'location' => 'BDI Yogyakarta',
                'price' => 0, 
                'quota' => 100,
                'is_published' => true,
            ]);

            $this->command->info('Trainings seeded.');
    }
}
