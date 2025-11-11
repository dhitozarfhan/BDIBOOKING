<?php

namespace App\Livewire\Gratification;

use App\Models\Gratification as GratificationModel;
use App\Models\GratificationProcess;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Gratification extends Component
{
    use WithFileUploads;

    // Variabel untuk formulir
    public $nama_pelapor;
    public $nomor_identitas;
    public $alamat;
    public $pekerjaan;
    public $telepon;
    public $email;
    public $judul_laporan;
    public $uraian_laporan;
    public $data_dukung;

    // Variabel untuk navigasi antar view
    public $currentView = 'index';
    
    // Variabel untuk cek status laporan
    public $kode_register;
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
            'nama_pelapor' => 'required|string|max:255',
            'nomor_identitas' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'judul_laporan' => 'required|string|max:255',
            'uraian_laporan' => 'required|string',
            'data_dukung' => 'nullable|file|max:1024', // 1MB max
        ];

        if ($this->currentView === 'form') {
        }

        if ($this->currentView === 'status') {
            $rules['kode_register'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'kode_register.required' => 'Kode register wajib diisi.',
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
            'nama_pelapor', 'nomor_identitas', 'alamat', 'pekerjaan', 
            'telepon', 'email', 'judul_laporan', 'uraian_laporan', 
            'data_dukung', 'kode_register',
            'showReportDetail', 'statusError', 'reportDetail'
        ]);
    }

    public function save()
    {
        $this->validate();

        $filePath = null;
        if ($this->data_dukung) {
            $filePath = $this->data_dukung->store('gratifications', 'public');
        }

        // Generate kode register unik
        $kodeRegister = $this->generateKodeRegister();

        $gratification = GratificationModel::create([
            'nama_pelapor' => $this->nama_pelapor,
            'nomor_identitas' => $this->nomor_identitas,
            'alamat' => $this->alamat,
            'pekerjaan' => $this->pekerjaan,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'judul_laporan' => $this->judul_laporan,
            'uraian_laporan' => $this->uraian_laporan,
            'data_dukung' => $filePath,
            'kode_register' => $kodeRegister,
        ]);

        // Create the initial process record for the gratification
        GratificationProcess::create([
            'gratification_id' => $gratification->id,
            'status' => 'I', // Set status awal sebagai Inisiasi
            'jawaban' => null,
            'waktu_publish' => null,
        ]);

        session()->flash('message', 'Laporan gratifikasi Anda telah berhasil dikirim dengan kode register: ');
        session()->flash('kode_register', $kodeRegister);

        $this->resetForm();

    }

    private function generateKodeRegister()
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = GratificationModel::where('kode_register', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    public function checkStatus()
    {
        $this->validate([
            'kode_register' => 'required|string|max:255',
        ]);

        $gratification = GratificationModel::where('kode_register', $this->kode_register)->first();

        if ($gratification) {
            $process = $gratification->processes()->latest()->first();

            $reportDetail = new \stdClass();
            $reportDetail->subject = $gratification->judul_laporan;
            $reportDetail->name = $gratification->nama_pelapor;
            $reportDetail->mobile = $gratification->telepon ? substr($gratification->telepon, 0, -4) . 'xxxx' : '-';
            $reportDetail->time_insert = $gratification->created_at;
            $reportDetail->content = $gratification->uraian_laporan;

            if ($process) {
                $reportDetail->status = $process->status;
                $reportDetail->answer = $process->jawaban;
            } else {
                $reportDetail->status = 'I';
                $reportDetail->answer = null;
            }

            // Set both the original attachment and answer attachment
            $reportDetail->attachment = $gratification->data_dukung; // Original gratification attachment
            $reportDetail->answer_attachment = $process ? $process->jawaban_lampiran : null; // Answer attachment from process

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
            ->join('gratification_processes', 'gratifications.id', '=', 'gratification_processes.gratification_id')
            ->selectRaw('EXTRACT(MONTH FROM gratifications.created_at) as month, AVG(EXTRACT(DAY FROM (gratification_processes.waktu_publish - gratifications.created_at))) as avg_days')
            ->whereYear('gratifications.created_at', $year)
            ->whereNotNull('gratification_processes.waktu_publish')
            ->where('gratification_processes.status', 'T') // Completed status
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('avg_days', 'month')
            ->map(function ($value) {
                return $value ? round($value) : 0;
            })
            ->toArray();

        // Distribusi status
        $lastProcessSubquery = DB::table('gratification_processes')
            ->select('gratification_id', DB::raw('MAX(id) as latest_process_id'))
            ->groupBy('gratification_id');

        $this->statusData = DB::table('gratifications')
            ->joinSub($lastProcessSubquery, 'latest_processes', function ($join) {
                $join->on('gratifications.id', '=', 'latest_processes.gratification_id');
            })
            ->join('gratification_processes', function ($join) {
                $join->on('gratifications.id', '=', 'gratification_processes.gratification_id')
                     ->on('gratification_processes.id', '=', 'latest_processes.latest_process_id');
            })
            ->select('gratification_processes.status', DB::raw('COUNT(*) as count'))
            ->whereYear('gratifications.created_at', $year)
            ->groupBy('gratification_processes.status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
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