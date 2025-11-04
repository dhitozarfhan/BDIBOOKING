<?php

namespace App\Livewire\Training;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Registration extends Component
{
    use WithFileUploads;

    // Data from API
    public ?array $diklat = null;
    public array $agama = [];
    public array $kelamin = [];
    public array $pendidikan = [];
    public array $provinsi = [];
    public array $kota = [];
    public array $kecamatan = [];
    public array $desa = [];
    public array $pangkat = [];
    public array $satker = [];

    // Form model
    public $id_diklat;
    public string $nama = '';
    public string $titel = '';
    public string $gelar = '';
    public string $ktp = '';
    public $scan_ktp;
    public string $tempat_lahir = '';
    public string $tanggal_lahir = '';
    public string $id_kelamin = '';
    public string $id_agama = '';
    public string $id_pendidikan = '';
    public $scan_ijazah;
    public $scan_foto;
    public $scan_surat_usulan;
    public $scan_surat_tugas;
    public $scan_surat_kesediaan;
    public $scan_suket_pengalaman_kerja;
    public string $pendidikan_jurusan = '';
    public string $pendidikan_tamat = '';

    // Address
    public string $selectedProvinsi = '';
    public string $selectedKota = '';
    public string $selectedKecamatan = '';
    public string $selectedDesa = '';
    public string $dusun = '';
    public string $rt = '';
    public string $rw = '';

    // Contact
    public string $telepon = '';
    public string $mobile = '';
    public string $email = '';
    public string $ttd = '';

    // SDMA fields
    public string $nip = '';
    public string $id_pangkat = '';
    public string $jabatan = '';
    public string $id_satker_jenis = '';
    public string $id_provinsi_satker = '';
    public string $id_kota_satker = '';
    public string $id_satker = '';
    public bool $from_kemenperin = false;

    // Infrastruktur Kompetensi fields
    public string $nomor_reg_asesor = '';
    public string $id_lsp = '';
    public string $id_skema = '';
    public string $instansi_nama = '';
    public string $instansi_jabatan = '';
    public string $instansi_alamat = '';
    public string $instansi_telepon = '';
    public string $instansi_fax = '';
    public string $instansi_email = '';

    // BigData fields
    public string $id_pekerjaan_sebelumnya = '';
    public string $id_penghasilan_sebelumnya = '';
    public string $id_status_nikah = '';
    public string $tanggal_lahir_pasangan = '';
    public string $id_pekerjaan_pasangan = '';
    public string $id_penghasilan_pasangan = '';
    public string $jumlah_anak = '';
    public string $id_status_hidup_ibu = '';
    public string $tanggal_lahir_ibu = '';
    public string $id_pekerjaan_ibu = '';
    public string $id_status_hidup_ayah = '';
    public string $tanggal_lahir_ayah = '';
    public string $id_pekerjaan_ayah = '';
    public string $id_penghasilan_ortu = '';

    // Approval
    public bool $approval = false;
    
    public string $error = '';

    protected $validationAttributes = [
        'nama' => 'Nama Lengkap',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'id_kelamin' => 'Jenis Kelamin',
        'id_agama' => 'Agama',
        'id_pendidikan' => 'Pendidikan',
        'pendidikan_jurusan' => 'Jurusan',
        'pendidikan_tamat' => 'Tahun Ijazah',
        'ktp' => 'Nomor KTP / NIK',
        'mobile' => 'Nomor Handphone',
        'email' => 'Email',
        'selectedProvinsi' => 'Provinsi',
        'selectedKota' => 'Kota',
        'selectedKecamatan' => 'Kecamatan',
        'selectedDesa' => 'Desa',
        'dusun' => 'Dusun/Jalan',
        'approval' => 'Persetujuan',
        'nip' => 'NIP',
        'id_pangkat' => 'Pangkat',
        'jabatan' => 'Jabatan',
        'id_satker' => 'Satuan Kerja',
        'id_lsp' => 'LSP Induk',
        'id_skema' => 'Skema Sertifikasi',
        'instansi_nama' => 'Nama Instansi',
        'instansi_jabatan' => 'Jabatan di Instansi',
        'instansi_alamat' => 'Alamat Instansi',
        'instansi_telepon' => 'Telepon Instansi',
        'ttd' => 'Tanda Tangan',
        'scan_foto' => 'Scan Foto',
        'scan_ktp' => 'Scan KTP',
        'scan_ijazah' => 'Scan Ijazah',
        'scan_surat_usulan' => 'Scan Surat Usulan',
        'scan_surat_tugas' => 'Scan Surat Tugas',
        'scan_surat_kesediaan' => 'Scan Surat Kesediaan',
        'scan_suket_pengalaman_kerja' => 'Scan Surat Keterangan Pengalaman Kerja',
    ];

