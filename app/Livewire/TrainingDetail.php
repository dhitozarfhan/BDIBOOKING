<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TrainingDetail extends Component
{
    public ?array $training = null;
    public string $error = '';
    public $id_diklat;

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
            $this->error = 'Konfigurasi API SIDIA belum lengkap. Silakan periksa file .env Anda.';
            Log::error('SIDIA API credentials are not fully configured.');
            return;
        }

        try {
            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sidia.url') . '/register/training', [
                    $credentials['key_name'] => $credentials['key'],
                    'id_diklat'              => $this->id_diklat,
                ]);

            if ($response->successful()) {
                $rawBody = $response->body();
                $decodedBody = json_decode($rawBody, true);

                $data = null;
                if (json_last_error() === JSON_ERROR_NONE && is_string($decodedBody)) {
                    $data = json_decode($decodedBody, true);
                } elseif (is_array($decodedBody)) {
                    $data = $decodedBody;
                }

                if (isset($data['data']['diklat']) && is_array($data['data']['diklat'])) {
                    $this->training = $data['data']['diklat'];
                } else {
                    $this->training = null;
                }

                if (empty($this->training)) {
                    $this->error = 'Data diklat tidak ditemukan atau tidak valid.';
                }

            } else {
                $this->error = "Gagal mengambil data dari API (Status: " . $response->status() . ").";
                Log::error('SIDIA API request failed for training detail', [
                    'status'    => $response->status(),
                    'response'  => $response->body(),
                    'id_diklat' => $this->id_diklat,
                ]);
            }
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat menghubungi API.';
            Log::error('SIDIA API request exception for training detail: ' . $e->getMessage(), ['id_diklat' => $this->id_diklat]);
        }
    }

    public function render()
    {
        return view('livewire.training.detail')->title($this->training['nama'] ?? 'Detail Diklat');
    }
}
