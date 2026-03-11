<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobileBookingController extends Controller
{
    /**
     * Display a listing of bookings for the app (Admin Dashboard).
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['bookable', 'customer'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'property_id' => $booking->bookable_id,
                    'user_id' => $booking->customer_id,
                    'contact_name' => $booking->customer ? $booking->customer->name : 'Unknown User',
                    'contact_email' => $booking->customer ? $booking->customer->email : '',
                    'contact_phone' => $booking->customer ? $booking->customer->phone ?? '' : '',
                    'institution' => null, // Provide default or read from customer profile
                    'start_date' => $booking->start_date ? $booking->start_date->toISOString() : null,
                    'end_date' => $booking->end_date ? $booking->end_date->toISOString() : null,
                    'status' => $booking->status ?? 'pending',
                    'property' => $booking->bookable ? [
                        'id' => $booking->bookable->id,
                        'property_type_id' => $booking->bookable->property_type_id,
                        'name' => $booking->bookable->name,
                        'capacity' => $booking->bookable->capacity ?? 1,
                        'status' => $booking->bookable->is_active ? 'available' : 'unavailable',
                    ] : null,
                ];
            });

        return response()->json(['data' => $bookings]);
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();

        $booking = new Booking();
        $booking->customer_id = $customer->id;
        $booking->bookable_id = $request->property_id;
        $booking->bookable_type = 'App\Models\Property'; // Assuming Flutter books Properties
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->status = 'pending';
        // Add additional tracking fields if your db requires them
        if (\Illuminate\Support\Facades\Schema::hasColumn('bookings', 'booking_code')) {
             $booking->booking_code = 'BKG-' . time();
        }
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }

    /**
     * Display the specified booking.
     */
    public function show($id, Request $request)
    {
        $employee = $request->user();
        $booking = Booking::with(['bookable', 'customer'])->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json([
            'id' => $booking->id,
            'property_id' => $booking->bookable_id,
            'user_id' => $booking->customer_id,
            'contact_name' => $booking->customer ? $booking->customer->name : 'Unknown User',
            'contact_email' => $booking->customer ? $booking->customer->email : '',
            'contact_phone' => $booking->customer ? $booking->customer->phone ?? '' : '',
            'institution' => null,
            'start_date' => $booking->start_date ? $booking->start_date->toISOString() : null,
            'end_date' => $booking->end_date ? $booking->end_date->toISOString() : null,
            'status' => $booking->status ?? 'pending',
            'property' => $booking->bookable ? [
                'id' => $booking->bookable->id,
                'property_type_id' => $booking->bookable->property_type_id,
                'name' => $booking->bookable->name,
                'capacity' => $booking->bookable->capacity ?? 1,
                'status' => $booking->bookable->is_active ? 'available' : 'unavailable',
            ] : null,
        ]);
    }
}
