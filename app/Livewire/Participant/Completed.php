<?php

namespace App\Livewire\Participant;

use App\Models\Property;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Completed extends Component
{
    public $filter = 'semua';

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function getBookingsProperty()
    {
        $query = Auth::guard('participant')->user()
            ->bookings()
            ->with(['bookable', 'certificate'])
            ->where('status', 'completed')
            ->latest();

        if ($this->filter === 'pelatihan') {
            $query->where('bookable_type', Training::class);
        } elseif ($this->filter === 'properti') {
            $query->where('bookable_type', Property::class);
        }

        return $query->get();
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
        return view('livewire.participant.completed', [
            'bookings' => $this->bookings,
        ])->layout('layouts.app', ['title' => 'Riwayat Layanan']);
    }
}
