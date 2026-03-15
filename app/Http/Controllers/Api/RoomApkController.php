<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomApkController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $propertyId = $request->query('property_id');
        
        $query = Room::query();
        
        if ($propertyId) {
            $query->where('property_id', $propertyId);
        }

        return $this->success($query->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'room_number' => 'required|string|max:50',
            'floor' => 'nullable|string|max:50',
            'status' => 'required|in:available,use,maintenance,cleaned',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $room = Room::create($validator->validated());

        return $this->success($room, 'Room created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return $this->success($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'sometimes|required|string|max:50',
            'floor' => 'nullable|string|max:50',
            'status' => 'sometimes|required|in:available,use,maintenance,cleaned',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $room->update($validator->validated());

        return $this->success($room, 'Room updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return $this->success(null, 'Room deleted successfully');
    }
}
