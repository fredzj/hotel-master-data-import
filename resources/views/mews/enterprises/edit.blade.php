@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Mews Enterprise</h4>
                        <a href="{{ route('mews-enterprises.show', $enterprise) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('mews-enterprises.update', $enterprise) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mews_id" class="form-label">Mews ID</label>
                                    <input type="text" class="form-control @error('mews_id') is-invalid @enderror" 
                                           id="mews_id" name="mews_id" value="{{ old('mews_id', $enterprise->mews_id) }}" required>
                                    @error('mews_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="external_identifier" class="form-label">External Identifier</label>
                                    <input type="text" class="form-control @error('external_identifier') is-invalid @enderror" 
                                           id="external_identifier" name="external_identifier" value="{{ old('external_identifier', $enterprise->external_identifier) }}">
                                    @error('external_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $enterprise->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="time_zone_identifier" class="form-label">Timezone</label>
                                    <input type="text" class="form-control @error('time_zone_identifier') is-invalid @enderror" 
                                           id="time_zone_identifier" name="time_zone_identifier" value="{{ old('time_zone_identifier', $enterprise->time_zone_identifier) }}" required>
                                    @error('time_zone_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="chain_name" class="form-label">Chain Name</label>
                                    <input type="text" class="form-control @error('chain_name') is-invalid @enderror" 
                                           id="chain_name" name="chain_name" value="{{ old('chain_name', $enterprise->chain_name) }}">
                                    @error('chain_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $enterprise->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $enterprise->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="website_url" class="form-label">Website URL</label>
                                    <input type="url" class="form-control @error('website_url') is-invalid @enderror" 
                                           id="website_url" name="website_url" value="{{ old('website_url', $enterprise->website_url) }}">
                                    @error('website_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="default_language_code" class="form-label">Default Language</label>
                                    <input type="text" class="form-control @error('default_language_code') is-invalid @enderror" 
                                           id="default_language_code" name="default_language_code" value="{{ old('default_language_code', $enterprise->default_language_code) }}">
                                    @error('default_language_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Address Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="address_line1" class="form-label">Address Line 1</label>
                                    <input type="text" class="form-control @error('address_line1') is-invalid @enderror" 
                                           id="address_line1" name="address_line1" value="{{ old('address_line1', $enterprise->address_line1) }}">
                                    @error('address_line1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="address_line2" class="form-label">Address Line 2</label>
                                    <input type="text" class="form-control @error('address_line2') is-invalid @enderror" 
                                           id="address_line2" name="address_line2" value="{{ old('address_line2', $enterprise->address_line2) }}">
                                    @error('address_line2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city', $enterprise->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code', $enterprise->postal_code) }}">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="country_code" class="form-label">Country Code</label>
                                    <input type="text" class="form-control @error('country_code') is-invalid @enderror" 
                                           id="country_code" name="country_code" value="{{ old('country_code', $enterprise->country_code) }}">
                                    @error('country_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="country_subdivision_code" class="form-label">State/Province</label>
                                    <input type="text" class="form-control @error('country_subdivision_code') is-invalid @enderror" 
                                           id="country_subdivision_code" name="country_subdivision_code" value="{{ old('country_subdivision_code', $enterprise->country_subdivision_code) }}">
                                    @error('country_subdivision_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-between">
                            <a href="{{ route('mews-enterprises.show', $enterprise) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Enterprise
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection