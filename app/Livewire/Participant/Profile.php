<?php

namespace App\Livewire\Participant;

use App\Models\Area;
use App\Models\Occupation;
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
    public $district_id;
    public $village_id;
    public $birth_place;
    public $birth_date;
    public $nik;

    public $cities = [];
    public $districts = [];
    public $villages = [];

    public function mount()
    {
        $user = Auth::guard('participant')->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->province_id = $user->province_id;
        $this->city_id = $user->city_id;
        $this->district_id = $user->district_id;
        $this->village_id = $user->village_id;
        $this->birth_place = $user->birth_place;
        $this->birth_date = $user->birth_date?->format('Y-m-d');
        $this->nik = $user->nik;

        // Load cascading locations
        if ($this->province_id) {
            $this->cities = Area::getCities(['province_id' => $this->province_id]);
        }
        if ($this->city_id) {
            $this->districts = Area::getDistricts(['city_id' => $this->city_id]);
        }
        if ($this->district_id) {
            $this->villages = Area::getVillages(['district_id' => $this->district_id]);
        }
    }

    /**
     * Ketika province_id berubah, load daftar kota/kabupaten terkait.
     */
    public function updatedProvinceId($value)
    {
        $this->cities = $value
            ? Area::getCities(['province_id' => $value])
            : collect([]);
        $this->city_id = null;
        $this->district_id = null;
        $this->village_id = null;
        $this->districts = collect([]);
        $this->villages = collect([]);
    }

    public function updatedCityId($value)
    {
        $this->districts = $value
            ? Area::getDistricts(['city_id' => $value])
            : collect([]);
        $this->district_id = null;
        $this->village_id = null;
        $this->villages = collect([]);
    }

    public function updatedDistrictId($value)
    {
        $this->villages = $value
            ? Area::getVillages(['district_id' => $value])
            : collect([]);
        $this->village_id = null;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:participants,email,' . Auth::guard('participant')->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'province_id' => 'nullable|exists:areas,id',
            'city_id' => 'nullable|exists:areas,id',
            'district_id' => 'nullable|exists:areas,id',
            'village_id' => 'nullable|exists:areas,id',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
        ]);

        Auth::guard('participant')->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'village_id' => $this->village_id,
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
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
            'provinces' => Area::getProvinces(),
            'occupations' => Occupation::orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Profil Peserta']);
    }
}
