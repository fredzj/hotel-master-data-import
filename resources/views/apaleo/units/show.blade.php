@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Unit Details: {{ $apaleoUnit->name }}</h4>
                    <div>
                        <a href="{{ route('apaleo-units.edit', $apaleoUnit) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('apaleo-units.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Units
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
                                    <td><code>{{ $apaleoUnit->apaleo_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $apaleoUnit->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($apaleoUnit->status)
                                            <span class="badge bg-{{ $apaleoUnit->status === 'Vacant' ? 'success' : ($apaleoUnit->status === 'Occupied' ? 'danger' : 'warning') }}">
                                                {{ $apaleoUnit->status }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Condition:</th>
                                    <td>{{ $apaleoUnit->condition ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $apaleoUnit->description ?? 'No description available' }}</td>
                                </tr>
                            </table>

                            <h5>Property & Unit Group</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Property:</th>
                                    <td>
                                        @if($apaleoUnit->property)
                                            <strong>{{ $apaleoUnit->property->name }}</strong>
                                            <br><small class="text-muted">{{ $apaleoUnit->property->city }}, {{ $apaleoUnit->property->country_code }}</small>
                                            <br><a href="{{ route('apaleo-properties.show', $apaleoUnit->property) }}" class="btn btn-outline-primary btn-sm mt-1">
                                                <i class="fas fa-eye"></i> View Property
                                            </a>
                                        @else
                                            <span class="text-danger">Property not found</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Unit Group:</th>
                                    <td>
                                        @if($apaleoUnit->unitGroup)
                                            <strong>{{ $apaleoUnit->unitGroup->name }}</strong>
                                            <br><small class="text-muted">{{ $apaleoUnit->unitGroup->type }}</small>
                                            <br><a href="{{ route('apaleo-unit-groups.show', $apaleoUnit->unitGroup) }}" class="btn btn-outline-info btn-sm mt-1">
                                                <i class="fas fa-eye"></i> View Unit Group
                                            </a>
                                        @else
                                            <span class="text-muted">No unit group assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Capacity & Features</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Max Persons:</th>
                                    <td>
                                        @if($apaleoUnit->max_persons)
                                            {{ $apaleoUnit->max_persons }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Size:</th>
                                    <td>
                                        @if($apaleoUnit->size)
                                            {{ $apaleoUnit->size }} m²
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>View:</th>
                                    <td>
                                        @if($apaleoUnit->view)
                                            <span class="badge bg-success">{{ $apaleoUnit->view }}</span>
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
                                            <h3>{{ $apaleoUnit->attributes->count() }}</h3>
                                            <p class="mb-0">Unit Attributes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($apaleoUnit->attributes->count() > 0)
                    <hr>
                    <h5>Unit Attributes</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Attribute Name</th>
                                    <th>Value</th>
                                    <th>Type</th>
                                    <th>Unit of Measure</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apaleoUnit->attributes as $attribute)
                                <tr>
                                    <td><strong>{{ $attribute->name }}</strong></td>
                                    <td>
                                        @if($attribute->value)
                                            {{ Str::limit($attribute->value, 40) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attribute->type)
                                            <span class="badge bg-secondary">{{ $attribute->type }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attribute->unit_of_measure)
                                            <span class="badge bg-success">{{ $attribute->unit_of_measure }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('apaleo-unit-attributes.show', $attribute) }}" 
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

                    @if($apaleoUnit->raw_data)
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
                                    <pre><code>{{ json_encode($apaleoUnit->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <small class="text-muted">
                                Created: {{ $apaleoUnit->created_at->format('Y-m-d H:i:s') }} |
                                Updated: {{ $apaleoUnit->updated_at->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection