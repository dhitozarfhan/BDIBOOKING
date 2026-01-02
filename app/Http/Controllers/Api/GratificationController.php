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
            'reporter_email' => 'required|email|max:255',
            'reporter_phone' => 'required|string|max:20',
            'incident_date' => 'required|date',
            'incident_location' => 'required|string',
            'gratification_type' => 'required|string|in:money,goods,service,other',
            'gratification_value' => 'nullable|numeric',
            'description' => 'required|string',
            'evidence_files' => 'nullable|array',
            'evidence_files.*' => 'nullable|string',
        ]);

        // Generate report code
        $reportCode = 'GRAT-' . date('Y') . '-' . str_pad(Gratification::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        $gratification = Gratification::create([
            'report_code' => $reportCode,
            'reporter_name' => $validated['reporter_name'],
            'reporter_email' => $validated['reporter_email'],
            'reporter_phone' => $validated['reporter_phone'],
            'incident_date' => $validated['incident_date'],
            'incident_location' => $validated['incident_location'],
            'gratification_type' => $validated['gratification_type'],
            'gratification_value' => $validated['gratification_value'] ?? null,
            'description' => $validated['description'],
            'evidence_files' => isset($validated['evidence_files']) ? json_encode($validated['evidence_files']) : null,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gratification report submitted successfully',
            'data' => [
                'report_code' => $gratification->report_code,
                'status' => $gratification->status,
            ],
        ], 201);
    }

    /**
     * Check gratification report status
     */
    public function checkReport($reportCode)
    {
        $gratification = Gratification::where('report_code', $reportCode)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'report_code' => $gratification->report_code,
                'reporter_name' => $gratification->reporter_name,
                'incident_date' => $gratification->incident_date,
                'incident_location' => $gratification->incident_location,
                'gratification_type' => $gratification->gratification_type,
                'gratification_value' => $gratification->gratification_value,
                'description' => $gratification->description,
                'status' => $gratification->status,
                'response' => $gratification->response,
                'responded_at' => $gratification->responded_at,
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
}
