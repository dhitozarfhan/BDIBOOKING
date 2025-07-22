<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Gender;
use App\Models\Rank;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Employee::count()) Employee::truncate();

        $datas = DB::connection('second_db')->table('pegawai')->get();

        foreach ($datas as $data) {
            Employee::create([
                'username'      => $data->id_pegawai,
                'nip'           => is_numeric($data->nip) && strlen($data->nip) == 18  && in_array($data->id_status_pegawai, [1,2,3]) ? (Employee::where('nip', $data->nip)->count() == 0 ? $data->nip : null ) : null,
                'nip_intranet'  => is_numeric($data->nip_intranet) && in_array($data->id_status_pegawai, [1,2,3]) ? (Employee::where('nip_intranet', $data->nip_intranet)->count() == 0 ? $data->nip_intranet : null ) : null,
                'name'          => $data->nama,
                'title_pre'     => $data->titel,
                'title_post'    => $data->gelar,
                'birth_place'   => $data->tempat_lahir,
                'birth_date'    => $data->tanggal_lahir,
                'gender_id'     => Gender::where('code', $data->id_kelamin)->pluck('id')->first(),
                'religion_id'   => $data->id_agama,
                'education_id'  => $data->id_pendidikan,
                'employee_status_id'    
                                => $data->id_status_pegawai,
                'rank_id'       => Rank::where('code', $data->id_pangkat)->pluck('id')->first(),
                'tmt_rank'      => $this->validateDate($data->tmt_pangkat) ? $data->tmt_pangkat : null,
                'position_id'   => $data->id_jabatan,
                'tmt_position'  => $this->validateDate($data->tmt_jabatan) ? $data->tmt_jabatan : null,
                'tmt_work'      => $this->validateDate($data->tmt_kerja) ? $data->tmt_kerja : null,
                'tmt_pns'       => $this->validateDate($data->tmt_pns) ? $data->tmt_pns : null,
                'karpeg_number' => $data->no_karpeg,
                'ktp_number'    => $data->no_ktp,
                'askes_number'  => $data->no_askes,
                'npwp'          => $data->npwp,
                'address'       => $data->alamat,
                'phone'         => $data->telepon,
                'mobile'        => $data->mobile,
                'email'         => $data->email,
                'image'         => null,
                'thumbnail'     => null,
                'password'      => Hash::make($data->id_pegawai),
                'can_edited'    => in_array($data->id_status_pegawai, [1,2,3,4]) == false,
                'is_active'     => $data->aktif == 'Y',
                'last_sync_at'  => null,
                'force_renew_password'  => true
            ]);
        }
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date && $date !== '0000-00-00';
    }
}
