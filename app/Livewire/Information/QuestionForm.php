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
            $process = $question->process; // HasOne relation

            $reportDetail = new \stdClass();
            $reportDetail->reporter_name = $question->reporter_name;
            $reportDetail->report_title = $question->report_title;
            $reportDetail->mobile = $question->mobile ? substr($question->mobile, 0, -4) . 'xxxx' : '-';
            $reportDetail->time_insert = $question->created_at;
            $reportDetail->content = $question->content;

            if ($process) {
                $reportDetail->status = $process->response_status_id;
                $reportDetail->answer = $process->answer;
                $reportDetail->answer_attachment = $process->answer_attachment;
            } else {
                $reportDetail->status = ResponseStatus::Initiation->value;
                $reportDetail->answer = null;
                $reportDetail->answer_attachment = null;
            }

            $this->reportDetail = $reportDetail;
            $this->currentView = 'response';

        } else {
            session()->flash('statusError', __('Registration code not found.'));
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
        } elseif ($this->currentView === 'response') {
            return view('livewire.information.question-response');
        }

        return view('livewire.information.question-form');
    }
}
