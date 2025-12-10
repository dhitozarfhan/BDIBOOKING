<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function download(Request $request)
    {
        $path = $request->query('path');
        
        // 1. Validasi path tidak boleh kosong
        if (!$path) {
            abort(403, 'Invalid request');
        }
        
        // 2. CRITICAL: Validasi path traversal
        if (str_contains($path, '..')) {
            abort(403, 'Invalid path');
        }
        
        // 3. CRITICAL: Whitelist directory
        $allowedDirectories = [
            'wbs',
            'wbs/answers',
            'gratifications',
            'gratifications/answers',
            'questions/answers',
            'information-requests/answers',
        ];
        
        $isAllowed = false;
        foreach ($allowedDirectories as $dir) {
            if (str_starts_with($path, $dir . '/')) {
                $isAllowed = true;
                break;
            }
        }
        
        if (!$isAllowed) {
            abort(403, 'Access denied');
        }
        
        // 4. Validasi file exists
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File not found');
        }
        
        // 5. Gunakan response() untuk menyajikan file
        return Storage::disk('private')->response($path);
    }
}
