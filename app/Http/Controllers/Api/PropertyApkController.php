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
        $properties = Property::all();
        return $this->success($properties, 'Properties retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        $property = Property::create($validated);

        return $this->success($property, 'Property created successfully', 201);
    }

    public function show($id)
    {
        $property = Property::find($id);

        if (!$property) {
            return $this->error('Property not found', 404);
        }

        return $this->success($property, 'Property retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $property = Property::find($id);

        if (!$property) {
            return $this->error('Property not found', 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:available,occupied,maintenance'
        ]);

        $property->update($validated);

        return $this->success($property, 'Property updated successfully');
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
