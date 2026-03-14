<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['bookable', 'assignedRoom', 'customer.party'])->get();
        return BookingResource::collection($bookings);
    }

    public function show($id)
    {
        $booking = Booking::with(['bookable', 'assignedRoom', 'customer.party'])->findOrFail($id);
        return new BookingResource($booking);
    }
}
