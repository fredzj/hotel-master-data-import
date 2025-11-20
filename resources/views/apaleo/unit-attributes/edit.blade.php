@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Edit Unit Attribute: {{ $apaleoUnitAttribute->name }}</h4>
                    <div>
                        <a href="{{ route('apaleo-unit-attributes.show', $apaleoUnitAttribute) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('apaleo-unit-attributes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Unit Attributes
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('apaleo-unit-attributes.update', $apaleoUnitAttribute) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apaleo_id" class="form-label">Apaleo ID *</label>
                                    <input type="text" class="form-control @error('apaleo_id') is-invalid @enderror" 
                                           id="apaleo_id" name="apaleo_id" value="{{ old('apaleo_id', $apaleoUnitAttribute->apaleo_id) }}" required>
                                    @error('apaleo_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="unit_id" class="form-label">Unit *</label>
                                    <select class="form-select @error('unit_id') is-invalid @enderror" 
                                            id="unit_id" name="unit_id" required>
                                        <option value="">Select Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->apaleo_id }}" 
                                                {{ old('unit_id', $apaleoUnitAttribute->unit_id) == $unit->apaleo_id ? 'selected' : '' }}>
                                                {{ $unit->name }} ({{ $unit->property->name ?? 'Unknown Property' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Attribute Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $apaleoUnitAttribute->name) }}" required
                                           placeholder="e.g., Wi-Fi, Air Conditioning, Balcony">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value" class="form-label">Value</label>
                                    <input type="text" class="form-control @error('value') is-invalid @enderror" 
                                           id="value" name="value" value="{{ old('value', $apaleoUnitAttribute->value) }}"
                                           placeholder="e.g., Yes, No, Free, Premium">
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                        <option value="">Select Type</option>
                                        <option value="amenity" {{ old('type', $apaleoUnitAttribute->type) == 'amenity' ? 'selected' : '' }}>Amenity</option>
                                        <option value="feature" {{ old('type', $apaleoUnitAttribute->type) == 'feature' ? 'selected' : '' }}>Feature</option>
                                        <option value="service" {{ old('type', $apaleoUnitAttribute->type) == 'service' ? 'selected' : '' }}>Service</option>
                                        <option value="equipment" {{ old('type', $apaleoUnitAttribute->type) == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                        <option value="other" {{ old('type', $apaleoUnitAttribute->type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $apaleoUnitAttribute->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ route('apaleo-unit-attributes.show', $apaleoUnitAttribute) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Unit Attribute
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection