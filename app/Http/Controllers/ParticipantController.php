<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    public function store(Request $request, Seminar $seminar)
{
    // Validasi input
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'no_wa' => 'required|string|max:20',
        'email' => 'required|email|max:100',
    ]);

    Participant::create([
        'seminar_id' => $seminar->seminar_id,
        'name' => $validated['name'],
        'no_wa' => $validated['no_wa'],
        'email' => $validated['email'],
    ]);

    $now = Carbon::now();
    $isWorkDay = $now->isWeekday(); // Senin–Jumat
    $isWorkHour = $now->hour >= 8 && $now->hour < 16;

    if ($isWorkDay && $isWorkHour) {
        $message = 'Terima Kasih! Kode billing akan segera dikirimkan melalui WhatsApp.';
    } else {
        $message = 'Terima Kasih! Kode billing akan dikirimkan pada jam kerja.';
    }

    return redirect()->route('booking.detail', [$seminar->seminar_id, Str::slug($seminar->title)])
        ->with('success', $message);
}
}
