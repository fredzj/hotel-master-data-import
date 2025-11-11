@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Mews Company</h4>
                        <a href="{{ route('mews-companies.show', $company) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('mews-companies.update', $company) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="mews_id" class="form-label">Mews ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mews_id') is-invalid @enderror" 
                                           id="mews_id" name="mews_id" value="{{ old('mews_id', $company->mews_id) }}" required>
                                    @error('mews_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $company->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="identifier" class="form-label">Identifier</label>
                                    <input type="text" class="form-control @error('identifier') is-invalid @enderror" 
                                           id="identifier" name="identifier" value="{{ old('identifier', $company->identifier) }}">
                                    @error('identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="external_identifier" class="form-label">External Identifier</label>
                                    <input type="text" class="form-control @error('external_identifier') is-invalid @enderror" 
                                           id="external_identifier" name="external_identifier" value="{{ old('external_identifier', $company->external_identifier) }}">
                                    @error('external_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="enterprise_id" class="form-label">Enterprise</label>
                                    <select class="form-control @error('enterprise_id') is-invalid @enderror" 
                                            id="enterprise_id" name="enterprise_id">
                                        <option value="">Select Enterprise</option>
                                        @foreach($enterprises as $enterprise)
                                            <option value="{{ $enterprise->mews_id }}" 
                                                {{ old('enterprise_id', $company->enterprise_id) == $enterprise->mews_id ? 'selected' : '' }}>
                                                {{ $enterprise->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('enterprise_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="mother_company_id" class="form-label">Mother Company</label>
                                    <select class="form-control @error('mother_company_id') is-invalid @enderror" 
                                            id="mother_company_id" name="mother_company_id">
                                        <option value="">None</option>
                                        @foreach($companies as $parentCompany)
                                            <option value="{{ $parentCompany->mews_id }}" 
                                                {{ old('mother_company_id', $company->mother_company_id) == $parentCompany->mews_id ? 'selected' : '' }}>
                                                {{ $parentCompany->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mother_company_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="iata" class="form-label">IATA</label>
                                    <input type="text" class="form-control @error('iata') is-invalid @enderror" 
                                           id="iata" name="iata" value="{{ old('iata', $company->iata) }}">
                                    @error('iata')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                           id="department" name="department" value="{{ old('department', $company->department) }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Contact Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contact_email" class="form-label">Contact Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                           id="contact_email" name="contact_email" value="{{ old('contact_email', $company->contact_email) }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="invoicing_email" class="form-label">Invoicing Email</label>
                                    <input type="email" class="form-control @error('invoicing_email') is-invalid @enderror" 
                                           id="invoicing_email" name="invoicing_email" value="{{ old('invoicing_email', $company->invoicing_email) }}">
                                    @error('invoicing_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="telephone" class="form-label">Telephone</label>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" name="telephone" value="{{ old('telephone', $company->telephone) }}">
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="website_url" class="form-label">Website URL</label>
                                    <input type="url" class="form-control @error('website_url') is-invalid @enderror" 
                                           id="website_url" name="website_url" value="{{ old('website_url', $company->website_url) }}">
                                    @error('website_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Address</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="address_line1" class="form-label">Address Line 1</label>
                                    <input type="text" class="form-control @error('address_line1') is-invalid @enderror" 
                                           id="address_line1" name="address_line1" value="{{ old('address_line1', $company->address_line1) }}">
                                    @error('address_line1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="address_line2" class="form-label">Address Line 2</label>
                                    <input type="text" class="form-control @error('address_line2') is-invalid @enderror" 
                                           id="address_line2" name="address_line2" value="{{ old('address_line2', $company->address_line2) }}">
                                    @error('address_line2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city', $company->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="country_code" class="form-label">Country Code</label>
                                    <input type="text" class="form-control @error('country_code') is-invalid @enderror" 
                                           id="country_code" name="country_code" value="{{ old('country_code', $company->country_code) }}" maxlength="2">
                                    @error('country_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="country_subdivision_code" class="form-label">Country Subdivision Code</label>
                                    <input type="text" class="form-control @error('country_subdivision_code') is-invalid @enderror" 
                                           id="country_subdivision_code" name="country_subdivision_code" value="{{ old('country_subdivision_code', $company->country_subdivision_code) }}">
                                    @error('country_subdivision_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Financial Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tax_identifier" class="form-label">Tax Identifier</label>
                                    <input type="text" class="form-control @error('tax_identifier') is-invalid @enderror" 
                                           id="tax_identifier" name="tax_identifier" value="{{ old('tax_identifier', $company->tax_identifier) }}">
                                    @error('tax_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="additional_tax_identifier" class="form-label">Additional Tax Identifier</label>
                                    <input type="text" class="form-control @error('additional_tax_identifier') is-invalid @enderror" 
                                           id="additional_tax_identifier" name="additional_tax_identifier" value="{{ old('additional_tax_identifier', $company->additional_tax_identifier) }}">
                                    @error('additional_tax_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="accounting_code" class="form-label">Accounting Code</label>
                                    <input type="text" class="form-control @error('accounting_code') is-invalid @enderror" 
                                           id="accounting_code" name="accounting_code" value="{{ old('accounting_code', $company->accounting_code) }}">
                                    @error('accounting_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="billing_code" class="form-label">Billing Code</label>
                                    <input type="text" class="form-control @error('billing_code') is-invalid @enderror" 
                                           id="billing_code" name="billing_code" value="{{ old('billing_code', $company->billing_code) }}">
                                    @error('billing_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="due_interval" class="form-label">Due Interval</label>
                                    <input type="text" class="form-control @error('due_interval') is-invalid @enderror" 
                                           id="due_interval" name="due_interval" value="{{ old('due_interval', $company->due_interval) }}">
                                    @error('due_interval')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="invoice_due_interval" class="form-label">Invoice Due Interval</label>
                                    <input type="text" class="form-control @error('invoice_due_interval') is-invalid @enderror" 
                                           id="invoice_due_interval" name="invoice_due_interval" value="{{ old('invoice_due_interval', $company->invoice_due_interval) }}">
                                    @error('invoice_due_interval')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="reference_identifier" class="form-label">Reference Identifier</label>
                                    <input type="text" class="form-control @error('reference_identifier') is-invalid @enderror" 
                                           id="reference_identifier" name="reference_identifier" value="{{ old('reference_identifier', $company->reference_identifier) }}">
                                    @error('reference_identifier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Notes</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="4">{{ old('notes', $company->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mews-companies.show', $company) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Company
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
