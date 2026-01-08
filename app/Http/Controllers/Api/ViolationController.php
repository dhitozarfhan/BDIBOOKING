<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    /**
     * Get all active violations
     */
    public function index()
    {
        // Mengambil data violation yang aktif, sesuai dengan schema database
        $violations = Violation::active()
            ->select('id', 'name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $violations,
        ]);
    }
}
