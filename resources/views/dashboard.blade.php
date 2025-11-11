@extends('layouts.app')

@section('styles')
<style>
.import-btn {
    transition: all 0.3s ease;
    min-width: 200px;
}

.progress {
    height: 25px;
    border-radius: 15px;
    background-color: rgba(0,0,0,0.1);
}

.progress-bar {
    border-radius: 15px;
    transition: width 0.3s ease;
    font-weight: bold;
    line-height: 25px;
    color: white;
    text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
}

.card {
    transition: all 0.3s ease;
}

.import-success {
    animation: pulse-success 2s infinite;
}

.import-error {
    animation: pulse-error 2s infinite;
}

@keyframes pulse-success {
    0% { box-shadow: 0 0 10px rgba(40, 167, 69, 0.3); }
    50% { box-shadow: 0 0 25px rgba(40, 167, 69, 0.6); }
    100% { box-shadow: 0 0 10px rgba(40, 167, 69, 0.3); }
}

@keyframes pulse-error {
    0% { box-shadow: 0 0 10px rgba(220, 53, 69, 0.3); }
    50% { box-shadow: 0 0 25px rgba(220, 53, 69, 0.6); }
    100% { box-shadow: 0 0 10px rgba(220, 53, 69, 0.3); }
}

.status-message {
    min-height: 20px;
    padding: 5px 0;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Hotel Master Data Import Dashboard</h4>
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

                    @if (session('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif

                    <!-- Toast Notification Container -->
                    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>

                    <!-- PMS Import Section -->
                    @if(auth()->user()->can('import_data'))
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>PMS Data Import</h5>
                            <p class="text-muted">Import data from Property Management Systems</p>
                            
                            <div class="row">
                                @foreach($availablePmsAdapters as $slug => $adapter)
                                    @if($adapter['enabled'])
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">{{ $adapter['name'] }}</h6>
                                                <form method="POST" action="{{ route('dashboard.import', $slug) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-primary import-btn" 
                                                            data-pms="{{ $slug }}"
                                                            @if(($slug === 'apaleo' && $apaleoHasData) || ($slug === 'mews' && $mewsHasData)) disabled @endif>
                                                        Import from {{ $adapter['name'] }}
                                                    </button>
                                                </form>
                                                @if($slug === 'apaleo' && $apaleoHasData)
                                                    <small class="text-muted d-block mt-2">
                                                        <i class="fas fa-info-circle"></i> Data already imported
                                                    </small>
                                                @endif
                                                @if($slug === 'mews' && $mewsHasData)
                                                    <small class="text-muted d-block mt-2">
                                                        <i class="fas fa-info-circle"></i> Data already imported
                                                    </small>
                                                @endif
                                                <div class="mt-2">
                                                    <div class="progress" style="display: none;" id="progress-{{ $slug }}">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                                    </div>
                                                    <div class="status-message">
                                                        <small id="status-{{ $slug }}"></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- PMS-Specific Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>PMS-Specific Data</h5>
                            <p class="text-muted">Data breakdown by Property Management System</p>
                            
                            <div class="row">
                                <!-- Apaleo Statistics -->
                                @if(isset($stats['apaleo']) && ($stats['apaleo']['properties'] > 0 || $stats['apaleo']['unit_types'] > 0 || $stats['apaleo']['units'] > 0 || $stats['apaleo']['unit_attributes'] > 0))
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-hotel"></i> Apaleo Data
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <h5 class="text-primary">{{ $stats['apaleo']['properties'] }}</h5>
                                                        <small class="text-muted">Properties</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <h5 class="text-info">{{ $stats['apaleo']['unit_types'] }}</h5>
                                                        <small class="text-muted">Unit Groups</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 mt-2">
                                                    <div class="text-center">
                                                        <h5 class="text-success">{{ $stats['apaleo']['units'] }}</h5>
                                                        <small class="text-muted">Units</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 mt-2">
                                                    <div class="text-center">
                                                        <h5 class="text-warning">{{ $stats['apaleo']['unit_attributes'] }}</h5>
                                                        <small class="text-muted">Attributes</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Mews Statistics -->
                                @if(isset($stats['mews']) && ($stats['mews']['enterprises'] > 0 || $stats['mews']['services'] > 0 || $stats['mews']['resource_categories'] > 0 || $stats['mews']['resources'] > 0 || $stats['mews']['resource_features'] > 0))
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-building"></i> Mews Data
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <h5 class="text-primary">{{ $stats['mews']['enterprises'] }}</h5>
                                                        <small class="text-muted">Enterprises</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center">
                                                        <h5 class="text-info">{{ $stats['mews']['services'] }}</h5>
                                                        <small class="text-muted">Services</small>
                                                    </div>
                                                </div>
                                                <div class="col-4 mt-2">
                                                    <div class="text-center">
                                                        <h5 class="text-success">{{ $stats['mews']['resource_categories'] }}</h5>
                                                        <small class="text-muted">Categories</small>
                                                    </div>
                                                </div>
                                                <div class="col-4 mt-2">
                                                    <div class="text-center">
                                                        <h5 class="text-warning">{{ $stats['mews']['resources'] }}</h5>
                                                        <small class="text-muted">Resources</small>
                                                    </div>
                                                </div>
                                                <div class="col-4 mt-2">
                                                    <div class="text-center">
                                                        <h5 class="text-secondary">{{ $stats['mews']['resource_features'] }}</h5>
                                                        <small class="text-muted">Features</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Quick Access Links -->
                            @if(auth()->user()->can('view_data'))
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Quick Access</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(isset($stats['mews']) && $stats['mews']['enterprises'] > 0)
                                            <a href="{{ route('mews-enterprises.index') }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-building"></i> Mews Enterprises ({{ $stats['mews']['enterprises'] }})
                                            </a>
                                            <a href="{{ route('mews-resource-categories.index') }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-tags"></i> Resource Categories ({{ $stats['mews']['resource_categories'] }})
                                            </a>
                                            <a href="{{ route('mews-resources.index') }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-door-open"></i> Resources ({{ $stats['mews']['resources'] }})
                                            </a>
                                            <a href="{{ route('mews-resource-features.index') }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-star"></i> Features ({{ $stats['mews']['resource_features'] }})
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistics Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Data Statistics</h5>
                            <p class="text-muted">
                                @if(auth()->user()->isSuperAdmin())
                                    System-wide statistics
                                @else
                                    Statistics for {{ auth()->user()->hotel ? auth()->user()->hotel->name : 'your hotel' }}
                                @endif
                            </p>
                            
                            <div class="row">
                                <!-- First row of cards -->
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title">Hotels</h6>
                                                    <h3>{{ $stats['hotels'] ?? 0 }}</h3>
                                                </div>
                                                <div>
                                                    <i class="fas fa-hotel fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title">Buildings</h6>
                                                    <h3>{{ $stats['buildings'] ?? 0 }}</h3>
                                                </div>
                                                <div>
                                                    <i class="fas fa-building fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title">Floors</h6>
                                                    <h3>{{ $stats['floors'] ?? 0 }}</h3>
                                                </div>
                                                <div>
                                                    <i class="fas fa-layer-group fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title">Room Types</h6>
                                                    <h3>{{ $stats['room_types'] ?? 0 }}</h3>
                                                </div>
                                                <div>
                                                    <i class="fas fa-bed fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Second row of cards -->
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title">Rooms</h6>
                                                    <h3>{{ $stats['rooms'] ?? 0 }}</h3>
                                                </div>
                                                <div>
                                                    <i class="fas fa-door-open fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title">Sunbed Areas</h6>
                                                    <h3>{{ $stats['sunbed_areas'] ?? 0 }}</h3>
                                                </div>
                                                <div>
                                                    <i class="fas fa-map-marker-alt fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title">Sunbed Types</h6>
                                                    <h3>{{ $stats['sunbed_types'] ?? 0 }}</h3>
                                                </div>
                                                <div>
                                                    <i class="fas fa-chair fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-secondary-subtle">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title">Sunbeds</h6>
                                                    <h3>{{ $stats['sunbeds'] ?? 0 }}</h3>
                                                </div>
                                                <div>
                                                    <i class="fas fa-umbrella-beach fa-2x"></i>
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
    </div>
</div>

@endsection

@section('scripts')
<script>
// Toast notification function
function showToast(message, type = 'success', duration = 5000) {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show`;
    toast.style.minWidth = '300px';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto-remove after duration
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, duration);
}

document.addEventListener('DOMContentLoaded', function() {
    // Monitor import progress
    const importBtns = document.querySelectorAll('.import-btn');
    
    importBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const pmsSlug = this.dataset.pms;
            
            // Disable button and show progress
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Import Starting...';
            this.className = 'btn btn-warning import-btn';
            
            const progressBar = document.getElementById('progress-' + pmsSlug);
            const statusText = document.getElementById('status-' + pmsSlug);
            
            progressBar.style.display = 'block';
            statusText.innerHTML = '<span class="text-info"><i class="fas fa-play"></i> Initializing import...</span>';
            
            // Show start toast
            showToast(`<i class="fas fa-play"></i> <strong>Import Started</strong><br><small>Importing data from ${pmsSlug.charAt(0).toUpperCase() + pmsSlug.slice(1)}...</small>`, 'info', 4000);
            
            // Start monitoring progress
            const interval = setInterval(() => {
                fetch(`/dashboard/import-status/${pmsSlug}`)
                    .then(response => response.json())
                    .then(data => {
                        const progress = data.progress || 0;
                        const progressBarElement = progressBar.querySelector('.progress-bar');
                        progressBarElement.style.width = progress + '%';
                        progressBarElement.textContent = progress + '%';
                        
                        if (data.step) {
                            statusText.innerHTML = `<span class="text-primary"><i class="fas fa-spinner fa-spin"></i> ${data.step}</span>`;
                        }
                        
                        if (data.status === 'completed') {
                            clearInterval(interval);
                            
                            // Show success state
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-check-circle"></i> Import Completed';
                            this.className = 'btn btn-success import-btn';
                            
                            // Show detailed results if available
                            let resultText = 'Import completed successfully!';
                            if (data.results) {
                                const results = data.results;
                                resultText = `✅ Import completed! Hotels: ${results.hotels || 0}, Room Types: ${results.room_types || 0}, Rooms: ${results.rooms || 0}, Room Attributes: ${results.room_attributes || 0}`;
                            }
                            statusText.innerHTML = `<span class="text-success"><strong>${resultText}</strong></span>`;
                            
                            // Hide progress bar
                            progressBar.style.display = 'none';
                            
                            // Add success animation
                            const card = this.closest('.card');
                            card.style.border = '2px solid #28a745';
                            card.style.boxShadow = '0 0 20px rgba(40, 167, 69, 0.3)';
                            card.classList.add('import-success');
                            
                            // Show toast notification
                            let toastMessage = '<i class="fas fa-check-circle"></i> <strong>Import Completed Successfully!</strong>';
                            if (data.results) {
                                const results = data.results;
                                toastMessage += `<br><small>Hotels: ${results.hotels || 0} | Room Types: ${results.room_types || 0} | Rooms: ${results.rooms || 0} | Attributes: ${results.room_attributes || 0}</small>`;
                            }
                            showToast(toastMessage, 'success', 8000);
                            
                            // Show "Refreshing..." message before reload
                            setTimeout(() => {
                                statusText.innerHTML = '<span class="text-info"><i class="fas fa-sync-alt fa-spin"></i> Refreshing dashboard...</span>';
                                showToast('<i class="fas fa-sync-alt fa-spin"></i> Updating dashboard statistics...', 'info', 3000);
                            }, 3000);
                            
                            // Reload page to update statistics
                            setTimeout(() => {
                                location.reload();
                            }, 4000);
                        } else if (data.status === 'failed') {
                            clearInterval(interval);
                            
                            // Show error state
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Import Failed';
                            this.className = 'btn btn-danger import-btn';
                            
                            statusText.innerHTML = `<span class="text-danger"><strong>❌ Import failed:</strong> ${data.error || 'Unknown error'}</span>`;
                            progressBar.style.display = 'none';
                            
                            // Add error styling to card
                            const card = this.closest('.card');
                            card.style.border = '2px solid #dc3545';
                            card.style.boxShadow = '0 0 20px rgba(220, 53, 69, 0.3)';
                            card.classList.add('import-error');
                            
                            // Show error toast
                            showToast(`<i class="fas fa-exclamation-triangle"></i> <strong>Import Failed!</strong><br><small>${data.error || 'Unknown error occurred'}</small>`, 'danger', 10000);
                            
                            // Reset button after 5 seconds
                            setTimeout(() => {
                                this.innerHTML = 'Import from ' + pmsSlug.charAt(0).toUpperCase() + pmsSlug.slice(1);
                                this.className = 'btn btn-primary import-btn';
                                statusText.textContent = '';
                                card.style.border = '';
                                card.style.boxShadow = '';
                                card.classList.remove('import-error');
                            }, 5000);
                        }
                    })
                    .catch(error => {
                        console.error('Error checking import status:', error);
                    });
            }, 2000); // Check every 2 seconds
        });
    });
});
</script>
@endsection