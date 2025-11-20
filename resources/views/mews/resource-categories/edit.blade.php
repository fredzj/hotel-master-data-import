@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Resource Category: {{ $category->name }}</h4>
                        <div>
                            <a href="{{ route('mews-resource-categories.show', $category) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('mews-resource-categories.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('mews-resource-categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mews_id" class="form-label">Mews ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mews_id') is-invalid @enderror" 
                                           id="mews_id" name="mews_id" value="{{ old('mews_id', $category->mews_id) }}" 
                                           placeholder="Enter unique Mews ID" required>
                                    @error('mews_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Must be unique across all resource categories</small>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $category->name) }}" 
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
                                                    {{ old('service_id', $category->service_id) == $service->mews_id ? 'selected' : '' }}>
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
                                           id="type" name="type" value="{{ old('type', $category->type) }}" 
                                           placeholder="e.g., Room, Meeting Room, Parking">
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="external_identifier" class="form-label">External ID</label>
                                    <input type="text" class="form-control @error('external_identifier') is-invalid @enderror" 
                                           id="external_identifier" name="external_identifier" 
                                           value="{{ old('external_identifier', $category->external_identifier) }}" 
                                           placeholder="External system identifier">
                                    @error('external_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
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
                                           id="capacity" name="capacity" value="{{ old('capacity', $category->capacity) }}" 
                                           min="0" placeholder="Maximum occupancy">
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum number of guests</small>
                                </div>

                                <div class="form-group">
                                    <label for="included_persons" class="form-label">Included Persons</label>
                                    <input type="number" class="form-control @error('included_persons') is-invalid @enderror" 
                                           id="included_persons" name="included_persons" 
                                           value="{{ old('included_persons', $category->included_persons) }}" 
                                           min="0" placeholder="Persons included in base rate">
                                    @error('included_persons')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="normal_bed_count" class="form-label">Normal Beds</label>
                                    <input type="number" class="form-control @error('normal_bed_count') is-invalid @enderror" 
                                           id="normal_bed_count" name="normal_bed_count" 
                                           value="{{ old('normal_bed_count', $category->normal_bed_count) }}" 
                                           min="0" placeholder="Number of regular beds">
                                    @error('normal_bed_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="extra_bed_count" class="form-label">Extra Beds</label>
                                    <input type="number" class="form-control @error('extra_bed_count') is-invalid @enderror" 
                                           id="extra_bed_count" name="extra_bed_count" 
                                           value="{{ old('extra_bed_count', $category->extra_bed_count) }}" 
                                           min="0" placeholder="Number of extra beds available">
                                    @error('extra_bed_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="area" class="form-label">Area (m²)</label>
                                    <input type="number" class="form-control @error('area') is-invalid @enderror" 
                                           id="area" name="area" value="{{ old('area', $category->area) }}" 
                                           min="0" step="0.01" placeholder="Room/space area">
                                    @error('area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="ordering" class="form-label">Display Order</label>
                                    <input type="number" class="form-control @error('ordering') is-invalid @enderror" 
                                           id="ordering" name="ordering" 
                                           value="{{ old('ordering', $category->ordering ?? 0) }}" 
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
                                      placeholder="Detailed description of the resource category">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($category->resources && $category->resources->count() > 0)
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Associated Resources</h6>
                            <p class="mb-1">This category has <strong>{{ $category->resources->count() }} associated resource(s)</strong>. 
                            Changes to this category may affect those resources.</p>
                            <small class="text-muted">
                                Resources: {{ $category->resources->pluck('name')->take(5)->implode(', ') }}
                                @if($category->resources->count() > 5)
                                    and {{ $category->resources->count() - 5 }} more...
                                @endif
                            </small>
                        </div>
                        @endif

                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ route('mews-resource-categories.show', $category) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Resource Category
                            </button>
                        </div>
                    </form>
                </div>

                @if($category->raw_data)
                <div class="card-footer">
                    <h6 class="text-muted">System Information</h6>
                    <div class="row text-sm">
                        <div class="col-md-4">
                            <strong>Created:</strong> {{ $category->mews_created_utc ? $category->mews_created_utc->format('Y-m-d H:i') : 'N/A' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Updated:</strong> {{ $category->mews_updated_utc ? $category->mews_updated_utc->format('Y-m-d H:i') : 'N/A' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Last Import:</strong> {{ $category->last_imported_at ? $category->last_imported_at->format('Y-m-d H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
                @endif
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
        
        if ((normal > 0 || extra > 0) && (!capacity.value || capacity.value == 0)) {
            // Estimate 2 people per bed as default
            capacity.value = (normal + extra) * 2;
        }
    }
    
    normalBeds.addEventListener('input', updateCapacity);
    extraBeds.addEventListener('input', updateCapacity);
});
</script>
@endpush