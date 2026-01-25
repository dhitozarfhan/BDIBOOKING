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
            'identity_card_attachment' => 'required|file|image|max:2048', // 2MB max
        ]);

        // Generate registration code (6 random characters like in Livewire)
        $registrationCode = $this->generateRandomRegistrationCode(Question::class);

        $identityCardPath = null;
        if ($request->hasFile('identity_card_attachment')) {
            $identityCardPath = $request->file('identity_card_attachment')->store('identity_cards', 'private');
        }

        $question = Question::create([
            'registration_code' => $registrationCode,
            'reporter_name' => $validated['reporter_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'identity_number' => $validated['identity_number'],
            'content' => $validated['content'],
            'report_title' => $validated['report_title'],
            'identity_card_attachment' => $identityCardPath,
        ]);

        // Create the initial process record for the Question (Logic match Livewire)
        $question->process()->create([
            'response_status_id' => \App\Enums\ResponseStatus::Initiation->value,
            'is_completed' => false,
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
        $question = Question::with(['reportProcesses.responseStatus', 'process.responseStatus'])
            ->where('registration_code', $registrationCode)
            ->firstOrFail();

        // Prepare timeline/history
        $history = $question->reportProcesses
            ->sortBy('created_at')
            ->map(function ($process) {
                $data = [
                    'status_id' => $process->response_status_id,
                    'status' => $process->responseStatus->name ?? '-',
                    'created_at' => $process->created_at,
                ];

                if ($process->is_completed) {
                    $data['answer'] = $process->answer;
                    $data['answer_attachment'] = $process->answer_attachment;
                }

                return $data;
            })->values();

        // Find termination process (last completed process) for final answer
        $terminationProcess = $question->reportProcesses
            ->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)
            ->where('is_completed', true)
            ->last();

        return response()->json([
            'success' => true,
            'data' => [
                // Original API keys
                'registration_code' => $question->registration_code,
                'reporter_name' => $question->reporter_name,
                'content' => $question->content,
                'report_title' => $question->report_title,
                'mobile' => $question->mobile, // Raw
                'email' => $question->email,
                'identity_number' => $question->identity_number,
                'identity_card_attachment' => $question->identity_card_attachment,
                'status_id' => $question->process->response_status_id ?? null,
                'status' => $question->process->responseStatus->name ?? 'Initiation', 
                'answer' => $terminationProcess->answer ?? null,
                'answer_attachment' => $terminationProcess->answer_attachment ?? null,
                'history' => $history,
                'created_at' => $question->created_at,

                // Livewire Compatibility Keys
                'subject' => $question->report_title,
                // 'content' is already there
                'name' => $question->reporter_name,
                'mobile_masked' => $question->mobile ? substr($question->mobile, 0, -4) . 'xxxx' : '-', // Masked, mapped to separate key to avoid conflict if 'mobile' used for raw
                'time_insert' => $question->created_at,
                'processes' => $question->reportProcesses->map(function ($process) {
                    $statusId = $process->response_status_id;
                    $statusConfig = [
                        \App\Enums\ResponseStatus::Initiation->value => ['label' => 'Initiation'],
                        \App\Enums\ResponseStatus::Process->value => ['label' => 'Process'],
                        \App\Enums\ResponseStatus::Disposition->value => ['label' => 'Disposition'],
                        \App\Enums\ResponseStatus::Termination->value => ['label' => 'Completed'],
                    ];
                    $config = $statusConfig[$statusId] ?? ['label' => 'Unknown'];

                    // Logic: Answer only visible if is_completed is true
                    $showAnswer = $process->is_completed;

                    $processData = [
                        'id' => $process->id,
                        'response_status_id' => $statusId,
                        'responseStatus' => [
                            'name' => $config['label'],
                        ],
                        'created_at' => $process->created_at,
                    ];

                    if ($showAnswer) {
                        $processData['answer'] = $process->answer;
                        $processData['answer_attachment'] = $process->answer_attachment;
                    }

                    return $processData;
                }),
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