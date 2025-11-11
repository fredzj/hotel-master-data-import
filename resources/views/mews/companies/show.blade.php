@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Company Details: {{ $company->name }}</h4>
                    <div>
                        <a href="{{ route('mews-companies.edit', $company) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('mews-companies.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Companies
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Mews ID:</th>
                                    <td><code>{{ $company->mews_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $company->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Identifier:</th>
                                    <td>
                                        @if($company->identifier)
                                            <span class="badge bg-secondary">{{ $company->identifier }}</span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>External ID:</th>
                                    <td>{{ $company->external_identifier ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Enterprise:</th>
                                    <td>
                                        @if($company->enterprise)
                                            <a href="{{ route('mews-enterprises.show', $company->enterprise) }}">
                                                {{ $company->enterprise->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mother Company:</th>
                                    <td>
                                        @if($company->motherCompany)
                                            <a href="{{ route('mews-companies.show', $company->motherCompany) }}">
                                                {{ $company->motherCompany->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>IATA:</th>
                                    <td>{{ $company->iata ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{ $company->department ?? 'Not set' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Contact Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Contact Email:</th>
                                    <td>
                                        @if($company->contact_email)
                                            <a href="mailto:{{ $company->contact_email }}">{{ $company->contact_email }}</a>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Invoicing Email:</th>
                                    <td>
                                        @if($company->invoicing_email)
                                            <a href="mailto:{{ $company->invoicing_email }}">{{ $company->invoicing_email }}</a>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Telephone:</th>
                                    <td>{{ $company->telephone ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Website:</th>
                                    <td>
                                        @if($company->website_url)
                                            <a href="{{ $company->website_url }}" target="_blank">{{ $company->website_url }}</a>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <h5>Address</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Address Line 1:</th>
                                    <td>{{ $company->address_line1 ?? 'Not set' }}</td>
                                </tr>
                                @if($company->address_line2)
                                <tr>
                                    <th>Address Line 2:</th>
                                    <td>{{ $company->address_line2 }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $company->city ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Postal Code:</th>
                                    <td>{{ $company->postal_code ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Country:</th>
                                    <td>
                                        @if($company->country_code)
                                            {{ $company->country_code }}
                                            @if($company->country_subdivision_code)
                                                ({{ $company->country_subdivision_code }})
                                            @endif
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($company->latitude && $company->longitude)
                                <tr>
                                    <th>Coordinates:</th>
                                    <td>
                                        {{ $company->latitude }}, {{ $company->longitude }}
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Financial Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="20%">Tax Identifier:</th>
                                    <td>{{ $company->tax_identifier ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Additional Tax ID:</th>
                                    <td>{{ $company->additional_tax_identifier ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Accounting Code:</th>
                                    <td>{{ $company->accounting_code ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Billing Code:</th>
                                    <td>{{ $company->billing_code ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Due Interval:</th>
                                    <td>{{ $company->due_interval ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Invoice Due Interval:</th>
                                    <td>{{ $company->invoice_due_interval ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Reference ID:</th>
                                    <td>{{ $company->reference_identifier ?? 'Not set' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($company->notes)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Notes</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    {{ $company->notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Metadata</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="20%">Created (Mews):</th>
                                    <td>
                                        @if($company->mews_created_utc)
                                            {{ $company->mews_created_utc->format('M j, Y H:i:s') }} UTC
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Updated (Mews):</th>
                                    <td>
                                        @if($company->mews_updated_utc)
                                            {{ $company->mews_updated_utc->format('M j, Y H:i:s') }} UTC
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Imported:</th>
                                    <td>
                                        @if($company->last_imported_at)
                                            {{ $company->last_imported_at->format('M j, Y H:i:s') }}
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
