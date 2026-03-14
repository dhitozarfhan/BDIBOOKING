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
        $query = Booking::with('bookable');
        
        // If the user is a Customer, they only see their own bookings.
        // If the user is an Employee (Admin), they can see all.
        if ($user instanceof \App\Models\Customer) {
            $query->where('customer_id', $user->id);
        }

        $bookings = $query->latest()->get();
        return $this->success($bookings, 'Bookings retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id'   => 'required|exists:properties,id',
            'contact_name'  => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'status'        => 'nullable|string|in:pending,approved,rejected,completed'
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $user = $request->user();
            
            // Find or create customer by email
            $customer = Customer::firstOrCreate(
                ['email' => $validated['contact_email']],
                [
                    'name'  => $validated['contact_name'],
                    'phone' => $validated['contact_phone']
                ]
            );

            // If the authenticated user is an employee, we can optionally track it 
            // but the primary link remains customer_id for mobile bookings.
            $booking = Booking::create([
                'customer_id'   => ($user instanceof \App\Models\Customer) ? $user->id : $customer->id,
                'bookable_id'   => $validated['property_id'],
                'bookable_type' => Property::class,
                'booking_type'  => 'individual',
                'quantity'      => 1,
                'start_date'    => $validated['start_date'],
                'end_date'      => $validated['end_date'],
                'status'        => $validated['status'] ?? 'pending',
            ]);

            return $this->success($booking->load('bookable'), 'Booking created successfully', 201);
        });
    }

    public function show($id)
    {
        $booking = Booking::with('bookable', 'customer')->find($id);

        if (!$booking) {
            return $this->error('Booking not found', 404);
        }

        return $this->success($booking, 'Booking retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->error('Booking not found', 404);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected,completed'
        ]);

        $booking->update($validated);

        return $this->success($booking, 'Booking updated successfully');
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
