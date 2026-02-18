<?php

namespace App\Livewire\Participant;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Enrolled extends Component
{
    public $bookings;

    public function mount()
    {
        $this->bookings = Auth::guard('participant')->user()
            ->bookings()
            ->with(['bookable'])
            ->whereIn('status', ['pending', 'approved'])
            ->latest()
            ->get();
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
        return view('livewire.participant.enrolled')
            ->layout('layouts.app', ['title' => 'Diklat Diikuti']);
    }
}
