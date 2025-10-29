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
                
                // The actual data might be in a double-encoded JSON string
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
    
    // Placeholder for cascading dropdown logic
    public function updatedSelectedProvinsi($provinsiId)
    {
        // This would typically make an API call to get cities based on province ID
        // For now, it just resets the dependent dropdowns.
        $this->kota = [];
        $this->selectedKota = '';
        $this->kecamatan = [];
        $this->selectedKecamatan = '';
        $this->desa = [];
        $this->selectedDesa = '';
    }

    public function updatedSelectedKota($kotaId)
    {
        // API call to get districts based on city ID
        $this->kecamatan = [];
        $this->selectedKecamatan = '';
        $this->desa = [];
        $this->selectedDesa = '';
    }
    
    public function updatedSelectedKecamatan($kecamatanId)
    {
        // API call to get villages based on district ID
        $this->desa = [];
        $this->selectedDesa = '';
    }

    public function submit()
    {
        // Validation logic would go here
        
        // API call to submit the form data would go here

        // Redirect to a success page or show a success message
    }

    public function render()
    {
        return view('livewire.training.registration')->title('Form Pendaftaran Diklat');
    }
}
