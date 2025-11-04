<?php

namespace App\Livewire\Training;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Register extends Component
{
    public array $trainings = [];
    public string $error = '';

    public function mount()
    {
        $credentials = [
            'username' => config('services.sidia.username'),
            'password' => config('services.sidia.password'),
            'key'  => config('services.sidia.key'),
            'key_name'  => config('services.sidia.key_name'),
        ];

        if (empty($credentials['username']) || empty($credentials['password']) || empty($credentials['key']) || empty($credentials['key_name'])) {
            $this->error = 'Konfigurasi API SIDIA belum lengkap. Silakan periksa file .env Anda.';
            Log::error('SIDIA API credentials are not fully configured.');
            return;
        }

        try {
            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sidia.url') . '/register/available', [
                    $credentials['key_name'] => $credentials['key'],
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
                    $trainings = $data['data']['diklat'];
                    usort($trainings, function($a, $b) {
                        $nameA = preg_replace('/ Angkatan .*/', '', $a['nama']);
                        $nameB = preg_replace('/ Angkatan .*/', '', $b['nama']);

                        if ($nameA == $nameB) {
                            return $a['angkatan'] <=> $b['angkatan'];
                        }
                        return strcmp($nameA, $nameB);
                    });
                    $this->trainings = $trainings;
                } else {
                    $this->trainings = [];
                }
            } else {
                $this->error = "Gagal mengambil data dari API (Status: " . $response->status() . ").";
                Log::error('SIDIA API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat menghubungi API.';
            Log::error('SIDIA API request exception: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.training.register')->title('Pendaftaran Diklat');
    }
}
