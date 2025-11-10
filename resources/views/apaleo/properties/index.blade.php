@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Apaleo Properties</h4>
                    <a href="{{ route('apaleo-properties.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Property
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($properties->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped sortable-table">
                            <thead>
                                <tr>
                                    <th>Apaleo ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th>Unit Groups</th>
                                    <th>Units</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($properties as $property)
                                <tr>
                                    <td>
                                        <code>{{ $property->apaleo_id }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $property->name }}</strong>
                                        @if($property->description)
                                            <br><small class="text-muted">{{ Str::limit($property->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($property->code)
                                            <span class="badge bg-secondary">{{ $property->code }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $property->city ?? '-' }}</td>
                                    <td>
                                        @if($property->country_code)
                                            {{ strtoupper($property->country_code) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($property->status)
                                            <span class="badge bg-{{ $property->status === 'Live' ? 'success' : ($property->status === 'Test' ? 'info' : 'warning') }}">
                                                {{ $property->status }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $property->unitGroups->count() }}
                                    </td>
                                    <td>
                                        {{ $property->units->count() }}
                                    </td>
                                    <td>
                                        <div class="btn-group gap-1" role="group">
                                            <a href="{{ route('apaleo-properties.show', $property) }}" 
                                               class="btn btn-outline-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('apaleo-properties.edit', $property) }}" 
                                               class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('apaleo-properties.destroy', $property) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this property?')">
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
                        {{ $properties->links() }}
                    </div>
                    @else
                    <div class="alert alert-info" role="alert">
                        <h5>No Properties Found</h5>
                        <p>No Apaleo properties have been imported yet. Use the dashboard import feature to import data from Apaleo.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Go to Dashboard
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection