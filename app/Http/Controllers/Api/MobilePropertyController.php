<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class MobilePropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request)
    {
        $query = Property::with(['propertyType']); // ensure relation matches the model method

        // Optional filtering by property_type_id
        if ($request->has('property_type_id')) {
            $query->where('property_type_id', $request->property_type_id);
        }

        $properties = $query->get()->map(function ($property) {
            return [
                'id' => $property->id,
                'property_type_id' => $property->property_type_id,
                'name' => $property->name,
                'description' => $property->description,
                'capacity' => $property->capacity ?? 1,
                'status' => $property->is_active ? 'available' : 'unavailable',
                'type' => $property->propertyType ? [
                    'id' => $property->propertyType->id,
                    'name' => $property->propertyType->name,
                    'description' => $property->propertyType->description,
                ] : null,
            ];
        });

        // Flutter app expects `data` wrapper for lists, or a direct list depending on API Service.
        // According to the Architecture docs: json.decode(response.body)['data']
        return response()->json(['data' => $properties]);
    }

    /**
     * Display the specified property.
     */
    public function show($id)
    {
        $property = Property::with(['propertyType'])->find($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        return response()->json([
            'id' => $property->id,
            'property_type_id' => $property->property_type_id,
            'name' => $property->name,
            'description' => $property->description,
            'capacity' => $property->capacity ?? 1,
            'status' => $property->is_active ? 'available' : 'unavailable',
            'type' => $property->propertyType ? [
                'id' => $property->propertyType->id,
                'name' => $property->propertyType->name,
                'description' => $property->propertyType->description,
            ] : null,
        ]);
    }
}
