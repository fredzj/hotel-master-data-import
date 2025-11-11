<?php

namespace App\Http\Controllers;

use App\Models\ApaleoUnit;
use App\Models\ApaleoProperty;
use App\Models\ApaleoUnitGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApaleoUnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $units = ApaleoUnit::with(['property', 'unitGroup', 'attributes'])
            ->orderBy('property_id')
            ->orderBy('unit_group_id')
            ->orderBy('name')
            ->paginate(10);

        return view('apaleo.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $properties = ApaleoProperty::orderBy('name')->get();
        $unitGroups = ApaleoUnitGroup::with('property')->orderBy('property_id')->orderBy('name')->get();
        
        return view('apaleo.units.create', compact('properties', 'unitGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'apaleo_id' => 'required|string|max:255|unique:apaleo_units',
            'property_id' => 'required|string|exists:apaleo_properties,apaleo_id',
            'unit_group_id' => 'nullable|string|exists:apaleo_unit_groups,apaleo_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'condition' => 'nullable|string|max:100',
            'max_persons' => 'nullable|integer|min:0',
            'size' => 'nullable|numeric|min:0',
            'view' => 'nullable|string|max:255',
        ]);

        // Validate that unit_group belongs to the same property if provided
        if ($validated['unit_group_id']) {
            $unitGroup = ApaleoUnitGroup::where('apaleo_id', $validated['unit_group_id'])->first();
            if ($unitGroup && $unitGroup->property_id !== $validated['property_id']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['unit_group_id' => 'The selected unit group must belong to the same property.']);
            }
        }

        $unit = ApaleoUnit::create($validated);

        return redirect()->route('apaleo-units.show', $unit)
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApaleoUnit $apaleoUnit): View
    {
        $apaleoUnit->load(['property', 'unitGroup', 'attributes']);
        
        return view('apaleo.units.show', compact('apaleoUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApaleoUnit $apaleoUnit): View
    {
        $properties = ApaleoProperty::orderBy('name')->get();
        $unitGroups = ApaleoUnitGroup::with('property')->orderBy('property_id')->orderBy('name')->get();
        
        return view('apaleo.units.edit', compact('apaleoUnit', 'properties', 'unitGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApaleoUnit $apaleoUnit): RedirectResponse
    {
        $validated = $request->validate([
            'apaleo_id' => 'required|string|max:255|unique:apaleo_units,apaleo_id,' . $apaleoUnit->id,
            'property_id' => 'required|string|exists:apaleo_properties,apaleo_id',
            'unit_group_id' => 'nullable|string|exists:apaleo_unit_groups,apaleo_id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'condition' => 'nullable|string|max:100',
            'max_persons' => 'nullable|integer|min:0',
            'size' => 'nullable|numeric|min:0',
            'view' => 'nullable|string|max:255',
        ]);

        // Validate that unit_group belongs to the same property if provided
        if ($validated['unit_group_id']) {
            $unitGroup = ApaleoUnitGroup::where('apaleo_id', $validated['unit_group_id'])->first();
            if ($unitGroup && $unitGroup->property_id !== $validated['property_id']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['unit_group_id' => 'The selected unit group must belong to the same property.']);
            }
        }

        $apaleoUnit->update($validated);

        return redirect()->route('apaleo-units.show', $apaleoUnit)
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApaleoUnit $apaleoUnit): RedirectResponse
    {
        // Check if unit has related attributes
        $attributesCount = $apaleoUnit->attributes()->count();

        if ($attributesCount > 0) {
            return redirect()->route('apaleo-units.index')
                ->with('error', 'Cannot delete unit. It has ' . $attributesCount . ' attributes associated with it.');
        }

        $apaleoUnit->delete();

        return redirect()->route('apaleo-units.index')
            ->with('success', 'Unit deleted successfully.');
    }

    /**
     * Get unit groups by property ID (for AJAX requests)
     */
    public function getUnitGroupsByProperty(Request $request)
    {
        $propertyId = $request->get('property_id');
        $unitGroups = ApaleoUnitGroup::where('property_id', $propertyId)
            ->orderBy('name')
            ->get(['id', 'apaleo_id', 'name']);

        return response()->json($unitGroups);
    }
}