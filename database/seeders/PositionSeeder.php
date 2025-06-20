<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Position::count()) Position::truncate();

        $datas = DB::connection('second_db')->table('m_jabatan')->get();

        // dd($datas);

        foreach ($datas as $data) {
            Position::create([
                'id'                => $data->id_jabatan,
                'position_type_id'  => $data->id_jenis,
                'name'              => $data->nama,
                'eselon'            => $data->eselon,
                'is_active'         => in_array($data->nama, ['Kepala Balai Diklat', 'Kepala Sub Bagian', 'Fungsional', 'Pelaksana'])
            ]);
        }
    }
}
