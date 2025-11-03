<?php

namespace App\Livewire\Gratification;

use App\Models\Gratification as GratificationModel;
use Livewire\Component;
use Livewire\WithFileUploads;

class Gratification extends Component
{
    use WithFileUploads;

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
            'data_dukung' => 'nullable|file|max:1024', // 1MB max
            'gRecaptchaResponse' => 'required|recaptcha',
        ];
    }

    public function messages()
    {
        return [
            'gRecaptchaResponse.required' => 'Verifikasi reCAPTCHA wajib diisi.',
            'gRecaptchaResponse.recaptcha' => 'Verifikasi reCAPTCHA gagal.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $filePath = null;
        if ($this->data_dukung) {
            $filePath = $this->data_dukung->store('gratifications', 'public');
        }

        GratificationModel::create([
            'nama_pelapor' => $this->nama_pelapor,
            'nomor_identitas' => $this->nomor_identitas,
            'alamat' => $this->alamat,
            'pekerjaan' => $this->pekerjaan,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'judul_laporan' => $this->judul_laporan,
            'uraian_laporan' => $this->uraian_laporan,
            'data_dukung' => $filePath,
        ]);

        session()->flash('message', 'Laporan gratifikasi Anda telah berhasil dikirim. Terima kasih atas partisipasi Anda dalam menjaga integritas.');

        $this->reset();
    }


    public function render()
    {
        return view('livewire.gratification.gratification');
    }
}