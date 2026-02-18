<?php

namespace App\Livewire\Participant;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Completed extends Component
{
    public $bookings;

    public function mount()
    {
        $this->bookings = Auth::guard('participant')->user()
            ->bookings()
            ->with(['bookable', 'certificate'])
            ->where('status', 'completed')
            ->latest()
            ->get();
    }

    public function downloadCertificate($bookingId)
    {
        $booking = Auth::guard('participant')->user()
            ->bookings()
            ->with('certificate')
            ->findOrFail($bookingId);

        if ($booking->certificate && Storage::disk('public')->exists($booking->certificate->file_path)) {
            return Storage::disk('public')->download(
                $booking->certificate->file_path,
                'Sertifikat_' . str_replace('/', '_', $booking->certificate->certificate_number) . '.pdf'
            );
        }

        session()->flash('error', 'File sertifikat tidak ditemukan.');
    }

    public function logout()
    {
        Auth::guard('participant')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.participant.completed')
            ->layout('layouts.app', ['title' => 'Diklat Diselesaikan']);
    }
}
