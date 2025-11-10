@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Mews Resources</h4>
                        <a href="{{ route('mews-resources.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Resource
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" placeholder="Name, ID, or External ID">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="">All Types</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="state" class="form-label">State</label>
                                    <select class="form-control" id="state" name="state">
                                        <option value="">All States</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>
                                                {{ $state }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="floor" class="form-label">Floor</label>
                                    <select class="form-control" id="floor" name="floor">
                                        <option value="">All Floors</option>
                                        @foreach($floors as $floor)
                                            <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>
                                                Floor {{ $floor }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="building" class="form-label">Building</label>
                                    <select class="form-control" id="building" name="building">
                                        <option value="">All Buildings</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building }}" {{ request('building') == $building ? 'selected' : '' }}>
                                                {{ $building }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="active" class="form-label">Status</label>
                                    <select class="form-control" id="active" name="active">
                                        <option value="">All</option>
                                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->mews_id }}" {{ request('category_id') == $category->mews_id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                                @if($category->service)
                                                    ({{ $category->service->name }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="service_id" class="form-label">Service</label>
                                    <select class="form-control" id="service_id" name="service_id">
                                        <option value="">All Services</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->mews_id }}" {{ request('service_id') == $service->mews_id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                                @if($service->enterprise)
                                                    ({{ $service->enterprise->name }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="enterprise_id" class="form-label">Enterprise</label>
                                    <select class="form-control" id="enterprise_id" name="enterprise_id">
                                        <option value="">All Enterprises</option>
                                        @foreach($enterprises as $enterprise)
                                            <option value="{{ $enterprise->mews_id }}" {{ request('enterprise_id') == $enterprise->mews_id ? 'selected' : '' }}>
                                                {{ $enterprise->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-search"></i> Apply Filters
                                </button>
                                <a href="{{ route('mews-resources.index') }}" class="btn btn-outline-secondary ml-2">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Resources Table -->
                    <div class="table-responsive">
                        <table class="table table-striped sortable-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>State</th>
                                    <th>Floor</th>
                                    <th>Building</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resources as $resource)
                                    <tr>
                                        <td>
                                            <a href="{{ route('mews-resources.show', $resource) }}" class="text-decoration-none">
                                                <strong>{{ $resource->name }}</strong>
                                            </a>
                                            @if($resource->external_identifier)
                                                <br><small class="text-muted">{{ $resource->external_identifier }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($resource->categories && $resource->categories->count() > 0)
                                                @php $category = $resource->categories->first() @endphp
                                                <a href="{{ route('mews-resource-categories.show', $category) }}">
                                                    {{ $category->name }}
                                                </a>
                                                @if($category->service)
                                                    <br><small class="text-muted">{{ $category->service->name }}</small>
                                                @endif
                                                @if($resource->categories->count() > 1)
                                                    <br><small class="text-info">+{{ $resource->categories->count() - 1 }} more</small>
                                                @endif
                                            @else
                                                <span class="text-muted">No category</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($resource->data_discriminator)
                                                {{ $resource->data_discriminator }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($resource->state)
                                                <span class="badge {{ $resource->state === 'Clean' ? 'bg-success' : ($resource->state === 'Dirty' ? 'bg-warning' : 'bg-info') }}">
                                                    {{ $resource->state }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($resource->floor_number !== null)
                                                Floor {{ $resource->floor_number }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            {{ $resource->building_number ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $resource->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $resource->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group gap-1" role="group">
                                                <a href="{{ route('mews-resources.show', $resource) }}" 
                                                   class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('mews-resources.edit', $resource) }}" 
                                                   class="btn btn-outline-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('mews-resources.destroy', $resource) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Are you sure you want to delete this resource?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No resources found matching your criteria.</p>
                                            <a href="{{ route('mews-resources.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Add First Resource
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
    @if($resources->hasPages())
        <div class="d-flex justify-content-center">
            {{ $resources->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
        </div>
    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection