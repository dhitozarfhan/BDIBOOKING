<?php

namespace App\Livewire\Training;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class KemenperinRegistration extends Component
{
    public $id_diklat;
    public $slug;
    public string $username = '';
    public string $password = '';
    public string $error = '';

    public function mount($id_diklat, $slug = null)
    {
        $this->id_diklat = $id_diklat;
        $this->slug = $slug;
    }

    public function submit()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => config('services.sippa.username'),
            'password' => config('services.sippa.password'),
            'key'      => config('services.sippa.key'),
            'key_name' => config('services.sippa.key_name'),
        ];

        try {
            $loginResponse = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sippa.url') . '/intranet/login', [
                    $credentials['key_name'] => $credentials['key'],
                    'username' => $this->username,
                    'password' => $this->password,
                ]);

            if (!$loginResponse->successful()) {
                $this->error = 'Gagal menghubungi layanan intranet.';
                Log::error('Intranet login failed', ['status' => $loginResponse->status(), 'response' => $loginResponse->body()]);
                return;
            }

            $loginData = $loginResponse->json();
            if (is_string($loginData)) {
                $loginData = json_decode($loginData, true);
            }

            // dd($loginData);

            if (isset($loginData['status']) && $loginData['status'] == 1 && !empty($loginData['data']['nip_intranet'])) {
                $nip_intranet = $loginData['data']['nip_intranet'];

                $biodataResponse = Http::withBasicAuth($credentials['username'], $credentials['password'])
                    ->post(config('services.sippa.url') . '/intranet/biodata', [
                        $credentials['key_name'] => $credentials['key'],
                        'nip_intranet' => $nip_intranet,
                        'password' => $this->password,
                    ]);

                if ($biodataResponse->successful()) {
                    $biodata = $biodataResponse->json();

                    if (is_string($biodata)) {
                        $biodata = json_decode($biodata, true);
                    }

                    if (isset($biodata['status']) && $biodata['status'] == 1) {
                        session(['kemenperin_user_data' => $biodata['data']]);
                        return redirect()->route('training.register', ['id_diklat' => $this->id_diklat, 'slug' => $this->slug, 'from_kemenperin' => 1]);
                    } else {
                        $this->error = $biodata['message'] ?? 'Gagal mengambil biodata.';
                    }
                } else {
                    $this->error = 'Gagal menghubungi layanan biodata.';
                    Log::error('Intranet biodata failed', ['status' => $biodataResponse->status(), 'response' => $biodataResponse->body()]);
                }
            } else {
                $this->error = $loginData['message'] ?? 'Username dan password tidak cocok.';
            }
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat menghubungi layanan Intranet Kemenperin. Silakan coba lagi.';
            Log::error('Intranet login exception: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.training.kemenperin-registration')->title('Pendaftaran Kemenperin');
    }
}
