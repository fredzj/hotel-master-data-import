@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Room Attribute Details</h4>
                    <div>
                        <a href="{{ route('room-attributes.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 40%;">ID:</th>
                                            <td>{{ $roomAttribute->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>PMS Attribute ID:</th>
                                            <td>{{ $roomAttribute->pms_attribute_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Attribute Type:</th>
                                            <td>
                                                <span class="badge bg-info">{{ $roomAttribute->attribute_type }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td><strong>{{ $roomAttribute->name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Description:</th>
                                            <td>{{ $roomAttribute->description ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Value Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><i class="fas fa-tag"></i> Value Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 40%;">Value:</th>
                                            <td>
                                                @if(is_array($roomAttribute->value))
                                                    <pre><code>{{ json_encode($roomAttribute->value, JSON_PRETTY_PRINT) }}</code></pre>
                                                @else
                                                    <strong>{{ $roomAttribute->value }}</strong>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Unit:</th>
                                            <td>{{ $roomAttribute->unit ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ $roomAttribute->created_at ? $roomAttribute->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated:</th>
                                            <td>{{ $roomAttribute->updated_at ? $roomAttribute->updated_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Information -->
                    @if($roomAttribute->room)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-bed"></i> Associated Room</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 40%;">Room Name:</th>
                                            <td>
                                                <a href="{{ route('rooms.show', $roomAttribute->room) }}" class="text-decoration-none">
                                                    {{ $roomAttribute->room->name ?? $roomAttribute->room->pms_room_id }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Room Number:</th>
                                            <td>{{ $roomAttribute->room->room_number ?? 'N/A' }}</td>
                                        </tr>
                                        @if($roomAttribute->room->roomType && $roomAttribute->room->roomType->hotel)
                                        <tr>
                                            <th>Hotel:</th>
                                            <td>
                                                <a href="{{ route('hotels.show', $roomAttribute->room->roomType->hotel) }}" class="text-decoration-none">
                                                    {{ $roomAttribute->room->roomType->hotel->name }}
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    @if($roomAttribute->room->roomType)
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 40%;">Room Type:</th>
                                            <td>
                                                <a href="{{ route('room-types.show', $roomAttribute->room->roomType) }}" class="text-decoration-none">
                                                    {{ $roomAttribute->room->roomType->name }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Max Occupancy:</th>
                                            <td>{{ $roomAttribute->room->roomType->max_occupancy ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-cogs"></i> Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="btn-group" role="group">
                                @can('update_room_attributes')
                                    <a href="{{ route('room-attributes.edit', $roomAttribute) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Attribute
                                    </a>
                                @endcan
                                
                                @can('delete_room_attributes')
                                    <form method="POST" action="{{ route('room-attributes.destroy', $roomAttribute) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this room attribute?')" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection