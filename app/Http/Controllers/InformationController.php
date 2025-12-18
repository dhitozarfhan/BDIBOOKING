<?php

namespace App\Http\Controllers;

use App\Models\Core;
use App\Models\Information;
use Illuminate\Support\Str;

class InformationController extends Controller
{
    public function home()
    {
        $cores = Core::all();
        return view('information.home', compact('cores'));
    }

    public function show ($id, $slug)
    {
        $post = Information::where('information_id', $id)->firstOrFail();
        $generatedSlug = Str::slug($post->id_title);

        if ($generatedSlug !== $slug){
            return redirect()->route('information.post', ['id' => $id, 'slug' => $generatedSlug]);
        }
        $cores = Core::all();

        return view('information.post', compact('post', 'cores'));
    }

    public function showCore($slug)
    {
        $cores = Core::all();
        $core = Core::where('slug', $slug)->firstOrFail();
        $informations = Information::where('is_active', 'Y')
            ->whereHas('category', function ($query) use ($core) {
                $query->where('core_id', $core->core_id);
            })
            ->with('category')
            ->orderBy('sort', 'asc')
            ->get()
            ->groupBy('category_id');
        return view('information.core', compact('cores', 'core', 'informations'));
    }

    public function procedure($type = null) {
        $validTypes = ['propose', 'challenge', 'dispute', 'court_dispute'];
        if (!in_array($type, $validTypes)) {
            $type = 'propose';
        }

        $data = [
            'title' => __('information.' . $type),
            'type' => $type
        ];

        return view('information.procedure', $data);
    }

    public function showDetail($registration_code)
    {
        $question = \App\Models\Question::where('registration_code', $registration_code)->first();
        $informationRequest = \App\Models\InformationRequest::where('registration_code', $registration_code)->first();

        if ($question) {
            $item = $question;
            $type = 'question';
            // Prepare data for question
            $latestProcess = $item->process;
            $reportDetail = new \stdClass();
            $reportDetail->report_title = $item->report_title;
            $reportDetail->reporter_name = $item->reporter_name;
            $reportDetail->mobile = $item->mobile ? substr($item->mobile, 0, -4) . 'xxxx' : '-';
            $reportDetail->time_insert = $item->created_at;
            $reportDetail->content = $item->content;
            $reportDetail->attachment = $item->identity_card_attachment;
            $reportDetail->processes = $item->reportProcesses()->with('responseStatus')->get();
            $reportDetail->status = $latestProcess ? $latestProcess->response_status_id : \App\Enums\ResponseStatus::Initiation->value;
            $terminationProcess = $item->reportProcesses()
                ->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)
                ->where('is_completed', true)
                ->latest()
                ->first();
            if ($terminationProcess) {
                $reportDetail->answer = $terminationProcess->answer;
                $reportDetail->answer_attachment = $terminationProcess->answer_attachment;
            } else {
                $reportDetail->answer = null;
                $reportDetail->answer_attachment = null;
            }
        } elseif ($informationRequest) {
            $item = $informationRequest;
            $type = 'information_request';
            // Prepare data for information request
            $latestProcess = $item->process;
            $reportDetail = new \stdClass();
            $reportDetail->subject = __('Information Request');
            $reportDetail->reporter_name = $item->reporter_name;
            $reportDetail->mobile = $item->mobile ? substr($item->mobile, 0, -4) . 'xxxx' : '-';
            $reportDetail->time_insert = $item->created_at;
            $reportDetail->report_title = $item->report_title;
            $reportDetail->used_for = $item->used_for;
            $reportDetail->grab_method = $item->grab_method;
            $reportDetail->delivery_method = $item->delivery_method;
            if ($latestProcess) {
                $reportDetail->status = $latestProcess->response_status_id;
            } else {
                $reportDetail->status = \App\Enums\ResponseStatus::Initiation->value;
            }
            $terminationProcess = $item->reportProcesses()
                ->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)
                ->where('is_completed', true)
                ->first();
            if ($terminationProcess) {
                $reportDetail->answer = $terminationProcess->answer;
                $reportDetail->answer_attachment = $terminationProcess->answer_attachment;
            } else {
                $reportDetail->answer = null;
                $reportDetail->answer_attachment = null;
            }
            $reportDetail->processes = $item->reportProcesses()
                ->with('responseStatus')
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            abort(404);
        }

        return view('information.answer-detail', compact('reportDetail', 'type'));
    }
}
