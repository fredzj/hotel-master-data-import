@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Unit Attribute Details: {{ $apaleoUnitAttribute->name }}</h4>
                    <div>
                        <a href="{{ route('apaleo-unit-attributes.edit', $apaleoUnitAttribute) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('apaleo-unit-attributes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Unit Attributes
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
                            <h5>Attribute Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Attribute ID:</th>
                                    <td><code>{{ $apaleoUnitAttribute->id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $apaleoUnitAttribute->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Value:</th>
                                    <td>
                                        @if($apaleoUnitAttribute->value)
                                            {{ $apaleoUnitAttribute->value }}
                                        @else
                                            <span class="text-muted">No value set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>
                                        @if($apaleoUnitAttribute->type)
                                            <span class="badge bg-info">{{ $apaleoUnitAttribute->type }}</span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Unit of Measure:</th>
                                    <td>
                                        @if($apaleoUnitAttribute->unit_of_measure)
                                            <span class="badge bg-success">{{ $apaleoUnitAttribute->unit_of_measure }}</span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Related Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Unit:</th>
                                    <td>
                                        @if($apaleoUnitAttribute->unit)
                                            <strong>{{ $apaleoUnitAttribute->unit->name }}</strong>
                                            <br><small class="text-muted">Status: {{ $apaleoUnitAttribute->unit->status ?? 'Not set' }}</small>
                                            <br><a href="{{ route('apaleo-units.show', $apaleoUnitAttribute->unit) }}" class="btn btn-outline-primary btn-sm mt-1">
                                                <i class="fas fa-eye"></i> View Unit
                                            </a>
                                        @else
                                            <span class="text-danger">Unit not found</span>
                                        @endif
                                    </td>
                                </tr>
                                
                                @if($apaleoUnitAttribute->unit && $apaleoUnitAttribute->unit->unitGroup)
                                <tr>
                                    <th>Unit Group:</th>
                                    <td>
                                        <strong>{{ $apaleoUnitAttribute->unit->unitGroup->name }}</strong>
                                        <br><small class="text-muted">{{ $apaleoUnitAttribute->unit->unitGroup->type }}</small>
                                        <br><a href="{{ route('apaleo-unit-groups.show', $apaleoUnitAttribute->unit->unitGroup) }}" class="btn btn-outline-info btn-sm mt-1">
                                            <i class="fas fa-eye"></i> View Unit Group
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if($apaleoUnitAttribute->unit && $apaleoUnitAttribute->unit->property)
                                <tr>
                                    <th>Property:</th>
                                    <td>
                                        <strong>{{ $apaleoUnitAttribute->unit->property->name }}</strong>
                                        <br><small class="text-muted">{{ $apaleoUnitAttribute->unit->property->city }}, {{ $apaleoUnitAttribute->unit->property->country_code }}</small>
                                        <br><a href="{{ route('apaleo-properties.show', $apaleoUnitAttribute->unit->property) }}" class="btn btn-outline-secondary btn-sm mt-1">
                                            <i class="fas fa-eye"></i> View Property
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($apaleoUnitAttribute->raw_data)
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
                                    <pre><code>{{ json_encode($apaleoUnitAttribute->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <small class="text-muted">
                                Created: {{ $apaleoUnitAttribute->created_at->format('Y-m-d H:i:s') }} |
                                Updated: {{ $apaleoUnitAttribute->updated_at->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection