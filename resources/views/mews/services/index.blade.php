@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Mews Services</h4>
                        <div>
                            <a href="{{ route('mews-services.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Service
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('mews-services.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" placeholder="Service name...">
                                </div>
                                <div class="col-md-3">
                                    <label for="type" class="form-label">Service Type</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="all">All Types</option>
                                        @foreach($serviceTypes as $type)
                                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="enterprise_id" class="form-label">Enterprise</label>
                                    <select class="form-control" id="enterprise_id" name="enterprise_id">
                                        <option value="">All Enterprises</option>
                                        @foreach($enterprises as $enterprise)
                                            <option value="{{ $enterprise->mews_id }}" {{ request('enterprise_id') === $enterprise->mews_id ? 'selected' : '' }}>
                                                {{ $enterprise->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-secondary me-2">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('mews-services.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped sortable-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Enterprise</th>
                                        <th>Status</th>
                                        <th>Package</th>
                                        <th>Last Import</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                    <tr>
                                        <td>
                                            <strong>{{ $service->name }}</strong>
                                            @if($service->external_identifier)
                                                <br><small class="text-muted">Ext ID: {{ $service->external_identifier }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $service->data_discriminator }}
                                        </td>
                                        <td>
                                            @if($service->enterprise)
                                                <a href="{{ route('mews-enterprises.show', $service->enterprise) }}" class="text-decoration-none">
                                                    {{ $service->enterprise->name }}
                                                </a>
                                            @else
                                                <code>{{ $service->enterprise_id }}</code>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($service->bill_as_package)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-muted"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($service->last_imported_at)
                                                {{ $service->last_imported_at->format('Y-m-d H:i') }}
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group gap-1" role="group">
                                                <a href="{{ route('mews-services.show', $service) }}" 
                                                   class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('mews-services.edit', $service) }}" 
                                                   class="btn btn-outline-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('mews-services.destroy', $service) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Are you sure you want to delete this service?')">
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
        @if($services instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-center">
                {{ $services->appends(request()->query())->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
            </div>
        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Mews services found</h5>
                            @if(request()->hasAny(['search', 'type', 'enterprise_id']))
                                <p class="text-muted">Try adjusting your filters or <a href="{{ route('mews-services.index') }}">clear all filters</a>.</p>
                            @else
                                <p class="text-muted">Import data from Mews or add services manually.</p>
                                <a href="{{ route('mews-services.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add First Service
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