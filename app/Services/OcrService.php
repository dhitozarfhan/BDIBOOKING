<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OcrService
{
    /**
     * Scan KTP image and return structured data.
     *
     * @param UploadedFile $file
     * @return array
     */
    public function scan(UploadedFile $file): array
    {
        // For now, we mock the service since the API endpoint is not provided.
        // In a real implementation, you would post the file to the external OCR API.
        
        // return $this->callRealApi($file);
        
        return $this->mockResponse();
    }

    protected function callRealApi(UploadedFile $file): array
    {
        try {
            // Placeholder for real API call
            // $response = Http::attach('image', file_get_contents($file), $file->getClientOriginalName())
            //     ->post(config('services.ocr.url'));
            
            // if ($response->successful()) {
            //     return $response->json();
            // }
            
            // Log::error('OCR API Error: ' . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error('OCR Service Exception: ' . $e->getMessage());
            return [];
        }
    }

    protected function mockResponse(): array
    {
        // Simulate processing time
        sleep(2);

        // Return dummy data for testing
        // You can change 'nik' here to test exist vs new user
        return [
            'nik' => '1234567890123456', // Example NIK
            'nama' => 'Budi Santoso', // Note: key might differ from API, adjust mapping in component
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01', // YYYY-MM-DD
            'alamat' => 'Jl. Merdeka No. 1, Jakarta Pusat',
            'agama' => 'Islam',
            'status_perkawinan' => 'Belum Kawin',
            'pekerjaan' => 'Wiraswasta',
            'kewarganegaraan' => 'WNI',
        ];
    }
}
