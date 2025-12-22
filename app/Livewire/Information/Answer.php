<?php

namespace App\Livewire\Information;

use App\Models\InformationRequest;
use App\Models\Question;
use App\Enums\ResponseStatus;
use Illuminate\Support\Str;
use Livewire\Component;

class Answer extends Component
{
    public $status = null;
    public $category = null;
    public $month = null;
    public $year = null;
    public $keywords = null;
    public $reg_code = null;
    public $sort = 'waktu_reg';

    public function mount()
    {
        $this->status = request()->query('status');
        $this->category = request()->query('category');
        $this->month = request()->query('month');
        $this->year = request()->query('year');
        $this->keywords = request()->query('keywords');
        $this->reg_code = request()->query('reg_code');
        $this->sort = request()->query('sort', 'waktu_reg');
    }

    public function render()
    {
        // Get all items for counting
        $allInformationRequests = InformationRequest::with('reportProcesses.responseStatus')->get();
        $allQuestions = Question::with('reportProcesses.responseStatus')->get();
        $allItems = $allInformationRequests->concat($allQuestions);

        // Count total
        $totalCount = $allItems->count();

        // Count by status
        $newCount = $allItems->filter(function ($item) {
            return $item->reportProcesses->isEmpty() ||
                   $item->reportProcesses->where('response_status_id', ResponseStatus::Initiation->value)->isNotEmpty();
        })->count();

        $processCount = $allItems->filter(function ($item) {
            return $item->reportProcesses->where('response_status_id', ResponseStatus::Process->value)->isNotEmpty() &&
                   $item->reportProcesses->where('response_status_id', ResponseStatus::Termination->value)->isEmpty();
        })->count();

        // For 'disposisi', you may need to check if there's a specific status for it
        // For now, I'll set it to 0 or you can add the logic if there's a disposal status
        $disposalCount = 0; // Update this logic based on your disposal status

        $finishedCount = $allItems->filter(function ($item) {
            return $item->reportProcesses->where('response_status_id', ResponseStatus::Termination->value)->isNotEmpty();
        })->count();

        // Filter items based on status query parameter
        $informationRequestsQuery = InformationRequest::with('reportProcesses.responseStatus');
        $questionsQuery = Question::with('reportProcesses.responseStatus');

        if ($this->status) {
            switch ($this->status) {
                case 'baru':
                    $informationRequestsQuery->where(function ($query) {
                        $query->whereDoesntHave('reportProcesses')
                              ->orWhereHas('reportProcesses', function ($q) {
                                  $q->where('response_status_id', ResponseStatus::Initiation->value);
                              });
                    });
                    $questionsQuery->where(function ($query) {
                        $query->whereDoesntHave('reportProcesses')
                              ->orWhereHas('reportProcesses', function ($q) {
                                  $q->where('response_status_id', ResponseStatus::Initiation->value);
                              });
                    });
                    break;
                case 'diproses':
                    $informationRequestsQuery->whereHas('reportProcesses', function ($q) {
                        $q->where('response_status_id', ResponseStatus::Process->value);
                    })->whereDoesntHave('reportProcesses', function ($q) {
                        $q->where('response_status_id', ResponseStatus::Termination->value);
                    });
                    $questionsQuery->whereHas('reportProcesses', function ($q) {
                        $q->where('response_status_id', ResponseStatus::Process->value);
                    })->whereDoesntHave('reportProcesses', function ($q) {
                        $q->where('response_status_id', ResponseStatus::Termination->value);
                    });
                    break;
                case 'disposisi':
                    // Add logic for disposisi status if you have one
                    // For now, returning empty results
                    $informationRequestsQuery->whereRaw('1 = 0'); // No results
                    $questionsQuery->whereRaw('1 = 0'); // No results
                    break;
                case 'selesai':
                    $informationRequestsQuery->whereHas('reportProcesses', function ($q) {
                        $q->where('response_status_id', ResponseStatus::Termination->value);
                    });
                    $questionsQuery->whereHas('reportProcesses', function ($q) {
                        $q->where('response_status_id', ResponseStatus::Termination->value);
                    });
                    break;
            }
        }

        if ($this->category) {
            if ($this->category === 'permohonan-informasi') {
                $questionsQuery->whereRaw('1 = 0'); // Exclude questions
            } elseif ($this->category === 'pengaduan-masyarakat') {
                $informationRequestsQuery->whereRaw('1 = 0'); // Exclude information requests
            }
        }

        if ($this->month) {
            $informationRequestsQuery->whereMonth('created_at', $this->month);
            $questionsQuery->whereMonth('created_at', $this->month);
        }

        if ($this->year) {
            $informationRequestsQuery->whereYear('created_at', $this->year);
            $questionsQuery->whereYear('created_at', $this->year);
        }

        if ($this->keywords) {
            $informationRequestsQuery->where(function ($query) {
                $query->where('report_title', 'like', "%{$this->keywords}%")
                      ->orWhere('reporter_name', 'like', "%{$this->keywords}%");
            });
            $questionsQuery->where(function ($query) {
                $query->where('content', 'like', "%{$this->keywords}%")
                      ->orWhere('reporter_name', 'like', "%{$this->keywords}%");
            });
        }

        if ($this->reg_code) {
            $informationRequestsQuery->where('registration_code', 'like', "%{$this->reg_code}%");
            $questionsQuery->where('registration_code', 'like', "%{$this->reg_code}%");
        }

        $informationRequests = $informationRequestsQuery->get();
        $questions = $questionsQuery->get();

        $items = $informationRequests->concat($questions)->sortByDesc('created_at');

        if ($this->sort === 'waktu_reg') {
            $items = $items->sortByDesc('created_at');
        }

        return view('livewire.information.answer', [
            'items' => $items,
            'totalCount' => $totalCount,
            'newCount' => $newCount,
            'processCount' => $processCount,
            'disposalCount' => $disposalCount,
            'finishedCount' => $finishedCount
        ]);
    }
}