<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
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
        return $this->callRealApi($file);
    }

    protected function callRealApi(UploadedFile $file): array
    {
        $url = 'https://rosalva-unrevealable-unsatisfiedly.ngrok-free.dev/ocr-ktp';
        $logFile = storage_path('logs/ocr_debug.log');
        $timestamp = now()->toDateTimeString();
        
        file_put_contents($logFile, "[$timestamp] Starting OCR Request (Native cURL)...\n", FILE_APPEND);
        file_put_contents($logFile, "[$timestamp] File: " . $file->getRealPath() . " (" . $file->getSize() . " bytes)\n", FILE_APPEND);

        $curl = curl_init();

        $cfile = new \CURLFile($file->getRealPath(), $file->getMimeType(), $file->getClientOriginalName());

        // Note: Field name is assumed to be 'file'. If API expects 'image', this needs to be changed.
        $postData = ['file' => $cfile];

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60, // Increased timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_SSL_VERIFYHOST => 0, // Disable SSL verification for development
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        file_put_contents($logFile, "[$timestamp] Executing curl...\n", FILE_APPEND);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        $errno = curl_errno($curl);
        
        curl_close($curl);

        file_put_contents($logFile, "[$timestamp] HTTP Code: $httpCode\n", FILE_APPEND);

        if ($error || $errno) {
            file_put_contents($logFile, "[$timestamp] cURL Error ($errno): $error\n", FILE_APPEND);
            return [];
        }

        file_put_contents($logFile, "[$timestamp] Response: " . substr($response, 0, 1000) . "...\n", FILE_APPEND);

        if ($httpCode >= 200 && $httpCode < 300) {
            $responseArray = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                file_put_contents($logFile, "[$timestamp] JSON Success\n", FILE_APPEND);
                
                // New API returns flat JSON (no 'status' wrapper)
                $data = $responseArray;

                // Validate at least NIK or Name exists
                if (!empty($data['nik']) || !empty($data['nama'])) {
                    
                    // Parse tempat_tgl_lahir if available
                    if (isset($data['tempat_tgl_lahir'])) {
                        $parts = explode(',', $data['tempat_tgl_lahir']);
                        if (count($parts) >= 1) {
                            $data['tempat_lahir'] = trim($parts[0]);
                        }
                        if (count($parts) >= 2) {
                            $dateStr = trim($parts[1]);
                            // Convert DD-MM-YYYY to YYYY-MM-DD
                            try {
                                $date = \DateTime::createFromFormat('d-m-Y', $dateStr);
                                if ($date) {
                                    $data['tanggal_lahir'] = $date->format('Y-m-d');
                                }
                            } catch (\Exception $e) {
                                file_put_contents($logFile, "[$timestamp] Date Parse Error: " . $e->getMessage() . "\n", FILE_APPEND);
                            }
                        }
                    }

                    return $data;
                }
                
                return $responseArray; // Return whole array if structure is different
            }
            file_put_contents($logFile, "[$timestamp] JSON Decode Error: " . json_last_error_msg() . "\n", FILE_APPEND);
        } else {
            file_put_contents($logFile, "[$timestamp] Request failed with status $httpCode\n", FILE_APPEND);
        }

        return [];
    }
}
