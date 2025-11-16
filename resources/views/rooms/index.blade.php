@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Transformed Rooms</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5>Total Rooms</h5>
                                    <h2>{{ $rooms->total() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Available Rooms</h5>
                                    <h2>{{ $rooms->where('status', 'available')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>Out of Order</h5>
                                    <h2>{{ $rooms->where('status', 'out_of_order')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>Maintenance</h5>
                                    <h2>{{ $rooms->where('status', 'maintenance')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rooms Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'number', 'direction' => request('sort') == 'number' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Room Number
                                            @if(request('sort') == 'number')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Room Name
                                            @if(request('sort') == 'name')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Hotel</th>
                                    <th>Room Type</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Status
                                            @if(request('sort') == 'status')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Attributes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $room)
                                <tr>
                                    <td>
                                        <strong>{{ $room->number }}</strong>
                                    </td>
                                    <td>{{ $room->name }}</td>
                                    <td>
                                        <a href="{{ route('hotels.show', $room->roomType->hotel) }}" class="text-decoration-none">
                                            {{ $room->roomType->hotel->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('room-types.show', $room->roomType) }}" class="text-decoration-none">
                                            {{ $room->roomType->name }}
                                        </a>
                                        @if($room->roomType->code)
                                            <br><small class="text-muted">{{ $room->roomType->code }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $room->status === 'available' ? 'success' : 
                                            ($room->status === 'out_of_order' ? 'danger' : 
                                            ($room->status === 'maintenance' ? 'warning' : 'secondary')) 
                                        }}">
                                            {{ ucwords(str_replace('_', ' ', $room->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($room->attributes->count() > 0)
                                            <span class="badge bg-info">{{ $room->attributes->count() }} attributes</span>
                                        @else
                                            <span class="text-muted">No attributes</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group gap-1" role="group">
                                            <a href="{{ route('rooms.show', $room) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            @can('edit_rooms')
                                                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No rooms found</h5>
                                            <p class="text-muted">Import data from your PMS to see rooms here.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($rooms->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            <nav aria-label="Rooms pagination">
                                {{ $rooms->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection