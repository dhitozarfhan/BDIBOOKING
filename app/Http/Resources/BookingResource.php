<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_booking' => $this->id_booking,
            'customer_id' => $this->customer_id,
            'contact_name' => $this->customer?->name,
            'contact_email' => $this->customer?->email,
            'contact_phone' => $this->customer?->phone,
            'institution' => $this->customer?->party?->company_name,
            'booking_type' => $this->booking_type,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'property' => new PropertyResource($this->whenLoaded('bookable')),
            'room' => new RoomResource($this->whenLoaded('assignedRoom')),
        ];
    }
}
