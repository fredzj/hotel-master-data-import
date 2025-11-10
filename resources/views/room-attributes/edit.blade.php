@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Edit Room Attribute</h4>
                    <a href="{{ route('room-attributes.show', $roomAttribute) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Room attribute editing functionality is coming soon. Currently, room attributes are managed through the PMS import process.
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>Current Attribute Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 30%;">Name:</th>
                                    <td>{{ $roomAttribute->name }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ $roomAttribute->attribute_type }}</td>
                                </tr>
                                <tr>
                                    <th>Value:</th>
                                    <td>
                                        @if(is_array($roomAttribute->value))
                                            <code>{{ json_encode($roomAttribute->value) }}</code>
                                        @else
                                            {{ $roomAttribute->value }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection