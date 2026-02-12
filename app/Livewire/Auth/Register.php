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
            
            // Map OCR data to component properties
            // Adjust keys based on OcrService mock/real response
            $this->nik = $ocrData['nik'] ?? $this->nik;
            $this->name = $ocrData['nama'] ?? $this->name;
            $this->birth_place = $ocrData['tempat_lahir'] ?? $this->birth_place;
            $this->birth_date = $ocrData['tanggal_lahir'] ?? $this->birth_date;
            $this->address = $ocrData['alamat'] ?? $this->address;
            
            // Optional: You might want to try to map gender, religion etc if the OCR returns them and they match your DB IDs or similar.
            // For now, valid defaults or just text fields are pre-filled.
            
            // Clear session data so it doesn't persist inappropriately if they leave and come back (optional logic)
            // session()->forget('ocr_data'); 
            
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
