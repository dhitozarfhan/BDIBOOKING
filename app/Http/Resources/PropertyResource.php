<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'price' => (float) $this->price,
            'status' => $this->status,
            'image' => $this->image,
            'rooms' => RoomResource::collection($this->whenLoaded('rooms')),
        ];
    }
}
