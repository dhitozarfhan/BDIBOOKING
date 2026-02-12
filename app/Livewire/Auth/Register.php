<?php

namespace App\Livewire\Auth;

use Livewire\Component;

use App\Models\Participant;
use App\Models\Gender;
use App\Models\Religion;
use App\Models\Occupation;
use App\Mail\ParticipantRegistered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Register extends Component
{
    public $nik;
    public $name;
    public $email;
    public $birth_place;
    public $birth_date;
    public $gender_id;
    public $religion_id;
    public $blood_type;
    public $phone;
    public $address;
    public $occupation_id;
    public $institution;

    public function mount()
    {
        if (session()->has('ocr_data')) {
            $ocrData = session('ocr_data');
            
            // Map OCR text fields
            $this->nik = $ocrData['nik'] ?? $this->nik;
            $this->name = $ocrData['nama'] ?? $this->name;
            $this->birth_place = $ocrData['tempat_lahir'] ?? $this->birth_place;
            $this->birth_date = $ocrData['tanggal_lahir'] ?? $this->birth_date;
            $this->address = $ocrData['alamat'] ?? $this->address;

            // Map golongan darah → blood_type (hanya jika valid: A, B, AB, O)
            if (!empty($ocrData['gol_darah']) && in_array(strtoupper($ocrData['gol_darah']), ['A', 'B', 'AB', 'O'])) {
                $this->blood_type = strtoupper($ocrData['gol_darah']);
            }
            
            // Map jenis kelamin → gender_id
            if (!empty($ocrData['jenis_kelamin'])) {
                $gender = Gender::whereRaw('LOWER(type) = ?', [strtolower($ocrData['jenis_kelamin'])])->first();
                if ($gender) {
                    $this->gender_id = $gender->id;
                }
            }

            // Map agama → religion_id
            if (!empty($ocrData['agama'])) {
                $religion = Religion::whereRaw('LOWER(name) = ?', [strtolower($ocrData['agama'])])->first();
                if ($religion) {
                    $this->religion_id = $religion->id;
                }
            }

            // Map pekerjaan → occupation_id (fallback ke "Lainnya" jika tidak ditemukan)
            if (!empty($ocrData['pekerjaan'])) {
                $occupation = Occupation::whereRaw('LOWER(name) = ?', [strtolower($ocrData['pekerjaan'])])->first();
                if ($occupation) {
                    $this->occupation_id = $occupation->id;
                } else {
                    $lainnya = Occupation::whereRaw('LOWER(name) = ?', ['lainnya'])->first();
                    if ($lainnya) {
                        $this->occupation_id = $lainnya->id;
                    }
                }
            }
            
            session()->flash('message', 'Formulir telah diisi otomatis dari hasil scan KTP.');
        }
    }

    protected function rules()
    {
        return [
            'nik' => ['required', 'string', 'max:16', 'unique:participants'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:participants'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender_id' => ['required', 'exists:genders,id'],
            'religion_id' => ['required', 'exists:religions,id'],
            'blood_type' => ['nullable', 'string', 'in:A,B,AB,O'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'occupation_id' => ['required', 'exists:occupations,id'],
            'institution' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function register()
    {
        $this->validate();

        // Auto-generate password
        $plainPassword = Str::random(8);

        $participant = Participant::create([
            'nik' => $this->nik,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($plainPassword),
            'birth_place' => $this->birth_place,
            'birth_date' => $this->birth_date,
            'gender_id' => $this->gender_id,
            'religion_id' => $this->religion_id,
            'blood_type' => $this->blood_type,
            'phone' => $this->phone,
            'address' => $this->address,
            'occupation_id' => $this->occupation_id,
            'institution' => $this->institution,
        ]);

        // Send password via email
        try {
            \Illuminate\Support\Facades\Log::info('Mencoba mengirim email password ke: ' . $participant->email);
            
            Mail::to($participant->email)->send(
                new ParticipantRegistered($participant->name, $plainPassword)
            );
            
            \Illuminate\Support\Facades\Log::info('Email password berhasil dikirim ke: ' . $participant->email);
            session()->flash('success', 'Pendaftaran berhasil! Password telah dikirim ke email Anda. Silakan cek Inbox atau Spam.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GAGAL mengirim email password ke ' . $participant->email . '. Error: ' . $e->getMessage());
            
            // Tampilkan pesan error ke user agar mereka sadar
            session()->flash('warning', 'Pendaftaran berhasil, namun GAGAL mengirim email password. Silakan hubungi admin atau coba "Lupa Password". Error: ' . $e->getMessage());
        }

        return redirect()->route('participant.login');
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'genders' => Gender::all(),
            'religions' => Religion::all(),
            'occupations' => Occupation::all(),
        ])->layout('layouts.guest');
    }
}
