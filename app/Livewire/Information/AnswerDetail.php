<?php

namespace App\Livewire\Information;

use App\Models\InformationRequest;
use App\Models\Question;
use App\Enums\ResponseStatus;
use Livewire\Component;

class AnswerDetail extends Component
{
    public $registration_code;
    public $reportDetail;
    public $type;

    public function mount($registration_code)
    {
        $this->registration_code = $registration_code;

        // Check if it's an information request or a question
        $infoRequest = InformationRequest::where('registration_code', $this->registration_code)->first();
        $question = Question::where('registration_code', $this->registration_code)->first();

        if ($infoRequest) {
            $this->type = 'request';
            $this->setRequestDetail($infoRequest);
        } elseif ($question) {
            $this->type = 'question';
            $this->setQuestionDetail($question);
        } else {
            $this->reportDetail = null;
        }
    }

    private function setRequestDetail($infoRequest)
    {
        $latestProcess = $infoRequest->process; // latestOfMany

        // Get the Termination process for answer (if exists and completed)
        $terminationProcess = $infoRequest->reportProcesses()
            ->where('response_status_id', ResponseStatus::Termination->value)
            ->where('is_completed', true)
            ->first();

        $this->reportDetail = new \stdClass();
        $this->reportDetail->report_title = $infoRequest->report_title;
        $this->reportDetail->reporter_name = $infoRequest->reporter_name;
        $this->reportDetail->mobile = $infoRequest->mobile ? substr($infoRequest->mobile, 0, -4) . 'xxxx' : '-';
        $this->reportDetail->time_insert = $infoRequest->created_at;
        $this->reportDetail->used_for = $infoRequest->used_for;
        $this->reportDetail->grab_method = $infoRequest->grab_method;
        $this->reportDetail->delivery_method = $infoRequest->delivery_method;

        if ($latestProcess) {
            $this->reportDetail->status = $latestProcess->response_status_id;
        } else {
            $this->reportDetail->status = ResponseStatus::Initiation->value;
        }

        // Get answer from Termination process if completed
        if ($terminationProcess) {
            $this->reportDetail->answer = $terminationProcess->answer;
            $this->reportDetail->answer_attachment = $terminationProcess->answer_attachment;
        } else {
            $this->reportDetail->answer = null;
            $this->reportDetail->answer_attachment = null;
        }

        // Add process history for timeline display
        $this->reportDetail->processes = $infoRequest->reportProcesses()
            ->with('responseStatus')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    private function setQuestionDetail($question)
    {
        // Get the latest process for current status
        $latestProcess = $question->process; // latestOfMany

        $this->reportDetail = new \stdClass();
        $this->reportDetail->report_title = $question->report_title;
        $this->reportDetail->reporter_name = $question->reporter_name;
        $this->reportDetail->mobile = $question->mobile ? substr($question->mobile, 0, -4) . 'xxxx' : '-';
        $this->reportDetail->time_insert = $question->created_at;
        $this->reportDetail->content = $question->content;
        $this->reportDetail->status = $latestProcess ? $latestProcess->response_status_id : ResponseStatus::Initiation->value;

        // Get answer from Termination process if completed
        $terminationProcess = $question->reportProcesses()
            ->where('response_status_id', ResponseStatus::Termination->value)
            ->where('is_completed', true)
            ->first();

        if ($terminationProcess) {
            $this->reportDetail->answer = $terminationProcess->answer;
            $this->reportDetail->answer_attachment = $terminationProcess->answer_attachment;
        } else {
            $this->reportDetail->answer = null;
            $this->reportDetail->answer_attachment = null;
        }

        // Add process history for timeline display
        $this->reportDetail->processes = $question->reportProcesses()
            ->with('responseStatus')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.information.answer-detail');
    }
}