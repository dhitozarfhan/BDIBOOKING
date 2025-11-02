<?php

namespace App\Livewire\Training;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Presence extends Component
{
    public ?array $diklat = null;
    public array $desa = [];
    public string $id_diklat;
    public string $username = '';
    public string $password = '';
    public string $signature = '';
    public string $error = '';
    public ?string $successMessage = null;
    public string $dateEnablePresence = 'N';
    public string $hourEnablePresence = 'N';
    public ?string $localDatetime = null;
    public ?string $localTimezone = null;

    public function mount($id_diklat): void
    {
        $this->id_diklat = (string) $id_diklat;
        $this->successMessage = session('success');
        $this->fetchPresenceConfiguration();
    }

    public function submit()
    {
        if (!$this->presenceAvailable) {
            $this->error = 'Presensi tidak tersedia saat ini.';
            return;
        }

        $this->error = '';

        $this->validate(
            [
                'username' => 'required|string|max:100',
                'password' => 'required|string|max:100',
                'signature' => [
                    'required',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        $payload = $this->extractBase64Data($value);
                        if ($payload === null) {
                            $fail('Tanda tangan wajib dibubuhkan pada form ini.');
                            return;
                        }
                        if (strlen($payload) < 6000) {
                            $fail('Tanda tangan tidak terdeteksi dengan jelas, silakan coba lagi.');
                        }
                    },
                ],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'password.required' => 'Password wajib diisi.',
                'signature.required' => 'Tanda tangan wajib dibubuhkan pada form ini.',
            ]
        );

        $credentials = $this->getSidiaCredentials();
        if (!$credentials) {
            $this->error = 'Konfigurasi API SIDIA belum lengkap.';
            Log::warning('Attempted to submit presence without complete SIDIA credentials.', [
                'id_diklat' => $this->id_diklat,
            ]);
            return;
        }

        try {
            $participantResponse = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sidia.url'). '/diklat/get_peserta_diklat',
                    [
                        $credentials['key_name'] => $credentials['key'],
                        'id_diklat' => $this->id_diklat,
                        'ktp' => $this->username,
                        'password' => $this->password,
                    ]
                );

            if (!$participantResponse->successful()) {
                $this->error = 'Gagal memverifikasi peserta (Status: ' . $participantResponse->status() . ').';
                Log::warning('SIDIA participant verification failed.', [
                    'status' => $participantResponse->status(),
                    'body' => $participantResponse->body(),
                    'id_diklat' => $this->id_diklat,
                    'username' => $this->username,
                ]);
                return;
            }

            $participantPayload = $this->decodeResponse($participantResponse);
            if (($participantPayload['status'] ?? null) !== 1) {
                $message = $participantPayload['message'] ?? 'Username atau password tidak valid.';
                $this->error = $message;
                $this->addError('username', $message);
                return;
            }

            $participant = $participantPayload['data']['peserta'] ?? null;
            if (!is_array($participant) || empty($participant['id_peserta'])) {
                $this->error = 'Data peserta tidak ditemukan pada sistem.';
                Log::warning('Participant data missing from SIDIA response.', [
                    'payload' => $participantPayload,
                    'id_diklat' => $this->id_diklat,
                    'username' => $this->username,
                ]);
                return;
            }

            $signaturePayload = $this->extractBase64Data($this->signature);
            if ($signaturePayload === null) {
                $this->addError('signature', 'Tanda tangan wajib dibubuhkan pada form ini.');
                return;
            }

            $timestamp = $this->resolvePresenceTimestamp();

            $presenceResponse = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sidia.url') . '/diklat/set_presensi',
                    [
                        $credentials['key_name'] => $credentials['key'],
                        'id_diklat' => $this->id_diklat,
                        'id_peserta' => $participant['id_peserta'],
                        'tanggal' => $timestamp->format('Y-m-d'),
                        'waktu' => $timestamp->format('H:i:s'),
                        'ttd' => $signaturePayload,
                    ]
                );

            if (!$presenceResponse->successful()) {
                $this->error = 'Gagal mengirim data presensi (Status: ' . $presenceResponse->status() . ').';
                Log::error('SIDIA set_presensi request failed.', [
                    'status' => $presenceResponse->status(),
                    'body' => $presenceResponse->body(),
                    'id_diklat' => $this->id_diklat,
                    'username' => $this->username,
                ]);
                return;
            }

            $presencePayload = $this->decodeResponse($presenceResponse);
            if (($presencePayload['status'] ?? null) !== 1) {
                $this->error = $presencePayload['message'] ?? 'Presensi gagal diproses.';
                Log::warning('SIDIA rejected presence submission.', [
                    'payload' => $presencePayload,
                    'id_diklat' => $this->id_diklat,
                    'username' => $this->username,
                ]);
                return;
            }

            $message = 'Anda telah melakukan presensi';
            $formatted = $this->formatPresenceTimestamp($timestamp);
            if ($formatted !== null) {
                $message .= ' pada ' . $formatted;
            }
            if (!empty($this->localTimezone)) {
                $message .= ' (GMT +' . ltrim((string) $this->localTimezone, '+') . ')';
            }
            $message .= '.';

            session()->flash('success', $message);
            return redirect()->route('training.presence', ['id_diklat' => $this->id_diklat]);
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat menghubungi API.';
            Log::error('Exception when submitting training presence: ' . $e->getMessage(), [
                'id_diklat' => $this->id_diklat,
                'username' => $this->username,
            ]);
        }
    }

    public function getPresenceAvailableProperty(): bool
    {
        return $this->dateEnablePresence === 'Y' && $this->hourEnablePresence === 'Y';
    }

    public function render()
    {
        return view('livewire.training.presence')
            ->layout('layouts.presence')
            ->title($this->diklat['nama_jenis'] ?? 'Presensi Diklat');
    }

    private function fetchPresenceConfiguration(): void
    {
        $credentials = $this->getSidiaCredentials();
        if (!$credentials) {
            $this->error = 'Konfigurasi API SIDIA belum lengkap.';
            Log::error('SIDIA credentials missing when fetching presence configuration.', [
                'id_diklat' => $this->id_diklat,
            ]);
            return;
        }

        try {
            $response = Http::withBasicAuth($credentials['username'], $credentials['password'])
                ->post(config('services.sidia.url'). '/diklat/get_diklat_presensi_qrcode',
                    [
                        $credentials['key_name'] => $credentials['key'],
                        'id_diklat' => $this->id_diklat,
                    ]
                );

            if (!$response->successful()) {
                $this->error = 'Gagal memuat data presensi (Status: ' . $response->status() . ').';
                Log::error('SIDIA presence configuration request failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'id_diklat' => $this->id_diklat,
                ]);
                return;
            }

            $payload = $this->decodeResponse($response);
            if (($payload['status'] ?? null) !== 1) {
                $this->error = $payload['message'] ?? 'Data presensi tidak ditemukan.';
                Log::warning('SIDIA presence configuration returned non-success status.', [
                    'payload' => $payload,
                    'id_diklat' => $this->id_diklat,
                ]);
                return;
            }

            $presence = $this->extractPresenceConfig($payload);

            if (empty($presence)) {
                $this->error = 'Data presensi tidak tersedia.';
                Log::warning('SIDIA presence configuration payload empty.', [
                    'payload' => $payload,
                    'id_diklat' => $this->id_diklat,
                ]);
                return;
            }

            $this->diklat = is_array($presence['diklat'] ?? null)
                ? $presence['diklat']
                : (is_array($presence) ? $presence['diklat'] ?? $presence : null);

            $this->dateEnablePresence = (string) ($presence['date_enable_presence'] ?? 'N');
            $this->hourEnablePresence = (string) ($presence['hour_enable_presence'] ?? 'N');
            $this->localTimezone = isset($presence['local_timezone']) ? (string) $presence['local_timezone'] : null;
            $this->localDatetime = isset($presence['local_datetime']) ? (string) $presence['local_datetime'] : null;
            $this->desa = is_array($presence['desa'] ?? null) ? $presence['desa'] : [];
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat menghubungi API.';
            Log::error('Exception when fetching presence configuration: ' . $e->getMessage(), [
                'id_diklat' => $this->id_diklat,
            ]);
        }
    }

    private function getSidiaCredentials(): ?array
    {
        $credentials = [
            'username' => config('services.sidia.username'),
            'password' => config('services.sidia.password'),
            'key' => config('services.sidia.key'),
            'key_name' => config('services.sidia.key_name'),
        ];

        if (empty($credentials['username']) || empty($credentials['password']) || empty($credentials['key']) || empty($credentials['key_name'])) {
            return null;
        }

        return $credentials;
    }

    private function decodeResponse(Response $response): array
    {
        $raw = $response->body();
        $decoded = json_decode($raw, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        $json = $response->json();
        return is_array($json) ? $json : [];
    }

    private function extractPresenceConfig(array $payload): array
    {
        $candidates = [
            $payload['data']['presensi_qrcode'] ?? null,
            $payload['presensi_qrcode'] ?? null,
            $payload['data'] ?? null,
        ];

        foreach ($candidates as $candidate) {
            if (is_array($candidate)) {
                return $candidate;
            }
        }

        return [];
    }

    private function extractBase64Data(?string $data): ?string
    {
        if (!is_string($data) || trim($data) === '') {
            return null;
        }

        if (!str_contains($data, ',')) {
            return null;
        }

        return trim(explode(',', $data, 2)[1]);
    }

    private function resolvePresenceTimestamp(): Carbon
    {
        if (is_string($this->localDatetime) && $this->localDatetime !== '') {
            try {
                return Carbon::parse($this->localDatetime);
            } catch (\Exception $e) {
                Log::warning('Failed to parse local datetime from SIDIA.', [
                    'local_datetime' => $this->localDatetime,
                    'id_diklat' => $this->id_diklat,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return Carbon::now('Asia/Jakarta');
    }

    private function formatPresenceTimestamp(?Carbon $timestamp): ?string
    {
        $timestamp ??= $this->resolvePresenceTimestamp();

        try {
            return $timestamp->locale(app()->getLocale())->translatedFormat('l, j F Y H:i');
        } catch (\Exception $e) {
            Log::warning('Failed to format presence timestamp.', [
                'timestamp' => $timestamp,
                'id_diklat' => $this->id_diklat,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
