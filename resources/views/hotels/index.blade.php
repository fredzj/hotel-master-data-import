@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Transformed Hotels</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($hotels->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'code', 'direction' => request('sort') == 'code' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Code
                                            @if(request('sort') == 'code')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Name
                                            @if(request('sort') == 'name')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'company_name', 'direction' => request('sort') == 'company_name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Company
                                            @if(request('sort') == 'company_name')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'city', 'direction' => request('sort') == 'city' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Location
                                            @if(request('sort') == 'city')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>PMS System</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Status
                                            @if(request('sort') == 'status')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'currency', 'direction' => request('sort') == 'currency' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Currency
                                            @if(request('sort') == 'currency')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hotels as $hotel)
                                <tr>
                                    <td><code>{{ $hotel->code ?? $hotel->external_id }}</code></td>
                                    <td>
                                        <strong>{{ $hotel->name }}</strong>
                                        @if($hotel->is_template)
                                            <span class="badge bg-secondary ms-1">Template</span>
                                        @endif
                                        @if($hotel->is_archived)
                                            <span class="badge bg-dark ms-1">Archived</span>
                                        @endif
                                    </td>
                                    <td>{{ $hotel->company_name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $hotel->city ?? 'N/A' }}
                                        @if($hotel->city && $hotel->country)
                                            , {{ $hotel->country }}
                                        @elseif($hotel->country)
                                            {{ $hotel->country }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $hotel->pmsSystem->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $hotel->status === 'Active' ? 'success' : ($hotel->status === 'Test' ? 'warning' : 'secondary') }}">
                                            {{ $hotel->status ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>{{ $hotel->currency ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group gap-1" role="group">
                                            <a href="{{ route('hotels.show', $hotel) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('manage_hotels')
                                            <a href="{{ route('hotels.edit', $hotel) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('hotels.destroy', $hotel) }}" style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this hotel?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        <nav aria-label="Hotels pagination">
                            {{ $hotels->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <h6>No Hotels Found</h6>
                        <p>No hotels have been added yet. 
                        @can('manage_hotels')
                        <a href="{{ route('hotels.create') }}">Create your first hotel</a> 
                        @endcan
                        or import data from your PMS system using the <a href="{{ route('dashboard') }}">dashboard</a>.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection