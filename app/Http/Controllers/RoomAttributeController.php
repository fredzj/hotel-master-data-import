<?php

namespace App\Http\Controllers;

use App\Models\RoomAttribute;
use Illuminate\Http\Request;

class RoomAttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_room_attributes')->only(['index', 'show']);
        $this->middleware('permission:create_room_attributes')->only(['create', 'store']);
        $this->middleware('permission:update_room_attributes')->only(['edit', 'update']);
        $this->middleware('permission:delete_room_attributes')->only(['destroy']);
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
        $allowedSorts = ['id', 'name', 'value', 'type', 'room_name', 'hotel_name', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        $query = RoomAttribute::with(['room.roomType.hotel'])
            ->leftJoin('transformed_rooms', 'transformed_room_attributes.room_id', '=', 'transformed_rooms.id')
            ->leftJoin('transformed_room_types', 'transformed_rooms.room_type_id', '=', 'transformed_room_types.id')
            ->leftJoin('transformed_hotels', 'transformed_room_types.hotel_id', '=', 'transformed_hotels.id')
            ->select('transformed_room_attributes.*');
        
        // Handle different sort columns
        if ($sortBy == 'room_name') {
            $query->orderBy('transformed_rooms.name', $sortDirection);
        } elseif ($sortBy == 'hotel_name') {
            $query->orderBy('transformed_hotels.name', $sortDirection);
        } else {
            $query->orderBy('transformed_room_attributes.' . $sortBy, $sortDirection);
        }
        
        if ($user->isSuperAdmin()) {
            // Super admin can see all room attributes
            $roomAttributes = $query->paginate(10);
        } else {
            // Hotel staff can only see room attributes from their assigned hotels
            $hotelIds = $user->hotels()->pluck('hotels.id');
            $roomAttributes = $query->whereIn('transformed_hotels.id', $hotelIds)->paginate(10);
        }
        
        // Append query parameters to pagination links
        $roomAttributes->appends($request->query());

        return view('room-attributes.index', compact('roomAttributes', 'sortBy', 'sortDirection'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomAttribute $roomAttribute)
    {
        $user = auth()->user();
        
        // Check if user has access to this room attribute
        if (!$user->isSuperAdmin()) {
            $hotelIds = $user->hotels()->pluck('hotels.id');
            $roomAttribute->load(['room.roomType.hotel']);
            if ($roomAttribute->room && $roomAttribute->room->roomType && $roomAttribute->room->roomType->hotel) {
                if (!$hotelIds->contains($roomAttribute->room->roomType->hotel->id)) {
                    abort(403, 'Access denied to this room attribute.');
                }
            }
        } else {
            $roomAttribute->load(['room.roomType.hotel']);
        }
        
        return view('room-attributes.show', compact('roomAttribute'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('room-attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Implementation for creating room attributes
        // This would typically be handled by the import process
        return redirect()->route('room-attributes.index')->with('success', 'Room attribute created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomAttribute $roomAttribute)
    {
        return view('room-attributes.edit', compact('roomAttribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomAttribute $roomAttribute)
    {
        // Implementation for updating room attributes
        return redirect()->route('room-attributes.show', $roomAttribute)->with('success', 'Room attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomAttribute $roomAttribute)
    {
        // Implementation for deleting room attributes
        return redirect()->route('room-attributes.index')->with('success', 'Room attribute deleted successfully.');
    }
}