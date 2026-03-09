<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propertyTypes = PropertyType::all();
        return view('property_types.index', compact('propertyTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('property_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        PropertyType::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('property-types.index')
                         ->with('success', 'Property Type created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $propertyType = PropertyType::findOrFail($id);
        return view('property_types.show', compact('propertyType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $propertyType = PropertyType::findOrFail($id);
        return view('property_types.edit', compact('propertyType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $propertyType = PropertyType::findOrFail($id);

        $propertyType->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('property-types.index')
                         ->with('success', 'Property Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $propertyType = PropertyType::findOrFail($id);
        $propertyType->delete();

        return redirect()->route('property-types.index')
                         ->with('success', 'Property Type deleted successfully');
    }
}