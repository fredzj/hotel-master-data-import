@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Service Details: {{ $service->name }}</h4>
                    <div>
                        <a href="{{ route('mews-services.edit', $service) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('mews-services.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Services
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
                                    <td><code>{{ $service->mews_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $service->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>
                                        @if($service->data_discriminator)
                                            {{ $service->data_discriminator }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $service->is_active ? 'success' : 'secondary' }}">
                                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>External ID:</th>
                                    <td>
                                        @if($service->external_identifier)
                                            <span class="badge bg-secondary">{{ $service->external_identifier }}</span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Enterprise:</th>
                                    <td>
                                        @if($service->enterprise)
                                            <a href="{{ route('mews-enterprises.show', $service->enterprise) }}">
                                                {{ $service->enterprise->name }}
                                            </a>
                                        @else
                                            {{ $service->enterprise_id }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Service Settings</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Bill as Package:</strong></td>
                                    <td>
                                        <span class="badge {{ $service->bill_as_package ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $service->bill_as_package ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                                @if($service->start_offset)
                                <tr>
                                    <td><strong>Start Offset:</strong></td>
                                    <td>{{ $service->start_offset }}</td>
                                </tr>
                                @endif
                                @if($service->end_offset)
                                <tr>
                                    <td><strong>End Offset:</strong></td>
                                    <td>{{ $service->end_offset }}</td>
                                </tr>
                                @endif
                                @if($service->time_unit_period)
                                <tr>
                                    <td><strong>Time Unit Period:</strong></td>
                                    <td>{{ $service->time_unit_period }}</td>
                                </tr>
                                @endif
                            </table>

                            <h5>Statistics</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body text-center">
                                            <h3>{{ $service->resourceCategories->count() ?? 0 }}</h3>
                                            <p class="mb-0">Categories</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body text-center">
                                            <h3>{{ $service->is_active ? 1 : 0 }}</h3>
                                            <p class="mb-0">Active Status</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($service->hasPromotions())
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Promotion Settings</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ $service->promotion_before_checkin ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">Before Check-in</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ $service->promotion_after_checkin ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">After Check-in</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ $service->promotion_during_stay ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">During Stay</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ $service->promotion_before_checkout ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">Before Check-out</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ $service->promotion_after_checkout ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">After Check-out</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{ $service->promotion_during_checkout ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">During Check-out</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>System Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Created in Mews:</strong></td>
                                    <td>{{ $service->mews_created_utc ? $service->mews_created_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $service->mews_updated_utc ? $service->mews_updated_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Import:</strong></td>
                                    <td>{{ $service->last_imported_at ? $service->last_imported_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($service->raw_data)
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
                                    <pre><code>{{ json_encode($service->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <small class="text-muted">
                                Created: {{ $service->created_at->format('Y-m-d H:i:s') }} |
                                Updated: {{ $service->updated_at->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection