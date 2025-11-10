@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $mewsResourceFeature->name }}</h4>
                        <div>
                            <a href="{{ route('mews-resource-features.edit', $mewsResourceFeature) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('mews-resource-features.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">Basic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Mews ID:</strong></td>
                                    <td>{{ $mewsResourceFeature->mews_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $mewsResourceFeature->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Classification:</strong></td>
                                    <td>
                                        @if($mewsResourceFeature->classification)
                                            <span class="badge badge-info">{{ $mewsResourceFeature->classification }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $mewsResourceFeature->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $mewsResourceFeature->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>External ID:</strong></td>
                                    <td>{{ $mewsResourceFeature->external_identifier ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5 class="text-primary">Enterprise & Usage</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Enterprise:</strong></td>
                                    <td>
                                        @if($mewsResourceFeature->enterprise)
                                            <a href="{{ route('mews-enterprises.show', $mewsResourceFeature->enterprise) }}">
                                                {{ $mewsResourceFeature->enterprise->name }}
                                            </a>
                                        @else
                                            {{ $mewsResourceFeature->enterprise_id }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Associated Resources:</strong></td>
                                    <td>
                                        @if($mewsResourceFeature->resources && $mewsResourceFeature->resources->count() > 0)
                                            <span class="badge badge-primary">{{ $mewsResourceFeature->resources->count() }}</span>
                                            resources assigned
                                        @else
                                            <span class="text-muted">No resources assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($mewsResourceFeature->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary">Description</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $mewsResourceFeature->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($mewsResourceFeature->resources && $mewsResourceFeature->resources->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary">Associated Resources ({{ $mewsResourceFeature->resources->count() }})</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>State</th>
                                            <th>Enterprise</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mewsResourceFeature->resources->take(20) as $resource)
                                        <tr>
                                            <td>
                                                <a href="{{ route('mews-resources.show', $resource) }}">
                                                    {{ $resource->name }}
                                                </a>
                                                @if($resource->external_identifier)
                                                    <br><small class="text-muted">{{ $resource->external_identifier }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($resource->data_discriminator)
                                                    <span class="badge badge-secondary">{{ $resource->data_discriminator }}</span>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($resource->state)
                                                    <span class="badge {{ $resource->state === 'Clean' ? 'badge-success' : ($resource->state === 'Dirty' ? 'badge-warning' : 'badge-info') }}">
                                                        {{ $resource->state }}
                                                    </span>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($resource->enterprise)
                                                    <a href="{{ route('mews-enterprises.show', $resource->enterprise) }}">
                                                        {{ $resource->enterprise->name }}
                                                    </a>
                                                @else
                                                    {{ $resource->enterprise_id }}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('mews-resources.show', $resource) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($mewsResourceFeature->resources->count() > 20)
                                    <p class="text-muted">
                                        Showing 20 of {{ $mewsResourceFeature->resources->count() }} resources. 
                                        <a href="{{ route('mews-resources.index', ['feature_id' => $mewsResourceFeature->mews_id]) }}">View all resources with this feature</a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary">System Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Created in Mews:</strong></td>
                                    <td>{{ $mewsResourceFeature->mews_created_utc ? $mewsResourceFeature->mews_created_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $mewsResourceFeature->mews_updated_utc ? $mewsResourceFeature->mews_updated_utc->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Import:</strong></td>
                                    <td>{{ $mewsResourceFeature->last_imported_at ? $mewsResourceFeature->last_imported_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($mewsResourceFeature->raw_data)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <button class="btn btn-link text-decoration-none p-0" type="button" data-toggle="collapse" data-target="#rawData" aria-expanded="false">
                                            <i class="fas fa-chevron-down"></i> Raw Data
                                        </button>
                                    </h6>
                                </div>
                                <div class="collapse" id="rawData">
                                    <div class="card-body">
                                        <pre><code>{{ json_encode($mewsResourceFeature->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
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