<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data lama dari tabel sebelum memasukkan data baru
        DB::table('accounts')->truncate();

        // Definisikan data akun baru dengan awalan kode '4' (Pendapatan-LRA) dan '5' (Belanja)
        // Versi ini jauh lebih lengkap dan detail
        $accounts = [
            // ===================================================================================
            // ====== PENDAPATAN (Kode awalan 4) =================================================
            // ===================================================================================

            // --- 41: PENDAPATAN PERPAJAKAN ---
            // 411: PENDAPATAN PAJAK DALAM NEGERI
            ['code' => '411111', 'name' => 'Pendapatan Pajak Penghasilan (PPh) Migas'],
            ['code' => '411121', 'name' => 'Pendapatan PPh Pasal 21'],
            ['code' => '411122', 'name' => 'Pendapatan PPh Pasal 22'],
            ['code' => '411123', 'name' => 'Pendapatan PPh Pasal 22 Impor'],
            ['code' => '411124', 'name' => 'Pendapatan PPh Pasal 25/29 Orang Pribadi'],
            ['code' => '411125', 'name' => 'Pendapatan PPh Pasal 25/29 Badan'],
            ['code' => '411126', 'name' => 'Pendapatan PPh Pasal 26'],
            ['code' => '411127', 'name' => 'Pendapatan PPh Final'],
            ['code' => '411128', 'name' => 'Pendapatan PPh Non Migas Lainnya'],
            ['code' => '411211', 'name' => 'Pendapatan Pajak Pertambahan Nilai (PPN) Dalam Negeri'],
            ['code' => '411212', 'name' => 'Pendapatan PPN Impor'],
            ['code' => '411221', 'name' => 'Pendapatan Pajak Penjualan atas Barang Mewah (PPnBM) Dalam Negeri'],
            ['code' => '411222', 'name' => 'Pendapatan PPnBM Impor'],
            ['code' => '411311', 'name' => 'Pendapatan Pajak Bumi dan Bangunan (PBB) Sektor Pertambangan Migas'],
            ['code' => '411312', 'name' => 'Pendapatan Pajak Bumi dan Bangunan (PBB) Sektor Pertambangan Minerba'],
            ['code' => '411313', 'name' => 'Pendapatan Pajak Bumi dan Bangunan (PBB) Perkebunan'],
            ['code' => '411314', 'name' => 'Pendapatan Pajak Bumi dan Bangunan (PBB) Perhutanan'],
            ['code' => '411411', 'name' => 'Pendapatan Cukai Hasil Tembakau'],
            ['code' => '411412', 'name' => 'Pendapatan Cukai Etil Alkohol'],
            ['code' => '411413', 'name' => 'Pendapatan Cukai Minuman Mengandung Etil Alkohol'],
            ['code' => '411611', 'name' => 'Pendapatan Bea Materai'],

            // 412: PENDAPATAN PAJAK PERDAGANGAN INTERNASIONAL
            ['code' => '412111', 'name' => 'Pendapatan Bea Masuk'],
            ['code' => '412112', 'name' => 'Pendapatan Denda Administrasi dan Bunga Bea Masuk'],
            ['code' => '412113', 'name' => 'Pendapatan Bea Masuk Ditanggung Pemerintah (BMDTP)'],
            ['code' => '412211', 'name' => 'Pendapatan Bea Keluar'],
            ['code' => '412212', 'name' => 'Pendapatan Denda Administrasi dan Bunga Bea Keluar'],

            // --- 42: PENDAPATAN NEGARA BUKAN PAJAK (PNBP) ---
            // 423: PNBP SUMBER DAYA ALAM
            ['code' => '423111', 'name' => 'Pendapatan Minyak Bumi'],
            ['code' => '423121', 'name' => 'Pendapatan Gas Alam'],
            ['code' => '423131', 'name' => 'Pendapatan Pertambangan Umum'],
            ['code' => '423141', 'name' => 'Pendapatan Kehutanan'],
            ['code' => '423151', 'name' => 'Pendapatan Perikanan'],
            // 424: PENDAPATAN DARI KEKAYAAN NEGARA YANG DIPISAHKAN
            ['code' => '424111', 'name' => 'Pendapatan dari Bagian Laba BUMN Perbankan'],
            ['code' => '424112', 'name' => 'Pendapatan dari Bagian Laba BUMN Non Perbankan'],
            // 425: PNBP LAINNYA
            ['code' => '425111', 'name' => 'Pendapatan Penjualan Hasil Produksi/Jasa'],
            ['code' => '425121', 'name' => 'Pendapatan Penjualan Aset yang Tidak Terpakai'],
            ['code' => '425131', 'name' => 'Pendapatan dari Penjualan Tandan Buah Segar (TBS)'],
            ['code' => '425211', 'name' => 'Pendapatan Sewa Tanah, Gedung, dan Bangunan'],
            ['code' => '425212', 'name' => 'Pendapatan Sewa Alat dan Mesin'],
            ['code' => '425311', 'name' => 'Pendapatan Jasa Giro'],
            ['code' => '425331', 'name' => 'Pendapatan Bunga Deposito'],
            ['code' => '425411', 'name' => 'Pendapatan Jasa Lembaga Keuangan'],
            ['code' => '425511', 'name' => 'Pendapatan Pendidikan'],
            ['code' => '425512', 'name' => 'Pendapatan Pelatihan dan Kursus'],
            ['code' => '425521', 'name' => 'Pendapatan Jasa Tenaga, Material dan Sarana Laboratorium'],
            ['code' => '425611', 'name' => 'Pendapatan Iuran dan Denda'],
            ['code' => '425711', 'name' => 'Pendapatan Tuntutan Ganti Kerugian Negara'],
            ['code' => '425712', 'name' => 'Pendapatan Tuntutan Perbendaharaan'],
            ['code' => '425811', 'name' => 'Pendapatan dari Pengelolaan BMN'],
            ['code' => '425911', 'name' => 'Pendapatan PNBP Lainnya'],
            // 426: PENDAPATAN BADAN LAYANAN UMUM (BLU)
            ['code' => '426111', 'name' => 'Pendapatan Jasa Layanan Umum BLU'],
            ['code' => '426121', 'name' => 'Pendapatan Hibah BLU'],
            ['code' => '426131', 'name' => 'Pendapatan Hasil Kerja Sama BLU'],
            ['code' => '426141', 'name' => 'Pendapatan BLU Lainnya'],

            // --- 43: PENDAPATAN HIBAH ---
            ['code' => '431111', 'name' => 'Pendapatan Hibah Dalam Negeri - Terikat'],
            ['code' => '431112', 'name' => 'Pendapatan Hibah Dalam Negeri - Tidak Terikat'],
            ['code' => '431211', 'name' => 'Pendapatan Hibah Luar Negeri - Terikat'],
            ['code' => '431212', 'name' => 'Pendapatan Hibah Luar Negeri - Tidak Terikat'],


            // ===================================================================================
            // ====== BELANJA (Kode awalan 5) ====================================================
            // ===================================================================================

            // --- 51: BELANJA PEGAWAI ---
            ['code' => '511111', 'name' => 'Belanja Gaji Pokok PNS'],
            ['code' => '511121', 'name' => 'Belanja Tunjangan Suami/Istri PNS'],
            ['code' => '511122', 'name' => 'Belanja Tunjangan Anak PNS'],
            ['code' => '511123', 'name' => 'Belanja Tunjangan Struktural PNS'],
            ['code' => '511124', 'name' => 'Belanja Tunjangan Fungsional PNS'],
            ['code' => '511125', 'name' => 'Belanja Tunjangan Beras PNS'],
            ['code' => '511129', 'name' => 'Belanja Uang Makan PNS'],
            ['code' => '511131', 'name' => 'Belanja Tunjangan Kinerja'],
            ['code' => '511141', 'name' => 'Belanja Tunjangan Jabatan Fungsional Tertentu'],
            ['code' => '511151', 'name' => 'Belanja Tunjangan Umum PNS'],
            ['code' => '511211', 'name' => 'Belanja Gaji Pokok TNI/POLRI'],
            ['code' => '512111', 'name' => 'Belanja Uang Lembur'],
            ['code' => '512211', 'name' => 'Belanja Honorarium Tim Pelaksana Kegiatan'],
            ['code' => '512311', 'name' => 'Belanja Gaji dan Tunjangan PPNPN'],
            ['code' => '513111', 'name' => 'Belanja Tunjangan Profesi Guru/Dosen'],

            // --- 52: BELANJA BARANG ---
            ['code' => '521111', 'name' => 'Belanja Keperluan Perkantoran'],
            ['code' => '521112', 'name' => 'Belanja Pengadaan Barang Operasional'],
            ['code' => '521113', 'name' => 'Belanja Langganan Daya dan Jasa'],
            ['code' => '521114', 'name' => 'Belanja Pengiriman Surat Dinas Pos Pusat'],
            ['code' => '521115', 'name' => 'Belanja Honor terkait Operasional Satker'],
            ['code' => '521119', 'name' => 'Belanja Keperluan Perkantoran Lainnya'],
            ['code' => '521211', 'name' => 'Belanja Bahan'],
            ['code' => '521213', 'name' => 'Belanja Honor Output Kegiatan'],
            ['code' => '521219', 'name' => 'Belanja Barang Non Operasional Lainnya'],
            ['code' => '521811', 'name' => 'Belanja Barang untuk Persediaan Barang Konsumsi'],
            ['code' => '522111', 'name' => 'Belanja Langganan Listrik'],
            ['code' => '522112', 'name' => 'Belanja Langganan Telepon'],
            ['code' => '522113', 'name' => 'Belanja Langganan Air'],
            ['code' => '522114', 'name' => 'Belanja Langganan Internet'],
            ['code' => '522121', 'name' => 'Belanja Langganan Jurnal/Surat Kabar/Majalah'],
            ['code' => '522131', 'name' => 'Belanja Jasa Konsultan'],
            ['code' => '522141', 'name' => 'Belanja Sewa Gedung dan Bangunan'],
            ['code' => '522151', 'name' => 'Belanja Jasa Pemeliharaan dan Perbaikan Peralatan dan Mesin'],
            ['code' => '522199', 'name' => 'Belanja Jasa Lainnya'],
            ['code' => '523111', 'name' => 'Belanja Biaya Pemeliharaan Gedung dan Bangunan'],
            ['code' => '523112', 'name' => 'Belanja Pemeliharaan Tanah'],
            ['code' => '523121', 'name' => 'Belanja Biaya Pemeliharaan Peralatan dan Mesin'],
            ['code' => '523131', 'name' => 'Belanja Pemeliharaan Jalan, Irigasi, dan Jaringan'],
            ['code' => '524111', 'name' => 'Belanja Perjalanan Dinas Biasa'],
            ['code' => '524113', 'name' => 'Belanja Perjalanan Dinas Dalam Kota'],
            ['code' => '524114', 'name' => 'Belanja Perjalanan Dinas Paket Meeting Dalam Kota'],
            ['code' => '524211', 'name' => 'Belanja Perjalanan Dinas Luar Negeri'],
            ['code' => '525111', 'name' => 'Belanja Barang untuk Diserahkan kepada Masyarakat/Pemda'],
            ['code' => '526111', 'name' => 'Belanja Barang untuk Bantuan Operasional Sekolah (BOS)'],

            // --- 53: BELANJA MODAL ---
            ['code' => '531111', 'name' => 'Belanja Modal Tanah'],
            ['code' => '532111', 'name' => 'Belanja Modal Peralatan dan Mesin'],
            ['code' => '532121', 'name' => 'Belanja Modal Alat Angkutan Darat Bermotor'],
            ['code' => '532131', 'name' => 'Belanja Modal Alat Besar'],
            ['code' => '533111', 'name' => 'Belanja Modal Gedung dan Bangunan'],
            ['code' => '533121', 'name' => 'Belanja Modal Pembangunan Gedung Kantor'],
            ['code' => '533131', 'name' => 'Belanja Modal Bangunan Gedung Monumen'],
            ['code' => '534111', 'name' => 'Belanja Modal Jalan, Irigasi, dan Jaringan'],
            ['code' => '534121', 'name' => 'Belanja Modal Pembangunan Jalan dan Jembatan'],
            ['code' => '534131', 'name' => 'Belanja Modal Pembangunan Bangunan Air (Irigasi/Rawa)'],
            ['code' => '534141', 'name' => 'Belanja Modal Pembangunan Jaringan Air Bersih'],
            ['code' => '536111', 'name' => 'Belanja Modal Aset Tetap Lainnya'],
            ['code' => '536112', 'name' => 'Belanja Modal Aset Tak Berwujud'],

            // --- 54: BELANJA BUNGA UTANG ---
            ['code' => '541111', 'name' => 'Belanja Bunga Utang Dalam Negeri - Surat Perbendaharaan Negara'],
            ['code' => '541112', 'name' => 'Belanja Bunga Utang Dalam Negeri - Obligasi Negara'],
            ['code' => '541113', 'name' => 'Belanja Bunga Utang Dalam Negeri - Surat Berharga Syariah Negara (SBSN)'],
            ['code' => '541211', 'name' => 'Belanja Bunga Utang Luar Negeri - Pinjaman Bank Dunia'],
            ['code' => '541212', 'name' => 'Belanja Bunga Utang Luar Negeri - Pinjaman ADB'],
            ['code' => '541213', 'name' => 'Belanja Bunga Utang Luar Negeri - Pinjaman Lembaga Keuangan Internasional Lainnya'],

            // --- 55: BELANJA SUBSIDI ---
            ['code' => '551111', 'name' => 'Belanja Subsidi Energi - BBM Tertentu'],
            ['code' => '551121', 'name' => 'Belanja Subsidi Energi - Listrik'],
            ['code' => '551131', 'name' => 'Belanja Subsidi Energi - LPG Tabung 3 Kg'],
            ['code' => '552111', 'name' => 'Belanja Subsidi Non-Energi - Pangan'],
            ['code' => '552121', 'name' => 'Belanja Subsidi Non-Energi - Pupuk'],
            ['code' => '552131', 'name' => 'Belanja Subsidi Benih'],
            ['code' => '552141', 'name' => 'Belanja Subsidi Non-Energi - Pelayanan Publik (PSO)'],
            ['code' => '552151', 'name' => 'Belanja Subsidi Bunga Kredit Program'],

            // --- 56: BELANJA HIBAH ---
            ['code' => '561111', 'name' => 'Belanja Hibah kepada Pemerintah Asing'],
            ['code' => '562111', 'name' => 'Belanja Hibah kepada Pemerintah Daerah'],
            ['code' => '562112', 'name' => 'Belanja Hibah Dana Bantuan Operasional Sekolah (BOS) ke Pemda'],
            ['code' => '563111', 'name' => 'Belanja Hibah kepada BUMN'],
            ['code' => '564111', 'name' => 'Belanja Hibah kepada Organisasi Internasional'],

            // --- 57: BELANJA BANTUAN SOSIAL ---
            ['code' => '571111', 'name' => 'Belanja Bantuan Sosial untuk Perlindungan Sosial'],
            ['code' => '571112', 'name' => 'Belanja Bantuan Sosial Program Keluarga Harapan (PKH)'],
            ['code' => '571113', 'name' => 'Belanja Bantuan Iuran Jaminan Kesehatan (PBI JK)'],
            ['code' => '572111', 'name' => 'Belanja Bantuan Sosial untuk Penanggulangan Bencana'],
            ['code' => '573111', 'name' => 'Belanja Bantuan Sosial untuk Pemberdayaan Sosial'],
            ['code' => '574111', 'name' => 'Belanja Bantuan Sosial Lainnya'],

            // --- 58: BELANJA LAIN-LAIN ---
            ['code' => '581111', 'name' => 'Belanja Lain-lain untuk Keadaan Darurat'],
            ['code' => '581112', 'name' => 'Belanja Lain-lain untuk Pembayaran Kewajiban Pemerintah'],

            // --- 59: BELANJA TRANSFER ---
            ['code' => '591111', 'name' => 'Belanja Transfer Dana Perimbangan - Dana Bagi Hasil Pajak'],
            ['code' => '592111', 'name' => 'Belanja Transfer Dana Perimbangan - Dana Alokasi Umum'],
            ['code' => '593111', 'name' => 'Belanja Transfer Dana Perimbangan - Dana Alokasi Khusus Fisik'],
            ['code' => '594111', 'name' => 'Belanja Transfer Dana Otonomi Khusus dan Dana Keistimewaan DIY'],
        ];

        // Menambahkan timestamp created_at dan updated_at ke setiap record
        $timestampedAccounts = array_map(function($account) {
            $account['created_at'] = now();
            $account['updated_at'] = now();
            return $account;
        }, $accounts);


        // Masukkan data secara berkelompok (chunk) untuk efisiensi memori
        foreach (array_chunk($timestampedAccounts, 500) as $chunk) {
            DB::table('accounts')->insert($chunk);
        }

        // Tampilkan pesan sukses di konsol
        $count = count($timestampedAccounts);
        $this->command->info("Seeder hardcode paling detail berhasil dijalankan. Sebanyak {$count} akun Pendapatan dan Belanja telah ditambahkan.");
    }
}

