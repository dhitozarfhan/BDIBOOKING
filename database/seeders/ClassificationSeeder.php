<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data klasifikasi berdasarkan dokumen "klasifikasi dan kode arsip ok anri.doc"
        $classifications = [
            // --- KLASIFIKASI DAN KODE ARSIP FASILITATIF ---
            [
                'code' => 'PR',
                'name' => 'Perencanaan',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Pokok-pokok kebijakan dan strategi pembangunan',
                        'children' => [
                            ['code' => '01', 'name' => 'Rencana Pembangunan Jangka Panjang (RPJP)/master plan'],
                            ['code' => '02', 'name' => 'Rencana Pembangunan Jangka Menengah (RPJM)/Renstra'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Program tahunan',
                        'children' => [
                            ['code' => '01', 'name' => 'Usulan program kerja satuan organisasi/kerja'],
                            ['code' => '02', 'name' => 'Program kerja satuan organisasi/kerja'],
                            ['code' => '03', 'name' => 'Program kerja tahunan kementerian'],
                            ['code' => '04', 'name' => 'Rencana kerja dan anggaran kementerian dan lembaga'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Penetapan/kontrak kinerja',
                        'children' => [
                            ['code' => '01', 'name' => 'Menteri'],
                            ['code' => '02', 'name' => 'Pimpinan unit kerja'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Laporan',
                        'children' => [
                            ['code' => '01', 'name' => 'Laporan berkala'],
                            ['code' => '02', 'name' => 'Laporan Akuntabilitas Kerja (LAKIP)'],
                            ['code' => '03', 'name' => 'Laporan insidental/rahasia'],
                        ],
                    ],
                    [
                        'code' => '05',
                        'name' => 'Evaluasi program',
                        'children' => [
                            ['code' => '01', 'name' => 'Evaluasi program satuan organisasi/kerja'],
                            ['code' => '02', 'name' => 'Evaluasi program kementerian'],
                        ],
                    ],
                    [
                        'code' => '06',
                        'name' => 'Data perencanaan',
                        'children' => [
                            ['code' => '01', 'name' => 'Data perencanaan satuan organisasi/kerja'],
                            ['code' => '02', 'name' => 'Data perencanaan kementerian'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'KU',
                'name' => 'Keuangan',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Rencana Anggaran Pendapatan dan Belanja Negara (RAPBN)',
                        'children' => [
                            ['code' => '01', 'name' => 'Penyusunan RAPBN'],
                            ['code' => '02', 'name' => 'Penyampaian RAPBN kepada DPR-RI'],
                            ['code' => '03', 'name' => 'Anggaran Pendapatan dan Belanja Negara'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Penyusunan APBN',
                        'children' => [
                            ['code' => '01', 'name' => 'Ketetapan pagu indikatif'],
                            ['code' => '02', 'name' => 'Ketetapan pagu sementara'],
                            ['code' => '03', 'name' => 'Ketetapan pagu definitif'],
                            ['code' => '04', 'name' => 'Rencana kerja anggaran kementerian'],
                            ['code' => '05', 'name' => 'Daftar Isian Pelaksana Anggaran (DIPA), Petunjuk Operasional Kegiatan (POK) dan revisinya'],
                            ['code' => '06', 'name' => 'Ketentuan/peraturan dan SOP yang menyangkut pelaksanaan, penatausahaan, dan pertanggungjawaban anggaran'],
                            ['code' => '07', 'name' => 'Target Penerimaan Negara Bukan Pajak (PNBP)'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Pelaksanaan Anggaran',
                        'children' => [
                            ['code' => '01', 'name' => 'Dokumen Realisasi Pengeluaran'],
                            ['code' => '02', 'name' => 'Belanja anggaran pemerintah'],
                            ['code' => '03', 'name' => 'Pembayaran keuangan'],
                            ['code' => '04', 'name' => 'Pembukuan anggaran'],
                            ['code' => '05', 'name' => 'Daftar gaji'],
                            ['code' => '06', 'name' => 'Kartu gaji'],
                            ['code' => '07', 'name' => 'Akuntansi keuangan'],
                            ['code' => '08', 'name' => 'Laporan keuangan tahunan'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Bantuan/pinjaman luar negeri',
                        'children' => [
                            ['code' => '01', 'name' => 'Permohonan pinjaman luar negeri (blue book)'],
                            ['code' => '02', 'name' => 'Dokumen kesanggupan negara donor untuk membiayai (grey book)'],
                            ['code' => '03', 'name' => 'Dokumen Memorandum Of Understanding (MOU) dan dokumen sejenisnya'],
                            ['code' => '04', 'name' => 'Dokumen Loan Agreement, Pinjaman dan/atau Hibah Luar Negeri (PHLN)'],
                            ['code' => '05', 'name' => 'Alokasi dan relokasi penggunaan dana luar negeri'],
                            ['code' => '06', 'name' => 'Aplikasi penarikan dana pinjaman/hibah luar negeri'],
                            ['code' => '07', 'name' => 'Otorisasi penarikan dana (Payment Advice)'],
                            ['code' => '08', 'name' => 'Realisasi pencairan dana bantuan luar negeri'],
                            ['code' => '09', 'name' => 'Staff appraisal report'],
                            ['code' => '10', 'name' => 'Report/laporan'],
                            ['code' => '11', 'name' => 'Completion report/annual report'],
                            ['code' => '12', 'name' => 'Ketentuan/peraturan yang menyangkut bantuan/pinjaman luar negeri'],
                        ],
                    ],
                    [
                        'code' => '05',
                        'name' => 'Pengelola APBN/dana Pinjaman/Hibah Luar Negeri (PHLN)',
                        'children' => [
                            ['code' => '01', 'name' => 'Keputusan menteri'],
                        ],
                    ],
                    [
                        'code' => '06',
                        'name' => 'Sistem Akuntansi Instansi (SAI)',
                        'children' => [
                            ['code' => '01', 'name' => 'Manual Implementasi SAI'],
                            ['code' => '02', 'name' => 'Rekonsiliasi SAI'],
                            ['code' => '03', 'name' => 'Daftar Transaksi (DT), Pengeluaran (PK), Penerimaan (PN), Dokumen Sumber (DS), Bukti Jurnal (BJ), Surat Tanda Setor (STS), SSBP, SP2D, SPM dalam Daftar Ringkasan Pengembalian dan Potongan dari Pengeluaran (SPDR)'],
                            ['code' => '04', 'name' => 'Listing (daftar rekaman penerimaan) buku temuan dan tindakan lain'],
                            ['code' => '05', 'name' => 'Laporan realisasi bulanan'],
                            ['code' => '06', 'name' => 'Laporan Realisasi Triwulan SAI dan Unit Akuntansi Wilayah (UAW) dan Unit Akutansi Pengguna Anggaran (UAPA)'],
                        ],
                    ],
                    [
                        'code' => '07',
                        'name' => 'Pertanggungjawaban keuangan',
                        'children' => [
                            ['code' => '01', 'name' => 'Laporan hasil pemeriksaan atas laporan keuangan oleh badan pemeriksa keuangan republik indonesia (BPK-RI)'],
                            ['code' => '02', 'name' => 'Hasil pengawasan dan pemeriksaan internal'],
                            ['code' => '03', 'name' => 'Laporan Aparat Pemeriksaan Fungsional'],
                            ['code' => '04', 'name' => 'Dokumen penyelesaian keuangan negara'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'HK',
                'name' => 'Hukum',
                'children' => [
                    ['code' => '01', 'name' => 'Peraturan/Keputusan Menteri Perindustrian/ Eselon I'],
                    ['code' => '02', 'name' => 'Bantuan/konsultasi hukum/advokasi'],
                    [
                        'code' => '03',
                        'name' => 'Program legislasi',
                        'children' => [
                            ['code' => '01', 'name' => 'Bahan/materi program legislasi nasional dan instansi pusat'],
                            ['code' => '02', 'name' => 'Program legislasi pusat/daerah'],
                        ],
                    ],
                    ['code' => '04', 'name' => 'Rancangan Peraturan Perundang-undangan'],
                    [
                        'code' => '05',
                        'name' => 'Instruksi/surat edaran',
                        'children' => [
                            ['code' => '01', 'name' => 'Instruksi/surat edaran Menteri Perindustrian'],
                            ['code' => '02', 'name' => 'Instruksi/surat edaran pejabat setingkat eselon I dan II'],
                        ],
                    ],
                    [
                        'code' => '06',
                        'name' => 'Standar/pedoman/prosedur kerja/petunjuk pelaksana/petunjuk teknis/MoU/Nota Kesepahaman',
                        'children' => [
                            ['code' => '01', 'name' => 'Standar/pedoman/prosedur kerja/petunjuk pelaksana/petunjuk teknis yang bersifat nasional/regional/internasional'],
                            ['code' => '02', 'name' => 'Nota kesepahaman/kontrak/perjanjian kerja sama dalam negeri dan luar negeri'],
                        ],
                    ],
                    ['code' => '07', 'name' => 'Dokumentasi hukum'],
                    ['code' => '08', 'name' => 'Sosialisasi/penyuluhan/pembina hukum'],
                    ['code' => '09', 'name' => 'Bantuan/konsultasi hukum/advokasi'],
                    [
                        'code' => '10',
                        'name' => 'Kasus/sengketa hukum',
                        'children' => [
                            ['code' => '01', 'name' => 'Pidana'],
                            ['code' => '02', 'name' => 'Perdata'],
                            ['code' => '03', 'name' => 'Tata Usaha Negara'],
                            ['code' => '04', 'name' => 'Arbitrase'],
                        ],
                    ],
                    ['code' => '11', 'name' => 'Perizinan'],
                    ['code' => '12', 'name' => 'Hak kekayaan Intelektual (HKI)'],
                ],
            ],
            [
                'code' => 'OT',
                'name' => 'Organisasi dan Tata Laksana',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Struktur organisasi kementerian perindustrian',
                        'children' => [
                            ['code' => '01', 'name' => 'Pembentukan'],
                            ['code' => '02', 'name' => 'Pengubahan'],
                            ['code' => '03', 'name' => 'Pembubaran'],
                        ],
                    ],
                    ['code' => '02', 'name' => 'Dokumen reformasi birokrasi dan zona integritas'],
                    ['code' => '03', 'name' => 'Uraian tugas dan tata kerja'],
                    ['code' => '04', 'name' => 'Pembinaan dan evaluasi organisasi'],
                    ['code' => '05', 'name' => 'Standar kompetensi jabatan struktural dan fungsional'],
                    ['code' => '06', 'name' => 'Analisa beban kerja dan peta jabatan'],
                    ['code' => '07', 'name' => 'Standard Operating Procedure (SOP)'],
                ],
            ],
            [
                'code' => 'DL',
                'name' => 'Pelatihan',
                'children' => [
                    ['code' => '01', 'name' => 'Pedoman pelatihan'],
                    ['code' => '02', 'name' => 'Kurikulum pelatihan'],
                    ['code' => '03', 'name' => 'Modul pelatihan'],
                    ['code' => '04', 'name' => 'Panduan fasilitator'],
                    ['code' => '05', 'name' => 'Saran/rekomendasi penyelenggaraan diklat'],
                    ['code' => '06', 'name' => 'Notulen sosialisasi/rapat koordinasi kebijakan diklat'],
                    ['code' => '07', 'name' => 'Akreditasi lembaga diklat'],
                    ['code' => '08', 'name' => 'Sertifikat sumber daya manusia diklat'],
                    ['code' => '09', 'name' => 'Sistem informasi diklat'],
                    ['code' => '10', 'name' => 'Registrasi sertifikat/sttpl peserta diklat'],
                    ['code' => '11', 'name' => 'Rencana tahunan diklat'],
                    ['code' => '12', 'name' => 'Rencana penyelengaraan diklat'],
                    ['code' => '13', 'name' => 'Penyelenggaraan diklat'],
                    ['code' => '14', 'name' => 'Evaluasi penyelenggara diklat'],
                    ['code' => '15', 'name' => 'Evaluasi pasca diklat'],
                ],
            ],
            [
                'code' => 'PBJ',
                'name' => 'Pengadaan Barang/Jasa',
                'children' => [
                    ['code' => '01', 'name' => 'Pengembangan Strategi, monitoring dan evaluasi, Sistem Informasi, pembinaan SDM, hukum dan penyelesaian sanggahan'],
                    [
                        'code' => '02',
                        'name' => 'Perencanaan pengadaan barang dan jasa',
                        'children' => [
                            ['code' => '01', 'name' => 'Analisis kebutuhan pengadaan barang dan jasa'],
                            ['code' => '02', 'name' => 'Perencanaan lelang'],
                            ['code' => '03', 'name' => 'Perencanaan pengadaan barang dan jasa'],
                        ],
                    ],
                    ['code' => '03', 'name' => 'Pelaksanaan pengadaan barang dan jasa'],
                    ['code' => '04', 'name' => 'Penyimpanan barang dan distribusi'],
                    ['code' => '05', 'name' => 'Monitoring dan evaluasi'],
                    ['code' => '06', 'name' => 'Unit layanan pengadaan'],
                    [
                        'code' => '07',
                        'name' => 'Hukum dan penyelesaian sanggah',
                        'children' => [
                            ['code' => '01', 'name' => 'Penanganan permasalahan kontrak'],
                            ['code' => '02', 'name' => 'Keterangan ahli'],
                        ],
                    ],
                    ['code' => '08', 'name' => 'Layanan pengadaan secara elektronik'],
                ],
            ],
            [
                'code' => 'BMN',
                'name' => 'Barang Milik Negara',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Perencanaan kebutuhan',
                        'children' => [
                            ['code' => '01', 'name' => 'Standar barang dan kebutuhan'],
                            ['code' => '02', 'name' => 'Analisa kebutuhan BMN'],
                            ['code' => '03', 'name' => 'Rencana Kebutuhan Barang Milik Negara (RKBMN)'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Penggunaan',
                        'children' => [
                            ['code' => '01', 'name' => 'Penetapan status pengguna tanah dan bangunan'],
                            ['code' => '02', 'name' => 'Penetapan status pengguna peralatan dan mesin'],
                            ['code' => '03', 'name' => 'Penggunaan oleh pihak lain'],
                            ['code' => '04', 'name' => 'Penggunaan sementara'],
                            ['code' => '05', 'name' => 'Pengalihan status penggunaan'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Pemanfaatan',
                        'children' => [
                            ['code' => '01', 'name' => 'Sewa'],
                            ['code' => '02', 'name' => 'Pinjam pakai'],
                            ['code' => '03', 'name' => 'Kerjasama pemanfaatan'],
                            ['code' => '04', 'name' => 'Bangun serah guna/bangun guna bangun'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Pengamanan',
                        'children' => [
                            ['code' => '01', 'name' => 'Dokumen kepemilikan'],
                            ['code' => '02', 'name' => 'Tindak lanjut penanganan aset'],
                        ],
                    ],
                    [
                        'code' => '05',
                        'name' => 'Penatausahaan BMN',
                        'children' => [
                            ['code' => '01', 'name' => 'Perolehan APBN (SP2D, SPM)'],
                            ['code' => '02', 'name' => 'Perolehan lainnya (berita acara serah terima transfer masuk, alih status, dan hibah)'],
                            ['code' => '03', 'name' => 'Perolehan lainnya (berita acara serah terima transfer masuk, alih status, dan hibah)'],
                            ['code' => '04', 'name' => 'Kodefikasi'],
                            ['code' => '05', 'name' => 'Daftar Inventaris Ruangan (DIR)'],
                            ['code' => '06', 'name' => 'Kartu Identitas Barang (KIB)'],
                            ['code' => '07', 'name' => 'Penatausahaan barang persediaan'],
                            ['code' => '08', 'name' => 'Laporan BMN Unit Akuntansi Pengguna Barang (UAPB)'],
                            ['code' => '09', 'name' => 'Laporan BMN Unit Akuntansi Pembantu Pengguna Barang Eselon 1 (UAPPB-E1)'],
                            ['code' => '10', 'name' => 'Laporan BMN Unit Akuntansi Pembantu Pengguna Barang Wilayah (UAPPB-W)'],
                            ['code' => '11', 'name' => 'Laporan BMN Unit Akuntansi Kuasa Pengguna Barang (UAKPB)'],
                            ['code' => '12', 'name' => 'Inventaris'],
                        ],
                    ],
                    ['code' => '06', 'name' => 'Pemindahtanganan BMN'],
                    ['code' => '07', 'name' => 'Pemusnahan BMN'],
                    ['code' => '08', 'name' => 'Penghapusan BMN'],
                    ['code' => '09', 'name' => 'Pembinaan, pengawasan dan pengendalian'],
                    ['code' => '10', 'name' => 'Rumah negara'],
                ],
            ],
            [
                'code' => 'KR',
                'name' => 'Kearsipan',
                'children' => [
                    ['code' => '01', 'name' => 'Kebijakan'],
                    [
                        'code' => '02',
                        'name' => 'Pembinaan kearsipan',
                        'children' => [
                            ['code' => '01', 'name' => 'Sosialisasi kearsipan (NSPK, dan peraturan lainnya)'],
                            ['code' => '02', 'name' => 'Pembinaan arsiparis'],
                            ['code' => '03', 'name' => 'Bimbingan dan konsultasi'],
                            ['code' => '04', 'name' => 'Supervisi dan evaluasi'],
                            ['code' => '05', 'name' => 'Fasilitas Kearsipan'],
                            ['code' => '06', 'name' => 'Unit kearsipan teladan'],
                            ['code' => '07', 'name' => 'Proses pengajuan persetujuan jadwal retensi arsip'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Pengelolaan arsip dinamis',
                        'children' => [
                            ['code' => '01', 'name' => 'Penciptaan arsip'],
                            ['code' => '02', 'name' => 'Penggunaan arsip'],
                            ['code' => '03', 'name' => 'Pemeliharaan arsip'],
                            ['code' => '04', 'name' => 'Alih media arsip'],
                            ['code' => '05', 'name' => 'Pengelolaan arsip vital'],
                            ['code' => '06', 'name' => 'Pengelolaan arsip terjaga'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Penyusutan arsip',
                        'children' => [
                            ['code' => '01', 'name' => 'Pemindahan arsip inaktif'],
                            ['code' => '02', 'name' => 'Pemusnahan arsip yang tidak bernilai guna'],
                            ['code' => '03', 'name' => 'Penyerahan arsip statis'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'PP',
                'name' => 'Pendidikan dan Pengajaran',
                'children' => [
                    ['code' => '01', 'name' => 'Kebijakan'],
                    [
                        'code' => '02',
                        'name' => 'Pendidikan Menengah (SMK-SMAK dan SMTI)',
                        'children' => [
                            ['code' => '01', 'name' => 'PPDB (Penerimaan Peserta Didik Baru)'],
                            ['code' => '02', 'name' => 'Pengelolaan Administrasi Kesiswaan'],
                            ['code' => '03', 'name' => 'Pengelolaan Administrasi Akademik'],
                            ['code' => '04', 'name' => 'Proses pembelajaran'],
                            ['code' => '05', 'name' => 'Penilaian dan Evaluasi Pendidikan'],
                            ['code' => '06', 'name' => 'Wisuda'],
                            ['code' => '07', 'name' => 'Pendataan Alumni dan tracer study'],
                            ['code' => '08', 'name' => 'Pemasaran Lulusan'],
                            ['code' => '09', 'name' => 'Akreditasi Sekolah'],
                            ['code' => '10', 'name' => 'Supervisi dan PKG (Penilaian Kinerja Guru)'],
                            ['code' => '11', 'name' => 'Pengelolaan Lingkungan (Adiwiyata)'],
                            ['code' => '12', 'name' => 'Pengelolaan Sarana dan Prasarana Pendidikan'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Pendidikan tinggi (politeknik/akademi)',
                        'children' => [
                            ['code' => '01', 'name' => 'Kurikulum/silabus'],
                            ['code' => '02', 'name' => 'Bahan ajar'],
                            ['code' => '03', 'name' => 'Pelatihan'],
                            ['code' => '04', 'name' => 'Bantuan operasional'],
                            ['code' => '05', 'name' => 'Pendidik/pengajar/dosen'],
                            ['code' => '06', 'name' => 'Tenaga pendidik'],
                            ['code' => '07', 'name' => 'Sertifikasi kompetisi'],
                            ['code' => '08', 'name' => 'Standar pendidik'],
                            ['code' => '09', 'name' => 'Proses pembelajaran'],
                            ['code' => '10', 'name' => 'Hasil prestasi mahasiswa'],
                            ['code' => '11', 'name' => 'Beasiswa'],
                            ['code' => '12', 'name' => 'Bimtek/sosialisasi'],
                            ['code' => '13', 'name' => 'Bantuan'],
                            ['code' => '14', 'name' => 'Alat bantu pembelajaran'],
                            ['code' => '15', 'name' => 'Absen mahasiswa'],
                            ['code' => '16', 'name' => 'UTS, UAS, Ujian lainnya'],
                            ['code' => '17', 'name' => 'Ijazah'],
                            ['code' => '18', 'name' => 'Wisuda'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'TIK',
                'name' => 'Teknologi informasi dan komunikasi',
                'children' => [
                    ['code' => '01', 'name' => 'Rencana strategi/master plan pembangunan sistem informasi'],
                    ['code' => '02', 'name' => 'Dokumentasi arsitektur'],
                    ['code' => '03', 'name' => 'Dokumentasi implementasi'],
                    ['code' => '04', 'name' => 'Perekaman dan pemuktahiran data'],
                    ['code' => '05', 'name' => 'Migrasi sistem aplikasi dan data'],
                    ['code' => '06', 'name' => 'Dokumen hosting'],
                    ['code' => '07', 'name' => 'Back-up data digital'],
                    ['code' => '08', 'name' => 'E-office/e-government'],
                    ['code' => '09', 'name' => 'Sarana dan prasarana teknologi informasi'],
                    ['code' => '10', 'name' => 'Sistem informasi industri nasional'],
                ],
            ],
            [
                'code' => 'KP',
                'name' => 'Kepegawaian',
                'children' => [
                    ['code' => '01', 'name' => 'Bezeeting kebutuhan pegawai'],
                    ['code' => '02', 'name' => 'Formasi pegawai'],
                    [
                        'code' => '03',
                        'name' => 'Pengadaan pegawai',
                        'children' => [
                            ['code' => '01', 'name' => 'Proses penerimaan pegawai'],
                            ['code' => '02', 'name' => 'Penetapan pengumuman kelulusan'],
                            ['code' => '03', 'name' => 'Berkas lamaran yang tidak diterima'],
                            ['code' => '04', 'name' => 'Nota usul dan kelengkapan penetapan NIP'],
                            ['code' => '05', 'name' => 'Nota usul pengangkatan CPNS menjadi PNS lebih 1 tahun'],
                            ['code' => '06', 'name' => 'SK CPNS/PNS Kolektif'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Pembina karir pegawai',
                        'children' => [
                            ['code' => '01', 'name' => 'Diklat/kursus/tugas belajar/ujian dinas/izin belajar pegawai'],
                            ['code' => '02', 'name' => 'Surat Tanda Tamat Pendidikan dan Pelatihan (STTPL)/Sertifikat'],
                            ['code' => '03', 'name' => 'Sasaran Kinerja Pegawai (SKP)/DP3'],
                            ['code' => '04', 'name' => 'Daftar usul penetapan angka kredit dan Penetapan Angka Kredit'],
                            ['code' => '05', 'name' => 'Disiplin pegawai'],
                            ['code' => '06', 'name' => 'Penghargaan dan tanda jasa'],
                        ],
                    ],
                    ['code' => '05', 'name' => 'Penyelesaian pengelolaan masalah pegawai'],
                    [
                        'code' => '06',
                        'name' => 'Mutasi pegawai',
                        'children' => [
                            ['code' => '01', 'name' => 'Alih status, pindah instansi, pindah wilayah kerja, diperbantukan, diperkerjakan, penugasan sementara, mutasi antar perwakilan, mutasi ke dan dari perwakilan, pemindahan sementara, mutasi antar unit'],
                            ['code' => '02', 'name' => 'Nota persetujuan/pertimbangan Kepala BKN'],
                            ['code' => '03', 'name' => 'Mutasi keluarga'],
                            ['code' => '04', 'name' => 'Usul kenaikan jabatan'],
                            ['code' => '05', 'name' => 'Usul pengangkatan dan pemberhentian dalam jabatan struktural/fungsional'],
                            ['code' => '06', 'name' => 'Usul penetapan dan perubahan data dasar/status/kedudukan'],
                            ['code' => '07', 'name' => 'Peninjauan masa kerja'],
                            ['code' => '08', 'name' => 'Berkas badan pertimbangan jabatan dan kepangkatan'],
                        ],
                    ],
                    [
                        'code' => '07',
                        'name' => 'Administrasi pegawai',
                        'children' => [
                            ['code' => '01', 'name' => 'Dokumentasi identitas pegawai'],
                            ['code' => '02', 'name' => 'Berkas kepegawaian Daftar Urut Kepangkatan (DUK) dan Daftar Susunan Pegawai (DSP)'],
                            ['code' => '03', 'name' => 'Penghargaan (satya lancana, dll)'],
                        ],
                    ],
                    ['code' => '08', 'name' => 'Kesejahteraan pegawai'],
                    ['code' => '09', 'name' => 'Pemberhentian pegawai tanpa hak pensiun'],
                    ['code' => '10', 'name' => 'Perselisihan/sengketa pegawai'],
                    ['code' => '11', 'name' => 'Usulan pemberhentian dan penetapan pensiun pegawai/janda/dudanya dan PNS yang meninggal'],
                    ['code' => '12', 'name' => 'Berkas perseorangan pegawai negeri sipil'],
                    ['code' => '13', 'name' => 'Berkas perseorangan pejabat negara'],
                    ['code' => '14', 'name' => 'Berkas perseorangan pejabat lainnya'],
                ],
            ],
            [
                'code' => 'PW',
                'name' => 'Pengawasan',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Rencana pengawasan',
                        'children' => [
                            ['code' => '01', 'name' => 'Rencana strategis pengawasan'],
                            ['code' => '02', 'name' => 'Rencana kerja tahunan'],
                            ['code' => '03', 'name' => 'Rencana kinerja tahunan'],
                            ['code' => '04', 'name' => 'Penetapan kinerja tahunan'],
                            ['code' => '05', 'name' => 'Rakor pengawasan tingkat nasional'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Pelaksanaan pengawasan',
                        'children' => [
                            ['code' => '01', 'name' => 'Laporan Hasil Audit (LHA), Laporan Akhir Review (LHR), Laporan Hasil Evaluasi (LHE), Laporan Akuntan (LA) yang memerlukan tindak lanjut'],
                            ['code' => '02', 'name' => 'Laporan Hasil Audit (LHA), Laporan Akhir Review (LHR), Laporan Hasil Evaluasi (LHE), Laporan Akuntan (LA) yang tidak memerlukan tindak lanjut'],
                            ['code' => '03', 'name' => 'Laporan Hasil Audit Investigasi (LHAI) yang mengandung unsur Tindak Pidana Korupsi (TPK) dan memerlukan tindak lanjut'],
                            ['code' => '04', 'name' => 'Laporan perkembangan penanganan surat pengaduan masyarakat'],
                            ['code' => '05', 'name' => 'Laporan pemutakhiran data'],
                            ['code' => '06', 'name' => 'Laporan perkembangan barang milik negara'],
                            ['code' => '07', 'name' => 'Laporan kegiatan pendampingan penyusunan, laporan keuangan dan reviu Kementerian Perindustrian Republik Indonesia'],
                            ['code' => '08', 'name' => 'good corporate governance (GCG)'],
                        ],
                    ],
                    ['code' => '03', 'name' => 'Updating bahan pengawasan'],
                    ['code' => '04', 'name' => 'Hasil pengawasan'],
                    ['code' => '05', 'name' => 'Tindak lanjut hasil pengawasan'],
                    ['code' => '06', 'name' => 'Reviu laporan keuangan dan BMN'],
                    ['code' => '07', 'name' => 'Reviu RKAKL/revisi anggaran'],
                    ['code' => '08', 'name' => 'Monitoring dan evaluasi'],
                    ['code' => '09', 'name' => 'Gratifikasi'],
                    ['code' => '10', 'name' => 'Klinik Inspektorat Jenderal'],
                ],
            ],
            [
                'code' => 'HM',
                'name' => 'Hubungan Masyarakat',
                'children' => [
                    ['code' => '01', 'name' => 'Naskah pidato Menteri Perindustrian/pejabat eselon I dan II'],
                    ['code' => '02', 'name' => 'Rapat kerja/dengar pendapat dengan anggota DPR/DPRD'],
                    ['code' => '03', 'name' => 'Publikasi melalui media cetak maupun elektronik'],
                    ['code' => '04', 'name' => 'Penerbitan majalah, buletin, koran dan jurnal'],
                    ['code' => '05', 'name' => 'Volunteer/tenaga suka rela'],
                    ['code' => '06', 'name' => 'Dokumentasi/liputan kegiatan dinas pimpinan, acara kedinasan, peristiwa bidang masing-masing dalam berbagai media'],
                    [
                        'code' => '07',
                        'name' => 'Pengumpulan, pengelolaan dan penyajian informasi kelembagaan',
                        'children' => [
                            ['code' => '01', 'name' => 'Kliping koran'],
                            ['code' => '02', 'name' => 'Brosur/leaflet/poster/plakat'],
                            ['code' => '03', 'name' => 'Pengumuman'],
                            ['code' => '04', 'name' => 'Laporan kegiatan'],
                            ['code' => '05', 'name' => 'Laporan tahunan'],
                            ['code' => '06', 'name' => 'Laporan triwulanan'],
                            ['code' => '07', 'name' => 'Laporan bulanan'],
                        ],
                    ],
                    ['code' => '08', 'name' => 'Pameran'],
                    ['code' => '09', 'name' => 'Kunjungan kerja'],
                    ['code' => '10', 'name' => 'Kunjungan tamu'],
                    ['code' => '11', 'name' => 'Sosialisasi kebijakan'],
                    ['code' => '12', 'name' => 'Perpustakaan'],
                    ['code' => '13', 'name' => 'Penyelenggaraan konferensi pers'],
                    ['code' => '14', 'name' => 'Penyelenggaraan jumpa pers'],
                ],
            ],
            [
                'code' => 'RT',
                'name' => 'Rumah Tangga',
                'children' => [
                    ['code' => '01', 'name' => 'Rapat-rapat'],
                    ['code' => '02', 'name' => 'Kunjungan kerja'],
                    ['code' => '03', 'name' => 'Kunjungan tamu'],
                    ['code' => '04', 'name' => 'Penyelenggaraan upacara'],
                    ['code' => '05', 'name' => 'Penyelenggaraan acara jamuan'],
                    ['code' => '06', 'name' => 'Penyelenggaraan acara peringatan hari besar nasional'],
                    ['code' => '07', 'name' => 'Penyelenggaraan acara peringatan hari besar keagamaan'],
                    ['code' => '08', 'name' => 'Penyelenggaraan acara peringatan hari besar lainnya'],
                    ['code' => '09', 'name' => 'Penyelenggaraan acara peringatan hari ulang tahun'],
                    ['code' => '10', 'name' => 'Penyelenggaraan acara peringatan hari jadi'],
                    ['code' => '11', 'name' => 'Penyelenggaraan acara peringatan hari pensiun'],
                    ['code' => '12', 'name' => 'Penyelenggaraan acara peringatan hari purna bakti'],
                    ['code' => '13', 'name' => 'Penyelenggaraan acara peringatan hari purnawirawan'],
                    ['code' => '14', 'name' => 'Penyelenggaraan acara peringatan hari veteran'],
                    ['code' => '15', 'name' => 'Penyelenggaraan acara peringatan hari pahlawan'],
                    ['code' => '16', 'name' => 'Penyelenggaraan acara peringatan hari kartini'],
                    ['code' => '17', 'name' => 'Penyelenggaraan acara peringatan hari ibu'],
                    ['code' => '18', 'name' => 'Penyelenggaraan acara peringatan hari anak'],
                    ['code' => '19', 'name' => 'Penyelenggaraan acara peringatan hari lingkungan hidup'],
                    ['code' => '20', 'name' => 'Penyelenggaraan acara peringatan hari kesehatan'],
                    ['code' => '21', 'name' => 'Penyelenggaraan acara peringatan hari olahraga'],
                    ['code' => '22', 'name' => 'Penyelenggaraan acara peringatan hari kesenian'],
                    ['code' => '23', 'name' => 'Penyelenggaraan acara peringatan hari kebudayaan'],
                    ['code' => '24', 'name' => 'Penyelenggaraan acara peringatan hari pariwisata'],
                    ['code' => '25', 'name' => 'Penyelenggaraan acara peringatan hari komunikasi'],
                    ['code' => '26', 'name' => 'Penyelenggaraan acara peringatan hari pers'],
                    ['code' => '27', 'name' => 'Penyelenggaraan acara peringatan hari telekomunikasi'],
                    ['code' => '28', 'name' => 'Penyelenggaraan acara peringatan hari informasi'],
                    ['code' => '29', 'name' => 'Penyelenggaraan acara peringatan hari teknologi'],
                    ['code' => '30', 'name' => 'Penyelenggaraan acara peringatan hari industri'],
                ],
            ],
            // --- KLASIFIKASI DAN KODE ARSIP SUBSTANTIF ---
            [
                'code' => 'IND',
                'name' => 'Industri',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Kebijakan',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengkajian dan pengusulan kebijakan'],
                            ['code' => '02', 'name' => 'Evaluasi kebijakan'],
                            ['code' => '03', 'name' => 'Penyempurnaan kebijakan'],
                            ['code' => '04', 'name' => 'Penetapan kebijakan'],
                            ['code' => '05', 'name' => 'Deregulasi Industri'],
                            ['code' => '06', 'name' => 'Pengembangan iklim industri'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Pengembangan potensi daerah',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan kawasan industri'],
                            ['code' => '02', 'name' => 'Pengembangan sentra industri'],
                            ['code' => '03', 'name' => 'Pengembangan klaster industri'],
                            ['code' => '04', 'name' => 'Pengembangan industri kecil dan menengah'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Pemberdayaan usaha',
                        'children' => [
                            ['code' => '01', 'name' => 'Bimbingan teknis'],
                            ['code' => '02', 'name' => 'Pelatihan'],
                            ['code' => '03', 'name' => 'Pendampingan'],
                            ['code' => '04', 'name' => 'Fasilitasi'],
                            ['code' => '05', 'name' => 'Kemitraan'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Promosi industri',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan promosi'],
                            ['code' => '02', 'name' => 'Fasilitas promosi'],
                            ['code' => '03', 'name' => 'Pameran dan sarana promosi'],
                        ],
                    ],
                    [
                        'code' => '05',
                        'name' => 'Standardisasi dan mutu',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan standar'],
                            ['code' => '02', 'name' => 'Penerapan standar'],
                            ['code' => '03', 'name' => 'Sertifikasi'],
                            ['code' => '04', 'name' => 'Akreditasi'],
                            ['code' => '05', 'name' => 'Pengawasan mutu'],
                        ],
                    ],
                    [
                        'code' => '06',
                        'name' => 'Inovasi dan teknologi',
                        'children' => [
                            ['code' => '01', 'name' => 'Penelitian dan pengembangan'],
                            ['code' => '02', 'name' => 'Alih teknologi'],
                            ['code' => '03', 'name' => 'Penerapan teknologi'],
                            ['code' => '04', 'name' => 'Inovasi produk'],
                        ],
                    ],
                    [
                        'code' => '07',
                        'name' => 'Investasi',
                        'children' => [
                            ['code' => '01', 'name' => 'Promosi investasi'],
                            ['code' => '02', 'name' => 'Fasilitasi investasi'],
                            ['code' => '03', 'name' => 'Pelayanan investasi'],
                            ['code' => '04', 'name' => 'Evaluasi investasi'],
                        ],
                    ],
                    [
                        'code' => '08',
                        'name' => 'Sumber daya manusia',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan kompetensi'],
                            ['code' => '02', 'name' => 'Pelatihan vokasi'],
                            ['code' => '03', 'name' => 'Sertifikasi kompetensi'],
                            ['code' => '04', 'name' => 'Penempatan tenaga kerja'],
                        ],
                    ],
                    [
                        'code' => '09',
                        'name' => 'Lingkungan',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengelolaan lingkungan'],
                            ['code' => '02', 'name' => 'Pengendalian pencemaran'],
                            ['code' => '03', 'name' => 'Pengelolaan limbah'],
                            ['code' => '04', 'name' => 'Produksi bersih'],
                        ],
                    ],
                    [
                        'code' => '10',
                        'name' => 'Energi',
                        'children' => [
                            ['code' => '01', 'name' => 'Konservasi energi'],
                            ['code' => '02', 'name' => 'Diversifikasi energi'],
                            ['code' => '03', 'name' => 'Efisiensi energi'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'KPA',
                'name' => 'Ketahanan dan Pengembangan Akses Industri Internasional',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Kerjasama bilateral dan multilateral',
                        'children' => [
                            ['code' => '01', 'name' => 'Perjanjian bilateral'],
                            ['code' => '02', 'name' => 'Perjanjian multilateral'],
                            ['code' => '03', 'name' => 'MoU kerjasama'],
                            ['code' => '04', 'name' => 'Implementasi perjanjian'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Kerjasama regional',
                        'children' => [
                            ['code' => '01', 'name' => 'ASEAN'],
                            ['code' => '02', 'name' => 'APEC'],
                            ['code' => '03', 'name' => 'Masyarakat Ekonomi Asean (MEA)'],
                            ['code' => '04', 'name' => 'Implementasi kerjasama regional'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Akses industri',
                        'children' => [
                            ['code' => '01', 'name' => 'Akses pasar'],
                            ['code' => '02', 'name' => 'Akses bahan baku'],
                            ['code' => '03', 'name' => 'Akses teknologi'],
                            ['code' => '04', 'name' => 'Akses pembiayaan'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Ketahanan industri',
                        'children' => [
                            ['code' => '01', 'name' => 'Kerja sama internasional'],
                            ['code' => '02', 'name' => 'Pengamanan dan penyelamatan persaingan global'],
                            ['code' => '03', 'name' => 'Anti-dumping dan safeguards'],
                            ['code' => '04', 'name' => 'Perlindungan industri dalam negeri'],
                        ],
                    ],
                    [
                        'code' => '05',
                        'name' => 'Perdagangan internasional',
                        'children' => [
                            ['code' => '01', 'name' => 'Ekspor'],
                            ['code' => '02', 'name' => 'Impor'],
                            ['code' => '03', 'name' => 'Tarif dan bea masuk'],
                            ['code' => '04', 'name' => 'Non-tariff measures'],
                        ],
                    ],
                    [
                        'code' => '06',
                        'name' => 'Standardisasi internasional',
                        'children' => [
                            ['code' => '01', 'name' => 'Harmonisasi standar'],
                            ['code' => '02', 'name' => 'Sertifikasi internasional'],
                            ['code' => '03', 'name' => 'Akreditasi internasional'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'IPH',
                'name' => 'Industri Pengolahan Hasil',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Industri makanan',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Industri minuman',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Industri tembakau',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'IT',
                'name' => 'Industri Tekstil',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Industri serat',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Industri benang',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Industri kain',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Industri pakaian jadi',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'IA',
                'name' => 'Industri Alas Kaki',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Industri kulit',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Industri alas kaki',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'IK',
                'name' => 'Industri Kimia',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Industri pupuk',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Industri farmasi',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Industri cat',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'IL',
                'name' => 'Industri Logam',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Industri besi baja',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Industri aluminium',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'IE',
                'name' => 'Industri Elektronika',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Industri komponen elektronika',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Industri peralatan elektronika',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                ],
            ],
            [
                'code' => 'OTM',
                'name' => 'Industri Alat Transportasi dan Mesin',
                'children' => [
                    [
                        'code' => '01',
                        'name' => 'Industri otomotif',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '02',
                        'name' => 'Industri perkapalan',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '03',
                        'name' => 'Industri pesawat terbang',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                    [
                        'code' => '04',
                        'name' => 'Industri kereta api',
                        'children' => [
                            ['code' => '01', 'name' => 'Pengembangan'],
                            ['code' => '02', 'name' => 'Standardisasi'],
                            ['code' => '03', 'name' => 'Pemasaran'],
                        ],
                    ],
                ],
            ],
        ];

        // Membuat data klasifikasi dengan struktur nested set
        foreach ($classifications as $classificationData) {
            $this->createClassificationWithChildren($classificationData);
        }
    }

    /**
     * Membuat klasifikasi dengan anak-anaknya secara rekursif
     *
     * @param array $data
     * @param Classification|null $parent
     * @return void
     */
    private function createClassificationWithChildren(array $data, ?Classification $parent = null): void
    {
        // Pisahkan data anak sebelum membuat klasifikasi
        $children = $data['children'] ?? [];
        unset($data['children']);

        // Tambahkan data default
        $data['is_active'] = true;
        
        // Buat klasifikasi
        $classification = $parent 
            ? $parent->children()->create($data)
            : Classification::create($data);

        // Buat anak-anaknya secara rekursif
        foreach ($children as $childData) {
            $this->createClassificationWithChildren($childData, $classification);
        }
    }
}