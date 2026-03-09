<?php

namespace App\Livewire\Participant;

use App\Models\Property;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Enrolled extends Component
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
            ->with(['bookable'])
            ->whereIn('status', ['pending', 'approved'])
            ->latest();

        if ($this->filter === 'pelatihan') {
            $query->where('bookable_type', Training::class);
        } elseif ($this->filter === 'properti') {
            $query->where('bookable_type', Property::class);
        }

        return $query->get();
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
        return view('livewire.participant.enrolled', [
            'bookings' => $this->bookings,
        ])->layout('layouts.app', ['title' => 'Layanan Pelatihan & Properti']);
    }
}
