<?php

namespace App\Livewire\Pnbp;

use App\Models\Booking;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Detail extends Component
{
    public Training $training;
    public $isRegistered = false;

    public function mount($id_diklat)
    {
        $this->training = Training::findOrFail($id_diklat);


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
            // Store intended URL to redirect back after login
            session()->put('url.intended', route('pnbp.detail', ['id_diklat' => $this->training->id, 'slug' => \Illuminate\Support\Str::slug($this->training->title)]));
            return redirect()->route('participant.login');
        }

        if ($this->isRegistered) {
            session()->flash('error', 'Anda sudah terdaftar di diklat ini.');
            return;
        }

        // Redirect to new Register Component
        return redirect()->route('training.pnbp.register', [
            'id_diklat' => $this->training->id,
            'slug' => \Illuminate\Support\Str::slug($this->training->title)
        ]);
    }
    public function render()
    {
        return view('livewire.pnbp.detail')->title($this->training->title);
    }
}
