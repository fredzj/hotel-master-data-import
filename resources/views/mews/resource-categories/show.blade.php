@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Resource Category Details: {{ $category->name }}</h4>
                        <div>
                            <a href="{{ route('mews-resource-categories.edit', $category) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('mews-resource-categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Mews ID:</strong></td>
                                    <td>{{ $category->mews_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>
                                        @if($category->type)
                                            <span class="badge badge-info">{{ $category->type }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>External ID:</strong></td>
                                    <td>{{ $category->external_identifier ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Service:</strong></td>
                                    <td>
                                        @if($category->service)
                                            <a href="{{ route('mews-services.show', $category->service) }}">
                                                {{ $category->service->name }}
                                            </a>
                                            @if($category->service->enterprise)
                                                <br><small class="text-muted">{{ $category->service->enterprise->name }}</small>
                                            @endif
                                        @else
                                            {{ $category->service_id }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Capacity & Configuration</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Capacity:</strong></td>
                                    <td>{{ $category->capacity ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Included Persons:</strong></td>
                                    <td>{{ $category->included_persons ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Normal Beds:</strong></td>
                                    <td>{{ $category->normal_bed_count ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Extra Beds:</strong></td>
                                    <td>{{ $category->extra_bed_count ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Area:</strong></td>
                                    <td>
                                        @if($category->area)
                                            {{ $category->area }} m²
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ordering:</strong></td>
                                    <td>{{ $category->ordering ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($category->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Description</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ $category->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($category->resources && $category->resources->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Associated Resources ({{ $category->resources->count() }})</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>State</th>
                                            <th>Floor</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->resources->take(10) as $resource)
                                        <tr>
                                            <td>{{ $resource->name }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $resource->data_discriminator }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $resource->state === 'Clean' ? 'badge-success' : 'badge-warning' }}">
                                                    {{ $resource->state }}
                                                </span>
                                            </td>
                                            <td>{{ $resource->floor_number ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('mews-resources.show', $resource) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($category->resources->count() > 10)
                                    <p class="text-muted">
                                        Showing 10 of {{ $category->resources->count() }} resources. 
                                        <a href="{{ route('mews-resources.index', ['category_id' => $category->mews_id]) }}">View all resources</a>
                                    </p>
                                @endif
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
                                    <td>{{ $category->mews_created_utc ? $category->mews_created_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $category->mews_updated_utc ? $category->mews_updated_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Import:</strong></td>
                                    <td>{{ $category->last_imported_at ? $category->last_imported_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($category->raw_data)
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
                                    <pre><code>{{ json_encode($category->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection