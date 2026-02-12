<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Occupation::count()) Occupation::truncate();

        $datas = array(
            array('id' => '1','name' => 'Belum/Tidak Bekerja','is_active' => true),
            array('id' => '2','name' => 'Mengurus Rumah Tangga','is_active' => true),
            array('id' => '3','name' => 'Pelajar/Mahasiswa','is_active' => true),
            array('id' => '4','name' => 'Pensiunan','is_active' => true),
            array('id' => '5','name' => 'Pegawai Negeri Sipil (PNS)','is_active' => true),
            array('id' => '6','name' => 'Tentara Nasional Indonesia (TNI)','is_active' => true),
            array('id' => '7','name' => 'Kepolisian RI (POLRI)','is_active' => true),
            array('id' => '8','name' => 'Perdagangan','is_active' => true),
            array('id' => '9','name' => 'Petani/Pekebun','is_active' => true),
            array('id' => '10','name' => 'Peternak','is_active' => true),
            array('id' => '11','name' => 'Nelayan/Perikanan','is_active' => true),
            array('id' => '12','name' => 'Industri','is_active' => true),
            array('id' => '13','name' => 'Konstruksi','is_active' => true),
            array('id' => '14','name' => 'Transportasi','is_active' => true),
            array('id' => '15','name' => 'Karyawan Swasta','is_active' => true),
            array('id' => '16','name' => 'Karyawan BUMN','is_active' => true),
            array('id' => '17','name' => 'Karyawan BUMD','is_active' => true),
            array('id' => '18','name' => 'Karyawan Honorer','is_active' => true),
            array('id' => '19','name' => 'Buruh Harian Lepas','is_active' => true),
            array('id' => '20','name' => 'Buruh Tani/Perkebunan','is_active' => true),
            array('id' => '21','name' => 'Buruh Nelayan/Perikanan','is_active' => true),
            array('id' => '22','name' => 'Buruh Peternakan','is_active' => true),
            array('id' => '23','name' => 'Pembantu Rumah Tangga','is_active' => true),
            array('id' => '24','name' => 'Tukang Cukur','is_active' => true),
            array('id' => '25','name' => 'Tukang Listrik','is_active' => true),
            array('id' => '26','name' => 'Tukang Batu','is_active' => true),
            array('id' => '27','name' => 'Tukang Kayu','is_active' => true),
            array('id' => '28','name' => 'Tukang Sol Sepatu','is_active' => true),
            array('id' => '29','name' => 'Tukang Las/Pandai Besi','is_active' => true),
            array('id' => '30','name' => 'Tukang Jahit','is_active' => true),
            array('id' => '31','name' => 'Tukang Gigi','is_active' => true),
            array('id' => '32','name' => 'Penata Rias','is_active' => true),
            array('id' => '33','name' => 'Penata Busana','is_active' => true),
            array('id' => '34','name' => 'Penata Rambut','is_active' => true),
            array('id' => '35','name' => 'Mekanik','is_active' => true),
            array('id' => '36','name' => 'Seniman','is_active' => true),
            array('id' => '37','name' => 'Tabib','is_active' => true),
            array('id' => '38','name' => 'Paraji','is_active' => true),
            array('id' => '39','name' => 'Perancang Busana','is_active' => true),
            array('id' => '40','name' => 'Penterjemah','is_active' => true),
            array('id' => '41','name' => 'Imam Masjid','is_active' => true),
            array('id' => '42','name' => 'Pendeta','is_active' => true),
            array('id' => '43','name' => 'Pastor','is_active' => true),
            array('id' => '44','name' => 'Wartawan','is_active' => true),
            array('id' => '45','name' => 'Ustadz/Mubaligh','is_active' => true),
            array('id' => '46','name' => 'Juru Masak','is_active' => true),
            array('id' => '47','name' => 'Promotor Acara','is_active' => true),
            array('id' => '48','name' => 'Anggora DPR-RI','is_active' => true),
            array('id' => '49','name' => 'Anggota DPD','is_active' => true),
            array('id' => '50','name' => 'Anggota BPK','is_active' => true),
            array('id' => '51','name' => 'Presiden','is_active' => true),
            array('id' => '52','name' => 'Wakil Presiden','is_active' => true),
            array('id' => '53','name' => 'Anggota Mahkamah','is_active' => true),
            array('id' => '54','name' => 'Konstitusi','is_active' => true),
            array('id' => '55','name' => 'Anggota Kabinet/ Kementrian','is_active' => true),
            array('id' => '56','name' => 'Duta Besar','is_active' => true),
            array('id' => '57','name' => 'Gubernur','is_active' => true),
            array('id' => '58','name' => 'Wakil Gubernur','is_active' => true),
            array('id' => '59','name' => 'Bupati','is_active' => true),
            array('id' => '60','name' => 'Wakil Bupati','is_active' => true),
            array('id' => '61','name' => 'Walikota','is_active' => true),
            array('id' => '62','name' => 'Wakil Walikota','is_active' => true),
            array('id' => '63','name' => 'Anggota DPRD Provinsi/ Anggota DPRD Kabupaten/Kota','is_active' => true),
            array('id' => '64','name' => 'Dosen','is_active' => true),
            array('id' => '65','name' => 'Guru','is_active' => true),
            array('id' => '66','name' => 'Pilot','is_active' => true),
            array('id' => '67','name' => 'Pengacara','is_active' => true),
            array('id' => '68','name' => 'Notaris','is_active' => true),
            array('id' => '69','name' => 'Arsitek','is_active' => true),
            array('id' => '70','name' => 'Akuntan','is_active' => true),
            array('id' => '71','name' => 'Konsultan','is_active' => true),
            array('id' => '72','name' => 'Dokter','is_active' => true),
            array('id' => '73','name' => 'Bidan','is_active' => true),
            array('id' => '74','name' => 'Perawat','is_active' => true),
            array('id' => '75','name' => 'Apoteker','is_active' => true),
            array('id' => '76','name' => 'Psikiater/Psikolog','is_active' => true),
            array('id' => '77','name' => 'Penyiar Televisi','is_active' => true),
            array('id' => '78','name' => 'Penyiar Radio','is_active' => true),
            array('id' => '79','name' => 'Pelaut','is_active' => true),
            array('id' => '80','name' => 'Peneliti','is_active' => true),
            array('id' => '81','name' => 'Sopir','is_active' => true),
            array('id' => '82','name' => 'Pialang','is_active' => true),
            array('id' => '83','name' => 'Paranormal','is_active' => false),
            array('id' => '84','name' => 'Pedagang','is_active' => true),
            array('id' => '85','name' => 'Perangkat Desa','is_active' => true),
            array('id' => '86','name' => 'Kepala Desa','is_active' => true),
            array('id' => '87','name' => 'Biarawati','is_active' => true),
            array('id' => '88','name' => 'Wiraswasta','is_active' => true),
            array('id' => '89','name' => 'Lainnya','is_active' => true)
        );

        foreach ($datas as $data) {
            Occupation::create($data);
        }
    }
}
