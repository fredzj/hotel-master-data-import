<?php

namespace App\Http\Controllers;

use App\Models\MewsResourceCategory;
use App\Models\MewsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MewsResourceCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = MewsResourceCategory::with(['service', 'service.enterprise']);

        // Filter by service if provided
        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by active status
        if ($request->has('is_active') && $request->is_active !== 'all') {
            $query->where('is_active', $request->is_active === '1');
        }

        $categories = $query->orderBy('name')->paginate(15);

        $services = MewsService::with('enterprise')->orderBy('name')->get();
        $categoryTypes = MewsResourceCategory::select('type')->distinct()->orderBy('type')->whereNotNull('type')->pluck('type');

        return view('mews.resource-categories.index', compact('categories', 'services', 'categoryTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $services = MewsService::with('enterprise')->orderBy('name')->get();
        return view('mews.resource-categories.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_resource_categories,mews_id',
            'service_id' => 'required|string',
            'external_identifier' => 'nullable|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'type' => 'nullable|string',
            'normal_bed_count' => 'nullable|integer|min:0',
            'extra_bed_count' => 'nullable|integer|min:0',
            'included_persons' => 'nullable|integer|min:0',
            'capacity' => 'nullable|integer|min:0',
            'ordering' => 'nullable|integer',
            'area' => 'nullable|numeric|min:0',
        ]);

        $category = MewsResourceCategory::create($validated);

        return redirect()->route('mews-resource-categories.show', $category)
            ->with('success', 'Mews resource category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MewsResourceCategory $mewsResourceCategory): View
    {
        $mewsResourceCategory->load(['service.enterprise', 'resources']);
        
        return view('mews.resource-categories.show', [
            'category' => $mewsResourceCategory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MewsResourceCategory $mewsResourceCategory): View
    {
        $services = MewsService::with('enterprise')->orderBy('name')->get();
        
        return view('mews.resource-categories.edit', [
            'category' => $mewsResourceCategory,
            'services' => $services
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MewsResourceCategory $mewsResourceCategory): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_resource_categories,mews_id,' . $mewsResourceCategory->id,
            'service_id' => 'required|string',
            'external_identifier' => 'nullable|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'type' => 'nullable|string',
            'normal_bed_count' => 'nullable|integer|min:0',
            'extra_bed_count' => 'nullable|integer|min:0',
            'included_persons' => 'nullable|integer|min:0',
            'capacity' => 'nullable|integer|min:0',
            'ordering' => 'nullable|integer',
            'area' => 'nullable|numeric|min:0',
        ]);

        $mewsResourceCategory->update($validated);

        return redirect()->route('mews-resource-categories.show', $mewsResourceCategory)
            ->with('success', 'Mews resource category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MewsResourceCategory $mewsResourceCategory): RedirectResponse
    {
        $mewsResourceCategory->delete();

        return redirect()->route('mews-resource-categories.index')
            ->with('success', 'Mews resource category deleted successfully.');
    }
}
