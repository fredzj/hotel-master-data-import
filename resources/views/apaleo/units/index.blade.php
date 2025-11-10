@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Apaleo Units</h4>
                    <a href="{{ route('apaleo-units.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Unit
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

                    @if($units->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped sortable-table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Unit Name</th>
                                    <th>Unit Group</th>
                                    <th>Status</th>
                                    <th>Max Persons</th>
                                    <th>Size</th>
                                    <th>Attributes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $unit)
                                <tr>
                                    <td>
                                        @if($unit->property)
                                            <strong>{{ $unit->property->name }}</strong>
                                        @else
                                            <span class="text-danger">Property not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $unit->name }}</strong>
                                        @if($unit->description)
                                            <br><small class="text-muted">{{ Str::limit($unit->description, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($unit->unitGroup)
                                            {{ $unit->unitGroup->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($unit->status)
                                            <span class="badge bg-{{ $unit->status === 'Vacant' ? 'success' : ($unit->status === 'Occupied' ? 'danger' : 'warning') }}">
                                                {{ $unit->status }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($unit->max_persons)
                                            {{ $unit->max_persons }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($unit->size)
                                            {{ $unit->size }} m²
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $unit->attributes->count() }}
                                    </td>
                                    <td>
                                        <div class="btn-group gap-1" role="group">
                                            <a href="{{ route('apaleo-units.show', $unit) }}" 
                                               class="btn btn-outline-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('apaleo-units.edit', $unit) }}" 
                                               class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('apaleo-units.destroy', $unit) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this unit?')">
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
                        {{ $units->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                    </div>
                    @else
                    <div class="alert alert-info" role="alert">
                        <h5>No Units Found</h5>
                        <p>No Apaleo units have been imported yet. Use the dashboard import feature to import data from Apaleo.</p>
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