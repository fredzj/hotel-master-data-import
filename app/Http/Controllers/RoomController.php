<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:view_rooms')->only(['index', 'show']);
        $this->middleware('can:edit_rooms')->only(['edit', 'update']);
        $this->middleware('can:create_rooms')->only(['create', 'store']);
        $this->middleware('can:delete_rooms')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get sorting parameters
        $sortBy = $request->get('sort', 'number');
        $sortDirection = $request->get('direction', 'asc');
        
        // Validate sort parameters
        $allowedSorts = ['number', 'name', 'floor', 'status', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'number';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        $query = Room::with(['roomType.hotel', 'attributes']);
        
        // Get rooms based on user role
        if ($user->isSuperAdmin()) {
            $rooms = $query->orderBy($sortBy, $sortDirection)->paginate(20);
            $hotels = Hotel::all();
        } else {
            $rooms = $query->whereHas('roomType.hotel', function($q) use ($user) {
                $q->where('id', $user->hotel_id);
            })->orderBy($sortBy, $sortDirection)->paginate(20);
            $hotels = $user->hotel ? [$user->hotel] : [];
        }
        
        // Append query parameters to pagination links
        $rooms->appends($request->query());

        return view('rooms.index', compact('rooms', 'hotels', 'sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Get available room types and floors based on user role
        if ($user->isSuperAdmin()) {
            $roomTypes = \App\Models\RoomType::with('hotel')->get();
            $floors = \App\Models\Floor::with('hotel')->get();
        } else {
            $roomTypes = \App\Models\RoomType::where('hotel_id', $user->hotel_id)->get();
            $floors = \App\Models\Floor::where('hotel_id', $user->hotel_id)->get();
        }
        
        return view('rooms.create', compact('roomTypes', 'floors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:50',
            'room_type_id' => 'required|exists:room_types,id',
            'floor_id' => 'nullable|exists:floors,id',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,maintenance,out_of_order',
        ]);

        // Verify user has access to the selected room type's hotel
        $roomType = \App\Models\RoomType::findOrFail($request->room_type_id);
        if (!$user->isSuperAdmin() && $roomType->hotel_id !== $user->hotel_id) {
            abort(403, 'Unauthorized');
        }

        Room::create([
            'name' => $request->name,
            'number' => $request->number,
            'room_type_id' => $request->room_type_id,
            'floor_id' => $request->floor_id,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $user = auth()->user();
        
        // Check if user has access to this room
        if (!$user->isSuperAdmin() && $room->roomType->hotel->id !== $user->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $room->load(['roomType.hotel', 'attributes', 'floor']);
        
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $user = auth()->user();
        
        // Check if user has access to this room
        if (!$user->isSuperAdmin() && $room->roomType->hotel->id !== $user->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $room->load(['roomType.hotel', 'floor']);
        
        // Get available room types for the hotel
        if ($user->isSuperAdmin()) {
            $roomTypes = \App\Models\RoomType::with('hotel')->get();
            $floors = \App\Models\Floor::with('hotel')->get();
        } else {
            $roomTypes = \App\Models\RoomType::where('hotel_id', $room->roomType->hotel->id)->get();
            $floors = \App\Models\Floor::where('hotel_id', $room->roomType->hotel->id)->get();
        }
        
        return view('rooms.edit', compact('room', 'roomTypes', 'floors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $user = auth()->user();
        
        // Check if user has access to this room
        if (!$user->isSuperAdmin() && $room->roomType->hotel->id !== $user->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:50',
            'room_type_id' => 'required|exists:room_types,id',
            'floor_id' => 'nullable|exists:floors,id',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,maintenance,out_of_order',
        ]);

        $room->update([
            'name' => $request->name,
            'number' => $request->number,
            'room_type_id' => $request->room_type_id,
            'floor_id' => $request->floor_id,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $user = auth()->user();
        
        // Check if user has access to this room
        if (!$user->isSuperAdmin() && $room->roomType->hotel->id !== $user->hotel_id) {
            abort(403, 'Unauthorized');
        }

        // Check if room has associated attributes
        if ($room->attributes()->count() > 0) {
            return redirect()->route('rooms.show', $room)
                ->with('error', 'Cannot delete room that has associated attributes.');
        }

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully!');
    }
}
