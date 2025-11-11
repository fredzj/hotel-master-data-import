<?php

namespace App\Http\Controllers;

use App\Models\ApaleoUnitGroup;
use App\Models\ApaleoProperty;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApaleoUnitGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $unitGroups = ApaleoUnitGroup::with(['property', 'units'])
            ->orderBy('property_id')
            ->orderBy('name')
            ->paginate(10);

        return view('apaleo.unit-groups.index', compact('unitGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $properties = ApaleoProperty::orderBy('name')->get();
        
        return view('apaleo.unit-groups.create', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'apaleo_id' => 'required|string|max:255|unique:apaleo_unit_groups',
            'property_id' => 'required|string|exists:apaleo_properties,apaleo_id',
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'max_persons' => 'nullable|integer|min:0',
            'member_count' => 'nullable|integer|min:0',
        ]);

        $unitGroup = ApaleoUnitGroup::create($validated);

        return redirect()->route('apaleo-unit-groups.show', $unitGroup)
            ->with('success', 'Unit Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApaleoUnitGroup $apaleoUnitGroup): View
    {
        $apaleoUnitGroup->load(['property', 'units']);
        
        return view('apaleo.unit-groups.show', compact('apaleoUnitGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApaleoUnitGroup $apaleoUnitGroup): View
    {
        $properties = ApaleoProperty::orderBy('name')->get();
        
        return view('apaleo.unit-groups.edit', compact('apaleoUnitGroup', 'properties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApaleoUnitGroup $apaleoUnitGroup): RedirectResponse
    {
        $validated = $request->validate([
            'apaleo_id' => 'required|string|max:255|unique:apaleo_unit_groups,apaleo_id,' . $apaleoUnitGroup->id,
            'property_id' => 'required|string|exists:apaleo_properties,apaleo_id',
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'max_persons' => 'nullable|integer|min:0',
            'member_count' => 'nullable|integer|min:0',
        ]);

        $apaleoUnitGroup->update($validated);

        return redirect()->route('apaleo-unit-groups.show', $apaleoUnitGroup)
            ->with('success', 'Unit Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApaleoUnitGroup $apaleoUnitGroup): RedirectResponse
    {
        // Check if unit group has related units
        $unitsCount = $apaleoUnitGroup->units()->count();

        if ($unitsCount > 0) {
            return redirect()->route('apaleo-unit-groups.index')
                ->with('error', 'Cannot delete unit group. It has ' . $unitsCount . ' units associated with it.');
        }

        $apaleoUnitGroup->delete();

        return redirect()->route('apaleo-unit-groups.index')
            ->with('success', 'Unit Group deleted successfully.');
    }
}