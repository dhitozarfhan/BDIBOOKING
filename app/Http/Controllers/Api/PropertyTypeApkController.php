<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeApkController extends Controller
{
    use ApiResponses;

    public function index()
    {
        $types = PropertyType::all();
        return $this->success($types, 'Property types retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $propertyType = PropertyType::create($validated);

        return $this->success($propertyType, 'Property type created successfully', 201);
    }

    public function show($id)
    {
        $propertyType = PropertyType::find($id);

        if (!$propertyType) {
            return $this->error('Property type not found', 404);
        }

        return $this->success($propertyType, 'Property type retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $propertyType = PropertyType::find($id);

        if (!$propertyType) {
            return $this->error('Property type not found', 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        $propertyType->update($validated);

        return $this->success($propertyType, 'Property type updated successfully');
    }

    public function destroy($id)
    {
        $propertyType = PropertyType::find($id);

        if (!$propertyType) {
            return $this->error('Property type not found', 404);
        }

        $propertyType->delete();

        return $this->success(null, 'Property type deleted successfully');
    }
}
