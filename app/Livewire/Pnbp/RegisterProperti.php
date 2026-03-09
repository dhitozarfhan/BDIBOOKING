<?php

namespace App\Livewire\Pnbp;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class RegisterProperti extends Component
{
    public Property $property;
    public $isBooked = false;
    public int $quantity = 1;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $notes = null;

    public function mount($id, $slug = null)
    {
        $this->property = Property::with('propertyType')->findOrFail($id);

        if (!Auth::guard('participant')->check()) {
            session()->put('url.intended', request()->url());
            return redirect()->route('participant.login');
        }

        $this->isBooked = Booking::where('customer_id', Auth::guard('participant')->id())
            ->where('bookable_type', Property::class)
            ->where('bookable_id', $this->property->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function submit()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);

        Booking::create([
            'customer_id' => Auth::guard('participant')->id(),
            'bookable_type' => Property::class,
            'bookable_id' => $this->property->id,
            'booking_type' => $this->quantity > 1 ? 'batch' : 'individual',
            'quantity' => $this->quantity,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Pemesanan properti berhasil dikirim! Silakan tunggu konfirmasi dari admin.');
        return redirect()->route('participant.dashboard');
    }

    public function render()
    {
        return view('livewire.pnbp.register-properti')->title('Pesan ' . $this->property->name);
    }
}
