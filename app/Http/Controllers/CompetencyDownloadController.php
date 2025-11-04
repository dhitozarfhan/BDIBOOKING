<?php

namespace App\Http\Controllers;

use App\Services\SidiaClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class CompetencyDownloadController extends Controller
{
    public function __invoke(SidiaClient $sidia, int $skkniId)
    {
        try {
            $payload = $sidia->post('competency/skkni/download', [
                'skkni_id' => $skkniId,
            ]);
        } catch (RuntimeException $e) {
            abort(404, $e->getMessage());
        }

        $fileUrl = data_get($payload, 'data.file');
        if (!$fileUrl) {
            abort(404, __('competency.item_not_found', ['item' => __('competency.download_document')]));
        }

        $fileName = data_get($payload, 'data.skkni.file_name')
            ?? basename((string) parse_url($fileUrl, PHP_URL_PATH))
            ?? 'skkni-document.pdf';

        try {
            $response = Http::timeout(60)->get($fileUrl);
        } catch (\Throwable $e) {
            abort(404, __('competency.item_not_found', ['item' => __('competency.download_document')]));
        }

        if (!$response->successful()) {
            abort(404, __('competency.item_not_found', ['item' => __('competency.download_document')]));
        }

        $contentType = $response->header('Content-Type') ?? 'application/octet-stream';
        $cleanName = Str::slug(pathinfo($fileName, PATHINFO_FILENAME) ?: 'skkni-document');
        $extension = pathinfo($fileName, PATHINFO_EXTENSION) ?: 'pdf';

        return response($response->body(), 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $cleanName . '.' . $extension . '"',
        ]);
    }
}
