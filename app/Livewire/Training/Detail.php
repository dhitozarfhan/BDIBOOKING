<?php

namespace App\Livewire\Training;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Detail extends Component
{
    public ?array $training = null;
    public array $participants = [];
    public string $error = '';
    public $id_diklat;
    public ?string $successMessage = null;

    public function mount($id_diklat)
    {
        $this->id_diklat = $id_diklat;
        $this->successMessage = session()->get('success');

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
                } else {
                    try {
                        $participantResponse = Http::withBasicAuth($credentials['username'], $credentials['password'])
                            ->post(config('services.sidia.url') . '/register/training/peserta', [
                                $credentials['key_name'] => $credentials['key'],
                                'id_diklat'              => $this->id_diklat,
                            ]);

                        if ($participantResponse->successful()) {
                            $rawParticipantBody = $participantResponse->body();
                            $decodedParticipantBody = json_decode($rawParticipantBody, true);

                            $participantData = null;
                            if (json_last_error() === JSON_ERROR_NONE && is_string($decodedParticipantBody)) {
                                $participantData = json_decode($decodedParticipantBody, true);
                            } elseif (is_array($decodedParticipantBody)) {
                                $participantData = $decodedParticipantBody;
                            }

                            if (isset($participantData['data']['peserta']) && is_array($participantData['data']['peserta'])) {
                                $statusOptions = ['' => 'Semua', 'accept' => 'Diterima', 'deny' => 'Ditolak', 'pending' => 'Direview'];

                                foreach ($participantData['data']['peserta'] as $dt) {
                                    $this->participants[] = [
                                        'nama'      => preg_replace_callback('/\b(\w{3})(\w+)\b/', fn($m) => $m[1] . str_repeat('*', strlen($m[2])), $dt['nama']),
                                        'umur'      => $dt['umur'],
                                        'kelamin'   => $dt['id_kelamin'],
                                        'pendidikan'=> $dt['pendidikan'],
                                        'penempatan'=> $dt['penempatan'],
                                        'ktp'       => isset($dt['ktp']) ? substr($dt['ktp'], 0, -8) . '********' : '-',
                                        'tuk'       => $dt['tuk'],
                                        'ukom'      => $dt['ukom'],
                                        'satker'    => $dt['satker'],
                                        'nip'       => $dt['nip'],
                                        'jabatan'   => $dt['jabatan'],
                                        'pangkat'   => $dt['uraian_pangkat'],
                                        'nomor_reg_asesor'=> $dt['nomor_reg_asesor'],
                                        'lsp'       => $dt['lsp'],
                                        'skema'     => $dt['skema'],
                                        'instansi'  => $dt['instansi_nama'],
                                        'status'    => $statusOptions[$dt['status']] ?? $dt['status'],
                                    ];
                                }
                            }
                        } else {
                            Log::warning('SIDIA API request failed for training participants', [
                                'status'    => $participantResponse->status(),
                                'response'  => $participantResponse->body(),
                                'id_diklat' => $this->id_diklat,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('SIDIA API request exception for training participants: ' . $e->getMessage(), ['id_diklat' => $this->id_diklat]);
                    }
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
