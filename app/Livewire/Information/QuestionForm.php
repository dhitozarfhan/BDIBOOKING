<?php

namespace App\Livewire\Information;

use Livewire\Component;

use App\Models\Question;
use Illuminate\Support\Str;

class QuestionForm extends Component
{
    public $subject;
    public $content;
    public $name;
    public $mobile;
    public $email;

    protected $rules = [
        'subject' => 'required',
        'content' => 'required',
        'name' => 'required',
        'mobile' => 'required',
        'email' => 'required|email',
    ];

    public function save()
    {
        $this->validate();

        Question::create([
            'subject' => $this->subject,
            'content' => $this->content,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Question successfully submitted.');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.information.question-form');
    }
}
