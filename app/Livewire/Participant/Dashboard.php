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
    public $payment_proofs = [];

    public function mount()
    {
        $this->refreshBookings();
    }

    public function refreshBookings()
    {
        $this->bookings = Auth::guard('participant')->user()
            ->bookings()
            ->with(['bookable', 'certificate', 'invoices' => function ($query) {
                $query->latest();
            }])
            ->latest()
            ->get();
    }

    public function uploadProof($invoiceId)
    {
        $this->validate([
            "payment_proofs.{$invoiceId}" => 'mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $invoice = Invoice::findOrFail($invoiceId);

        if ($invoice->booking->customer_id !== Auth::guard('participant')->id()) {
            abort(403);
        }

        if (isset($this->payment_proofs[$invoiceId])) {
            $path = $this->payment_proofs[$invoiceId]->store('invoices/payments', 'public');
            
            $invoice->update([
                'payment_proof' => $path,
            ]);

            $this->payment_proofs[$invoiceId] = null;
            session()->flash('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
            $this->refreshBookings();
        }
    }

    public function downloadInvoice($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        
        if ($invoice->booking->customer_id !== Auth::guard('participant')->id()) {
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
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.participant.dashboard')
            ->layout('layouts.app', ['title' => 'Dashboard Peserta']);
    }
}
