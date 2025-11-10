@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Property Details: {{ $apaleoProperty->name }}</h4>
                    <div>
                        <a href="{{ route('apaleo-properties.edit', $apaleoProperty) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('apaleo-properties.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Properties
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
                                    <td><code>{{ $apaleoProperty->apaleo_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong>{{ $apaleoProperty->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Code:</th>
                                    <td>
                                        @if($apaleoProperty->code)
                                            <span class="badge bg-secondary">{{ $apaleoProperty->code }}</span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($apaleoProperty->status)
                                            <span class="badge bg-{{ $apaleoProperty->status === 'Live' ? 'success' : ($apaleoProperty->status === 'Test' ? 'info' : 'warning') }}">
                                                {{ $apaleoProperty->status }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $apaleoProperty->description ?? 'No description available' }}</td>
                                </tr>
                            </table>

                            <h5>Location Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Address:</th>
                                    <td>
                                        @if($apaleoProperty->address_line1)
                                            {{ $apaleoProperty->address_line1 }}<br>
                                            @if($apaleoProperty->address_line2)
                                                {{ $apaleoProperty->address_line2 }}<br>
                                            @endif
                                            @if($apaleoProperty->city || $apaleoProperty->postal_code)
                                                {{ $apaleoProperty->postal_code }} {{ $apaleoProperty->city }}<br>
                                            @endif
                                            @if($apaleoProperty->state)
                                                {{ $apaleoProperty->state }}<br>
                                            @endif
                                            @if($apaleoProperty->country_code)
                                                {{ strtoupper($apaleoProperty->country_code) }}
                                            @endif
                                        @else
                                            <span class="text-muted">No address information</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Timezone:</th>
                                    <td>{{ $apaleoProperty->timezone ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Currency:</th>
                                    <td>
                                        @if($apaleoProperty->currency_code)
                                            {{ $apaleoProperty->currency_code }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Company Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Company Name:</th>
                                    <td>{{ $apaleoProperty->company_name ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Tax ID:</th>
                                    <td>{{ $apaleoProperty->tax_id ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>Commercial Register:</th>
                                    <td>{{ $apaleoProperty->commercial_register_entry ?? 'Not set' }}</td>
                                </tr>
                            </table>

                            <h5>Banking Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Bank Name:</th>
                                    <td>{{ $apaleoProperty->bank_name ?? 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <th>IBAN:</th>
                                    <td>
                                        @if($apaleoProperty->iban)
                                            <code>{{ $apaleoProperty->iban }}</code>
                                        @else
                                            Not set
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>BIC:</th>
                                    <td>
                                        @if($apaleoProperty->bic)
                                            <code>{{ $apaleoProperty->bic }}</code>
                                        @else
                                            Not set
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <h5>Statistics</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body text-center">
                                            <h3>{{ $apaleoProperty->unitGroups->count() }}</h3>
                                            <p class="mb-0">Unit Groups</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body text-center">
                                            <h3>{{ $apaleoProperty->units->count() }}</h3>
                                            <p class="mb-0">Units</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($apaleoProperty->unitGroups->count() > 0)
                    <hr>
                    <h5>Unit Groups</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Max Persons</th>
                                    <th>Member Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apaleoProperty->unitGroups as $unitGroup)
                                <tr>
                                    <td>{{ $unitGroup->name }}</td>
                                    <td>
                                        @if($unitGroup->code)
                                            <span class="badge bg-secondary">{{ $unitGroup->code }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $unitGroup->type ?? '-' }}</td>
                                    <td>{{ $unitGroup->max_persons ?? '-' }}</td>
                                    <td>{{ $unitGroup->member_count ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('apaleo-unit-groups.show', $unitGroup) }}" 
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

                    @if($apaleoProperty->units->count() > 0)
                    <hr>
                    <h5>Units (Showing first 10)</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Unit Group</th>
                                    <th>Status</th>
                                    <th>Max Persons</th>
                                    <th>Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apaleoProperty->units->take(10) as $unit)
                                <tr>
                                    <td>{{ $unit->name }}</td>
                                    <td>
                                        @if($unit->unitGroup)
                                            <span class="badge bg-info">{{ $unit->unitGroup->name }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $unit->status ?? '-' }}</td>
                                    <td>{{ $unit->max_persons ?? '-' }}</td>
                                    <td>{{ $unit->size ?? '-' }}</td>
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
                        @if($apaleoProperty->units->count() > 10)
                        <p class="text-muted">
                            Showing 10 of {{ $apaleoProperty->units->count() }} units. 
                            <a href="{{ route('apaleo-units.index', ['property_id' => $apaleoProperty->apaleo_id]) }}">View all units</a>
                        </p>
                        @endif
                    </div>
                    @endif

                    @if($apaleoProperty->raw_data)
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
                                    <pre><code>{{ json_encode($apaleoProperty->raw_data, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <small class="text-muted">
                                Created: {{ $apaleoProperty->created_at->format('Y-m-d H:i:s') }} |
                                Updated: {{ $apaleoProperty->updated_at->format('Y-m-d H:i:s') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection