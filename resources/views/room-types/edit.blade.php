@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Edit Room Type</h4>
                    <div>
                        <a href="{{ route('room-types.show', $roomType) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to Room Type
                        </a>
                        <a href="{{ route('room-types.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> All Room Types
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('room-types.update', $roomType) }}">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label"><strong>Room Type Name *</strong></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $roomType->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="hotel_id" class="form-label"><strong>Hotel *</strong></label>
                                    <select class="form-control @error('hotel_id') is-invalid @enderror" 
                                            id="hotel_id" name="hotel_id" required>
                                        <option value="">Select Hotel</option>
                                        @foreach($hotels as $hotel)
                                            <option value="{{ $hotel->id }}" 
                                                    {{ old('hotel_id', $roomType->hotel_id) == $hotel->id ? 'selected' : '' }}>
                                                {{ $hotel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hotel_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Room Details -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="max_occupancy" class="form-label"><strong>Max Occupancy *</strong></label>
                                    <input type="number" class="form-control @error('max_occupancy') is-invalid @enderror" 
                                           id="max_occupancy" name="max_occupancy" min="1" max="20"
                                           value="{{ old('max_occupancy', $roomType->max_occupancy) }}" required>
                                    @error('max_occupancy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="base_price" class="form-label"><strong>Base Price</strong></label>
                                    <input type="number" class="form-control @error('base_price') is-invalid @enderror" 
                                           id="base_price" name="base_price" step="0.01" min="0"
                                           value="{{ old('base_price', $roomType->base_price) }}" 
                                           placeholder="0.00">
                                    @error('base_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="size_sqm" class="form-label"><strong>Size (m²)</strong></label>
                                    <input type="number" class="form-control @error('size_sqm') is-invalid @enderror" 
                                           id="size_sqm" name="size_sqm" step="0.1" min="0"
                                           value="{{ old('size_sqm', $roomType->size_sqm) }}" 
                                           placeholder="0.0">
                                    @error('size_sqm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-4">
                            <label for="description" class="form-label"><strong>Description</strong></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Enter room type description (optional)">{{ old('description', $roomType->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Information Display -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Current Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>External ID:</strong> {{ $roomType->external_id ?? 'N/A' }}</p>
                                        <p><strong>Current Hotel:</strong> {{ $roomType->hotel->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Associated Rooms:</strong> {{ $roomType->rooms->count() ?? 0 }} rooms</p>
                                        <p><strong>Created:</strong> {{ $roomType->created_at ? $roomType->created_at->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($roomType->rooms->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> This room type has {{ $roomType->rooms->count() }} associated room(s). 
                            Changes to this room type may affect those rooms.
                        </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ route('room-types.show', $roomType) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Room Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection