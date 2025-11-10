@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Resource Details: {{ $mewsResource->name }}</h4>
                    <div>
                        <a href="{{ route('mews-resources.edit', $mewsResource) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('mews-resources.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Resources
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
                                    <td><code>{{ $mewsResource->mews_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $mewsResource->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>
                                        @if($mewsResource->data_discriminator)
                                            {{ $mewsResource->data_discriminator }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>State:</th>
                                    <td>
                                        @if($mewsResource->state)
                                            <span class="badge bg-{{ $mewsResource->state === 'Clean' ? 'success' : ($mewsResource->state === 'Dirty' ? 'warning' : 'info') }}">
                                                {{ $mewsResource->state }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $mewsResource->is_active ? 'success' : 'secondary' }}">
                                            {{ $mewsResource->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>External ID:</strong></td>
                                    <td>{{ $mewsResource->external_identifier ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Location & Configuration</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Floor Number:</strong></td>
                                    <td>
                                        @if($mewsResource->floor_number !== null)
                                            Floor {{ $mewsResource->floor_number }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Building:</strong></td>
                                    <td>{{ $mewsResource->building_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Categories:</strong></td>
                                    <td>
                                        @if($mewsResource->categories && $mewsResource->categories->count() > 0)
                                            @foreach($mewsResource->categories as $category)
                                                <div class="mb-1">
                                                    <a href="{{ route('mews-resource-categories.show', $category) }}">
                                                        {{ $category->name }}
                                                    </a>
                                                    @if($category->type)
                                                        <span class="badge badge-secondary badge-sm ml-1">{{ $category->type }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No categories assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Enterprise:</strong></td>
                                    <td>
                                        @if($mewsResource->enterprise)
                                            <a href="{{ route('mews-enterprises.show', $mewsResource->enterprise) }}">
                                                {{ $mewsResource->enterprise->name }}
                                            </a>
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
                                            <h3>{{ $mewsResource->categories->count() ?? 0 }}</h3>
                                            <p class="mb-0">Categories</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body text-center">
                                            <h3>{{ $mewsResource->features->count() ?? 0 }}</h3>
                                            <p class="mb-0">Features</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($mewsResource->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Description</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $mewsResource->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($mewsResource->categories && $mewsResource->categories->count() > 0)
                    <hr>
                    <h5>Resource Categories</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Capacity</th>
                                    <th>Beds</th>
                                    <th>Service</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                                @foreach($mewsResource->categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        @if($category->capacity)
                                            {{ $category->capacity }} guests
                                            @if($category->included_persons)
                                                <br><small class="text-muted">({{ $category->included_persons }} included)</small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($category->normal_bed_count || $category->extra_bed_count)
                                            {{ $category->normal_bed_count ?? 0 }} normal
                                            @if($category->extra_bed_count)
                                                + {{ $category->extra_bed_count }} extra
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($category->service)
                                            <a href="{{ route('mews-services.show', $category->service) }}">
                                                {{ $category->service->name }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('mews-resource-categories.show', $category) }}" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if($mewsResource->features && $mewsResource->features->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Features ({{ $mewsResource->features->count() }})</h5>
                            <div class="row">
                                @foreach($mewsResource->features as $feature)
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-1">{{ $feature->name }}</h6>
                                                @if($feature->classification)
                                                    <span class="badge badge-info badge-sm">{{ $feature->classification }}</span>
                                                @endif
                                                @if($feature->description)
                                                    <p class="card-text small text-muted mt-2 mb-0">{{ $feature->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                                    <td>{{ $mewsResource->mews_created_utc ? $mewsResource->mews_created_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $mewsResource->mews_updated_utc ? $mewsResource->mews_updated_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Import:</strong></td>
                                    <td>{{ $mewsResource->last_imported_at ? $mewsResource->last_imported_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($mewsResource->raw_data)
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
                                    <pre><code>{{ json_encode($mewsResource->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <small class="text-muted">
                                Created: {{ $mewsResource->created_at->format('Y-m-d H:i:s') }} |
                                Updated: {{ $mewsResource->updated_at->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection