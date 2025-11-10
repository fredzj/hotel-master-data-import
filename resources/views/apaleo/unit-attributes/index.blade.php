@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Apaleo Unit Attributes</h4>
                    <a href="{{ route('apaleo-unit-attributes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Unit Attribute
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

                    @if($attributes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped sortable-table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Unit</th>
                                    <th>Unit Group</th>
                                    <th>Attribute Name</th>
                                    <th>Value</th>
                                    <th>Type</th>
                                    <th>Unit of Measure</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attributes as $attribute)
                                <tr>
                                    <td>
                                        @if($attribute->unit && $attribute->unit->property)
                                            <strong>{{ $attribute->unit->property->name }}</strong>
                                        @else
                                            <span class="text-danger">Property not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attribute->unit)
                                            <strong>{{ $attribute->unit->name }}</strong>
                                        @else
                                            <span class="text-danger">Unit not found</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attribute->unit && $attribute->unit->unitGroup)
                                            {{ $attribute->unit->unitGroup->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $attribute->name }}</strong>
                                    </td>
                                    <td>
                                        @if($attribute->value)
                                            {{ Str::limit($attribute->value, 30) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attribute->type)
                                            <span class="badge bg-secondary">{{ $attribute->type }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attribute->unit_of_measure)
                                            <span class="badge bg-success">{{ $attribute->unit_of_measure }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group gap-1" role="group">
                                            <a href="{{ route('apaleo-unit-attributes.show', $attribute) }}" 
                                               class="btn btn-outline-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('apaleo-unit-attributes.edit', $attribute) }}" 
                                               class="btn btn-outline-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('apaleo-unit-attributes.destroy', $attribute) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this attribute?')">
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
                        {{ $attributes->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                    </div>
                    @else
                    <div class="alert alert-info" role="alert">
                        <h5>No Unit Attributes Found</h5>
                        <p>No Apaleo unit attributes have been imported yet. Use the dashboard import feature to import data from Apaleo.</p>
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