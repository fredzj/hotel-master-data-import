<?php

namespace App\Http\Controllers;

use App\Models\ApaleoProperty;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApaleoPropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $properties = ApaleoProperty::with(['unitGroups', 'units'])
            ->orderBy('name')
            ->paginate(10);

        return view('apaleo.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('apaleo.properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'apaleo_id' => 'required|string|max:255|unique:apaleo_properties',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'country_code' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'commercial_register_entry' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:34',
            'bic' => 'nullable|string|max:11',
            'bank_name' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:50',
            'currency_code' => 'nullable|string|max:3',
        ]);

        $property = ApaleoProperty::create($validated);

        return redirect()->route('apaleo-properties.show', $property)
            ->with('success', 'Property created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ApaleoProperty $apaleoProperty): View
    {
        $apaleoProperty->load(['unitGroups', 'units']);
        
        return view('apaleo.properties.show', compact('apaleoProperty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApaleoProperty $apaleoProperty): View
    {
        return view('apaleo.properties.edit', compact('apaleoProperty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApaleoProperty $apaleoProperty): RedirectResponse
    {
        $validated = $request->validate([
            'apaleo_id' => 'required|string|max:255|unique:apaleo_properties,apaleo_id,' . $apaleoProperty->id,
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'country_code' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'commercial_register_entry' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:34',
            'bic' => 'nullable|string|max:11',
            'bank_name' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:50',
            'currency_code' => 'nullable|string|max:3',
        ]);

        $apaleoProperty->update($validated);

        return redirect()->route('apaleo-properties.show', $apaleoProperty)
            ->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApaleoProperty $apaleoProperty): RedirectResponse
    {
        // Check if property has related data
        $unitGroupsCount = $apaleoProperty->unitGroups()->count();
        $unitsCount = $apaleoProperty->units()->count();

        if ($unitGroupsCount > 0 || $unitsCount > 0) {
            return redirect()->route('apaleo-properties.index')
                ->with('error', 'Cannot delete property. It has ' . $unitGroupsCount . ' unit groups and ' . $unitsCount . ' units associated with it.');
        }

        $apaleoProperty->delete();

        return redirect()->route('apaleo-properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}