<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_all_hotels')->only(['index', 'show']);
        $this->middleware('can:edit_room_types')->only(['edit', 'update']);
        $this->middleware('can:create_room_types')->only(['create', 'store']);
        $this->middleware('can:delete_room_types')->only(['destroy']);
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
        $allowedSorts = ['code', 'name', 'type', 'max_occupancy', 'units_available'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        $query = RoomType::with(['hotel']);
        
        if ($user->isSuperAdmin()) {
            $roomTypes = $query->orderBy($sortBy, $sortDirection)->paginate(15);
        } else {
            // Hotel staff can only see room types from their own hotel
            $roomTypes = $query->whereHas('hotel', function($q) use ($user) {
                $q->where('id', $user->hotel_id);
            })->orderBy($sortBy, $sortDirection)->paginate(15);
        }
        
        // Append query parameters to pagination links
        $roomTypes->appends($request->query());

        return view('room-types.index', compact('roomTypes', 'sortBy', 'sortDirection'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomType $roomType)
    {
        $user = auth()->user();
        
        // Check if user has access to this room type
        if (!$user->isSuperAdmin() && $user->hotel_id !== $roomType->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $roomType->load(['hotel', 'rooms']);
        return view('room-types.show', compact('roomType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            $hotels = Hotel::all();
        } else {
            $hotels = Hotel::where('id', $user->hotel_id)->get();
        }
        
        return view('room-types.create', compact('hotels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,id',
            'description' => 'nullable|string|max:1000',
            'max_occupancy' => 'required|integer|min:1|max:20',
            'base_price' => 'nullable|numeric|min:0',
            'size_sqm' => 'nullable|numeric|min:0',
        ]);

        $user = auth()->user();
        
        // Check if user has access to this hotel
        if (!$user->isSuperAdmin() && $user->hotel_id != $request->hotel_id) {
            abort(403, 'Unauthorized');
        }

        RoomType::create([
            'name' => $request->name,
            'hotel_id' => $request->hotel_id,
            'description' => $request->description,
            'max_occupancy' => $request->max_occupancy,
            'base_price' => $request->base_price,
            'size_sqm' => $request->size_sqm,
        ]);

        return redirect()->route('room-types.index')
            ->with('success', 'Room type created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomType $roomType)
    {
        $user = auth()->user();
        
        // Check if user has access to this room type
        if (!$user->isSuperAdmin() && $user->hotel_id !== $roomType->hotel_id) {
            abort(403, 'Unauthorized');
        }

        if ($user->isSuperAdmin()) {
            $hotels = Hotel::all();
        } else {
            $hotels = Hotel::where('id', $user->hotel_id)->get();
        }
        
        return view('room-types.edit', compact('roomType', 'hotels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomType $roomType)
    {
        $user = auth()->user();
        
        // Check if user has access to this room type
        if (!$user->isSuperAdmin() && $user->hotel_id !== $roomType->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,id',
            'description' => 'nullable|string|max:1000',
            'max_occupancy' => 'required|integer|min:1|max:20',
            'base_price' => 'nullable|numeric|min:0',
            'size_sqm' => 'nullable|numeric|min:0',
        ]);

        // Additional check if hotel is being changed
        if (!$user->isSuperAdmin() && $user->hotel_id != $request->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $roomType->update([
            'name' => $request->name,
            'hotel_id' => $request->hotel_id,
            'description' => $request->description,
            'max_occupancy' => $request->max_occupancy,
            'base_price' => $request->base_price,
            'size_sqm' => $request->size_sqm,
        ]);

        return redirect()->route('room-types.show', $roomType)
            ->with('success', 'Room type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        $user = auth()->user();
        
        // Check if user has access to this room type
        if (!$user->isSuperAdmin() && $user->hotel_id !== $roomType->hotel_id) {
            abort(403, 'Unauthorized');
        }

        // Check if room type has associated rooms
        if ($roomType->rooms()->count() > 0) {
            return redirect()->route('room-types.show', $roomType)
                ->with('error', 'Cannot delete room type that has associated rooms.');
        }

        $roomType->delete();

        return redirect()->route('room-types.index')
            ->with('success', 'Room type deleted successfully!');
    }
}
