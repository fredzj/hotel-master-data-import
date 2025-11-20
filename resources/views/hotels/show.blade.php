@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Hotel Details: {{ $hotel->name }}</h4>
                    <div>
                        @can('manage_hotels')
                            <a href="{{ route('hotels.edit', $hotel) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endcan
                        <a href="{{ route('hotels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Hotels
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Basic Information Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Basic Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Hotel Name:</strong></td>
                                    <td>{{ $hotel->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td>{{ $hotel->code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>External ID:</strong></td>
                                    <td>{{ $hotel->external_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>PMS System:</strong></td>
                                    <td>{{ $hotel->pmsSystem->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge text-bg-{{ $hotel->status === 'Active' ? 'success' : ($hotel->status === 'Test' ? 'warning' : 'secondary') }}">
                                            {{ $hotel->status ?? 'Unknown' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Template:</strong></td>
                                    <td>{{ $hotel->is_template ? 'Yes' : 'No' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Archived:</strong></td>
                                    <td>{{ $hotel->is_archived ? 'Yes' : 'No' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Location & Contact</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $hotel->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City:</strong></td>
                                    <td>{{ $hotel->city ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Country:</strong></td>
                                    <td>{{ $hotel->country ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Postal Code:</strong></td>
                                    <td>{{ $hotel->postal_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $hotel->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $hotel->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Website:</strong></td>
                                    <td>
                                        @if($hotel->website)
                                            <a href="{{ $hotel->website }}" target="_blank">{{ $hotel->website }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Timezone:</strong></td>
                                    <td>{{ $hotel->timezone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Currency:</strong></td>
                                    <td>{{ $hotel->currency ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Company Information Row -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Company Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Company Name:</strong></td>
                                    <td>{{ $hotel->company_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Commercial Register:</strong></td>
                                    <td>{{ $hotel->commercial_register_entry ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax ID:</strong></td>
                                    <td>{{ $hotel->tax_id ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Banking Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Bank Name:</strong></td>
                                    <td>{{ $hotel->bank_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>IBAN:</strong></td>
                                    <td>{{ $hotel->bank_iban ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>BIC:</strong></td>
                                    <td>{{ $hotel->bank_bic ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Terms -->
                    @if($hotel->payment_terms)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-muted">Payment Terms</h6>
                                <div class="row">
                                    @foreach($hotel->payment_terms as $lang => $term)
                                        <div class="col-md-6">
                                            <strong>{{ strtoupper($lang) }}:</strong> {{ $term }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- System Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted">System Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Created in PMS:</strong><br>
                                    {{ $hotel->external_created_at ? $hotel->external_created_at->format('M d, Y H:i') : 'N/A' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Imported:</strong><br>
                                    {{ $hotel->created_at->format('M d, Y H:i') }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Last Updated:</strong><br>
                                    {{ $hotel->updated_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($hotel->description)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-muted">Description</h6>
                                <p class="text-muted">{{ $hotel->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Related Data Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-muted">Related Data</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $hotel->buildings->count() }}</h5>
                                            <p class="card-text">Buildings</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $hotel->roomTypes->count() }}</h5>
                                            <p class="card-text">Room Types</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $hotel->rooms->count() }}</h5>
                                            <p class="card-text">Rooms</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">0</h5>
                                            <p class="card-text">Sunbed Areas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection