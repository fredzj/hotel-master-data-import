@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Resource</h4>
                        <a href="{{ route('mews-resources.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('mews-resources.store') }}">
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
                                    <small class="form-text text-muted">Must be unique across all resources</small>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="e.g., Room 101, Meeting Room A" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="enterprise_id" class="form-label">Enterprise <span class="text-danger">*</span></label>
                                    <select class="form-control @error('enterprise_id') is-invalid @enderror" 
                                            id="enterprise_id" name="enterprise_id" required>
                                        <option value="">Select an enterprise...</option>
                                        @foreach($enterprises as $enterprise)
                                            <option value="{{ $enterprise->mews_id }}" 
                                                    {{ old('enterprise_id') == $enterprise->mews_id ? 'selected' : '' }}>
                                                {{ $enterprise->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('enterprise_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="category_id" class="form-label">Category (Optional)</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id">
                                        <option value="">Select a category...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->mews_id }}" 
                                                    {{ old('category_id') == $category->mews_id ? 'selected' : '' }}
                                                    data-service="{{ $category->service_id }}"
                                                    data-service-name="{{ $category->service ? $category->service->name : '' }}"
                                                    data-enterprise="{{ $category->service && $category->service->enterprise ? $category->service->enterprise->name : '' }}">
                                                {{ $category->name }}
                                                @if($category->service)
                                                    - {{ $category->service->name }}
                                                    @if($category->service->enterprise)
                                                        ({{ $category->service->enterprise->name }})
                                                    @endif
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Optional: Assign to a resource category</small>
                                </div>

                                <div class="form-group">
                                    <label for="data_discriminator" class="form-label">Type</label>
                                    <input type="text" class="form-control @error('data_discriminator') is-invalid @enderror" 
                                           id="data_discriminator" name="data_discriminator" value="{{ old('data_discriminator') }}" 
                                           placeholder="e.g., Room, Space, Bed">
                                    @error('data_discriminator')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="state" class="form-label">State</label>
                                    <select class="form-control @error('state') is-invalid @enderror" 
                                            id="state" name="state">
                                        <option value="">Select state...</option>
                                        <option value="Clean" {{ old('state') == 'Clean' ? 'selected' : '' }}>Clean</option>
                                        <option value="Dirty" {{ old('state') == 'Dirty' ? 'selected' : '' }}>Dirty</option>
                                        <option value="InspectedClean" {{ old('state') == 'InspectedClean' ? 'selected' : '' }}>Inspected Clean</option>
                                        <option value="OutOfOrder" {{ old('state') == 'OutOfOrder' ? 'selected' : '' }}>Out of Order</option>
                                        <option value="OutOfService" {{ old('state') == 'OutOfService' ? 'selected' : '' }}>Out of Service</option>
                                    </select>
                                    @error('state')
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
                                    <small class="form-text text-muted">Inactive resources won't be available for bookings</small>
                                </div>
                            </div>

                            <div class="col-md-6">
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
                                    <label for="floor_number" class="form-label">Floor Number</label>
                                    <input type="number" class="form-control @error('floor_number') is-invalid @enderror" 
                                           id="floor_number" name="floor_number" value="{{ old('floor_number') }}" 
                                           min="0" placeholder="e.g., 1, 2, 3">
                                    @error('floor_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="building_number" class="form-label">Building</label>
                                    <input type="text" class="form-control @error('building_number') is-invalid @enderror" 
                                           id="building_number" name="building_number" value="{{ old('building_number') }}" 
                                           placeholder="e.g., A, B, Main Building">
                                    @error('building_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category Info Display -->
                                <div id="category-info" class="form-group" style="display: none;">
                                    <label class="form-label">Category Information</label>
                                    <div class="card bg-light">
                                        <div class="card-body p-3">
                                            <div id="category-details"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Additional details about this resource">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Resource
                                    </button>
                                    <a href="{{ route('mews-resources.index') }}" class="btn btn-secondary ml-2">
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
// Show category information when category is selected
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const categoryInfo = document.getElementById('category-info');
    const categoryDetails = document.getElementById('category-details');
    
    categorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const serviceName = selectedOption.dataset.serviceName;
            const enterpriseName = selectedOption.dataset.enterprise;
            
            let html = '<p class="mb-1"><strong>Category:</strong> ' + selectedOption.text.split(' - ')[0] + '</p>';
            if (serviceName) {
                html += '<p class="mb-1"><strong>Service:</strong> ' + serviceName + '</p>';
            }
            if (enterpriseName) {
                html += '<p class="mb-0"><strong>Enterprise:</strong> ' + enterpriseName + '</p>';
            }
            
            categoryDetails.innerHTML = html;
            categoryInfo.style.display = 'block';
        } else {
            categoryInfo.style.display = 'none';
        }
    });
    
    // Trigger change event if there's a selected value
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush