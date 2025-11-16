@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Transformed Room Types</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($roomTypes->count() > 0)
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
                                    <th>Hotel</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'type', 'direction' => request('sort') == 'type' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Type
                                            @if(request('sort') == 'type')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'max_occupancy', 'direction' => request('sort') == 'max_occupancy' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Max Occupancy
                                            @if(request('sort') == 'max_occupancy')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'units_available', 'direction' => request('sort') == 'units_available' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-body">
                                            Units Available
                                            @if(request('sort') == 'units_available')
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
                                @foreach($roomTypes as $roomType)
                                <tr>
                                    <td><code>{{ $roomType->code ?? $roomType->external_id }}</code></td>
                                    <td>
                                        <strong>{{ $roomType->name }}</strong>
                                        @if($roomType->description)
                                            <br><small class="text-muted">{{ Str::limit($roomType->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('hotels.show', $roomType->hotel) }}">
                                            {{ $roomType->hotel->name }}
                                        </a>
                                        <br><small class="text-muted">{{ $roomType->hotel->code }}</small>
                                    </td>
                                    <td>
                                        {{ $roomType->type ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $roomType->max_occupancy ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $roomType->member_count ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group gap-1" role="group">
                                            <a href="{{ route('room-types.show', $roomType) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit_room_types')
                                                <a href="{{ route('room-types.edit', $roomType) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
                        <nav aria-label="Room types pagination">
                            {{ $roomTypes->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>

                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Room Types Found</h5>
                        <p class="text-muted">Import room types from your PMS to get started.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            Go to Dashboard
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection