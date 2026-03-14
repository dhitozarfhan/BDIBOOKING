<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Detect role based on instance type or role field
        $role = $this->role;
        if (!$role) {
            if ($this->resource instanceof \App\Models\Customer) {
                $role = 'customer';
            } elseif ($this->resource instanceof \App\Models\Employee) {
                $role = 'employee';
            } else {
                $role = 'user';
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $role,
            'phone' => $this->phone,
            'username' => $this->username ?? $this->nip ?? null,
            'avatar_url' => $this->profile_photo_url ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
