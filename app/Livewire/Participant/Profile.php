<?php

namespace App\Livewire\Participant;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;
    public $province;
    public $city;
    public $birth_place;
    public $birth_date;
    public $institution;
    public $nik;

    public function mount()
    {
        $user = Auth::guard('participant')->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->province = $user->province;
        $this->city = $user->city;
        $this->birth_place = $user->birth_place;
        $this->birth_date = $user->birth_date?->format('Y-m-d');
        $this->institution = $user->institution;
        $this->nik = $user->nik;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:participants,email,' . Auth::guard('participant')->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'institution' => 'nullable|string|max:255',
        ]);

        Auth::guard('participant')->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'province' => $this->province,
            'city' => $this->city,
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'institution' => $this->institution,
        ]);

        session()->flash('success', 'Profil berhasil diperbarui.');
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
        return view('livewire.participant.profile')
            ->layout('layouts.app', ['title' => 'Profil Peserta']);
    }
}
