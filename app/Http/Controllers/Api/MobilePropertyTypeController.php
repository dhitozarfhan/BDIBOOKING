<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class MobilePropertyTypeController extends Controller
{
    /**
     * Display a listing of property types.
     */
    public function index()
    {
        $propertyTypes = PropertyType::all()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'description' => $type->description,
            ];
        });

        // Flutter app expects `data` wrapper for lists
        return response()->json(['data' => $propertyTypes]);
    }

    /**
     * Display the specified property type.
     */
    public function show($id)
    {
        $type = PropertyType::find($id);

        if (!$type) {
            return response()->json(['message' => 'Property type not found'], 404);
        }

        return response()->json([
            'id' => $type->id,
            'name' => $type->name,
            'description' => $type->description,
        ]);
    }
}
