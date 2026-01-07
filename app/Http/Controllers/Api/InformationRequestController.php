<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InformationRequest;
use Illuminate\Http\Request;

class InformationRequestController extends Controller
{
    /**
     * Submit information request
     */
    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'id_card_number' => 'required|string|max:255',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'report_title' => 'required|string',
            'used_for' => 'required|string',
            'grab_method' => 'required|array', // Checkbox array
            'delivery_method' => 'nullable|array', // Checkbox array, nullable depending on grab_method
            'rule_accepted' => 'required|boolean',
            'identity_card_attachment' => 'nullable|string',
        ]);

        // Generate registration code (6 random characters like in Livewire)
        $registrationCode = $this->generateRandomRegistrationCode(InformationRequest::class);

        $informationRequest = InformationRequest::create([
            'registration_code' => $registrationCode,
            'reporter_name' => $validated['reporter_name'],
            'id_card_number' => $validated['id_card_number'],
            'identity_card_attachment' => $validated['identity_card_attachment'] ?? null,
            'address' => $validated['address'],
            'occupation' => $validated['occupation'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'report_title' => $validated['report_title'],
            'used_for' => $validated['used_for'],
            'grab_method' => $validated['grab_method'],
            'delivery_method' => $validated['delivery_method'] ?? [],
            'rule_accepted' => $validated['rule_accepted'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Create the initial process record for the Information Request (Logic match Livewire)
        $informationRequest->process()->create([
            'response_status_id' => \App\Enums\ResponseStatus::Initiation->value,
            'is_completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Information request submitted successfully',
            'data' => [
                'registration_code' => $informationRequest->registration_code,
            ],
        ], 201);
    }

    /**
     * Check information request status
     */
    public function checkRequest($registrationCode)
    {
        $informationRequest = InformationRequest::with(['reportProcesses.responseStatus', 'process.responseStatus'])
            ->where('registration_code', $registrationCode)
            ->firstOrFail();

        // Prepare timeline/history
        $history = $informationRequest->reportProcesses
            ->sortBy('created_at')
            ->map(function ($process) {
                return [
                    'status' => $process->responseStatus->name ?? 'Unknown',
                    'answer' => $process->answer,
                    'answer_attachment' => $process->answer_attachment,
                    'created_at' => $process->created_at,
                ];
            })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'registration_code' => $informationRequest->registration_code,
                'reporter_name' => $informationRequest->reporter_name,
                'id_card_number' => $informationRequest->id_card_number,
                'address' => $informationRequest->address,
                'occupation' => $informationRequest->occupation,
                'mobile' => $informationRequest->mobile,
                'email' => $informationRequest->email,
                'report_title' => $informationRequest->report_title,
                'used_for' => $informationRequest->used_for,
                'grab_method' => $informationRequest->grab_method,
                'delivery_method' => $informationRequest->delivery_method,
                'rule_accepted' => $informationRequest->rule_accepted,
                'status' => $informationRequest->process->responseStatus->name ?? 'Initiation', // Current status
                'history' => $history,
                'created_at' => $informationRequest->created_at,
            ],
        ]);
    }

    /**
     * Generate random registration code (6 characters like in Livewire)
     */
    private function generateRandomRegistrationCode($model)
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = $model::where('registration_code', $kode)->exists();
        } while ($exists);

        return $kode;
    }
}