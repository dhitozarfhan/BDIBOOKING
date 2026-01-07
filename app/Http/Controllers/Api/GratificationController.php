<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gratification;
use Illuminate\Http\Request;

class GratificationController extends Controller
{
    /**
     * Submit gratification report
     */
    public function submitReport(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:255',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'report_title' => 'required|string|max:255',
            'report_description' => 'required|string',
            'attachment' => 'nullable|string',
            'identity_card_attachment' => 'nullable|string',
        ]);

        // Generate registration code (6 random characters like in Livewire)
        $registrationCode = $this->generateRandomReportCode(Gratification::class);

        $gratification = Gratification::create([
            'registration_code' => $registrationCode,
            'reporter_name' => $validated['reporter_name'],
            'identity_number' => $validated['identity_number'],
            'identity_card_attachment' => $validated['identity_card_attachment'] ?? null,
            'address' => $validated['address'],
            'occupation' => $validated['occupation'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'report_title' => $validated['report_title'],
            'report_description' => $validated['report_description'],
            'attachment' => $validated['attachment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gratification report submitted successfully',
            'data' => [
                'report_code' => $gratification->registration_code,
            ],
        ], 201);
    }

    /**
     * Check gratification report status
     */
    public function checkReport($reportCode)
    {
        $gratification = Gratification::where('registration_code', $reportCode)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'report_code' => $gratification->registration_code,
                'reporter_name' => $gratification->reporter_name,
                'identity_number' => $gratification->identity_number,
                'address' => $gratification->address,
                'occupation' => $gratification->occupation,
                'phone' => $gratification->phone,
                'email' => $gratification->email,
                'report_title' => $gratification->report_title,
                'report_description' => $gratification->report_description,
                'attachment' => $gratification->attachment,
                'identity_card_attachment' => $gratification->identity_card_attachment,
                'created_at' => $gratification->created_at,
            ],
        ]);
    }

    /**
     * Get all gratification reports (for authenticated users)
     */
    public function index(Request $request)
    {
        $query = Gratification::orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 10);
        $reports = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $reports->currentPage(),
                'data' => $reports->items(),
                'total' => $reports->total(),
                'per_page' => $reports->perPage(),
                'last_page' => $reports->lastPage(),
            ],
        ]);
    }

    /**
     * Generate random report code (6 characters like in Livewire)
     */
    private function generateRandomReportCode($model)
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = $model::where('registration_code', $kode)->exists();
        } while ($exists);

        return $kode;
    }
}
