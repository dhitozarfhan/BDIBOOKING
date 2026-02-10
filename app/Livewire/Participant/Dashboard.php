<?php

namespace App\Livewire\Participant;

use App\Models\Invoice;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Dashboard extends Component
{
    use WithFileUploads;

    public $bookings;
    public $payment_proofs = []; // Array to hold temporary file uploads keyed by invoice ID

    public function mount()
    {
        $this->refreshBookings();
    }

    public function refreshBookings()
    {
        $this->bookings = Auth::guard('participant')->user()
            ->bookings()
            ->with(['bookable', 'invoices' => function ($query) {
                $query->latest();
            }])
            ->latest()
            ->get();
    }

    public function uploadProof($invoiceId)
    {
        $this->validate([
            "payment_proofs.{$invoiceId}" => 'mimes:jpg,jpeg,png,pdf|max:2048', // 2MB Max
        ]);

        $invoice = Invoice::findOrFail($invoiceId);

        // Ensure the invoice belongs to a booking of the current user
        if ($invoice->booking->participant_id !== Auth::guard('participant')->id()) {
            abort(403);
        }

        if (isset($this->payment_proofs[$invoiceId])) {
            $path = $this->payment_proofs[$invoiceId]->store('invoices/payments', 'public');
            
            $invoice->update([
                'payment_proof' => $path,
                // We keep status as 'unpaid' until admin verifies, or maybe we can add a 'verification_pending' status if we want.
                // For now, based on plan, admin verifies 'unpaid' invoices that have proof.
            ]);

            $this->payment_proofs[$invoiceId] = null; // Reset
            session()->flash('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
            $this->refreshBookings();
        }
    }

    public function downloadInvoice($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        
        if ($invoice->booking->participant_id !== Auth::guard('participant')->id()) {
            abort(403);
        }

        if ($invoice->invoice_file && Storage::disk('public')->exists($invoice->invoice_file)) {
            return Storage::disk('public')->download($invoice->invoice_file);
        }

        session()->flash('error', 'File invoice tidak ditemukan.');
    }

    public function logout()
    {
        Auth::guard('participant')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('home'); // Or participant.login
    }

    public function render()
    {
        return view('livewire.participant.dashboard')
            ->layout('layouts.app', ['title' => 'Dashboard Peserta']);
    }
}
