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
    public $gRecaptchaResponse;

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
            $rules['gRecaptchaResponse'] = 'required|recaptcha';
        }

        if ($this->currentView === 'status') {
            $rules['kode_register'] = 'required|string|max:255';
            $rules['gRecaptchaResponse'] = 'required|recaptcha';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'gRecaptchaResponse.required' => 'Verifikasi reCAPTCHA wajib diisi.',
            'gRecaptchaResponse.recaptcha' => 'Verifikasi reCAPTCHA gagal.',
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
            'data_dukung', 'gRecaptchaResponse', 'kode_register',
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

        session()->flash('message', "Laporan gratifikasi Anda telah berhasil dikirim dengan kode register: $kodeRegister. Terima kasih atas partisipasi Anda dalam menjaga integritas.");

        $this->resetForm();
        $this->currentView = 'index';
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
        $this->validate();
        
        // Cari berdasarkan kode_register terlebih dahulu
        $gratification = GratificationModel::where('kode_register', $this->kode_register)->first();

        if ($gratification) {
            // Ambil detail proses terbaru dari tabel pivot
            $process = GratificationProcess::where('gratification_id', $gratification->id)
                        ->latest('created_at')
                        ->first();
            
            $this->reportDetail = $gratification;
            $this->reportDetail->status = $process->status ?? 'I';
            $this->reportDetail->jawaban = $process->jawaban ?? null;
            
            $this->showReportDetail = true;
            $this->statusError = '';
        } else {
            $this->showReportDetail = false;
            $this->statusError = 'Kode register tidak ditemukan dalam sistem kami.';
        }
    }

    public function updateReport()
    {
        // Ambil data dari database untuk laporan statistik
        $year = $this->selectedYear;
        
        // Jumlah laporan per bulan
        $this->reportCountData = [];
        for ($month = 1; $month <= 12; $month++) {
            $this->reportCountData[$month] = GratificationModel::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
        }
        
        // Rata-rata waktu penyelesaian per bulan
        // Ambil waktu antara laporan dibuat dan status berubah menjadi T (Terminasi)
        $this->timeToAnswerData = [];
        for ($month = 1; $month <= 12; $month++) {
            $processes = GratificationProcess::whereHas('gratification', function($query) use ($year, $month) {
                $query->whereYear('created_at', $year)
                      ->whereMonth('created_at', $month);
            })
            ->where('status', 'T') // Hanya laporan yang sudah selesai
            ->with('gratification')
            ->get();
            
            $totalDays = 0;
            foreach ($processes as $process) {
                if ($process->gratification) {
                    $days = $process->gratification->created_at->diffInDays($process->created_at);
                    $totalDays += $days;
                }
            }
            
            $this->timeToAnswerData[$month] = $processes->count() > 0 ? round($totalDays / $processes->count()) : 0;
        }
        
        // Distribusi status laporan berdasarkan status terbaru
        $subquery = GratificationProcess::select('gratification_id', \DB::raw('MAX(created_at) as max_created_at'))
            ->whereYear('created_at', $year)
            ->groupBy('gratification_id');
    
        $latestProcesses = GratificationProcess::select('status', \DB::raw('count(*) as count'))
            ->joinSub($subquery, 'latest_status', function($join) {
                $join->on('gratification_processes.gratification_id', '=', 'latest_status.gratification_id')
                     ->on('gratification_processes.created_at', '=', 'latest_status.max_created_at');
            })
            ->groupBy('status')
            ->get();
        
        $this->statusData = $latestProcesses->map(function($item) {
            return [
                'status' => $item->status,
                'count' => $item->count
            ];
        })->toArray();
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