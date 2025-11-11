<?php

namespace App\Http\Controllers;

use App\Models\MewsEnterprise;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MewsEnterprisesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $enterprises = MewsEnterprise::with(['services'])
            ->orderBy('name')
            ->paginate(10);

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
            'mews_id' => 'required|string|max:255|unique:mews_enterprises',
            'name' => 'required|string|max:255',
            'time_zone_identifier' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $enterprise = MewsEnterprise::create($validated);

        return redirect()->route('mews-enterprises.show', $enterprise)
            ->with('success', 'Enterprise created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MewsEnterprise $mewsEnterprise): View
    {
        $mewsEnterprise->load(['services', 'resources']);
        
        return view('mews.enterprises.show', compact('mewsEnterprise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MewsEnterprise $mewsEnterprise): View
    {
        return view('mews.enterprises.edit', compact('mewsEnterprise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MewsEnterprise $mewsEnterprise): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|max:255|unique:mews_enterprises,mews_id,' . $mewsEnterprise->id,
            'name' => 'required|string|max:255',
            'time_zone_identifier' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $mewsEnterprise->update($validated);

        return redirect()->route('mews-enterprises.show', $mewsEnterprise)
            ->with('success', 'Enterprise updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MewsEnterprise $mewsEnterprise): RedirectResponse
    {
        // Check if enterprise has related data
        if ($mewsEnterprise->services()->exists() || $mewsEnterprise->resources()->exists()) {
            return redirect()->route('mews-enterprises.index')
                ->with('error', 'Cannot delete enterprise that has services or resources. Delete related data first.');
        }

        $mewsEnterprise->delete();

        return redirect()->route('mews-enterprises.index')
            ->with('success', 'Enterprise deleted successfully.');
    }
}
