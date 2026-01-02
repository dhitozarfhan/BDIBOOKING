<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\InformationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InformationController extends Controller
{
    /**
     * Submit a question
     */
    public function submitQuestion(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'identity_number' => 'required|string|max:20',
            'address' => 'required|string',
            'question' => 'required|string',
            'question_type' => 'nullable|string',
        ]);

        // Generate registration code
        $registrationCode = 'QST-' . date('Y') . '-' . str_pad(Question::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        $question = Question::create([
            'registration_code' => $registrationCode,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'identity_number' => $validated['identity_number'],
            'address' => $validated['address'],
            'question' => $validated['question'],
            'question_type' => $validated['question_type'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question submitted successfully',
            'data' => [
                'registration_code' => $question->registration_code,
                'status' => $question->status,
            ],
        ], 201);
    }

    /**
     * Check question status
     */
    public function checkQuestion($registrationCode)
    {
        $question = Question::where('registration_code', $registrationCode)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'registration_code' => $question->registration_code,
                'name' => $question->name,
                'question' => $question->question,
                'status' => $question->status,
                'answer' => $question->answer,
                'answered_at' => $question->answered_at,
                'created_at' => $question->created_at,
            ],
        ]);
    }

    /**
     * Submit information request
     */
    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'identity_number' => 'required|string|max:20',
            'address' => 'required|string',
            'request_type' => 'required|string',
            'information_needed' => 'required|string',
            'purpose' => 'required|string',
        ]);

        // Generate registration code
        $registrationCode = 'REQ-' . date('Y') . '-' . str_pad(InformationRequest::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);

        $informationRequest = InformationRequest::create([
            'registration_code' => $registrationCode,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'identity_number' => $validated['identity_number'],
            'address' => $validated['address'],
            'request_type' => $validated['request_type'],
            'information_needed' => $validated['information_needed'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Information request submitted successfully',
            'data' => [
                'registration_code' => $informationRequest->registration_code,
                'status' => $informationRequest->status,
            ],
        ], 201);
    }

    /**
     * Check information request status
     */
    public function checkRequest($registrationCode)
    {
        $informationRequest = InformationRequest::where('registration_code', $registrationCode)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'registration_code' => $informationRequest->registration_code,
                'name' => $informationRequest->name,
                'information_needed' => $informationRequest->information_needed,
                'purpose' => $informationRequest->purpose,
                'status' => $informationRequest->status,
                'response' => $informationRequest->response,
                'documents' => $informationRequest->documents ? json_decode($informationRequest->documents, true) : [],
                'responded_at' => $informationRequest->responded_at,
                'created_at' => $informationRequest->created_at,
            ],
        ]);
    }
}
