<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    <style>
        /* Fix pagination arrow sizes */
        .pagination .page-link {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .pagination .page-link svg {
            width: 0.75rem !important;
            height: 0.75rem !important;
            max-width: 0.75rem;
            max-height: 0.75rem;
        }
        
        /* Control pagination arrow icons specifically */
        .pagination .page-link[aria-label*="Previous"] svg,
        .pagination .page-link[aria-label*="Next"] svg {
            width: 0.5rem !important;
            height: 0.5rem !important;
        }
        
        /* Ensure pagination text is readable */
        .pagination .page-link {
            line-height: 1.2;
            min-height: 2.25rem;
        }
        
        /* Better pagination spacing */
        .pagination {
            margin-bottom: 0;
        }
        
        .pagination .page-item {
            margin: 0 2px;
        }
        
        /* Ensure consistent button sizing in tables */
        .table .btn-group .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Improve table responsiveness */
        .table-responsive {
            border-radius: 0.375rem;
        }
        
        /* Better spacing for action buttons */
        .btn-group .btn {
            margin-right: 0;
        }
        
        /* Fix any oversized icons in general */
        .pagination svg {
            vertical-align: middle;
        }
        
        /* Theme-responsive navbar styling */
        .navbar {
            background-color: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-border-color);
        }
        
        .navbar .navbar-brand,
        .navbar .nav-link {
            color: var(--bs-body-color) !important;
        }
        
        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: var(--bs-primary) !important;
        }
        
        .navbar-toggler {
            border-color: var(--bs-border-color);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='#{to-rgb(var(--bs-body-color))}' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Dark theme specific navbar adjustments */
        [data-bs-theme="dark"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Dropdown menu theme support */
        .dropdown-menu {
            background-color: var(--bs-body-bg);
            border-color: var(--bs-border-color);
        }
        
        .dropdown-item {
            color: var(--bs-body-color);
        }
        
        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm" data-bs-theme-target="navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        
                        @if($apaleoHasData ?? false)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="apaleoDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-building"></i> Apaleo Data
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('apaleo-properties.index') }}">
                                    <i class="fas fa-hotel"></i> Properties
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('apaleo-unit-groups.index') }}">
                                    <i class="fas fa-layer-group"></i> Unit Groups
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('apaleo-units.index') }}">
                                    <i class="fas fa-door-open"></i> Units
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('apaleo-unit-attributes.index') }}">
                                    <i class="fas fa-tags"></i> Unit Attributes
                                </a></li>
                            </ul>
                        </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="mewsDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-server"></i> Mews Data
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('mews-enterprises.index') }}">
                                    <i class="fas fa-building"></i> Enterprises
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('mews-companies.index') }}">
                                    <i class="fas fa-briefcase"></i> Companies
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('mews-services.index') }}">
                                    <i class="fas fa-concierge-bell"></i> Services
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('mews-resource-categories.index') }}">
                                    <i class="fas fa-th-list"></i> Resource Categories
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('mews-resources.index') }}">
                                    <i class="fas fa-cube"></i> Resources
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('mews-resource-features.index') }}">
                                    <i class="fas fa-star"></i> Resource Features
                                </a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="legacyDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-database"></i> Transformed Data
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('hotels.index') }}">
                                    <i class="fas fa-building"></i> Hotels
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('room-types.index') }}">
                                    <i class="fas fa-bed"></i> Room Types
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('rooms.index') }}">
                                    <i class="fas fa-door-closed"></i> Rooms
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('room-attributes.index') }}">
                                    <i class="fas fa-list-alt"></i> Room Attributes
                                </a></li>
                            </ul>
                        </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Theme Switcher -->
                        <li class="nav-item dropdown">
                            <a id="themeSwitcher" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-palette me-1"></i>
                                <span id="currentTheme">System</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="themeSwitcher">
                                <a class="dropdown-item theme-option" href="#" data-theme="light">
                                    <i class="fas fa-sun me-2"></i> Light
                                </a>
                                <a class="dropdown-item theme-option" href="#" data-theme="dark">
                                    <i class="fas fa-moon me-2"></i> Dark
                                </a>
                                <a class="dropdown-item theme-option" href="#" data-theme="system">
                                    <i class="fas fa-desktop me-2"></i> System
                                </a>
                            </div>
                        </li>

                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
    <!-- Theme Switcher Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeOptions = document.querySelectorAll('.theme-option');
            const currentThemeSpan = document.getElementById('currentTheme');
            const htmlElement = document.documentElement;
            
            // Get saved theme from localStorage or default to 'system'
            const savedTheme = localStorage.getItem('theme') || 'system';
            
            // Apply theme on page load
            applyTheme(savedTheme);
            updateCurrentThemeDisplay(savedTheme);
            
            // Add click event listeners to theme options
            themeOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedTheme = this.getAttribute('data-theme');
                    
                    // Save theme to localStorage
                    localStorage.setItem('theme', selectedTheme);
                    
                    // Apply the theme
                    applyTheme(selectedTheme);
                    updateCurrentThemeDisplay(selectedTheme);
                });
            });
            
            function applyTheme(theme) {
                if (theme === 'system') {
                    // Remove any explicit theme and let system preference take over
                    htmlElement.removeAttribute('data-bs-theme');
                    // Check system preference
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        htmlElement.setAttribute('data-bs-theme', 'dark');
                    }
                } else {
                    htmlElement.setAttribute('data-bs-theme', theme);
                }
            }
            
            function updateCurrentThemeDisplay(theme) {
                const themeNames = {
                    'light': 'Light',
                    'dark': 'Dark',
                    'system': 'System'
                };
                currentThemeSpan.textContent = themeNames[theme] || 'System';
            }
            
            // Listen for system theme changes when using 'system' setting
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                const currentTheme = localStorage.getItem('theme') || 'system';
                if (currentTheme === 'system') {
                    applyTheme('system');
                }
            });
        });
    </script>
    
    <!-- Table Sorting Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add sorting functionality to all tables with class 'sortable-table'
            const tables = document.querySelectorAll('.sortable-table');
            
            tables.forEach(table => {
                const headers = table.querySelectorAll('thead th');
                
                headers.forEach((header, index) => {
                    // Skip action columns and other non-sortable columns
                    if (header.textContent.toLowerCase().includes('action') || 
                        header.classList.contains('no-sort')) {
                        return;
                    }
                    
                    // Add sorting cursor and click event
                    header.style.cursor = 'pointer';
                    header.style.userSelect = 'none';
                    header.innerHTML += ' <i class="fas fa-sort text-muted ms-1"></i>';
                    
                    header.addEventListener('click', function() {
                        sortTable(table, index);
                    });
                });
            });
            
            function sortTable(table, columnIndex) {
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const header = table.querySelectorAll('thead th')[columnIndex];
                const icon = header.querySelector('i');
                
                // Reset all other column icons
                table.querySelectorAll('thead th i').forEach(i => {
                    if (i !== icon) {
                        i.className = 'fas fa-sort text-muted ms-1';
                    }
                });
                
                // Determine sort direction
                let ascending = true;
                if (icon.classList.contains('fa-sort-up')) {
                    ascending = false;
                    icon.className = 'fas fa-sort-down text-primary ms-1';
                } else {
                    ascending = true;
                    icon.className = 'fas fa-sort-up text-primary ms-1';
                }
                
                // Sort rows
                rows.sort((a, b) => {
                    const aValue = getCellValue(a, columnIndex);
                    const bValue = getCellValue(b, columnIndex);
                    
                    // Handle numeric values
                    const aNum = parseFloat(aValue);
                    const bNum = parseFloat(bValue);
                    if (!isNaN(aNum) && !isNaN(bNum)) {
                        return ascending ? aNum - bNum : bNum - aNum;
                    }
                    
                    // Handle text values
                    return ascending ? 
                        aValue.localeCompare(bValue) : 
                        bValue.localeCompare(aValue);
                });
                
                // Reorder rows in DOM
                rows.forEach(row => tbody.appendChild(row));
            }
            
            function getCellValue(row, columnIndex) {
                const cell = row.cells[columnIndex];
                if (!cell) return '';
                
                // Get text content, removing extra whitespace
                let value = cell.textContent || cell.innerText || '';
                value = value.trim();
                
                // Handle common patterns
                if (value.toLowerCase() === 'active') return '1';
                if (value.toLowerCase() === 'inactive') return '0';
                
                return value;
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
