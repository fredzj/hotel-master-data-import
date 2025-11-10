@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Unit Group Details: {{ $apaleoUnitGroup->name }}</h4>
                    <div>
                        <a href="{{ route('apaleo-unit-groups.edit', $apaleoUnitGroup) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('apaleo-unit-groups.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Unit Groups
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Apaleo ID:</th>
                                    <td><code>{{ $apaleoUnitGroup->apaleo_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $apaleoUnitGroup->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Code:</th>
                                    <td>
                                        @if($apaleoUnitGroup->code)
                                            <span class="badge bg-secondary">{{ $apaleoUnitGroup->code }}</span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>
                                        @if($apaleoUnitGroup->type)
                                            {{ $apaleoUnitGroup->type }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $apaleoUnitGroup->description ?? 'No description available' }}</td>
                                </tr>
                            </table>

                            <h5>Property Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Property:</th>
                                    <td>
                                        @if($apaleoUnitGroup->property)
                                            <strong>{{ $apaleoUnitGroup->property->name }}</strong>
                                            <br><small class="text-muted">{{ $apaleoUnitGroup->property->city }}, {{ $apaleoUnitGroup->property->country_code }}</small>
                                            <br><a href="{{ route('apaleo-properties.show', $apaleoUnitGroup->property) }}" class="btn btn-outline-primary btn-sm mt-1">
                                                <i class="fas fa-eye"></i> View Property
                                            </a>
                                        @else
                                            <span class="text-danger">Property not found</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Capacity Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Max Persons:</th>
                                    <td>
                                        @if($apaleoUnitGroup->max_persons)
                                            {{ $apaleoUnitGroup->max_persons }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Member Count:</th>
                                    <td>
                                        @if($apaleoUnitGroup->member_count)
                                            {{ $apaleoUnitGroup->member_count }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <h5>Statistics</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body text-center">
                                            <h3>{{ $apaleoUnitGroup->units->count() }}</h3>
                                            <p class="mb-0">Units in this Group</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($apaleoUnitGroup->units->count() > 0)
                    <hr>
                    <h5>Units in this Group</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Unit Name</th>
                                    <th>Status</th>
                                    <th>Condition</th>
                                    <th>Max Persons</th>
                                    <th>Size</th>
                                    <th>View</th>
                                    <th>Attributes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apaleoUnitGroup->units as $unit)
                                <tr>
                                    <td><strong>{{ $unit->name }}</strong></td>
                                    <td>
                                        @if($unit->status)
                                            <span class="badge bg-{{ $unit->status === 'Vacant' ? 'success' : ($unit->status === 'Occupied' ? 'danger' : 'warning') }}">{{ $unit->status }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $unit->condition ?? '-' }}</td>
                                    <td>
                                        @if($unit->max_persons)
                                            <span class="badge bg-info">{{ $unit->max_persons }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($unit->size)
                                            {{ $unit->size }} m²
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $unit->view ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $unit->attributes->count() }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('apaleo-units.show', $unit) }}" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if($apaleoUnitGroup->raw_data)
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
                                    <pre><code>{{ json_encode($apaleoUnitGroup->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <small class="text-muted">
                                Created: {{ $apaleoUnitGroup->created_at->format('Y-m-d H:i:s') }} |
                                Updated: {{ $apaleoUnitGroup->updated_at->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection