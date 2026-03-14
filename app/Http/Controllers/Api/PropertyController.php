<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Http\Resources\PropertyResource;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('rooms')->get();
        return PropertyResource::collection($properties);
    }

    public function show($id)
    {
        $property = Property::with('rooms')->findOrFail($id);
        return new PropertyResource($property);
    }
}
