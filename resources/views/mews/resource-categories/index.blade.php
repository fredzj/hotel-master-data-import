@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Mews Resource Categories</h4>
                        <div>
                            <a href="{{ route('mews-resource-categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Category
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('mews-resource-categories.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" placeholder="Category name...">
                                </div>
                                <div class="col-md-2">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="all">All Types</option>
                                        @foreach($categoryTypes as $type)
                                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select class="form-control" id="is_active" name="is_active">
                                        <option value="all">All</option>
                                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="service_id" class="form-label">Service</label>
                                    <select class="form-control" id="service_id" name="service_id">
                                        <option value="">All Services</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->mews_id }}" {{ request('service_id') === $service->mews_id ? 'selected' : '' }}>
                                                {{ $service->name }} ({{ $service->enterprise->name ?? 'Unknown' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-secondary me-2">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('mews-resource-categories.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped sortable-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Service</th>
                                        <th>Capacity</th>
                                        <th>Beds</th>
                                        <th>Status</th>
                                        <th>Last Import</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                            @if($category->description)
                                                <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                            @endif
                                            @if($category->external_identifier)
                                                <br><small class="text-muted">Ext ID: {{ $category->external_identifier }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->type)
                                                <span class="badge bg-info text-dark">{{ $category->type }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->service)
                                                <a href="{{ route('mews-services.show', $category->service) }}" class="text-decoration-none">
                                                    {{ $category->service->name }}
                                                </a>
                                                @if($category->service->enterprise)
                                                    <br><small class="text-muted">{{ $category->service->enterprise->name }}</small>
                                                @endif
                                            @else
                                                <code>{{ $category->service_id }}</code>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->capacity)
                                                <span class="badge bg-primary">{{ $category->capacity }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                            @if($category->included_persons)
                                                <br><small class="text-muted">Inc: {{ $category->included_persons }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->normal_bed_count || $category->extra_bed_count)
                                                <span class="text-primary">{{ $category->normal_bed_count ?? 0 }}</span>
                                                @if($category->extra_bed_count)
                                                    <span class="text-muted">+{{ $category->extra_bed_count }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($category->last_imported_at)
                                                {{ $category->last_imported_at->format('Y-m-d H:i') }}
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group gap-1" role="group">
                                                <a href="{{ route('mews-resource-categories.show', $category) }}" 
                                                   class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('mews-resource-categories.edit', $category) }}" 
                                                   class="btn btn-outline-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('mews-resource-categories.destroy', $category) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center">
                                {{ $categories->appends(request()->query())->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Mews resource categories found</h5>
                            @if(request()->hasAny(['search', 'type', 'service_id', 'is_active']))
                                <p class="text-muted">Try adjusting your filters or <a href="{{ route('mews-resource-categories.index') }}">clear all filters</a>.</p>
                            @else
                                <p class="text-muted">Import data from Mews or add categories manually.</p>
                                <a href="{{ route('mews-resource-categories.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add First Category
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection