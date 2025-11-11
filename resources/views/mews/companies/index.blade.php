@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Mews Companies</h4>
                    <div>
                        <a href="{{ route('mews-companies.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Company
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($companies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover sortable-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Enterprise</th>
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th>Mother Company</th>
                                        <th>Last Import</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($companies as $company)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $company->name }}</div>
                                                @if($company->identifier)
                                                    <small class="text-muted">{{ $company->identifier }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->enterprise)
                                                    <a href="{{ route('mews-enterprises.show', $company->enterprise) }}">
                                                        {{ $company->enterprise->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->contact_email)
                                                    <div>
                                                        <i class="fas fa-envelope"></i>
                                                        <small>{{ $company->contact_email }}</small>
                                                    </div>
                                                @endif
                                                @if($company->telephone)
                                                    <div>
                                                        <i class="fas fa-phone"></i>
                                                        <small>{{ $company->telephone }}</small>
                                                    </div>
                                                @endif
                                                @if(!$company->contact_email && !$company->telephone)
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->city)
                                                    <div>{{ $company->city }}</div>
                                                    @if($company->country_code)
                                                        <small class="text-muted">{{ $company->country_code }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->motherCompany)
                                                    <a href="{{ route('mews-companies.show', $company->motherCompany) }}">
                                                        {{ $company->motherCompany->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->last_imported_at)
                                                    <small class="text-muted">
                                                        {{ $company->last_imported_at->format('M j, Y H:i') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group gap-1" role="group">
                                                    <a href="{{ route('mews-companies.show', $company) }}" 
                                                       class="btn btn-outline-info btn-sm" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('mews-companies.edit', $company) }}" 
                                                       class="btn btn-outline-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" 
                                                          action="{{ route('mews-companies.destroy', $company) }}" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Are you sure you want to delete this company?')">
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

                        <!-- Pagination -->
                        @if($companies instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center">
                                {{ $companies->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info" role="alert">
                            <h5>No Companies Found</h5>
                            <p>No Mews companies have been imported yet. Use the dashboard extraction feature to extract data from Mews.</p>
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
