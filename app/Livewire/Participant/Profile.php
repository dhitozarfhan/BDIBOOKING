<?php

namespace App\Livewire\Participant;

use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;
    public $province_id;
    public $city_id;
    public $birth_place;
    public $birth_date;
    public $institution;
    public $nik;

    public $cities = [];

    public function mount()
    {
        $user = Auth::guard('participant')->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->province_id = $user->province_id;
        $this->city_id = $user->city_id;
        $this->birth_place = $user->birth_place;
        $this->birth_date = $user->birth_date?->format('Y-m-d');
        $this->institution = $user->institution;
        $this->nik = $user->nik;

        // Load cities for current province
        if ($this->province_id) {
            $this->cities = City::where('province_id', $this->province_id)->orderBy('name')->get();
        }
    }

    /**
     * Ketika province_id berubah, load daftar kota/kabupaten terkait.
     */
    public function updatedProvinceId($value)
    {
        $this->city_id = null;
        $this->cities = $value
            ? City::where('province_id', $value)->orderBy('name')->get()
            : [];
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:participants,email,' . Auth::guard('participant')->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'institution' => 'nullable|string|max:255',
        ]);

        Auth::guard('participant')->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
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
        return view('livewire.participant.profile', [
            'provinces' => Province::orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Profil Peserta']);
    }
}
