<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\PmsSystem;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_all_hotels')->only(['index', 'show']);
        $this->middleware('permission:manage_hotels')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get sorting parameters
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        
        // Validate sort parameters
        $allowedSorts = ['code', 'name', 'company_name', 'city', 'country', 'status', 'currency'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        $query = Hotel::with('pmsSystem');
        
        if ($user->isSuperAdmin()) {
            $hotels = $query->orderBy($sortBy, $sortDirection)->paginate(10);
        } else {
            // Hotel staff can only see their own hotel
            $hotels = $query->where('id', $user->hotel_id)->orderBy($sortBy, $sortDirection)->paginate(10);
        }
        
        // Append query parameters to pagination links
        $hotels->appends($request->query());

        return view('hotels.index', compact('hotels', 'sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pmsSystems = PmsSystem::all();
        return view('hotels.create', compact('pmsSystems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pms_system_id' => 'required|exists:pms_systems,id',
            'name' => 'required|string|max:255',
            'external_id' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255',
            'is_template' => 'boolean',
            'description' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'commercial_register_entry' => 'nullable|string',
            'tax_id' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:3',
            'bank_iban' => 'nullable|string|max:255',
            'bank_bic' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'is_archived' => 'boolean',
        ]);

        Hotel::create($validated);

        return redirect()->route('hotels.index')->with('success', 'Hotel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        $user = auth()->user();
        
        // Check if user has access to this hotel
        if (!$user->isSuperAdmin() && $user->hotel_id !== $hotel->id) {
            abort(403, 'Unauthorized');
        }

        $hotel->load(['pmsSystem', 'buildings', 'roomTypes', 'rooms']);
        return view('hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        $pmsSystems = PmsSystem::all();
        return view('hotels.edit', compact('hotel', 'pmsSystems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'pms_system_id' => 'required|exists:pms_systems,id',
            'name' => 'required|string|max:255',
            'external_id' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255',
            'is_template' => 'boolean',
            'description' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'commercial_register_entry' => 'nullable|string',
            'tax_id' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:3',
            'bank_iban' => 'nullable|string|max:255',
            'bank_bic' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'is_archived' => 'boolean',
        ]);

        $hotel->update($validated);

        return redirect()->route('hotels.index')->with('success', 'Hotel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        
        return redirect()->route('hotels.index')->with('success', 'Hotel deleted successfully.');
    }
}
