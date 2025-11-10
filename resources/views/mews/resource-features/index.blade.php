@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Mews Resource Features</h4>
                        <a href="{{ route('mews-resource-features.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Feature
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" placeholder="Name, ID, or External ID">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="classification" class="form-label">Classification</label>
                                    <select class="form-control" id="classification" name="classification">
                                        <option value="">All Classifications</option>
                                        @foreach($classifications as $classification)
                                            <option value="{{ $classification }}" {{ request('classification') == $classification ? 'selected' : '' }}>
                                                {{ $classification }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
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

                            <div class="col-md-2">
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

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-search"></i> Apply Filters
                                </button>
                                <a href="{{ route('mews-resource-features.index') }}" class="btn btn-outline-secondary ml-2">
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

                    <!-- Features Table -->
                    <div class="table-responsive">
                        <table class="table table-striped sortable-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Classification</th>
                                    <th>Enterprise</th>
                                    <th>Resources</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($features as $feature)
                                    <tr>
                                        <td>
                                            <a href="{{ route('mews-resource-features.show', $feature) }}" class="text-decoration-none">
                                                <strong>{{ $feature->name }}</strong>
                                            </a>
                                            @if($feature->external_identifier)
                                                <br><small class="text-muted">{{ $feature->external_identifier }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($feature->classification)
                                                <span class="badge badge-info">{{ $feature->classification }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($feature->enterprise)
                                                <a href="{{ route('mews-enterprises.show', $feature->enterprise) }}">
                                                    {{ $feature->enterprise->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ $feature->enterprise_id }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($feature->resources && $feature->resources->count() > 0)
                                                <span class="badge badge-primary">{{ $feature->resources->count() }}</span>
                                                <small class="text-muted">resources</small>
                                            @else
                                                <span class="text-muted">No resources</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $feature->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $feature->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group gap-1" role="group">
                                                <a href="{{ route('mews-resource-features.show', $feature) }}" 
                                                   class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('mews-resource-features.edit', $feature) }}" 
                                                   class="btn btn-outline-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('mews-resource-features.destroy', $feature) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Are you sure you want to delete this feature?')">
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
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No resource features found matching your criteria.</p>
                                            <a href="{{ route('mews-resource-features.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Add First Feature
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($features->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $features->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection