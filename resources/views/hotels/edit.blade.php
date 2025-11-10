@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Hotel: {{ $hotel->name }}</h5>
                    <div>
                        <a href="{{ route('hotels.show', $hotel) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hotels.index') }}" class="btn btn-secondary btn-sm">Back to Hotels</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hotels.update', $hotel) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Hotel Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $hotel->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pms_system_id" class="form-label">PMS System <span class="text-danger">*</span></label>
                                <select class="form-control @error('pms_system_id') is-invalid @enderror" 
                                        id="pms_system_id" name="pms_system_id" required>
                                    <option value="">Select PMS System</option>
                                    @foreach($pmsSystems as $pms)
                                        <option value="{{ $pms->id }}" {{ old('pms_system_id', $hotel->pms_system_id) == $pms->id ? 'selected' : '' }}>
                                            {{ $pms->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pms_system_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="external_id" class="form-label">External ID</label>
                                <input type="text" class="form-control @error('external_id') is-invalid @enderror" 
                                       id="external_id" name="external_id" value="{{ old('external_id', $hotel->external_id) }}">
                                @error('external_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="code" class="form-label">Hotel Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $hotel->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="">Select Status</option>
                                    <option value="Active" {{ old('status', $hotel->status) === 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Test" {{ old('status', $hotel->status) === 'Test' ? 'selected' : '' }}>Test</option>
                                    <option value="Inactive" {{ old('status', $hotel->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_template" name="is_template" value="1" 
                                           {{ old('is_template', $hotel->is_template) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_template">
                                        Is Template Property
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_archived" name="is_archived" value="1"
                                           {{ old('is_archived', $hotel->is_archived) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_archived">
                                        Is Archived
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" value="{{ old('company_name', $hotel->company_name) }}">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="tax_id" class="form-label">Tax ID</label>
                                <input type="text" class="form-control @error('tax_id') is-invalid @enderror" 
                                       id="tax_id" name="tax_id" value="{{ old('tax_id', $hotel->tax_id) }}">
                                @error('tax_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="commercial_register_entry" class="form-label">Commercial Register Entry</label>
                            <input type="text" class="form-control @error('commercial_register_entry') is-invalid @enderror" 
                                   id="commercial_register_entry" name="commercial_register_entry" 
                                   value="{{ old('commercial_register_entry', $hotel->commercial_register_entry) }}">
                            @error('commercial_register_entry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $hotel->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $hotel->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website', $hotel->website) }}">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2">{{ old('address', $hotel->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $hotel->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country', $hotel->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $hotel->postal_code) }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $hotel->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="timezone" class="form-label">Timezone</label>
                                <input type="text" class="form-control @error('timezone') is-invalid @enderror" 
                                       id="timezone" name="timezone" value="{{ old('timezone', $hotel->timezone) }}" 
                                       placeholder="e.g., Europe/Berlin">
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="currency" class="form-label">Currency</label>
                                <input type="text" class="form-control @error('currency') is-invalid @enderror" 
                                       id="currency" name="currency" value="{{ old('currency', $hotel->currency) }}" 
                                       placeholder="e.g., EUR" maxlength="3">
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Banking Information -->
                        <h6 class="text-muted mt-4 mb-3">Banking Information</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                       id="bank_name" name="bank_name" value="{{ old('bank_name', $hotel->bank_name) }}">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bank_bic" class="form-label">BIC/SWIFT Code</label>
                                <input type="text" class="form-control @error('bank_bic') is-invalid @enderror" 
                                       id="bank_bic" name="bank_bic" value="{{ old('bank_bic', $hotel->bank_bic) }}">
                                @error('bank_bic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bank_iban" class="form-label">IBAN</label>
                            <input type="text" class="form-control @error('bank_iban') is-invalid @enderror" 
                                   id="bank_iban" name="bank_iban" value="{{ old('bank_iban', $hotel->bank_iban) }}">
                            @error('bank_iban')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary me-2">Update Hotel</button>
                                <a href="{{ route('hotels.show', $hotel) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                            <div>
                                <form method="POST" action="{{ route('hotels.destroy', $hotel) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this hotel?')">
                                        Delete Hotel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection