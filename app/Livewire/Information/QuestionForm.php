<?php

namespace App\Livewire\Information;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\Question;
use App\Enums\ResponseStatus;
use Illuminate\Support\Str;

class QuestionForm extends Component
{
    use WithFileUploads;
    public $reporter_name;
    public $content;
    public $report_title;
    public $identity_number;
    public $identity_card_attachment;
    public $mobile;
    public $email;

    // View State
    public $currentView = 'form';
    public $registration_code = '';
    public $reportDetail;
    public $statusError = '';

    protected function rules()
    {
        if ($this->currentView === 'status') {
            return [
                'registration_code' => 'required|string|max:255',
            ];
        }

        return [
            'reporter_name' => 'required',
            'content' => 'required',
            'report_title' => 'required',
            'identity_number' => 'nullable|string',
            'identity_card_attachment' => 'nullable|file|image|max:2048', // 2MB max
            'mobile' => 'required',
            'email' => 'required|email',
        ];
    }

    public function mount()
    {
        $currentRoute = request()->route()->getName();
        if (str_contains($currentRoute, 'information.question.status')) {
            $this->currentView = 'status';
        } else {
            $this->currentView = 'form';
        }
    }

    public function save()
    {
        $this->validate();

        $identityCardPath = null;
        if ($this->identity_card_attachment) {
            $identityCardPath = $this->identity_card_attachment->store('identity_cards', 'private');
        }

        // Generate registration code
        $registrationCode = $this->generateKodeRegister();

        $question = Question::create([
            'reporter_name' => $this->reporter_name,
            'content' => $this->content,
            'report_title' => $this->report_title,
            'identity_number' => $this->identity_number,
            'identity_card_attachment' => $identityCardPath,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'registration_code' => $registrationCode,
        ]);

        // Create initial process
        $question->process()->create([
            'response_status_id' => ResponseStatus::Initiation->value,
            'is_completed' => false,
        ]);

        session()->flash('message', 'Question successfully submitted. Registration Code: ' . $registrationCode);
        session()->flash('registration_code', $registrationCode);

        $this->reset();
    }

    private function generateKodeRegister()
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = Question::where('registration_code', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    public function checkStatus()
    {
        $this->validate([
            'registration_code' => 'required|string|max:255',
        ]);

        $question = Question::where('registration_code', $this->registration_code)->first();

        if ($question) {
            // Get the latest process for current status
            $latestProcess = $question->process; // latestOfMany

            $reportDetail = new \stdClass();
            $reportDetail->subject = $question->report_title;
            $reportDetail->name = $question->reporter_name;
            $reportDetail->mobile = $question->mobile ? substr($question->mobile, 0, -4) . 'xxxx' : '-';
            $reportDetail->time_insert = $question->created_at;
            $reportDetail->content = $question->content;
            $reportDetail->attachment = $question->identity_card_attachment;
            $reportDetail->processes = $question->reportProcesses()->with('responseStatus')->get();
            $reportDetail->status = $latestProcess ? $latestProcess->response_status_id : ResponseStatus::Initiation->value;

            // Get the latest completed termination process for the final answer
            $terminationProcess = $question->reportProcesses()
                ->where('response_status_id', ResponseStatus::Termination->value)
                ->where('is_completed', true)
                ->latest()
                ->first();



            // Get answer from Termination process if completed
            if ($terminationProcess) {
                $reportDetail->answer = $terminationProcess->answer;
                $reportDetail->answer_attachment = $terminationProcess->answer_attachment;
            } else {
                $reportDetail->answer = null;
                $reportDetail->answer_attachment = null;
            }

            // Store the report detail in the session and redirect
            session()->flash('reportDetail', $reportDetail);
            return redirect()->route('information.question.response');

        } else {
            // If not found, flash an error message and redirect back
            session()->flash('statusError', 'Kode register tidak ditemukan dalam sistem kami.');
            return redirect()->route('information.question.status');
        }
    }

    public function setView($view)
    {
        $this->currentView = $view;
        if ($view === 'form') {
            $this->reset(['registration_code', 'statusError', 'reportDetail']);
        }
    }

    public function render()
    {
        if ($this->currentView === 'status') {
            return view('livewire.information.question-status');
        }

        return view('livewire.information.question-form');
    }
}
