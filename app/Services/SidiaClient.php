<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SidiaClient
{
    /**
     * @var array{
     *     url: string|null,
     *     username: string|null,
     *     password: string|null,
     *     key: string|null,
     *     key_name: string|null
     * }
     */
    protected array $credentials;

    public function __construct()
    {
        $this->credentials = [
            'url' => rtrim((string) config('services.sidia.url'), '/'),
            'username' => config('services.sidia.username'),
            'password' => config('services.sidia.password'),
            'key' => config('services.sidia.key'),
            'key_name' => config('services.sidia.key_name', 'SRV-KEY'),
        ];
    }

    public function isConfigured(): bool
    {
        return !empty($this->credentials['url'])
            && !empty($this->credentials['username'])
            && !empty($this->credentials['password'])
            && !empty($this->credentials['key'])
            && !empty($this->credentials['key_name']);
    }

    /**
     * Perform a POST request to the SIDIA API and return the decoded payload.
     *
     * @throws RuntimeException
     */
    public function post(string $endpoint, array $payload = []): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Konfigurasi kredensial SIDIA belum lengkap.');
        }

        $url = $this->credentials['url'] . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withBasicAuth(
                $this->credentials['username'],
                $this->credentials['password']
            )->post($url, $this->buildPayload($payload));
        } catch (\Throwable $e) {
            Log::error('SIDIA API request failed to send.', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);

            throw new RuntimeException('Gagal menghubungi layanan SIDIA.', 0, $e);
        }

        if (!$response->successful()) {
            Log::warning('SIDIA API responded with an error.', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Gagal mengambil data dari SIDIA (Status: ' . $response->status() . ').');
        }

        return $this->decodeResponse($response);
    }

    protected function buildPayload(array $payload = []): array
    {
        return array_merge([
            $this->credentials['key_name'] => $this->credentials['key'],
            'lang' => app()->getLocale(),
        ], $payload);
    }

    protected function decodeResponse(Response $response): array
    {
        $decoded = $response->json();

        if (is_array($decoded)) {
            return $decoded;
        }

        if (is_string($decoded)) {
            $jsonDecoded = json_decode($decoded, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($jsonDecoded)) {
                return $jsonDecoded;
            }
        }

        $fallbackDecoded = json_decode($response->body(), true);

        return is_array($fallbackDecoded) ? $fallbackDecoded : [];
    }
}
