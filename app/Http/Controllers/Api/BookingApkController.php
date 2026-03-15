<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingApkController extends Controller
{
    use ApiResponses;

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Booking::with(['bookable', 'assignedRoom', 'customer']);
        
        // If the user is a Customer, they only see their own bookings.
        // If the user is an Employee (Admin), they can see all.
        if ($user instanceof \App\Models\Customer) {
            $query->where('customer_id', $user->id);
        }

        $bookings = $query->latest()->get();

        // Normalize relationship names for the Flutter app
        $normalized = $bookings->map(function ($booking) {
            $booking->property = $booking->bookable;
            $booking->assigned_room = $booking->assignedRoom;
            $booking->customer_data = $booking->customer;
            return $booking;
        });

        return $this->success($normalized, 'Bookings retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id'   => 'required|exists:properties,id',
            'contact_name'  => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'institution'   => 'nullable|string|max:255',
            'total_price'   => 'nullable|numeric|min:0',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'status'        => 'nullable|string|in:scheduled,in_use,finished,cancelled'
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $user = $request->user();
            $property = Property::find($validated['property_id']);
            
            // Find or create customer by email
            $customer = Customer::firstOrCreate(
                ['email' => $validated['contact_email']],
                [
                    'name'  => $validated['contact_name'],
                    'phone' => $validated['contact_phone'],
                
                
                ]
            );

            // Calculate total price based on duration if not provided
            $totalPrice = $validated['total_price'] ?? null;
            if ($totalPrice === null) {
                $start = new \DateTime($validated['start_date']);
                $end = new \DateTime($validated['end_date']);
                $hours = max(1, ceil(($end->getTimestamp() - $start->getTimestamp()) / 3600));
                $totalPrice = ($property->price ?? 0) * $hours;
            }

            $booking = Booking::create([
                'id_booking'    => 'BKG-PRP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
                'customer_id'   => $customer->id,
                'user_id'       => $user ? $user->id : null,
                'bookable_id'   => $validated['property_id'],
                'bookable_type' => Property::class,
                'contact_name'  => $validated['contact_name'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'institution'   => $validated['institution'],
                'total_price'   => $totalPrice,
                'booking_type'  => 'individual',
                'quantity'      => 1,
                'start_date'    => $validated['start_date'],
                'end_date'      => $validated['end_date'],
                'status'        => $validated['status'] ?? 'scheduled',
            ]);

            $booking->load(['bookable', 'assignedRoom', 'customer']);
            $booking->property = $booking->bookable;
            $booking->assigned_room = $booking->assignedRoom;
            $booking->customer_data = $booking->customer;

            return $this->success($booking, 'Booking created successfully', 201);
        });
    }

    public function show($id)
    {
        $booking = Booking::with(['bookable', 'customer', 'assignedRoom'])->find($id);

        if (!$booking) {
            return $this->error('Booking not found', 404);
        }

        $booking->property = $booking->bookable;
        $booking->assigned_room = $booking->assignedRoom;
        $booking->customer_data = $booking->customer;

        return $this->success($booking, 'Booking retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->error('Booking not found', 404);
        }

        $validated = $request->validate([
            'status'           => 'sometimes|required|string|in:scheduled,in_use,finished,cancelled',
            'assigned_room_id' => 'sometimes|nullable|exists:rooms,id',
            'contact_name'     => 'sometimes|required|string|max:255',
            'contact_email'    => 'sometimes|required|email|max:255',
            'contact_phone'    => 'sometimes|required|string|max:20',
            'institution'      => 'sometimes|nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($booking, $validated) {
            // Logic for status transitions
            if (isset($validated['status']) && $validated['status'] !== $booking->status) {
                // If moving to in_use, ensure a room is assigned
                if ($validated['status'] === 'in_use') {
                    if (!isset($validated['assigned_room_id']) && !$booking->assigned_room_id) {
                        return $this->error('Room assignment is required to check-in/start use.', 422);
                    }
                    
                    $roomId = $validated['assigned_room_id'] ?? $booking->assigned_room_id;
                    $room = \App\Models\Room::find($roomId);
                    if ($room) {
                        $room->update(['status' => 'use']);
                    }
                }

                // If moving to finished, free up the room
                if ($validated['status'] === 'finished' || $validated['status'] === 'cancelled') {
                    if ($booking->assigned_room_id) {
                        $room = \App\Models\Room::find($booking->assigned_room_id);
                        if ($room) {
                            $room->update(['status' => ($validated['status'] === 'finished' ? 'cleaned' : 'available')]);
                        }
                    }
                }
            }

            $booking->update($validated);

            $booking->load(['bookable', 'assignedRoom', 'customer']);
            $booking->property = $booking->bookable;
            $booking->assigned_room = $booking->assignedRoom;
            $booking->customer_data = $booking->customer;

            return $this->success($booking, 'Booking updated successfully');
        });
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->error('Booking not found', 404);
        }

        $booking->delete();

        return $this->success(null, 'Booking deleted successfully');
    }
}
