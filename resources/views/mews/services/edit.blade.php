@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Mews Service</h4>
                        <a href="{{ route('mews-services.show', $service) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('mews-services.update', $service) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mews_id" class="form-label">Mews ID</label>
                                    <input type="text" class="form-control @error('mews_id') is-invalid @enderror" 
                                           id="mews_id" name="mews_id" value="{{ old('mews_id', $service->mews_id) }}" required>
                                    @error('mews_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $service->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="enterprise_id" class="form-label">Enterprise</label>
                                    <select class="form-control @error('enterprise_id') is-invalid @enderror" 
                                            id="enterprise_id" name="enterprise_id" required>
                                        <option value="">Select Enterprise</option>
                                        @foreach($enterprises as $enterprise)
                                            <option value="{{ $enterprise->mews_id }}" {{ old('enterprise_id', $service->enterprise_id) === $enterprise->mews_id ? 'selected' : '' }}>
                                                {{ $enterprise->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('enterprise_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="data_discriminator" class="form-label">Service Type</label>
                                    <select class="form-control @error('data_discriminator') is-invalid @enderror" 
                                            id="data_discriminator" name="data_discriminator" required>
                                        <option value="">Select Type</option>
                                        <option value="Bookable" {{ old('data_discriminator', $service->data_discriminator) === 'Bookable' ? 'selected' : '' }}>Bookable</option>
                                        <option value="Additional" {{ old('data_discriminator', $service->data_discriminator) === 'Additional' ? 'selected' : '' }}>Additional</option>
                                    </select>
                                    @error('data_discriminator')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="external_identifier" class="form-label">External Identifier</label>
                                    <input type="text" class="form-control @error('external_identifier') is-invalid @enderror" 
                                           id="external_identifier" name="external_identifier" value="{{ old('external_identifier', $service->external_identifier) }}">
                                    @error('external_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
                                               id="is_active" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Service
                                        </label>
                                    </div>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input @error('bill_as_package') is-invalid @enderror" 
                                               id="bill_as_package" name="bill_as_package" value="1" {{ old('bill_as_package', $service->bill_as_package) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bill_as_package">
                                            Bill as Package
                                        </label>
                                    </div>
                                    @error('bill_as_package')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ route('mews-services.show', $service) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection