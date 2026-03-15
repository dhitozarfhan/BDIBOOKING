<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyApkController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $properties = Property::with('propertyType')->get()->map(function ($property) {
            $property->total_rooms = $property->rooms()->count();
            $property->available_rooms = $property->rooms()->where('status', 'available')->count();
            return $property;
        });
        return $this->success($properties, 'Properties retrieved successfully');
    }

    public function getTypes()
    {
        $types = \App\Models\PropertyType::all();
        return $this->success($types, 'Property types retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_type_id' => 'required|exists:property_types,id',
            'category' => 'required|string|in:kamar_inap,ruang_rapat,kelas,lainnya',
            'name' => 'required|string|max:255|unique:properties,name',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string|in:active,inactive',
            'image' => 'nullable|array',
        ]);

        $property = Property::create($validated);

        return $this->success($property->load('propertyType'), 'Property created successfully', 201);
    }

    public function show($id)
    {
        $property = Property::with(['propertyType', 'rooms'])->find($id);

        if (!$property) {
            return $this->error('Property not found', 404);
        }

        $property->total_rooms = $property->rooms()->count();
        $property->available_rooms = $property->rooms()->where('status', 'available')->count();

        return $this->success($property, 'Property retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $property = Property::find($id);

        if (!$property) {
            return $this->error('Property not found', 404);
        }

        $validated = $request->validate([
            'property_type_id' => 'sometimes|required|exists:property_types,id',
            'category' => 'sometimes|required|string|in:kamar_inap,ruang_rapat,kelas,lainnya',
            'name' => 'sometimes|required|string|max:255|unique:properties,name,' . $id,
            'description' => 'nullable|string',
            'capacity' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|string|in:active,inactive',
            'image' => 'nullable|array',
        ]);

        $property->update($validated);

        return $this->success($property->load('propertyType'), 'Property updated successfully');
    }

    public function destroy($id)
    {
        $property = Property::find($id);

        if (!$property) {
            return $this->error('Property not found', 404);
        }

        $property->delete();

        return $this->success(null, 'Property deleted successfully');
    }
}