    protected $messages = [
        'approval.accepted' => 'Anda harus menyetujui syarat dan ketentuan yang berlaku.',
        'mobile.regex' => 'Format Nomor Handphone tidak valid. Contoh: 081234567890',
        'ktp.digits' => ':attribute harus 16 digit.',
        'pendidikan_tamat.digits' => ':attribute harus 4 digit.',
        'nip.digits' => ':attribute harus 18 digit.',
        'email.email' => 'Format :attribute tidak valid.',
        'scan_foto.required' => 'Bagian foto peserta wajib diisi.',
        'scan_ktp.required' => 'Bagian KTP peserta wajib diisi.',
        'scan_ijazah.required' => 'Bagian ijazah peserta wajib diisi.',
        'scan_surat_usulan.required' => 'Bagian surat usulan peserta wajib diisi.',
        'scan_surat_tugas.required' => 'Bagian surat tugas peserta wajib diisi.',
        'scan_surat_kesediaan.required' => 'Bagian surat kesediaan peserta wajib diisi.',
        'scan_suket_pengalaman_kerja.required' => 'Bagian surat keterangan pengalaman kerja peserta wajib diisi.',
        'scan_foto.mimes' => 'Format berkas foto harus JPG atau JPEG.',
        'scan_ktp.mimes' => 'Format berkas KTP harus JPG atau JPEG.',
        'scan_ijazah.mimes' => 'Format berkas ijazah harus JPG atau JPEG.',
        'scan_surat_usulan.mimes' => 'Format berkas surat usulan harus PDF.',
        'scan_surat_tugas.mimes' => 'Format berkas surat tugas harus PDF.',
        'scan_surat_kesediaan.mimes' => 'Format berkas surat kesediaan harus PDF.',
        'scan_suket_pengalaman_kerja.mimes' => 'Format berkas surat keterangan pengalaman kerja harus PDF.',
        'ttd.required' => 'Tanda tangan wajib dibubuhkan pada form ini.',
        'ttd.min' => 'Tanda tangan tidak terdeteksi dengan baik, silakan coba lagi.'
    ];

    public function mount($id_diklat, $slug = null, $from_kemenperin = false)
    {
        $this->id_diklat = $id_diklat;
        $this->from_kemenperin = (bool)$from_kemenperin;

        if ($this->from_kemenperin && session()->has('kemenperin_user_data')) {
            $userData = session('kemenperin_user_data.peserta');
            $this->nip = $userData['nip'] ?? '';
            $this->nama = $userData['nama'] ?? '';
            $this->titel = $userData['titel'] ?? '';
            $this->gelar = $userData['gelar'] ?? '';
            $this->tempat_lahir = $userData['tempat_lahir'] ?? '';
            $this->tanggal_lahir = $userData['tanggal_lahir'] ?? '';
            $this->ktp = $userData['ktp'] ?? '';
            $this->id_kelamin = $userData['id_kelamin'] ?? '';
            $this->id_agama = $userData['id_agama'] ?? '';
            $this->telepon = $userData['pelanggan_telepon'] ?? '';
            $this->mobile = $userData['pelanggan_mobile'] ?? '';
            $this->email = $userData['pelanggan_email'] ?? '';
            $this->dusun = $userData['pelanggan_dusun'] ?? '';
            $this->rt = $userData['pelanggan_rt'] ?? '';
            $this->rw = $userData['pelanggan_rw'] ?? '';
            $this->selectedProvinsi = $userData['pelanggan_id_provinsi'] ?? '';
            $this->selectedKota = $userData['pelanggan_id_kota'] ?? '';
            $this->selectedKecamatan = $userData['pelanggan_id_kecamatan'] ?? '';
            $this->selectedDesa = $userData['pelanggan_id_desa'] ?? '';
            $this->id_pendidikan = $userData['id_pendidikan'] ?? '';
            $this->pendidikan_jurusan = $userData['pendidikan_jurusan'] ?? '';
            $this->pendidikan_tamat = $userData['pendidikan_tamat'] ?? '';
            $this->id_pangkat = $userData['id_pangkat'] ?? '';
            $this->jabatan = $userData['jabatan'] ?? '';
            $this->id_satker = $userData['id_satker'] ?? '';

            $masterData = session('kemenperin_user_data.master');
            if(isset($masterData['pangkat'])) {
                $this->pangkat = $masterData['pangkat'];
            }
            if(isset($masterData['satker'])) {
                $this->satker = $masterData['satker'];
            }

            if($this->selectedProvinsi) {
                $this->updatedSelectedProvinsi($this->selectedProvinsi);
            }
            if($this->selectedKota) {
                $this->updatedSelectedKota($this->selectedKota);
            }
            if($this->selectedKecamatan) {
                $this->updatedSelectedKecamatan($this->selectedKecamatan);
            }
        }

        $credentials = [
            'username' => config('services.sidia.username'),
            'password' => config('services.sidia.password'),
            'key'      => config('services.sidia.key'),
            'key_name' => config('services.sidia.key_name'),
        ];

        if (empty($credentials['username']) || empty($credentials['password']) || empty($credentials['key']) || empty($credentials['key_name'])) {
            $this->error = 'Konfigurasi API SIDIA belum lengkap.';
            return;
        }

        try {
            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sidia.url') . '/register/training/form', [
                    $credentials['key_name'] => $credentials['key'],
                    'id_diklat'              => $this->id_diklat,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (is_string($data)) {
                    $data = json_decode($data, true);
                }

                if (isset($data['status']) && $data['status'] == 1) {
                    $this->diklat = $data['data']['diklat'] ?? null;
                    $this->agama = $data['data']['agama'] ?? [];
                    $this->kelamin = $data['data']['kelamin'] ?? [];
                    $this->pendidikan = $data['data']['pendidikan'] ?? [];
                    $this->provinsi = $data['data']['w_provinsi'] ?? [];
                } else {
                    $this->error = 'Gagal memuat data form pendaftaran: ' . ($data['message'] ?? 'Status API tidak valid.');
                }
            } else {
                $this->error = "Gagal mengambil data dari API (Status: " . $response->status() . ").";
            }
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat menghubungi API.';
            Log::error('SIDIA API request exception for training form: ' . $e->getMessage(), ['id_diklat' => $this->id_diklat]);
        }
    }
    
    public function updatedSelectedProvinsi($provinsiId)
    {
        $this->kota = [];
        $this->selectedKota = '';
        $this->kecamatan = [];
        $this->selectedKecamatan = '';
        $this->desa = [];
        $this->selectedDesa = '';

        if (empty($provinsiId)) {
            return;
        }

        $wilayah = $this->fetchWilayah('provinsi', $provinsiId);

        $this->kota = array_map(static function ($item) {
            return [
                'id_kota' => $item['value'],
                'kota' => $item['name'],
            ];
        }, $wilayah);
    }

    public function updatedSelectedKota($kotaId)
    {
        $this->kecamatan = [];
        $this->selectedKecamatan = '';
        $this->desa = [];
        $this->selectedDesa = '';

        if (empty($kotaId)) {
            return;
        }

        $wilayah = $this->fetchWilayah('kota', $kotaId);

        $this->kecamatan = array_map(static function ($item) {
            return [
                'id_kecamatan' => $item['value'],
                'kecamatan' => $item['name'],
            ];
        }, $wilayah);
    }
    
    public function updatedSelectedKecamatan($kecamatanId)
    {
        $this->desa = [];
        $this->selectedDesa = '';

        if (empty($kecamatanId)) {
            return;
        }

        $wilayah = $this->fetchWilayah('kecamatan', $kecamatanId);

        $this->desa = array_map(static function ($item) {
            return [
                'id_desa' => $item['value'],
                'desa' => $item['name'],
            ];
        }, $wilayah);
    }

    public function submit()
    {
        if (empty($this->diklat)) {
            $this->error = 'Data diklat tidak tersedia. Silakan muat ulang halaman.';
            return;
        }

        $rules = [
            'nama' => 'required|string|max:50',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'id_kelamin' => 'required',
            'id_agama' => 'required',
            'id_pendidikan' => 'required',
            'pendidikan_jurusan' => 'required|string|max:200',
            'pendidikan_tamat' => 'required|numeric|digits:4',
            'ktp' => [
                'required',
                'numeric',
                'digits:16',
                function ($attribute, $value, $fail) {
                    $credentials = [
                        'username' => config('services.sidia.username'),
                        'password' => config('services.sidia.password'),
                        'key'      => config('services.sidia.key'),
                        'key_name' => config('services.sidia.key_name'),
                    ];
                    try {
                        $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
                            ->post(config('services.sidia.url') . '/register/is_allowed', [
                                $credentials['key_name'] => $credentials['key'],
                                'ktp' => $value,
                                'id_diklat' => $this->id_diklat,
                            ]);
                        
                        $result = $response->json();
                        if (is_string($result)) {
                            $result = json_decode($result, true);
                        }

                        if (isset($result['data']['allowed']) && !$result['data']['allowed']) {
                            $fail('Nomor KTP atau biodata peserta yang Anda masukkan sudah ada sebelumnya.');
                        }
                    } catch (\Exception $e) {
                        $fail('Terjadi kesalahan dalam memvalidasi KTP via API.');
                    }
                },
            ],
            'mobile' => ['required', 'string', 'max:30', 'regex:/^[0]{1}[1-9]{1}[0-9]{7,15}$/'],
            'email' => 'required|email|max:70',
            'selectedProvinsi' => 'required',
            'selectedKota' => 'required',
            'selectedKecamatan' => 'required',
            'selectedDesa' => 'required',
            'dusun' => 'required|string|max:100',
            'rt' => 'nullable|numeric|min:1',
            'rw' => 'nullable|numeric|min:1',
            'approval' => 'accepted',
            'ttd' => 'required|min:6000',
        ];

        if ($this->diklat['jenis'] == 'sdma') {
            $rules['nip'] = 'required|numeric|digits:18';
            $rules['id_pangkat'] = 'required';
            $rules['jabatan'] = 'required|string|max:200';
            $rules['id_satker'] = 'required';
        }

        if ($this->diklat['jenis'] == 'infrastruktur_kompetensi') {
            $rules['id_lsp'] = 'required';
            $rules['id_skema'] = 'required';
            $rules['instansi_nama'] = 'required|string|max:200';
            $rules['instansi_jabatan'] = 'required|string|max:200';
            $rules['instansi_alamat'] = 'required|string|max:300';
            $rules['instansi_telepon'] = 'required|string|max:30';
        }
        
        if ($this->diklat['bigdata'] == 'Y') {
            $rules['id_pekerjaan_sebelumnya'] = 'required';
            $rules['id_penghasilan_sebelumnya'] = 'required';
            $rules['id_status_nikah'] = 'required';
            $rules['id_status_hidup_ibu'] = 'required';
            $rules['tanggal_lahir_ibu'] = 'required|date';
            $rules['id_pekerjaan_ibu'] = 'required';
            $rules['id_status_hidup_ayah'] = 'required';
            $rules['tanggal_lahir_ayah'] = 'required|date';
            $rules['id_pekerjaan_ayah'] = 'required';
            $rules['id_penghasilan_ortu'] = 'required';

            if ($this->id_status_nikah == 'M') { // Assuming 'M' means 'Married'
                $rules['tanggal_lahir_pasangan'] = 'required|date';
                $rules['id_pekerjaan_pasangan'] = 'required';
                $rules['id_penghasilan_pasangan'] = 'required';
                $rules['jumlah_anak'] = 'required|numeric|min:0|max:100';
            }
        }

        $fileRules = [
            'scan_foto' => 'jpg,jpeg',
            'scan_ktp' => 'jpg,jpeg',
            'scan_ijazah' => 'jpg,jpeg',
            'scan_surat_usulan' => 'pdf',
            'scan_surat_tugas' => 'pdf',
            'scan_surat_kesediaan' => 'pdf',
            'scan_suket_pengalaman_kerja' => 'pdf',
        ];

        foreach ([
            'scan_foto' => 'kapan_upload_foto',
            'scan_ktp' => 'kapan_upload_ktp',
            'scan_ijazah' => 'kapan_upload_ijazah',
            'scan_surat_usulan' => 'kapan_upload_surat_usulan',
            'scan_surat_tugas' => 'kapan_upload_surat_tugas',
            'scan_surat_kesediaan' => 'kapan_upload_surat_kesediaan',
            'scan_suket_pengalaman_kerja' => 'kapan_upload_suket_pengalaman_kerja',
        ] as $field => $timing) {
            if ($this->shouldProcessUpload($field, $timing)) {
                $rules[$field] = [
                    $this->isUploadRequired($field, $timing) ? 'required' : 'nullable',
                    'file',
                    'mimes:' . ($fileRules[$field] ?? 'jpg,jpeg'),
                ];
            }
        }

        $this->validate($rules);

        $this->error = '';

        $filePayload = $this->prepareFilePayload();
        if ($filePayload === false) {
            // Specific field error already set in prepareFilePayload.
            return;
        }

        $pesertaPayload = $this->buildPesertaPayload();

        $credentials = $this->getSidiaCredentials();
        if (!$credentials) {
            $this->error = 'Konfigurasi API SIDIA belum lengkap.';
            Log::warning('Attempted to submit registration without complete SIDIA credentials.', ['id_diklat' => $this->id_diklat]);
            return;
        }

        try {
            $post = [
                $credentials['key_name'] => $credentials['key'],
                'id_diklat' => $this->id_diklat,
                'peserta' => $pesertaPayload,
                'file' => $filePayload,
            ];
            // dd($post);
            // exit();
            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sidia.url') . '/register', $post);

            if ($response->successful()) {
                $result = $response->json();
                if (is_string($result)) {
                    $result = json_decode($result, true);
                }

                if (($result['status'] ?? null) == 1) {
                    $diklatName = $this->diklat['nama_lengkap'] ?? $this->diklat['nama'] ?? 'diklat ini';
                    $message = 'Pendaftaran Anda pada "' . $diklatName . '" sudah kami terima, silakan menunggu verifikasi dari admin diklat kami.';
                    session()->flash('success', $message);
                    Log::info('Training registration submitted successfully.', [
                        'id_diklat' => $this->id_diklat,
                        'ktp' => $this->ktp,
                    ]);

                    return redirect()->route('training.detail', ['id_diklat' => $this->id_diklat]);
                }

                $this->error = $result['message'] ?? 'Pendaftaran gagal diproses.';
                Log::warning('Training registration rejected by SIDIA.', [
                    'response' => $result,
                    'id_diklat' => $this->id_diklat,
                    'ktp' => $this->ktp,
                ]);
                return;
            }

            $this->error = 'Gagal mengirim data ke API (Status: ' . $response->status() . ').';
            Log::error('Training registration request failed.', [
                'status' => $response->status(),
                'body' => $response->body(),
                'id_diklat' => $this->id_diklat,
                'ktp' => $this->ktp,
            ]);
            return;
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat menghubungi API.';
            Log::error('Exception when submitting training registration: ' . $e->getMessage(), [
                'id_diklat' => $this->id_diklat,
                'ktp' => $this->ktp,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.training.registration')->title('Form Pendaftaran Diklat');
    }

    private function prepareFilePayload(): array|false
    {
        $files = [
            'foto' => null,
            'ktp' => null,
            'ijazah' => null,
            'surat_usulan' => null,
            'surat_tugas' => null,
            'surat_kesediaan' => null,
            'suket_pengalaman_kerja' => null,
            'bukti_kompetensi' => null,
            'sertifikat_asesor' => null,
            'ma' => null,
            'mpa_mkva' => null,
            'mma_mapa' => null,
            'ttd' => null,
        ];

        if (empty($this->diklat)) {
            return $files;
        }

        $uploads = [
            'scan_foto' => [
                'destination' => 'foto',
                'timing' => 'kapan_upload_foto',
                'message' => 'Bagian foto peserta wajib diisi.',
            ],
            'scan_ktp' => [
                'destination' => 'ktp',
                'timing' => 'kapan_upload_ktp',
                'message' => 'Bagian KTP peserta wajib diisi.',
            ],
            'scan_ijazah' => [
                'destination' => 'ijazah',
                'timing' => 'kapan_upload_ijazah',
                'message' => 'Bagian ijazah peserta wajib diisi.',
            ],
            'scan_surat_usulan' => [
                'destination' => 'surat_usulan',
                'timing' => 'kapan_upload_surat_usulan',
                'message' => 'Bagian surat usulan peserta wajib diisi.',
            ],
            'scan_surat_tugas' => [
                'destination' => 'surat_tugas',
                'timing' => 'kapan_upload_surat_tugas',
                'message' => 'Bagian surat tugas peserta wajib diisi.',
            ],
            'scan_surat_kesediaan' => [
                'destination' => 'surat_kesediaan',
                'timing' => 'kapan_upload_surat_kesediaan',
                'message' => 'Bagian surat kesediaan peserta wajib diisi.',
            ],
            'scan_suket_pengalaman_kerja' => [
                'destination' => 'suket_pengalaman_kerja',
                'timing' => 'kapan_upload_suket_pengalaman_kerja',
                'message' => 'Bagian surat keterangan pengalaman kerja peserta wajib diisi.',
            ],
        ];

        $fileRules = [
            'scan_foto' => 'jpg,jpeg',
            'scan_ktp' => 'jpg,jpeg',
            'scan_ijazah' => 'jpg,jpeg',
            'scan_surat_usulan' => 'pdf',
            'scan_surat_tugas' => 'pdf',
            'scan_surat_kesediaan' => 'pdf',
            'scan_suket_pengalaman_kerja' => 'pdf',
        ];

        foreach ($uploads as $property => $config) {
            $shouldHandle = $this->shouldProcessUpload($property, $config['timing']);
            $hasUpload = $this->{$property} instanceof TemporaryUploadedFile;

            if (!$shouldHandle) {
                if ($hasUpload) {
                    Log::info('Ignoring uploaded file because diklat configuration does not require initial upload.', [
                        'field' => $property,
                        'id_diklat' => $this->id_diklat,
                        'scan_flag' => $this->diklat[$property] ?? null,
                        'timing' => $this->diklat[$config['timing']] ?? null,
                    ]);
                }
                continue;
            }

            $result = $this->processFileUpload(
                $this->{$property},
                $property,
                $this->isUploadRequired($property, $config['timing']),
                $config['message'],
                $fileRules[$property] ?? 'jpg,jpeg'
            );

            if ($result === false) {
                return false;
            }

            $files[$config['destination']] = $result;
        }

        if (!empty($this->ttd)) {
            $ttdData = $this->extractBase64Data($this->ttd);
            if ($ttdData !== null) {
                $files['ttd'] = $ttdData;
            }
        }

        return $files;
    }

    private function processFileUpload($file, string $field, bool $required, string $requiredMessage, string $allowedMimes): string|null|false
    {
        if (!$file) {
            if ($required) {
                $this->addError($field, $requiredMessage);
                Log::warning('Missing required upload for field.', ['field' => $field, 'id_diklat' => $this->id_diklat]);
                return false;
            }
            return null;
        }

        if (!$file instanceof TemporaryUploadedFile) {
            $this->addError($field, 'Berkas yang diunggah tidak valid.');
            Log::warning('Invalid upload instance detected.', ['field' => $field, 'id_diklat' => $this->id_diklat]);
            return false;
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, explode(',', $allowedMimes), true)) {
            $this->addError($field, 'Format file tidak diizinkan.');
            return false;
        }

        try {
            $mime = $file->getMimeType() ?: 'application/octet-stream';
            $contents = file_get_contents($file->getRealPath());
        } catch (\Throwable $throwable) {
            Log::error('Failed to read uploaded file.', [
                'field' => $field,
                'id_diklat' => $this->id_diklat,
                'error' => $throwable->getMessage(),
            ]);
            $this->addError($field, 'Gagal memproses berkas yang diunggah.');
            return false;
        }

        if ($contents === false) {
            $this->addError($field, 'Gagal memproses berkas yang diunggah.');
            return false;
        }

        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }

    private function shouldProcessUpload(string $flagKey, string $timingKey): bool
    {
        return in_array($this->diklat[$flagKey] ?? 'N', ['O', 'Y'], true)
            && ($this->diklat[$timingKey] ?? '') === 'initial';
    }

    private function isUploadRequired(string $flagKey, string $timingKey): bool
    {
        return ($this->diklat[$flagKey] ?? 'N') === 'Y'
            && ($this->diklat[$timingKey] ?? '') === 'initial';
    }

    private function buildPesertaPayload(): array
    {
        $payload = [
            'ktp' => $this->ktp,
            'nama' => $this->nama,
            'titel' => $this->titel,
            'gelar' => $this->gelar,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'id_kelamin' => $this->id_kelamin,
            'telepon' => $this->telepon,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'id_desa' => $this->selectedDesa,
            'dusun' => $this->dusun,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'id_agama' => $this->id_agama,
            'id_pendidikan' => $this->id_pendidikan,
            'pendidikan_jurusan' => $this->pendidikan_jurusan,
            'pendidikan_tamat' => $this->pendidikan_tamat,
        ];

        if (($this->diklat['jenis'] ?? '') === 'sdma') {
            $payload['nip'] = $this->nip;
            $payload['id_pangkat'] = $this->id_pangkat;
            $payload['jabatan'] = $this->jabatan;
            $payload['id_satker'] = $this->id_satker;
        }

        if (($this->diklat['jenis'] ?? '') === 'infrastruktur_kompetensi') {
            $payload['nomor_reg_asesor'] = $this->nomor_reg_asesor;
            $payload['id_lsp'] = $this->id_lsp;
            $payload['id_skema'] = $this->id_skema;
            $payload['instansi_nama'] = $this->instansi_nama;
            $payload['instansi_jabatan'] = $this->instansi_jabatan;
            $payload['instansi_alamat'] = $this->instansi_alamat;
            $payload['instansi_telepon'] = $this->instansi_telepon;
            $payload['instansi_fax'] = $this->instansi_fax;
            $payload['instansi_email'] = $this->instansi_email;
        }

        if (($this->diklat['bigdata'] ?? 'N') === 'Y') {
            $payload['id_pekerjaan_sebelumnya'] = $this->id_pekerjaan_sebelumnya;
            $payload['id_penghasilan_sebelumnya'] = $this->id_penghasilan_sebelumnya;
            $payload['id_status_nikah'] = $this->id_status_nikah;
            $payload['tanggal_lahir_pasangan'] = $this->tanggal_lahir_pasangan;
            $payload['id_pekerjaan_pasangan'] = $this->id_pekerjaan_pasangan;
            $payload['id_penghasilan_pasangan'] = $this->id_penghasilan_pasangan;
            $payload['jumlah_anak'] = $this->jumlah_anak;
            $payload['id_status_hidup_ibu'] = $this->id_status_hidup_ibu;
            $payload['tanggal_lahir_ibu'] = $this->tanggal_lahir_ibu;
            $payload['id_pekerjaan_ibu'] = $this->id_pekerjaan_ibu;
            $payload['id_status_hidup_ayah'] = $this->id_status_hidup_ayah;
            $payload['tanggal_lahir_ayah'] = $this->tanggal_lahir_ayah;
            $payload['id_pekerjaan_ayah'] = $this->id_pekerjaan_ayah;
            $payload['id_penghasilan_ortu'] = $this->id_penghasilan_ortu;
        }

        foreach ($payload as $key => $value) {
            if ($value === '') {
                $payload[$key] = null;
            }
        }

        return $payload;
    }

    private function extractBase64Data(?string $dataUrl): ?string
    {
        if (empty($dataUrl)) {
            return null;
        }

        $parts = explode(',', $dataUrl, 2);
        return $parts[1] ?? $parts[0] ?? null;
    }

    private function getSidiaCredentials(): ?array
    {
        $credentials = [
            'username' => config('services.sidia.username'),
            'password' => config('services.sidia.password'),
            'key'      => config('services.sidia.key'),
            'key_name' => config('services.sidia.key_name'),
        ];

        if (empty($credentials['username']) || empty($credentials['password']) || empty($credentials['key']) || empty($credentials['key_name'])) {
            return null;
        }

        return $credentials;
    }

    /**
     * Fetch wilayah data from SIDIA API based on the provided level.
     */
    private function fetchWilayah(string $wilayah, string $idWilayah): array
    {
        if (empty($idWilayah)) {
            return [];
        }

        $credentials = $this->getSidiaCredentials();
        if (!$credentials) {
            Log::warning('SIDIA credentials incomplete when fetching wilayah data.', ['wilayah' => $wilayah]);
            return [];
        }

        try {
            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sidia.url') . '/fetch/wilayah', [
                    $credentials['key_name'] => $credentials['key'],
                    'id_wilayah' => $idWilayah,
                    'wilayah' => $wilayah,
                ]);

            if (!$response->successful()) {
                Log::warning('Failed to fetch wilayah data from SIDIA.', ['wilayah' => $wilayah, 'status' => $response->status()]);
                return [];
            }

            $result = $response->json();
            if (is_string($result)) {
                $result = json_decode($result, true);
            }

            if (($result['status'] ?? null) != 1 || !isset($result['data']['wilayah']) || !is_array($result['data']['wilayah'])) {
                Log::warning('Unexpected response structure when fetching wilayah data from SIDIA.', ['wilayah' => $wilayah, 'payload' => $result]);
                return [];
            }

            return array_values(array_filter($result['data']['wilayah'], static function ($item) {
                return !empty($item['value']) && !empty($item['name']);
            }));
        } catch (\Exception $e) {
            Log::error('SIDIA API exception when fetching wilayah data: ' . $e->getMessage(), ['wilayah' => $wilayah, 'id_wilayah' => $idWilayah]);
            return [];
        }
    }
}
