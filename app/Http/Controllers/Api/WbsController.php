<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wbs;
use Illuminate\Http\Request;

class WbsController extends Controller
{
    /**
     * Submit WBS report
     */
    public function submitReport(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'nullable|string|max:255',
            'reporter_email' => 'nullable|email|max:255',
            'reporter_phone' => 'nullable|string|max:20',
            'violation_type' => 'required|string',
            'violation_date' => 'required|date',
            'violation_location' => 'required|string',
            'perpetrator_name' => 'nullable|string|max:255',
            'perpetrator_position' => 'nullable|string|max:255',
            'description' => 'required|string',
            'evidence_files' => 'nullable|array',
            'evidence_files.*' => 'nullable|string',
        ]);

        // Generate report code
        $reportCode = 'WBS-' . date('Y') . '-' . str_pad(Wbs::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        $wbs = Wbs::create([
            'report_code' => $reportCode,
            'reporter_name' => $validated['reporter_name'] ?? 'Anonymous',
            'reporter_email' => $validated['reporter_email'] ?? null,
            'reporter_phone' => $validated['reporter_phone'] ?? null,
            'violation_type' => $validated['violation_type'],
            'violation_date' => $validated['violation_date'],
            'violation_location' => $validated['violation_location'],
            'perpetrator_name' => $validated['perpetrator_name'] ?? null,
            'perpetrator_position' => $validated['perpetrator_position'] ?? null,
            'description' => $validated['description'],
            'evidence_files' => isset($validated['evidence_files']) ? json_encode($validated['evidence_files']) : null,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'WBS report submitted successfully',
            'data' => [
                'report_code' => $wbs->report_code,
                'status' => $wbs->status,
            ],
        ], 201);
    }

    /**
     * Check WBS report status
     */
    public function checkReport($reportCode)
    {
        $wbs = Wbs::where('report_code', $reportCode)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'report_code' => $wbs->report_code,
                'violation_type' => $wbs->violation_type,
                'violation_date' => $wbs->violation_date,
                'violation_location' => $wbs->violation_location,
                'perpetrator_name' => $wbs->perpetrator_name,
                'perpetrator_position' => $wbs->perpetrator_position,
                'description' => $wbs->description,
                'status' => $wbs->status,
                'response' => $wbs->response,
                'responded_at' => $wbs->responded_at,
                'created_at' => $wbs->created_at,
            ],
        ]);
    }

    /**
     * Get all WBS reports (for authenticated users)
     */
    public function index(Request $request)
    {
        $query = Wbs::orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by violation type
        if ($request->has('violation_type')) {
            $query->where('violation_type', $request->violation_type);
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
