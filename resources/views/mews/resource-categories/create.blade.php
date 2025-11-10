@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Resource Category</h4>
                        <a href="{{ route('mews-resource-categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('mews-resource-categories.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mews_id" class="form-label">Mews ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mews_id') is-invalid @enderror" 
                                           id="mews_id" name="mews_id" value="{{ old('mews_id') }}" 
                                           placeholder="Enter unique Mews ID" required>
                                    @error('mews_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Must be unique across all resource categories</small>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="e.g., Standard Double Room" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                                    <select class="form-control @error('service_id') is-invalid @enderror" 
                                            id="service_id" name="service_id" required>
                                        <option value="">Select a service...</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->mews_id }}" 
                                                    {{ old('service_id') == $service->mews_id ? 'selected' : '' }}>
                                                {{ $service->name }} 
                                                @if($service->enterprise)
                                                    ({{ $service->enterprise->name }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="type" class="form-label">Type</label>
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" 
                                           id="type" name="type" value="{{ old('type') }}" 
                                           placeholder="e.g., Room, Meeting Room, Parking">
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="external_identifier" class="form-label">External ID</label>
                                    <input type="text" class="form-control @error('external_identifier') is-invalid @enderror" 
                                           id="external_identifier" name="external_identifier" value="{{ old('external_identifier') }}" 
                                           placeholder="External system identifier">
                                    @error('external_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Inactive categories won't be available for bookings</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="capacity" class="form-label">Capacity</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" value="{{ old('capacity') }}" 
                                           min="0" placeholder="Maximum occupancy">
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum number of guests</small>
                                </div>

                                <div class="form-group">
                                    <label for="included_persons" class="form-label">Included Persons</label>
                                    <input type="number" class="form-control @error('included_persons') is-invalid @enderror" 
                                           id="included_persons" name="included_persons" value="{{ old('included_persons') }}" 
                                           min="0" placeholder="Persons included in base rate">
                                    @error('included_persons')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="normal_bed_count" class="form-label">Normal Beds</label>
                                    <input type="number" class="form-control @error('normal_bed_count') is-invalid @enderror" 
                                           id="normal_bed_count" name="normal_bed_count" value="{{ old('normal_bed_count') }}" 
                                           min="0" placeholder="Number of regular beds">
                                    @error('normal_bed_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="extra_bed_count" class="form-label">Extra Beds</label>
                                    <input type="number" class="form-control @error('extra_bed_count') is-invalid @enderror" 
                                           id="extra_bed_count" name="extra_bed_count" value="{{ old('extra_bed_count') }}" 
                                           min="0" placeholder="Number of extra beds available">
                                    @error('extra_bed_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="area" class="form-label">Area (m²)</label>
                                    <input type="number" class="form-control @error('area') is-invalid @enderror" 
                                           id="area" name="area" value="{{ old('area') }}" 
                                           min="0" step="0.01" placeholder="Room/space area">
                                    @error('area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="ordering" class="form-label">Display Order</label>
                                    <input type="number" class="form-control @error('ordering') is-invalid @enderror" 
                                           id="ordering" name="ordering" value="{{ old('ordering', 0) }}" 
                                           placeholder="Sort order (lower numbers first)">
                                    @error('ordering')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Detailed description of the resource category">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Resource Category
                                    </button>
                                    <a href="{{ route('mews-resource-categories.index') }}" class="btn btn-secondary ml-2">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-calculate capacity based on bed counts
document.addEventListener('DOMContentLoaded', function() {
    const normalBeds = document.getElementById('normal_bed_count');
    const extraBeds = document.getElementById('extra_bed_count');
    const capacity = document.getElementById('capacity');
    
    function updateCapacity() {
        const normal = parseInt(normalBeds.value) || 0;
        const extra = parseInt(extraBeds.value) || 0;
        
        if ((normal > 0 || extra > 0) && !capacity.value) {
            // Estimate 2 people per bed as default
            capacity.value = (normal + extra) * 2;
        }
    }
    
    normalBeds.addEventListener('input', updateCapacity);
    extraBeds.addEventListener('input', updateCapacity);
});
</script>
@endpush