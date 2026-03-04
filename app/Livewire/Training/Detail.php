<?php

namespace App\Livewire\Training;

use App\Models\Booking;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Detail extends Component
{
    public Training $training;
    public $isRegistered = false;

    public function mount($id_diklat)
    {
        $this->training = Training::findOrFail($id_diklat);

        if ($this->training->type !== Training::TYPE_3IN1) {
            return redirect()->route('pnbp.detail', ['id_diklat' => $this->training->id, 'slug' => \Illuminate\Support\Str::slug($this->training->title)]);
        }

        if (Auth::guard('participant')->check()) {
            $this->isRegistered = Booking::where('customer_id', Auth::guard('participant')->id())
                ->where('bookable_type', Training::class)
                ->where('bookable_id', $this->training->id)
                ->exists();
        }
    }

    public function register()
    {
        if (!Auth::guard('participant')->check()) {
            return redirect()->route('participant.login');
        }

        if ($this->isRegistered) {
            session()->flash('error', 'Anda sudah terdaftar di diklat ini.');
            return;
        }

        // Create Booking
        Booking::create([
            'customer_id' => Auth::guard('participant')->id(),
            'bookable_type' => Training::class,
            'bookable_id' => $this->training->id,
            'status' => 'pending',
        ]);

        // Send Email Notification (Inline HTML)
        $user = Auth::guard('participant')->user();
        $training = $this->training;
        
        $htmlContent = "
            <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                <h2 style='color: #4f46e5;'>Pendaftaran Berhasil</h2>
                <p>Halo <strong>{$user->name}</strong>,</p>
                <p>Anda telah berhasil mendaftar pada diklat:</p>
                <div style='background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 15px 0;'>
                    <h3 style='margin: 0 0 10px 0;'>{$training->title}</h3>
                    <p style='margin: 5px 0;'>📅 <strong>Jadwal:</strong> {$training->start_date->format('d M Y')} - {$training->end_date->format('d M Y')}</p>
                    <p style='margin: 5px 0;'>📍 <strong>Lokasi:</strong> {$training->location}</p>
                </div>
                <p>Silakan pantau status pendaftaran Anda melalui Dashboard Peserta.</p>
                <br>
                <p style='color: #666; font-size: 12px;'>Email ini dikirim otomatis oleh sistem BDI Yogyakarta.</p>
            </div>
        ";

        try {
            Mail::html($htmlContent, function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Pendaftaran Diklat Berhasil - BDI Yogyakarta');
            });
        } catch (\Exception $e) {
            // Log error but don't stop the process
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email notifikasi diklat: ' . $e->getMessage());
        }

        $this->isRegistered = true;
        session()->flash('success', 'Pendaftaran berhasil! Silakan cek dashboard untuk status dan pembayaran.');
        
        return redirect()->route('participant.dashboard');
    }

    public function render()
    {
        return view('livewire.training.detail')->title($this->training->title);
    }
}
