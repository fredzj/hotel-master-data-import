<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MewsResourceFeature;
use App\Models\MewsEnterprise;
use Illuminate\Validation\Rule;

class MewsResourceFeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MewsResourceFeature::with(['service.enterprise', 'resources']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('mews_id', 'LIKE', "%{$search}%")
                  ->orWhere('external_identifier', 'LIKE', "%{$search}%");
            });
        }

        // Classification filter
        if ($request->filled('classification')) {
            $query->where('classification', $request->get('classification'));
        }

        // Enterprise filter
        if ($request->filled('enterprise_id')) {
            $query->where('enterprise_id', $request->get('enterprise_id'));
        }

        // Active filter
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $features = $query->orderBy('name')->paginate(10)->withQueryString();

        // Get filter options
        $enterprises = MewsEnterprise::orderBy('name')->get();
        $classifications = MewsResourceFeature::distinct()->pluck('classification')->filter()->sort();

        return view('mews.resource-features.index', compact(
            'features', 'enterprises', 'classifications'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $enterprises = MewsEnterprise::orderBy('name')->get();
        
        return view('mews.resource-features.create', compact('enterprises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_resource_features,mews_id',
            'name' => 'required|string|max:255',
            'enterprise_id' => 'required|string|exists:mews_enterprises,mews_id',
            'classification' => 'nullable|string|max:100',
            'external_identifier' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $feature = MewsResourceFeature::create($validated);

        return redirect()
            ->route('mews-resource-features.show', $feature)
            ->with('success', 'Resource feature created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MewsResourceFeature $mewsResourceFeature)
    {
        $mewsResourceFeature->load(['service.enterprise', 'resources']);

        return view('mews.resource-features.show', compact('mewsResourceFeature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MewsResourceFeature $mewsResourceFeature)
    {
        $mewsResourceFeature->load(['service.enterprise']);
        
        $enterprises = MewsEnterprise::orderBy('name')->get();
        
        return view('mews.resource-features.edit', compact('mewsResourceFeature', 'enterprises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MewsResourceFeature $mewsResourceFeature)
    {
        $validated = $request->validate([
            'mews_id' => [
                'required',
                'string',
                Rule::unique('mews_resource_features', 'mews_id')->ignore($mewsResourceFeature->id)
            ],
            'name' => 'required|string|max:255',
            'enterprise_id' => 'required|string|exists:mews_enterprises,mews_id',
            'classification' => 'nullable|string|max:100',
            'external_identifier' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $mewsResourceFeature->update($validated);

        return redirect()
            ->route('mews-resource-features.show', $mewsResourceFeature)
            ->with('success', 'Resource feature updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MewsResourceFeature $mewsResourceFeature)
    {
        $name = $mewsResourceFeature->name;
        $mewsResourceFeature->delete();

        return redirect()
            ->route('mews-resource-features.index')
            ->with('success', "Resource feature '{$name}' has been deleted.");
    }
}
