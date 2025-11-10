<?php

namespace App\Http\Controllers;

use App\Models\ApaleoUnitAttribute;
use App\Models\ApaleoUnit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApaleoUnitAttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $attributes = ApaleoUnitAttribute::with(['unit.property', 'unit.unitGroup'])
            ->orderBy('unit_id')
            ->orderBy('name')
            ->paginate(15);

        return view('apaleo.unit-attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $units = ApaleoUnit::with(['property', 'unitGroup'])
            ->orderBy('property_id')
            ->orderBy('name')
            ->get();
        
        return view('apaleo.unit-attributes.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unit_id' => 'required|string|exists:apaleo_units,apaleo_id',
            'name' => 'required|string|max:255',
            'value' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'unit_of_measure' => 'nullable|string|max:50',
        ]);

        $attribute = ApaleoUnitAttribute::create($validated);

        return redirect()->route('apaleo-unit-attributes.show', $attribute)
            ->with('success', 'Unit Attribute created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApaleoUnitAttribute $apaleoUnitAttribute): View
    {
        $apaleoUnitAttribute->load(['unit.property', 'unit.unitGroup']);
        
        return view('apaleo.unit-attributes.show', compact('apaleoUnitAttribute'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApaleoUnitAttribute $apaleoUnitAttribute): View
    {
        $units = ApaleoUnit::with(['property', 'unitGroup'])
            ->orderBy('property_id')
            ->orderBy('name')
            ->get();
        
        return view('apaleo.unit-attributes.edit', compact('apaleoUnitAttribute', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApaleoUnitAttribute $apaleoUnitAttribute): RedirectResponse
    {
        $validated = $request->validate([
            'unit_id' => 'required|string|exists:apaleo_units,apaleo_id',
            'name' => 'required|string|max:255',
            'value' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'unit_of_measure' => 'nullable|string|max:50',
        ]);

        $apaleoUnitAttribute->update($validated);

        return redirect()->route('apaleo-unit-attributes.show', $apaleoUnitAttribute)
            ->with('success', 'Unit Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApaleoUnitAttribute $apaleoUnitAttribute): RedirectResponse
    {
        $apaleoUnitAttribute->delete();

        return redirect()->route('apaleo-unit-attributes.index')
            ->with('success', 'Unit Attribute deleted successfully.');
    }
}