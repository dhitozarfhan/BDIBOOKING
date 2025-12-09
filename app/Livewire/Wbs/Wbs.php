<?php

namespace App\Livewire\Wbs;

use App\Enums\ResponseStatus;
use App\Models\Wbs as WbsModel;
use App\Models\Violation;
use App\Models\ReportProcess;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Wbs extends Component
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
    public $violation_id;

    // Variabel untuk navigasi antar view
    public $currentView = 'form';

    // Variabel untuk status laporan
    public $registration_code;
    public $reportDetail;

    // Variabel untuk laporan statistik
    public $selectedYear;
    public $reportCountData;
    public $timeToAnswerData;
    public $statusData;

    public $violations = [];

    public function mount()
    {
        // Menentukan view awal berdasarkan route
        $currentRoute = request()->route()->getName();
        if (str_contains($currentRoute, 'wbs.form')) {
            $this->currentView = 'form';
        } elseif (str_contains($currentRoute, 'wbs.status')) {
            $this->currentView = 'status';
        } elseif (str_contains($currentRoute, 'wbs.report')) {
            $this->currentView = 'report';
            $this->selectedYear = date('Y');
            $this->loadReportData();
        } else {
            $this->currentView = 'index'; // default
        }
        
        $this->violations = Violation::all();
    }

    public function setView($view)
    {
        $this->currentView = $view;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'reporter_name', 'identity_number', 'address', 'occupation',
            'phone', 'email', 'report_title', 'report_description',
            'attachment', 'identity_card_attachment', 'violation_id'
        ]);
    }

    public function rules()
    {
        return [
            'reporter_name' => 'required|string|max:255',
            'identity_number' => 'nullable|string|max:255',
            'identity_card_attachment' => 'nullable|file|image|max:2048', // 2MB max
            'address' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'report_title' => 'required|string|max:255',
            'report_description' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'violation_id' => 'nullable|exists:violations,id',
        ];
    }

    public function messages()
    {
        return [
            'reporter_name.required' => 'Nama pelapor wajib diisi.',
            'report_title.required' => 'Judul laporan wajib diisi.',
            'report_description.required' => 'Uraian laporan wajib diisi.',
            'attachment.max' => 'File data dukung maksimal 10MB.',
            'violation_id.exists' => 'Pelanggaran yang dipilih tidak valid.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validatedData = $this->validate();

        $filePath = null;
        if ($this->attachment) {
            $filePath = $this->attachment->store('wbs', 'private');
        }

        $identityCardPath = null;
        if ($this->identity_card_attachment) {
            $identityCardPath = $this->identity_card_attachment->store('identity_cards', 'private');
        }

        // Generate kode register unik
        $registrationCode = $this->generateKodeRegister();

        $wbs = WbsModel::create([
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
            'violation_id' => $this->violation_id,
            'registration_code' => $registrationCode,
        ]);

        // Create the initial process record for the WBS
        $wbs->reportProcesses()->create([
            'response_status_id' => ResponseStatus::Initiation->value,
            'answer' => null,
        ]);

        session()->flash('message', 'Laporan WBS Anda telah berhasil dikirim dengan kode register: ');
        session()->flash('registration_code', $registrationCode);

        // Reset form
        $this->reset([
            'reporter_name', 'identity_number', 'address', 'occupation',
            'phone', 'email', 'report_title', 'report_description',
            'attachment', 'identity_card_attachment', 'violation_id'
        ]);
    }

    private function generateKodeRegister()
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = WbsModel::where('registration_code', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    public function checkStatus()
    {
        $this->validate([
            'registration_code' => 'required|string|exists:wbs,registration_code'
        ]);

        $wbs = WbsModel::with('reportProcesses')->where('registration_code', $this->registration_code)->first();
        
        if ($wbs) {
            $this->reportDetail = $wbs;
            $this->currentView = 'response';
        }
    }

    public function loadReportData()
    {
        $year = $this->selectedYear;

        // Data laporan per bulan
        $this->reportCountData = DB::table('wbs')
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Waktu rata-rata penyelesaian per bulan
        $this->timeToAnswerData = DB::table('wbs')
            ->join('report_processes', function ($join) {
                $join->on('wbs.id', '=', 'report_processes.reportable_id')
                     ->where('report_processes.reportable_type', '=', WbsModel::class);
            })
            ->selectRaw('EXTRACT(MONTH FROM wbs.created_at) as month, AVG(EXTRACT(DAY FROM (report_processes.created_at - wbs.created_at))) as avg_days')
            ->whereYear('wbs.created_at', $year)
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
            ->where('reportable_type', WbsModel::class)
            ->groupBy('reportable_id');

        $this->statusData = DB::table('wbs')
            ->joinSub($lastProcessSubquery, 'latest_processes', function ($join) {
                $join->on('wbs.id', '=', 'latest_processes.reportable_id');
            })
            ->join('report_processes', function ($join) {
                $join->on('report_processes.id', '=', 'latest_processes.latest_process_id');
            })
            ->select('report_processes.response_status_id', DB::raw('COUNT(*) as count'))
            ->whereYear('wbs.created_at', $year)
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
            return view('livewire.wbs.form');
        } elseif ($this->currentView === 'status') {
            return view('livewire.wbs.status');
        } elseif ($this->currentView === 'report') {
            return view('livewire.wbs.report');
        } elseif ($this->currentView === 'response') {
            return view('livewire.wbs.response');
        } else {
            return view('livewire.wbs.index');
        }
    }
}
