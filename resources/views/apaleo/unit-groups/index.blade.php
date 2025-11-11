@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Apaleo Unit Groups</h4>
                    <a href="{{ route('apaleo-unit-groups.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Unit Group
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

                    @if($unitGroups->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped sortable-table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Unit Group Name</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Max Persons</th>
                                    <th>Units</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unitGroups as $unitGroup)
                                <tr>
                                    <td>
                                        @if($unitGroup->property)
                                            <strong>{{ $unitGroup->property->name }}</strong>
                                            <br><small class="text-muted">{{ $unitGroup->property->city }}</small>
                                        @else
                                            <span class="text-danger">Property not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $unitGroup->name }}</strong>
                                        @if($unitGroup->description)
                                            <br><small class="text-muted">{{ Str::limit($unitGroup->description, 40) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($unitGroup->code)
                                            <span class="badge bg-secondary">{{ $unitGroup->code }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $unitGroup->type ?? '-' }}</td>
                                    <td>
                                        @if($unitGroup->max_persons)
                                            {{ $unitGroup->max_persons }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $unitGroup->units->count() }}
                                    </td>
                                    <td>
                                        <div class="btn-group gap-1" role="group">
                                            <a href="{{ route('apaleo-unit-groups.show', $unitGroup) }}" 
                                               class="btn btn-outline-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('apaleo-unit-groups.edit', $unitGroup) }}" 
                                               class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('apaleo-unit-groups.destroy', $unitGroup) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this unit group?')">
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
                        {{ $unitGroups->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                    </div>
                    @else
                    <div class="alert alert-info" role="alert">
                        <h5>No Unit Groups Found</h5>
                        <p>No Apaleo unit groups have been imported yet. Use the dashboard import feature to import data from Apaleo.</p>
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