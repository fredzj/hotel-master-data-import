<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MewsResource;
use App\Models\MewsResourceCategory;
use App\Models\MewsService;
use App\Models\MewsEnterprise;
use Illuminate\Validation\Rule;

class MewsResourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MewsResource::with(['categories', 'enterprise']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('mews_id', 'LIKE', "%{$search}%")
                  ->orWhere('external_identifier', 'LIKE', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('data_discriminator', $request->get('type'));
        }

        // State filter
        if ($request->filled('state')) {
            $query->where('state', $request->get('state'));
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('mews_id', $request->get('category_id'));
            });
        }

        // Service filter
        if ($request->filled('service_id')) {
            $query->whereHas('categories.service', function($q) use ($request) {
                $q->where('mews_id', $request->get('service_id'));
            });
        }

        // Enterprise filter
        if ($request->filled('enterprise_id')) {
            $query->where('enterprise_id', $request->get('enterprise_id'));
        }

        // Floor filter
        if ($request->filled('floor')) {
            $query->where('floor_number', $request->get('floor'));
        }

        // Building filter
        if ($request->filled('building')) {
            $query->where('building_number', $request->get('building'));
        }

        // Active filter
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $resources = $query->orderBy('name')->paginate(50)->withQueryString();

        // Get filter options
        $categories = MewsResourceCategory::with('service')->orderBy('name')->get();
        $services = MewsService::with('enterprise')->orderBy('name')->get();
        $enterprises = MewsEnterprise::orderBy('name')->get();
        $types = MewsResource::distinct()->pluck('data_discriminator')->filter()->sort();
        $states = MewsResource::distinct()->pluck('state')->filter()->sort();
        $floors = MewsResource::distinct()->pluck('floor_number')->filter()->sort();
        $buildings = MewsResource::distinct()->pluck('building_number')->filter()->sort();

        return view('mews.resources.index', compact(
            'resources', 'categories', 'services', 'enterprises', 
            'types', 'states', 'floors', 'buildings'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MewsResourceCategory::with(['service', 'service.enterprise'])->orderBy('name')->get();
        $services = MewsService::with('enterprise')->orderBy('name')->get();
        $enterprises = MewsEnterprise::orderBy('name')->get();
        
        return view('mews.resources.create', compact('categories', 'services', 'enterprises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_resources,mews_id',
            'name' => 'required|string|max:255',
            'enterprise_id' => 'required|string|exists:mews_enterprises,mews_id',
            'category_id' => 'nullable|string|exists:mews_resource_categories,mews_id',
            'data_discriminator' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'external_identifier' => 'nullable|string|max:255',
            'floor_number' => 'nullable|integer|min:0',
            'building_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $resource = MewsResource::create($validated);

        // If category is provided, create the assignment
        if ($request->filled('category_id')) {
            $resource->categories()->attach($request->get('category_id'));
        }

        return redirect()
            ->route('mews-resources.show', $resource)
            ->with('success', 'Resource created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MewsResource $mewsResource)
    {
        $mewsResource->load([
            'categories', 
            'categories.service', 
            'categories.service.enterprise',
            'enterprise',
            'features'
        ]);

        return view('mews.resources.show', compact('mewsResource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MewsResource $mewsResource)
    {
        $mewsResource->load(['categories', 'categories.service', 'enterprise']);
        
        $categories = MewsResourceCategory::with(['service', 'service.enterprise'])->orderBy('name')->get();
        $services = MewsService::with('enterprise')->orderBy('name')->get();
        $enterprises = MewsEnterprise::orderBy('name')->get();
        
        return view('mews.resources.edit', compact('mewsResource', 'categories', 'services', 'enterprises'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MewsResource $mewsResource)
    {
        $validated = $request->validate([
            'mews_id' => [
                'required',
                'string',
                Rule::unique('mews_resources', 'mews_id')->ignore($mewsResource->id)
            ],
            'name' => 'required|string|max:255',
            'enterprise_id' => 'required|string|exists:mews_enterprises,mews_id',
            'category_id' => 'nullable|string|exists:mews_resource_categories,mews_id',
            'data_discriminator' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'external_identifier' => 'nullable|string|max:255',
            'floor_number' => 'nullable|integer|min:0',
            'building_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $mewsResource->update($validated);

        // Handle category assignments
        if ($request->filled('category_id')) {
            // Sync categories (replace existing with new one)
            $mewsResource->categories()->sync([$request->get('category_id')]);
        } else {
            // Remove all category assignments
            $mewsResource->categories()->detach();
        }

        return redirect()
            ->route('mews-resources.show', $mewsResource)
            ->with('success', 'Resource updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MewsResource $mewsResource)
    {
        $name = $mewsResource->name;
        $mewsResource->delete();

        return redirect()
            ->route('mews-resources.index')
            ->with('success', "Resource '{$name}' has been deleted.");
    }
}
