<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function download(Request $request): StreamedResponse
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

        $directoryMapping = [
            'wbs' => 'wbs.disk',
            'gratifications' => 'gratification.disk',
            'questions' => 'question.disk',
            'information-requests' => 'information_request.disk',
            'identity_cards' => 'gratification.disk',
        ];

        $disk = null;
        $isAllowed = false;
        foreach ($directoryMapping as $dir => $configKey) {
            if (str_starts_with($path, $dir . '/')) {
                $disk = config('services.' . $configKey, 'private');
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            abort(403, 'Access denied');
        }

        // 4. Validasi file exists
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found');
        }

        // 5. Gunakan response() untuk menyajikan file
        return Storage::disk($disk)->response($path);
    }
}
