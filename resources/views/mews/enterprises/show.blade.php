@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Enterprise Details: {{ $enterprise->name }}</h4>
                    <div>
                        <a href="{{ route('mews-enterprises.edit', $enterprise) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('mews-enterprises.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Enterprises
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Mews ID:</th>
                                    <td><code>{{ $enterprise->mews_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $enterprise->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>External ID:</th>
                                    <td>
                                        @if($enterprise->external_identifier)
                                            <span class="badge bg-secondary">{{ $enterprise->external_identifier }}</span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Timezone:</th>
                                    <td>{{ $enterprise->time_zone_identifier ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Chain:</th>
                                    <td>{{ $enterprise->chain_name ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Default Language:</th>
                                    <td>
                                        @if($enterprise->default_language_code)
                                            {{ strtoupper($enterprise->default_language_code) }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Contact Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Email:</th>
                                    <td>{{ $enterprise->email ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $enterprise->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Website:</strong></td>
                                    <td>
                                        @if($enterprise->website_url)
                                            <a href="{{ $enterprise->website_url }}" target="_blank">{{ $enterprise->website_url }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <h5>Statistics</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body text-center">
                                            <h3>{{ $enterprise->services->count() }}</h3>
                                            <p class="mb-0">Services</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body text-center">
                                            <h3>{{ $enterprise->resources->count() ?? 0 }}</h3>
                                            <p class="mb-0">Resources</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($enterprise->address)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Address</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-1">{{ $enterprise->address_line1 }}</p>
                                    @if($enterprise->address_line2)
                                        <p class="mb-1">{{ $enterprise->address_line2 }}</p>
                                    @endif
                                    <p class="mb-1">
                                        {{ $enterprise->city }}
                                        @if($enterprise->postal_code), {{ $enterprise->postal_code }}@endif
                                    </p>
                                    <p class="mb-0">
                                        {{ $enterprise->country_code }}
                                        @if($enterprise->country_subdivision_code) - {{ $enterprise->country_subdivision_code }}@endif
                                    </p>
                                    @if($enterprise->latitude && $enterprise->longitude)
                                        <p class="mb-0 text-muted">
                                            <small>Coordinates: {{ $enterprise->latitude }}, {{ $enterprise->longitude }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Environment Settings</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Legal Environment:</strong></td>
                                    <td>{{ $enterprise->legal_environment_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Accommodation Environment:</strong></td>
                                    <td>{{ $enterprise->accommodation_environment_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Accounting Environment:</strong></td>
                                    <td>{{ $enterprise->accounting_environment_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax Environment:</strong></td>
                                    <td>{{ $enterprise->tax_environment_code ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>System Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Pricing:</strong></td>
                                    <td>{{ $enterprise->pricing ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax Precision:</strong></td>
                                    <td>{{ $enterprise->tax_precision ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax Identifier:</strong></td>
                                    <td>{{ $enterprise->tax_identifier ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created in Mews:</strong></td>
                                    <td>{{ $enterprise->mews_created_utc ? $enterprise->mews_created_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $enterprise->mews_updated_utc ? $enterprise->mews_updated_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Import:</strong></td>
                                    <td>{{ $enterprise->last_imported_at ? $enterprise->last_imported_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($enterprise->services->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Associated Services ({{ $enterprise->services->count() }})</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enterprise->services->take(10) as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $service->data_discriminator }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $service->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('mews-services.show', $service) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($enterprise->services->count() > 10)
                                    <p class="text-muted">
                                        Showing 10 of {{ $enterprise->services->count() }} services. 
                                        <a href="{{ route('mews-services.index', ['enterprise_id' => $enterprise->mews_id]) }}">View all services</a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($enterprise->raw_data)
                    <hr>
                    <div class="accordion" id="rawDataAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="rawDataHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rawDataCollapse">
                                    <i class="fas fa-code me-2"></i> Raw API Data
                                </button>
                            </h2>
                            <div id="rawDataCollapse" class="accordion-collapse collapse" data-bs-parent="#rawDataAccordion">
                                <div class="accordion-body">
                                    <pre><code>{{ json_encode($enterprise->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <small class="text-muted">
                                Created: {{ $enterprise->created_at->format('Y-m-d H:i:s') }} |
                                Updated: {{ $enterprise->updated_at->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection