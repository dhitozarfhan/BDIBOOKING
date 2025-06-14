<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seminar;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $seminars = Seminar::latest()->get();
        return view('booking.index', compact('seminars'));
    }

    public function show($id, $title)
    {
        $seminar = Seminar::findOrFail($id);
        return view('booking.post', compact('seminar'));
    }

    public function detail($id, $title)
    {
        $seminar = Seminar::findOrFail($id);
        return view('booking.detail', compact('seminar'));
    }
}
