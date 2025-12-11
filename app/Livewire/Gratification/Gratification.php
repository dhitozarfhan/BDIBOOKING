<?php

namespace App\Livewire\Gratification;

use App\Enums\ResponseStatus;
use App\Models\Gratification as GratificationModel;
use App\Models\ReportProcess;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Gratification extends Component
{
    use WithFileUploads;

    // Variabel untuk formulir
    public $reporter_name;
    public $identity_number;
    public $address;
    public $occupation;
    public $phone;
    public $email;
    public $report_title;
    public $report_description;
    public $attachment;
    public $identity_card_attachment;

    // Variabel untuk navigasi antar view
    public $currentView = 'index';
    
    // Variabel untuk cek status laporan
    public $registration_code;
    public $showReportDetail = false;
    public $statusError = '';
    public $reportDetail;
    
    // Variabel untuk laporan statistik
    public $selectedYear;
    public $reportCountData;
    public $timeToAnswerData;
    public $statusData;

    public function mount()
    {
        $this->selectedYear = date('Y');
        
        // Menentukan view awal berdasarkan route
        $currentRoute = request()->route()->getName();
        if (str_contains($currentRoute, 'gratification.form')) {
            $this->currentView = 'form';
        } elseif (str_contains($currentRoute, 'gratification.status')) {
            $this->currentView = 'status';
        } elseif (str_contains($currentRoute, 'gratification.report')) {
            $this->currentView = 'report';
            $this->loadReportData();
        } else {
            $this->currentView = 'index'; // default
        }
    }

    public function rules()
    {
        $rules = [
            'reporter_name' => 'required|string|max:255',
            'identity_number' => 'nullable|string|max:255',
            'identity_card_attachment' => 'nullable|file|image|max:2048', // 2MB max
            'address' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'report_title' => 'required|string|max:255',
            'report_description' => 'required|string',
            'attachment' => 'nullable|file|max:1024', // 1MB max
        ];

        if ($this->currentView === 'form') {
        }

        if ($this->currentView === 'status') {
            $rules['registration_code'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'registration_code.required' => 'Kode register wajib diisi.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function setView($view)
    {
        $this->currentView = $view;
        $this->resetForm();
        
        if ($view === 'report') {
            $this->updateReport();
        }
    }
    
    public function resetForm()
    {
        $this->reset([
            'reporter_name', 'identity_number', 'address', 'occupation', 
            'phone', 'email', 'report_title', 'report_description', 
            'attachment', 'identity_card_attachment', 'registration_code',
            'showReportDetail', 'statusError', 'reportDetail'
        ]);
    }

    public function save()
    {
        $this->validate();

        $filePath = null;
        if ($this->attachment) {
            $filePath = $this->attachment->store('gratifications', 'private');
        }

        $identityCardPath = null;
        if ($this->identity_card_attachment) {
            $identityCardPath = $this->identity_card_attachment->store('identity_cards', 'private');
        }

        // Generate kode register unik
        $registrationCode = $this->generateKodeRegister();

        $gratification = GratificationModel::create([
            'reporter_name' => $this->reporter_name,
            'identity_number' => $this->identity_number,
            'identity_card_attachment' => $identityCardPath,
            'address' => $this->address,
            'occupation' => $this->occupation,
            'phone' => $this->phone,
            'email' => $this->email,
            'report_title' => $this->report_title,
            'report_description' => $this->report_description,
            'attachment' => $filePath,
            'registration_code' => $registrationCode,
        ]);

        // Create the initial process record for the gratification
        $gratification->reportProcesses()->create([
            'response_status_id' => ResponseStatus::Initiation->value,
            'answer' => null,
        ]);

        session()->flash('message', 'Laporan gratifikasi Anda telah berhasil dikirim dengan kode register: ');
        session()->flash('registration_code', $registrationCode);

        $this->resetForm();

    }

    private function generateKodeRegister()
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = GratificationModel::where('registration_code', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    public function checkStatus()
    {
        $this->validate([
            'registration_code' => 'required|string|max:255',
        ]);

        $gratification = GratificationModel::where('registration_code', $this->registration_code)->first();

        if ($gratification) {
            // Get the latest process for current status
            $latestProcess = $gratification->process; // latestOfMany

            $reportDetail = new \stdClass();
            $reportDetail->subject = $gratification->report_title;
            $reportDetail->name = $gratification->reporter_name;
            // PII removed for privacy
            $reportDetail->mobile = $gratification->phone ? substr($gratification->phone, 0, -4) . 'xxxx' : '-';
            $reportDetail->time_insert = $gratification->created_at;
            $reportDetail->content = $gratification->report_description;
            $reportDetail->processes = $gratification->reportProcesses()->with('responseStatus')->get();
            $reportDetail->status = $latestProcess ? $latestProcess->response_status_id : ResponseStatus::Initiation->value;

            // Get the latest completed termination process for the final answer
            $terminationProcess = $gratification->reportProcesses()
                ->where('response_status_id', ResponseStatus::Termination->value)
                ->where('is_completed', true)
                ->latest()
                ->first();

            $reportDetail->answer = $terminationProcess->answer ?? null;
            $reportDetail->answer_attachment = $terminationProcess->answer_attachment ?? null;

            // Set original attachment
            $reportDetail->attachment = $gratification->attachment;

            // Store the report detail in the session and redirect
            session()->flash('reportDetail', $reportDetail);
            return redirect()->route('gratification.response');

        } else {
            // If not found, flash an error message and redirect back
            session()->flash('statusError', 'Kode register tidak ditemukan dalam sistem kami.');
            return redirect()->route('gratification.status');
        }
    }

    public function loadReportData()
    {
        $year = $this->selectedYear;

        // Data laporan per bulan
        $this->reportCountData = DB::table('gratifications')
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Waktu rata-rata penyelesaian per bulan
        $this->timeToAnswerData = DB::table('gratifications')
            ->join('report_processes', function ($join) {
                $join->on('gratifications.id', '=', 'report_processes.reportable_id')
                     ->where('report_processes.reportable_type', '=', GratificationModel::class);
            })
            ->selectRaw('EXTRACT(MONTH FROM gratifications.created_at) as month, AVG(EXTRACT(DAY FROM (report_processes.created_at - gratifications.created_at))) as avg_days')
            ->whereYear('gratifications.created_at', $year)
            ->whereNotNull('report_processes.created_at')
            ->where('report_processes.response_status_id', ResponseStatus::Termination->value) // Completed status
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('avg_days', 'month')
            ->map(function ($value) {
                return $value ? round($value) : 0;
            })
            ->toArray();

        // Distribusi status
        $lastProcessSubquery = DB::table('report_processes')
            ->select('reportable_id', DB::raw('MAX(id) as latest_process_id'))
            ->where('reportable_type', GratificationModel::class)
            ->groupBy('reportable_id');

        $this->statusData = DB::table('gratifications')
            ->joinSub($lastProcessSubquery, 'latest_processes', function ($join) {
                $join->on('gratifications.id', '=', 'latest_processes.reportable_id');
            })
            ->join('report_processes', function ($join) {
                $join->on('report_processes.id', '=', 'latest_processes.latest_process_id');
            })
            ->select('report_processes.response_status_id', DB::raw('COUNT(*) as count'))
            ->whereYear('gratifications.created_at', $year)
            ->groupBy('report_processes.response_status_id')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ResponseStatus::from($item->response_status_id),
                    'count' => $item->count
                ];
            })
            ->toArray();
    }

    public function updateReport()
    {
        $this->loadReportData();
    }

    public function render()
    {
        if ($this->currentView === 'form') {
            return view('livewire.gratification.form');
        } elseif ($this->currentView === 'status') {
            return view('livewire.gratification.status');
        } elseif ($this->currentView === 'report') {
            return view('livewire.gratification.report');
        } else {
            return view('livewire.gratification.index');
        }
    }
}
