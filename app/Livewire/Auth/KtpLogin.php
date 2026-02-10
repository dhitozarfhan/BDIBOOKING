<?php

namespace App\Livewire\Auth;

use App\Models\Participant;
use App\Services\OcrService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;

class KtpLogin extends Component
{
    use WithFileUploads;

    public $ktp_image;

    public function scan(OcrService $ocrService)
    {
        $this->validate([
            'ktp_image' => 'required|image|max:10240', // 10MB max
        ]);

        $data = $ocrService->scan($this->ktp_image);

        if (empty($data) || !isset($data['nik'])) {
            throw ValidationException::withMessages([
                'ktp_image' => 'Gagal memindai KTP. Pastikan gambar jelas dan coba lagi.',
            ]);
        }

        $nik = $data['nik'];
        
        // Find existing participant
        $participant = Participant::where('nik', $nik)->first();

        if ($participant) {
            // Login user
            Auth::guard('participant')->login($participant);
            return redirect()->route('participant.dashboard'); // Assuming dashboard is the intended route
        } else {
            // New user, redirect to registration with pre-filled data
            // Store OCR data in session
            session()->put('ocr_data', $data);
            return redirect()->route('participant.register');
        }
    }

    public function render()
    {
        return view('livewire.auth.ktp-login')->layout('layouts.guest');
    }
}
