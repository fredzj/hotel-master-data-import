<?php

namespace App\Http\Controllers;

use App\Models\MewsService;
use App\Models\MewsEnterprise;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MewsServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = MewsService::with(['enterprise']);

        // Filter by enterprise if provided
        if ($request->has('enterprise_id')) {
            $query->where('enterprise_id', $request->enterprise_id);
        }

        // Filter by service type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('data_discriminator', $request->type);
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $services = $query->orderBy('name')->paginate(10);

        $enterprises = MewsEnterprise::orderBy('name')->get();
        $serviceTypes = MewsService::select('data_discriminator')->distinct()->orderBy('data_discriminator')->pluck('data_discriminator');

        return view('mews.services.index', compact('services', 'enterprises', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $enterprises = MewsEnterprise::orderBy('name')->get();
        return view('mews.services.create', compact('enterprises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_services,mews_id',
            'enterprise_id' => 'required|string',
            'external_identifier' => 'nullable|string',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'bill_as_package' => 'boolean',
            'data_discriminator' => 'required|string',
        ]);

        $service = MewsService::create($validated);

        return redirect()->route('mews-services.show', $service)
            ->with('success', 'Mews service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MewsService $mewsService): View
    {
        $mewsService->load('enterprise');
        
        return view('mews.services.show', [
            'service' => $mewsService
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MewsService $mewsService): View
    {
        $enterprises = MewsEnterprise::orderBy('name')->get();
        
        return view('mews.services.edit', [
            'service' => $mewsService,
            'enterprises' => $enterprises
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MewsService $mewsService): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_services,mews_id,' . $mewsService->id,
            'enterprise_id' => 'required|string',
            'external_identifier' => 'nullable|string',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'bill_as_package' => 'boolean',
            'data_discriminator' => 'required|string',
        ]);

        $mewsService->update($validated);

        return redirect()->route('mews-services.show', $mewsService)
            ->with('success', 'Mews service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MewsService $mewsService): RedirectResponse
    {
        $mewsService->delete();

        return redirect()->route('mews-services.index')
            ->with('success', 'Mews service deleted successfully.');
    }
}
