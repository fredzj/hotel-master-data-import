@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Mews Enterprises</h4>
                    <div>
                        <a href="{{ route('mews-enterprises.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Enterprise
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($enterprises->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover sortable-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Time Zone</th>
                                        <th>Services</th>
                                        <th>Resources</th>
                                        <th>Last Import</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enterprises as $enterprise)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $enterprise->name }}</div>
                                                @if($enterprise->external_identifier)
                                                    <small class="text-muted">ID: {{ $enterprise->external_identifier }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($enterprise->city)
                                                    <div>{{ $enterprise->city }}</div>
                                                    @if($enterprise->country_code)
                                                        <small class="text-muted">{{ $enterprise->country_code }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $enterprise->time_zone_identifier }}</small>
                                            </td>
                                            <td>
                                                {{ $enterprise->services()->count() }}
                                            </td>
                                            <td>
                                                {{ $enterprise->resources()->count() }}
                                            </td>
                                            <td>
                                                @if($enterprise->last_imported_at)
                                                    <small class="text-muted">
                                                        {{ $enterprise->last_imported_at->format('M j, Y H:i') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group gap-1" role="group">
                                                    <a href="{{ route('mews-enterprises.show', $enterprise) }}" 
                                                       class="btn btn-outline-info btn-sm" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('mews-enterprises.edit', $enterprise) }}" 
                                                       class="btn btn-outline-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" 
                                                          action="{{ route('mews-enterprises.destroy', $enterprise) }}" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Are you sure you want to delete this enterprise?')">
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

                        <div class="d-flex justify-content-center">
                            {{ $enterprises->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No enterprises found.</p>
                            <a href="{{ route('mews-enterprises.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Enterprise
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection