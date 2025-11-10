@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Create New Unit Group</h4>
                    <a href="{{ route('apaleo-unit-groups.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Unit Groups
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('apaleo-unit-groups.store') }}">
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
                                    <select class="form-select @error('property_id') is-invalid @enderror" id="property_id" name="property_id" required>
                                        <option value="">Select Property</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->apaleo_id }}" {{ old('property_id') == $property->apaleo_id ? 'selected' : '' }}>
                                                {{ $property->name }} ({{ $property->city }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('property_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Unit Group Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="code" class="form-label">Unit Group Code</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" 
                                           id="type" name="type" value="{{ old('type') }}" 
                                           placeholder="e.g., Room, Suite, Apartment">
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

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
                                    <label for="member_count" class="form-label">Member Count</label>
                                    <input type="number" class="form-control @error('member_count') is-invalid @enderror" 
                                           id="member_count" name="member_count" value="{{ old('member_count') }}" 
                                           min="0">
                                    @error('member_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="text-end">
                            <a href="{{ route('apaleo-unit-groups.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Unit Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection