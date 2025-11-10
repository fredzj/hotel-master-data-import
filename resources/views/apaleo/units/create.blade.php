@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Create New Unit</h4>
                    <a href="{{ route('apaleo-units.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Units
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('apaleo-units.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apaleo_id" class="form-label">Apaleo ID *</label>
                                    <input type="text" class="form-control @error('apaleo_id') is-invalid @enderror" 
                                           id="apaleo_id" name="apaleo_id" value="{{ old('apaleo_id') }}" required>
                                    @error('apaleo_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="property_id" class="form-label">Property *</label>
                                    <select class="form-select @error('property_id') is-invalid @enderror" 
                                            id="property_id" name="property_id" required onchange="loadUnitGroups()">
                                        <option value="">Select Property</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->apaleo_id }}" 
                                                {{ old('property_id') == $property->apaleo_id ? 'selected' : '' }}>
                                                {{ $property->name }} ({{ $property->city }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('property_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="unit_group_id" class="form-label">Unit Group</label>
                                    <select class="form-select @error('unit_group_id') is-invalid @enderror" 
                                            id="unit_group_id" name="unit_group_id">
                                        <option value="">Select Unit Group (Optional)</option>
                                    </select>
                                    @error('unit_group_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Unit Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="">Select Status</option>
                                        <option value="Vacant" {{ old('status') == 'Vacant' ? 'selected' : '' }}>Vacant</option>
                                        <option value="Occupied" {{ old('status') == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="Out of Order" {{ old('status') == 'Out of Order' ? 'selected' : '' }}>Out of Order</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="condition" class="form-label">Condition</label>
                                    <select class="form-select @error('condition') is-invalid @enderror" id="condition" name="condition">
                                        <option value="">Select Condition</option>
                                        <option value="Clean" {{ old('condition') == 'Clean' ? 'selected' : '' }}>Clean</option>
                                        <option value="Dirty" {{ old('condition') == 'Dirty' ? 'selected' : '' }}>Dirty</option>
                                        <option value="Maintenance" {{ old('condition') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_persons" class="form-label">Max Persons</label>
                                    <input type="number" class="form-control @error('max_persons') is-invalid @enderror" 
                                           id="max_persons" name="max_persons" value="{{ old('max_persons') }}" 
                                           min="0" max="99">
                                    @error('max_persons')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="size" class="form-label">Size (m²)</label>
                                    <input type="number" step="0.01" class="form-control @error('size') is-invalid @enderror" 
                                           id="size" name="size" value="{{ old('size') }}" 
                                           min="0">
                                    @error('size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="view" class="form-label">View</label>
                                    <input type="text" class="form-control @error('view') is-invalid @enderror" 
                                           id="view" name="view" value="{{ old('view') }}" 
                                           placeholder="e.g., Sea View, Garden View, City View">
                                    @error('view')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="text-end">
                            <a href="{{ route('apaleo-units.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function loadUnitGroups() {
    const propertyId = document.getElementById('property_id').value;
    const unitGroupSelect = document.getElementById('unit_group_id');
    
    // Clear current options except the first one
    unitGroupSelect.innerHTML = '<option value="">Select Unit Group (Optional)</option>';
    
    if (propertyId) {
        fetch(`{{ route('api.unit-groups-by-property') }}?property_id=${propertyId}`)
            .then(response => response.json())
            .then(unitGroups => {
                unitGroups.forEach(unitGroup => {
                    const option = document.createElement('option');
                    option.value = unitGroup.apaleo_id;
                    option.textContent = `${unitGroup.name}`;
                    unitGroupSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading unit groups:', error);
            });
    }
}
</script>
@endsection
@endsection