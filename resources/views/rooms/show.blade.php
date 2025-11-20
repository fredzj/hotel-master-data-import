@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Room Details: {{ $room->name }}</h4>
                    <div>
                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Rooms
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Room Information -->
                        <div class="col-md-6">
                            <h5>Room Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Room Number</th>
                                    <td><strong>{{ $room->number }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Room Name</th>
                                    <td>{{ $room->name }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $room->description ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $room->status === 'available' ? 'success' : 
                                            ($room->status === 'out_of_order' ? 'danger' : 
                                            ($room->status === 'maintenance' ? 'warning' : 'secondary')) 
                                        }}">
                                            {{ ucwords(str_replace('_', ' ', $room->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>External ID</th>
                                    <td>
                                        @if($room->external_id)
                                            <code>{{ $room->external_id }}</code>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <td>{{ $room->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated</th>
                                    <td>{{ $room->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Hotel and Room Type Information -->
                        <div class="col-md-6">
                            <h5>Hotel & Room Type</h5>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-hotel"></i> 
                                        <a href="{{ route('hotels.show', $room->roomType->hotel) }}" class="text-decoration-none">
                                            {{ $room->roomType->hotel->name }}
                                        </a>
                                    </h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            {{ $room->roomType->hotel->city }}, {{ $room->roomType->hotel->country }}
                                        </small>
                                    </p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-bed"></i>
                                        <a href="{{ route('room-types.show', $room->roomType) }}" class="text-decoration-none">
                                            {{ $room->roomType->name }}
                                        </a>
                                    </h6>
                                    @if($room->roomType->code)
                                        <p class="card-text">
                                            <strong>Code:</strong> {{ $room->roomType->code }}
                                        </p>
                                    @endif
                                    @if($room->roomType->description)
                                        <p class="card-text">{{ $room->roomType->description }}</p>
                                    @endif
                                    @if($room->roomType->member_count)
                                        <p class="card-text">
                                            <strong>Max Occupancy:</strong> {{ $room->roomType->member_count }} guests
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Attributes -->
                    @if($room->attributes->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Room Attributes ({{ $room->attributes->count() }})</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($room->attributes as $attribute)
                                        <tr>
                                            <td><strong>{{ $attribute->name }}</strong></td>
                                            <td>
                                                @if($attribute->code)
                                                    <code>{{ $attribute->code }}</code>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attribute->type)
                                                    <span class="badge bg-secondary">{{ $attribute->type }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attribute->value)
                                                    {{ $attribute->value }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $attribute->description ?: 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Floor Information (if available) -->
                    @if($room->floor)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Floor Information</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p><strong>Floor:</strong> {{ $room->floor->name }}</p>
                                    @if($room->floor->description)
                                        <p><strong>Description:</strong> {{ $room->floor->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection