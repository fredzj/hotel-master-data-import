@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Room Type Details: {{ $roomType->name }}</h4>
                    <div>
                        <a href="{{ route('room-types.edit', $roomType) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('room-types.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Room Types
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Basic Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Room Type Name:</strong></td>
                                    <td>{{ $roomType->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td><code>{{ $roomType->code ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>External ID:</strong></td>
                                    <td><code>{{ $roomType->external_id ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $roomType->type === 'BedRoom' ? 'primary' : 'info' }}">
                                            {{ $roomType->type ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Hotel:</strong></td>
                                    <td>
                                        <a href="{{ route('hotels.show', $roomType->hotel) }}">
                                            {{ $roomType->hotel->name }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Capacity & Availability</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Maximum Occupancy:</strong></td>
                                    <td>
                                        @if($roomType->max_occupancy)
                                            {{ $roomType->max_occupancy }} person{{ $roomType->max_occupancy > 1 ? 's' : '' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Available Units:</strong></td>
                                    <td>
                                        @if($roomType->member_count)
                                            <span class="badge bg-info">{{ $roomType->member_count }} unit{{ $roomType->member_count > 1 ? 's' : '' }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Room Size:</strong></td>
                                    <td>{{ $roomType->size ? $roomType->size . ' m²' : 'N/A' }}</td>
                                </tr>
                            </table>

                            <h6 class="text-muted mt-4">System Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Imported:</strong></td>
                                    <td>{{ $roomType->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $roomType->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($roomType->description)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-muted">Description</h6>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $roomType->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Related Rooms Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted">Individual Rooms</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $roomType->rooms->count() }}</h5>
                                            <p class="card-text">Individual Rooms</p>
                                            @if($roomType->rooms->count() > 0)
                                                <small class="text-muted">Individual room records available</small>
                                            @else
                                                <small class="text-muted">No individual room records imported yet</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection