<?php

namespace App\Http\Controllers;

use App\Models\MewsEnterprise;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MewsEnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $enterprises = MewsEnterprise::with('services')
            ->orderBy('name')
            ->paginate(15);

        return view('mews.enterprises.index', compact('enterprises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('mews.enterprises.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_enterprises,mews_id',
            'external_identifier' => 'nullable|string',
            'holding_key' => 'nullable|string',
            'chain_id' => 'nullable|string',
            'chain_name' => 'nullable|string',
            'name' => 'required|string|max:255',
            'time_zone_identifier' => 'required|string|max:255',
            'legal_environment_code' => 'nullable|string',
            'accommodation_environment_code' => 'nullable|string',
            'accounting_environment_code' => 'nullable|string',
            'tax_environment_code' => 'nullable|string',
            'default_language_code' => 'nullable|string',
            'pricing' => 'nullable|string',
            'tax_precision' => 'nullable|integer',
            'website_url' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'logo_image_id' => 'nullable|string',
            'cover_image_id' => 'nullable|string',
            'address_id' => 'nullable|string',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country_code' => 'nullable|string',
            'country_subdivision_code' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tax_identifier' => 'nullable|string',
        ]);

        $enterprise = MewsEnterprise::create($validated);

        return redirect()->route('mews-enterprises.show', $enterprise)
            ->with('success', 'Mews enterprise created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MewsEnterprise $mewsEnterprise): View
    {
        $mewsEnterprise->load(['services' => function ($query) {
            $query->orderBy('name');
        }]);

        return view('mews.enterprises.show', [
            'enterprise' => $mewsEnterprise
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MewsEnterprise $mewsEnterprise): View
    {
        return view('mews.enterprises.edit', [
            'enterprise' => $mewsEnterprise
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MewsEnterprise $mewsEnterprise): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_enterprises,mews_id,' . $mewsEnterprise->id,
            'external_identifier' => 'nullable|string',
            'holding_key' => 'nullable|string',
            'chain_id' => 'nullable|string',
            'chain_name' => 'nullable|string',
            'name' => 'required|string|max:255',
            'time_zone_identifier' => 'required|string|max:255',
            'legal_environment_code' => 'nullable|string',
            'accommodation_environment_code' => 'nullable|string',
            'accounting_environment_code' => 'nullable|string',
            'tax_environment_code' => 'nullable|string',
            'default_language_code' => 'nullable|string',
            'pricing' => 'nullable|string',
            'tax_precision' => 'nullable|integer',
            'website_url' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'logo_image_id' => 'nullable|string',
            'cover_image_id' => 'nullable|string',
            'address_id' => 'nullable|string',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country_code' => 'nullable|string',
            'country_subdivision_code' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tax_identifier' => 'nullable|string',
        ]);

        $mewsEnterprise->update($validated);

        return redirect()->route('mews-enterprises.show', $mewsEnterprise)
            ->with('success', 'Mews enterprise updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MewsEnterprise $mewsEnterprise): RedirectResponse
    {
        $mewsEnterprise->delete();

        return redirect()->route('mews-enterprises.index')
            ->with('success', 'Mews enterprise deleted successfully.');
    }
}