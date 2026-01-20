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
            'reporter_name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:255',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'report_title' => 'required|string|max:255',
            'report_description' => 'required|string',
            'attachment' => 'required|file|max:10240', // 10MB max
            'identity_card_attachment' => 'required|file|image|max:2048', // 2MB max
            'violation_id' => 'required|exists:violations,id', // Changed to required as per Livewire
        ]);

        // Generate registration code (6 random characters like in Livewire)
        $registrationCode = $this->generateRandomReportCode(Wbs::class);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('wbs', 'private');
        }

        $identityCardPath = null;
        if ($request->hasFile('identity_card_attachment')) {
            $identityCardPath = $request->file('identity_card_attachment')->store('identity_cards', 'private');
        }

        $wbs = Wbs::create([
            'registration_code' => $registrationCode,
            'reporter_name' => $validated['reporter_name'],
            'identity_number' => $validated['identity_number'],
            'identity_card_attachment' => $identityCardPath,
            'address' => $validated['address'],
            'occupation' => $validated['occupation'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'report_title' => $validated['report_title'],
            'report_description' => $validated['report_description'],
            'attachment' => $attachmentPath,
            'violation_id' => $validated['violation_id'],
        ]);

        // Create the initial process record for the WBS (Logic match Livewire)
        $wbs->reportProcesses()->create([
            'response_status_id' => \App\Enums\ResponseStatus::Initiation->value,
            'answer' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'WBS report submitted successfully',
            'data' => [
                'report_code' => $wbs->registration_code,
            ],
        ], 201);
    }

    /**
     * Check WBS report status
     */
    public function checkReport($reportCode)
    {
        $wbs = Wbs::with(['reportProcesses.responseStatus', 'process.responseStatus', 'violation'])
            ->where('registration_code', $reportCode)
            ->firstOrFail();

        // Prepare timeline/history
        $history = $wbs->reportProcesses
            ->sortBy('created_at')
            ->map(function ($process) {
                return [
                    'status' => $process->responseStatus->name ?? 'Unknown',
                    'answer' => $process->answer,
                    'answer_attachment' => $process->answer_attachment,
                    'created_at' => $process->created_at,
                ];
            })->values();
        
        // Find termination process (last completed process) for final answer
        $terminationProcess = $wbs->reportProcesses
            ->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)
            ->where('is_completed', true)
            ->last();

        return response()->json([
            'success' => true,
            'data' => [
                'report_code' => $wbs->registration_code,
                'reporter_name' => $wbs->reporter_name,
                'identity_number' => $wbs->identity_number,
                'address' => $wbs->address,
                'occupation' => $wbs->occupation,
                'phone' => $wbs->phone,
                'email' => $wbs->email,
                'report_title' => $wbs->report_title,
                'report_description' => $wbs->report_description,
                'attachment' => $wbs->attachment,
                'identity_card_attachment' => $wbs->identity_card_attachment,
                'violation_id' => $wbs->violation_id,
                'violation_name' => $wbs->violation->name ?? null,
                'status' => $wbs->process->responseStatus->name ?? 'Initiation', 
                'answer' => $terminationProcess->answer ?? null,
                'answer_attachment' => $terminationProcess->answer_attachment ?? null,
                'history' => $history,
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
