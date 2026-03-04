<?php

namespace App\Livewire\Pnbp;

use App\Models\Booking;
use App\Models\ParticipantData;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Register extends Component
{
    use WithFileUploads;

    public Training $training;
    public $isRegistered = false;
    public int $quantity = 1;
    
    // To store dynamic input values keyed by required_field_id
    public array $formData = [];

    public function mount($id_diklat, $slug)
    {
        $this->training = Training::with('requiredFields')->findOrFail($id_diklat);

        if (!Auth::guard('participant')->check()) {
            session()->put('url.intended', request()->url());
            return redirect()->route('participant.login');
        }

        $this->isRegistered = Booking::where('customer_id', Auth::guard('participant')->id())
            ->where('bookable_type', Training::class)
            ->where('bookable_id', $this->training->id)
            ->exists();
            
        if ($this->isRegistered) {
            session()->flash('error', 'Anda sudah terdaftar di diklat ini.');
            return redirect()->route('pnbp.detail', ['id_diklat' => $this->training->id, 'slug' => Str::slug($this->training->title)]);
        }

        // Initialize form data
        foreach ($this->training->requiredFields as $field) {
            $this->formData[$field->id] = null;
        }
    }

    public function submit()
    {
        // 1. Validation
        $rules = [];
        $messages = [];
        
        foreach ($this->training->requiredFields as $field) {
            if ($field->is_file) {
                // Adjust max file size based on requirements (e.g. 5MB)
                $rules['formData.' . $field->id] = 'required|file|max:5120';
                $messages['formData.' . $field->id . '.required'] = "File {$field->name} wajib diupload.";
                $messages['formData.' . $field->id . '.file'] = "Format {$field->name} tidak valid.";
                $messages['formData.' . $field->id . '.max'] = "Ukuran file {$field->name} maksimal 5MB.";
            } else {
                $rules['formData.' . $field->id] = 'required|string';
                $messages['formData.' . $field->id . '.required'] = "Field {$field->name} wajib diisi.";
            }
        }

        // Validate quantity
        $maxQuota = $this->training->quota > 0 ? $this->training->quota : 100;
        $rules['quantity'] = "required|integer|min:1|max:{$maxQuota}";
        $messages['quantity.required'] = 'Jumlah wajib diisi.';
        $messages['quantity.min'] = 'Jumlah minimal 1.';
        $messages['quantity.max'] = "Jumlah maksimal {$maxQuota}.";

        $this->validate($rules, $messages);

        // 2. Create Booking
        $booking = Booking::create([
            'customer_id' => Auth::guard('participant')->id(),
            'bookable_type' => Training::class,
            'bookable_id' => $this->training->id,
            'booking_type' => $this->quantity > 1 ? 'batch' : 'individual',
            'quantity' => $this->quantity,
            'status' => 'pending',
        ]);

        // 3. Save Participant Data
        foreach ($this->training->requiredFields as $field) {
            $value = $this->formData[$field->id];
            
            if ($field->is_file && $value instanceof \Illuminate\Http\UploadedFile) {
                // Store file in storage/app/public/participant_data
                $path = $value->store('participant_data', 'public');
                $value = $path;
            }

            ParticipantData::create([
                'customer_id' => Auth::guard('participant')->id(),
                'booking_id' => $booking->id,
                'required_field_id' => $field->id,
                'value' => $value,
            ]);
        }

        // 4. Success and Redirect
        session()->flash('success', 'Pendaftaran berhasil dikirim! File persyaratan berhasil diunggah.');
        return redirect()->route('participant.dashboard');
    }

    public function render()
    {
        return view('livewire.pnbp.register')->title('Daftar ' . $this->training->title);
    }
}
