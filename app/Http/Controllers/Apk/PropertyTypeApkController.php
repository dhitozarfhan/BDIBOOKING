<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PropertyTypeAPK;
use Illuminate\Http\Request;

class PropertyTypeApkController extends Controller
{
    public function index()
    {
        return response()->json(PropertyTypeAPK::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $propertyType = PropertyTypeAPK::create($validated);

        return response()->json($propertyType, 201);
    }

    public function show($id)
    {
        $propertyType = PropertyTypeAPK::find($id);

        if (!$propertyType) {
            return response()->json(['message' => 'Property type not found'], 404);
        }

        return response()->json($propertyType);
    }

    public function update(Request $request, $id)
    {
        $propertyType = PropertyTypeAPK::find($id);

        if (!$propertyType) {
            return response()->json(['message' => 'Property type not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        $propertyType->update($validated);

        return response()->json($propertyType);
    }

    public function destroy($id)
    {
        $propertyType = PropertyTypeAPK::find($id);

        if (!$propertyType) {
            return response()->json(['message' => 'Property type not found'], 404);
        }

        $propertyType->delete();

        return response()->json(['message' => 'Property type deleted successfully']);
    }
}
