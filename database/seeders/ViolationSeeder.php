<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ViolationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel kosong sebelum insert
        DB::table('violations')->truncate();

        $violations = [
            ['name' => 'Pelanggaran terhadap peraturan', 'is_active' => true],
            ['name' => 'Penyalahgunaan wewenang atau jabatan', 'is_active' => true],
            ['name' => 'Pelanggaran kode etik', 'is_active' => true],
            ['name' => 'Perbuatan yang membahayakan K3 atau keamanan organisasi', 'is_active' => true],
            ['name' => 'Perbuatan yang dapat merugikan Kemenperin', 'is_active' => true],
            ['name' => 'Pelanggaran terhadap SOP', 'is_active' => true],
        ];

        foreach ($violations as $violation) {
            DB::table('violations')->insert($violation);
        }
    }
}