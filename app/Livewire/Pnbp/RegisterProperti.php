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
    public int $max_quantity = 1;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $notes = null;
    public int $total_days = 0;
    public float $total_price = 0;

    public function mount($id, $slug = null)
    {
        $this->property = Property::findOrFail($id);

        if (!Auth::guard('participant')->check()) {
            session()->put('url.intended', request()->url());
            return redirect()->route('participant.login');
        }

        $this->isBooked = Booking::where('customer_id', Auth::guard('participant')->id())
            ->where('bookable_type', Property::class)
            ->where('bookable_id', $this->property->id)
            ->where('status', 'pending')
            ->exists();

        $this->max_quantity = $this->property->total_rooms_count ?: 1;
        $this->calculateTotalPrice();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['start_date', 'end_date', 'quantity'])) {
            $this->calculateTotalPrice();
        }
    }

    public function calculateTotalPrice()
    {
        if ($this->start_date && $this->end_date) {
            $start = \Carbon\Carbon::parse($this->start_date);
            $end = \Carbon\Carbon::parse($this->end_date);
            
            // Renting for 1 day (e.g., today to today) should count as 1 day of rental
            $this->total_days = max(1, $start->diffInDays($end) + 1);
        } else {
            $this->total_days = 0;
        }

        $this->total_price = $this->property->price * $this->total_days * $this->quantity;
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
            'total_price' => $this->total_price,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'contact_name' => Auth::guard('participant')->user()->name,
            'contact_email' => Auth::guard('participant')->user()->email,
            'contact_phone' => Auth::guard('participant')->user()->phone,
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
