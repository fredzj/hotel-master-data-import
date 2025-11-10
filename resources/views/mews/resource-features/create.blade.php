@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create Resource Feature</h4>
                        <a href="{{ route('mews-resource-features.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('mews-resource-features.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mews_id" class="form-label">
                                        Mews ID <span class="text-danger">*</span>
                                    </label>
                                    <input id="mews_id" type="text" class="form-control @error('mews_id') is-invalid @enderror" 
                                           name="mews_id" value="{{ old('mews_id') }}" required maxlength="255" placeholder="Enter Mews resource feature ID">
                                    @error('mews_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Unique identifier from Mews API</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="enterprise_id" class="form-label">
                                        Enterprise <span class="text-danger">*</span>
                                    </label>
                                    <select id="enterprise_id" name="enterprise_id" class="form-control @error('enterprise_id') is-invalid @enderror" required>
                                        <option value="">Select Enterprise</option>
                                        @foreach($enterprises as $enterprise)
                                            <option value="{{ $enterprise->mews_id }}" {{ old('enterprise_id') == $enterprise->mews_id ? 'selected' : '' }}>
                                                {{ $enterprise->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('enterprise_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Enterprise this feature belongs to</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">
                                Feature Name <span class="text-danger">*</span>
                            </label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required maxlength="255" placeholder="Enter feature name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Descriptive name for the resource feature (e.g., WiFi, Balcony, Ocean View)</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="classification" class="form-label">Classification</label>
                                    <select id="classification" name="classification" class="form-control @error('classification') is-invalid @enderror">
                                        <option value="">Select Classification</option>
                                        <option value="Accessory" {{ old('classification') == 'Accessory' ? 'selected' : '' }}>Accessory</option>
                                        <option value="Feature" {{ old('classification') == 'Feature' ? 'selected' : '' }}>Feature</option>
                                        <option value="Amenity" {{ old('classification') == 'Amenity' ? 'selected' : '' }}>Amenity</option>
                                        <option value="Service" {{ old('classification') == 'Service' ? 'selected' : '' }}>Service</option>
                                        <option value="Equipment" {{ old('classification') == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                                        <option value="View" {{ old('classification') == 'View' ? 'selected' : '' }}>View</option>
                                        <option value="Other" {{ old('classification') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('classification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional classification for grouping features</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="external_identifier" class="form-label">External Identifier</label>
                                    <input id="external_identifier" type="text" class="form-control @error('external_identifier') is-invalid @enderror" 
                                           name="external_identifier" value="{{ old('external_identifier') }}" maxlength="255" 
                                           placeholder="External system identifier">
                                    @error('external_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Identifier from external systems (optional)</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="3" placeholder="Enter feature description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional detailed description of the feature</div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                   type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Feature
                            </label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Inactive features won't be available for assignment to resources</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mews_created_utc" class="form-label">Created in Mews (UTC)</label>
                                    <input id="mews_created_utc" type="datetime-local" class="form-control @error('mews_created_utc') is-invalid @enderror" 
                                           name="mews_created_utc" value="{{ old('mews_created_utc') }}">
                                    @error('mews_created_utc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">When the feature was created in Mews (optional)</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mews_updated_utc" class="form-label">Updated in Mews (UTC)</label>
                                    <input id="mews_updated_utc" type="datetime-local" class="form-control @error('mews_updated_utc') is-invalid @enderror" 
                                           name="mews_updated_utc" value="{{ old('mews_updated_utc') }}">
                                    @error('mews_updated_utc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">When the feature was last updated in Mews (optional)</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('mews-resource-features.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Feature
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate name suggestions based on classification
    const classificationSelect = document.getElementById('classification');
    const nameInput = document.getElementById('name');
    
    const suggestions = {
        'Feature': ['WiFi', 'Air Conditioning', 'Heating', 'Balcony', 'Terrace', 'Fireplace'],
        'Amenity': ['Mini Bar', 'Safe', 'Hair Dryer', 'Iron & Board', 'Coffee Machine', 'Kitchenette'],
        'View': ['Ocean View', 'Garden View', 'City View', 'Mountain View', 'Pool View', 'Courtyard View'],
        'Equipment': ['Television', 'Telephone', 'Sound System', 'Projector', 'Refrigerator', 'Microwave'],
        'Service': ['Room Service', 'Housekeeping', 'Turndown Service', 'Wake-up Call', 'Concierge'],
        'Accessory': ['Extra Bed', 'Crib', 'High Chair', 'Rollaway Bed', 'Extra Pillow', 'Blanket']
    };
    
    classificationSelect.addEventListener('change', function() {
        if (this.value && suggestions[this.value] && !nameInput.value) {
            // Show suggestions as placeholder
            nameInput.placeholder = 'e.g., ' + suggestions[this.value].slice(0, 3).join(', ');
        }
    });

    // Convert datetime fields to proper format for API
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const datetimeFields = ['mews_created_utc', 'mews_updated_utc'];
        datetimeFields.forEach(field => {
            const input = document.getElementById(field);
            if (input.value) {
                // Convert to UTC format expected by Laravel
                input.value = new Date(input.value).toISOString().slice(0, 19).replace('T', ' ');
            }
        });
    });
});
</script>
@endsection