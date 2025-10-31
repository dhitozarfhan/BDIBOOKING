<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class TrainingRegistration extends Component
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

    // Form model
    public $id_diklat;
    public string $nama = '';
    public string $ktp = '';
    public $scan_ktp;
    public string $tempat_lahir = '';
    public string $tanggal_lahir = '';
    public string $id_kelamin = '';
    public string $id_agama = '';
    public string $id_pendidikan = '';
    public $scan_ijazah;
    public $scan_foto;
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

    public function mount($id_diklat)
    {
        $this->id_diklat = $id_diklat;
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
    }

    public function updatedSelectedKota($kotaId)
    {
        $this->kecamatan = [];
        $this->selectedKecamatan = '';
        $this->desa = [];
        $this->selectedDesa = '';
    }
    
    public function updatedSelectedKecamatan($kecamatanId)
    {
        $this->desa = [];
        $this->selectedDesa = '';
    }

    public function submit()
    {
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

        $this->validate($rules);

        // Validation passed, handle submission...
        Log::info('Registration form validated successfully.');
    }

    public function render()
    {
        return view('livewire.training.registration')->title('Form Pendaftaran Diklat');
    }
}