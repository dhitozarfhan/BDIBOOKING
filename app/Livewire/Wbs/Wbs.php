<?php

namespace App\Livewire\Wbs;

use App\Models\Wbs as WbsModel;
use App\Models\Violation;
use App\Models\WbsProcess;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Wbs extends Component
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
    public $violation_id;

    // Variabel untuk navigasi antar view
    public $currentView = 'form';

    public $violations = [];

    public function mount()
    {
        // Menentukan view awal berdasarkan route
        $currentRoute = request()->route()->getName();
        if (str_contains($currentRoute, 'wbs.form')) {
            $this->currentView = 'form';
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
            'nama_pelapor', 'nomor_identitas', 'alamat', 'pekerjaan',
            'telepon', 'email', 'judul_laporan', 'uraian_laporan',
            'data_dukung', 'violation_id'
        ]);
    }

    public function rules()
    {
        return [
            'nama_pelapor' => 'required|string|max:255',
            'nomor_identitas' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'judul_laporan' => 'required|string|max:255',
            'uraian_laporan' => 'required|string',
            'data_dukung' => 'nullable|file|max:10240', // 10MB max
            'violation_id' => 'nullable|exists:violations,id',
        ];
    }

    public function messages()
    {
        return [
            'nama_pelapor.required' => 'Nama pelapor wajib diisi.',
            'judul_laporan.required' => 'Judul laporan wajib diisi.',
            'uraian_laporan.required' => 'Uraian laporan wajib diisi.',
            'data_dukung.max' => 'File data dukung maksimal 10MB.',
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
        if ($this->data_dukung) {
            $filePath = $this->data_dukung->store('wbs', 'public');
        }

        // Generate kode register unik
        $kodeRegister = $this->generateKodeRegister();

        $wbs = WbsModel::create([
            'nama_pelapor' => $this->nama_pelapor,
            'nomor_identitas' => $this->nomor_identitas,
            'alamat' => $this->alamat,
            'pekerjaan' => $this->pekerjaan,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'judul_laporan' => $this->judul_laporan,
            'uraian_laporan' => $this->uraian_laporan,
            'data_dukung' => $filePath,
            'violation_id' => $this->violation_id,
            'kode_register' => $kodeRegister,
        ]);

        // Create the initial process record for the WBS
        WbsProcess::create([
            'wbs_id' => $wbs->id,
            'status' => 'I', // Set status awal sebagai Inisiasi
            'jawaban' => null,
            'waktu_publish' => null,
        ]);

        session()->flash('message', 'Laporan WBS Anda telah berhasil dikirim dengan kode register: ');
        session()->flash('kode_register', $kodeRegister);

        // Reset form
        $this->reset([
            'nama_pelapor', 'nomor_identitas', 'alamat', 'pekerjaan',
            'telepon', 'email', 'judul_laporan', 'uraian_laporan',
            'data_dukung', 'violation_id'
        ]);
    }

    private function generateKodeRegister()
    {
        do {
            $kode = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
            $exists = WbsModel::where('kode_register', $kode)->exists();
        } while ($exists);

        return $kode;
    }

    public function render()
    {
        if ($this->currentView === 'form') {
            return view('livewire.wbs.form');
        } else {
            return view('livewire.wbs.index');
        }
    }
}