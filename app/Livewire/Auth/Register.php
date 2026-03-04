<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Party;
use App\Mail\ParticipantRegistered;
use App\Mail\OtpVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Register extends Component
{
    // Form fields
    public $name;
    public $email;
    public $phone;
    public $password;
    public $party_type = 'individual';
    public $company_name;

    // OTP fields
    public $otp_input;
    public $step = 1; // 1 = form, 2 = OTP verification
    public $otp_sent_at;
    public $otp_cooldown = false;

    public function mount()
    {
        //
    }

    protected function formRules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'party_type' => ['required', 'in:individual,company'],
        ];

        if ($this->party_type === 'company') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'company_name.required' => 'Nama organisasi wajib diisi jika mendaftar sebagai organisasi.',
        ];
    }

    /**
     * Step 1: Validate form and send OTP
     */
    public function sendOtp()
    {
        $this->validate($this->formRules(), $this->messages());

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in session with expiry (5 minutes)
        Session::put('registration_otp', [
            'code' => $otp,
            'email' => $this->email,
            'expires_at' => now()->addMinutes(5)->timestamp,
        ]);

        // Send OTP email
        try {
            Mail::to($this->email)->send(new OtpVerification($otp, $this->name));
            Log::info("OTP dikirim ke: {$this->email}");
            $this->step = 2;
            $this->otp_sent_at = now()->timestamp;
        } catch (\Exception $e) {
            Log::error("Gagal kirim OTP ke {$this->email}: " . $e->getMessage());
            $this->addError('email', 'Gagal mengirim kode verifikasi. Pastikan email valid dan coba lagi.');
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp()
    {
        $this->resetErrorBag('otp_input');
        $this->sendOtp();
    }

    /**
     * Go back to form
     */
    public function backToForm()
    {
        $this->step = 1;
        $this->otp_input = null;
        $this->resetErrorBag();
    }

    /**
     * Step 2: Verify OTP and complete registration
     */
    public function verifyOtp()
    {
        $this->validate([
            'otp_input' => ['required', 'string', 'size:6'],
        ], [
            'otp_input.required' => 'Kode OTP wajib diisi.',
            'otp_input.size' => 'Kode OTP harus 6 digit.',
        ]);

        $otpData = Session::get('registration_otp');

        // Check OTP exists
        if (!$otpData) {
            $this->addError('otp_input', 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.');
            return;
        }

        // Check expiry
        if (now()->timestamp > $otpData['expires_at']) {
            Session::forget('registration_otp');
            $this->addError('otp_input', 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.');
            return;
        }

        // Check email match
        if ($otpData['email'] !== $this->email) {
            $this->addError('otp_input', 'Email tidak sesuai. Silakan ulangi pendaftaran.');
            return;
        }

        // Check OTP code
        if ($otpData['code'] !== $this->otp_input) {
            $this->addError('otp_input', 'Kode OTP salah. Periksa kembali email Anda.');
            return;
        }

        // OTP valid — clear session
        Session::forget('registration_otp');

        // 1. Create Party
        $party = Party::create([
            'type' => $this->party_type,
            'company_name' => $this->party_type === 'company' ? $this->company_name : null,
        ]);

        // 2. Create Customer
        $customer = Customer::create([
            'party_id' => $party->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
        ]);

        // Registration complete
        session()->flash('success', 'Pendaftaran berhasil! Silakan login dengan email dan password yang sudah dibuat.');

        return redirect()->route('participant.login');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}
