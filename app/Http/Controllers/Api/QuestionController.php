<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Submit a question
     */
    public function submitQuestion(Request $request)
    {
        $validated = $request->validate([
            'reporter_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'identity_number' => 'required|string|max:20',
            'content' => 'required|string',
            'report_title' => 'required|string|max:255',
            'identity_card_attachment' => 'nullable|string',
        ]);

        // Generate registration code (6 random characters like in Livewire)
        $registrationCode = $this->generateRandomRegistrationCode(Question::class);

        $question = Question::create([
            'registration_code' => $registrationCode,
            'reporter_name' => $validated['reporter_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'identity_number' => $validated['identity_number'],
            'content' => $validated['content'],
            'report_title' => $validated['report_title'],
            'identity_card_attachment' => $validated['identity_card_attachment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question submitted successfully',
            'data' => [
                'registration_code' => $question->registration_code,
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
                'reporter_name' => $question->reporter_name,
                'content' => $question->content,
                'report_title' => $question->report_title,
                'mobile' => $question->mobile,
                'email' => $question->email,
                'identity_number' => $question->identity_number,
                'identity_card_attachment' => $question->identity_card_attachment,
                'created_at' => $question->created_at,
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