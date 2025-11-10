@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Create Room Attribute</h4>
                    <a href="{{ route('room-attributes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Manual room attribute creation is coming soon. Currently, room attributes are imported automatically from your PMS system.
                    </div>

                    <div class="text-center">
                        <p class="text-muted">Room attributes are currently managed through the automated import process from your Property Management System.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Go to Import Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection