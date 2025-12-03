<?php

namespace App\Livewire\Information;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\Question;
use Illuminate\Support\Str;

class QuestionForm extends Component
{
    use WithFileUploads;
    public $subject;
    public $content;
    public $name;
    public $identity_number;
    public $identity_card_attachment;
    public $mobile;
    public $email;

    protected $rules = [
        'subject' => 'required',
        'content' => 'required',
        'name' => 'required',
        'identity_number' => 'nullable|string',
        'identity_card_attachment' => 'nullable|file|image|max:2048', // 2MB max
        'mobile' => 'required',
        'email' => 'required|email',
    ];

    public function save()
    {
        $this->validate();

        $identityCardPath = null;
        if ($this->identity_card_attachment) {
            $identityCardPath = $this->identity_card_attachment->store('identity_cards', 'public');
        }

        // Generate registration code
        $registrationCode = $this->generateKodeRegister();

        Question::create([
            'subject' => $this->subject,
            'content' => $this->content,
            'name' => $this->name,
            'identity_number' => $this->identity_number,
            'identity_card_attachment' => $identityCardPath,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'registration_code' => $registrationCode,
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

    public function render()
    {
        return view('livewire.information.question-form');
    }
}
