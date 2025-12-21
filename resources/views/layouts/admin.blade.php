<!DOCTYPE html>
<html lan, g="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - Anseyo Restaurant')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/mazer/svg/favicon.svg') }}">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/mazer/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/mazer/css/app-dark.css') }}" id="dark">
    <link rel="stylesheet" href="{{ asset('assets/mazer/css/iconly.css') }}">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('assets/mazer/fonts/bootstrap-icons.woff2') }}">
    
    <!-- Custom Dark Mode Styles -->
    <style>
    /* Smooth transitions for theme switching */
    body, body * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
    
    /* Dark mode toggle animation */
    #toggle-dark {
        transition: all 0.3s ease;
        cursor: pointer !important;
        pointer-events: auto !important;
        z-index: 1000 !important;
    }
m/bni"''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''':                DQFDAQ'    
    /* Theme toggle icons */
    .theme-toggle svg {
        transition: opacity 0.3s ease;
    }
    
    /* Dark mode specific adjustments */
    body.dark .theme-toggle svg:first-child {
        opacity: 0.5;
    }
    
    body:not(.dark) .theme-toggle svg:last-child {
        opacity: 0.5;
    }
    
    /* Ensure toggle is clickable */
    .form-check-input {
        cursor: pointer !important;
        pointer-events: auto !important;
        position: relative !important;
        z-index: 1000 !important;
    }
    
    /* Override any Mazer styles that might interfere */
    #toggle-dark:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    }
    
    /* Force toggle to be interactive */
    .theme-toggle .form-check {
        pointer-events: auto !important;
    }
    
    .theme-toggle .form-check-input {
        pointer-events: auto !important;
        cursor: pointer !important;
    }
    
    /* Stats Icon Centering - Perfect Center Alignment */
    .stats-icon {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        height: 100% !important;
        min-height: 60px !important;
    }
    
    .stats-icon i {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        height: 100% !important;
        font-size: 1.5rem !important;
    }
    
    /* Ensure the container also centers properly */
    .col-md-4.col-lg-12.col-xl-12.col-xxl-5 {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-height: 80px !important;
    }
    
    /* Debug styles */
    .dark-mode-debug {
        position: fixed;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 10px;
        border-radius: 5px;
        z-index: 9999;
        font-size: 12px;
    }
    </style>
    
    @stack('css')
</head>

<body>
    <div id="app">
        <!-- Sidebar -->
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            @if(auth()->user()->role == 'admin')
                                <a href="{{ route('admin.dashboard') }}">
                                    <img src="{{ asset('assets/mazer/png/logo-anseyo.png') }}" alt="Anseyo Logo" style="height: 3rem;">
                                </a>
                            @elseif(auth()->user()->role == 'kitchen')
                                <a href="{{ route('kitchen.dashboard') }}">
                                    <img src="{{ asset('assets/mazer/png/logo-anseyo.png') }}" alt="Anseyo Logo" style="height: 3rem;">
                                </a>
                            @elseif(auth()->user()->role == 'cashier')
                                <a href="{{ route('cashier.dashboard') }}">
                                    <img src="{{ asset('assets/mazer/png/logo-anseyo.png') }}" alt="Anseyo Logo" style="height: 3rem;">
                                </a>
                            @endif
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5l1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path></svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3m3.5 6.91l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L17.44 13l1.06-3l1.06 3l3.19.09m3.5 6.91l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L20.94 20l1.06-3l1.06 3l3.19.09M4.5 11l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06L1.44 11L4.5 8l1.06 3L4.5 11m3.5 6.91l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L7.94 20l1.06-3L9 20l3.19.09M4.5 2l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06L1.44 2L4.5-1l1.06 3L4.5 2z"></path></svg>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>
                        
                        @if(auth()->user()->role == 'admin')
                        <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.menu.index') }}" class='sidebar-link'>
                                <i class="bi bi-grid-1x2-fill"></i>
                                <span>Manajemen Menu</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.categories.index') }}" class='sidebar-link'>
                                <i class="bi bi-tags-fill"></i>
                                <span>Kategori Menu</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.orders.index') }}" class='sidebar-link'>
                                <i class="bi bi-cart-check"></i>
                                <span>Manajemen Pesanan</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.discounts.index') }}" class='sidebar-link'>
                                <i class="bi bi-percent"></i>
                                <span>Discounts</span>
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->role == 'kitchen' || auth()->user()->role == 'admin')
                        <li class="sidebar-item {{ request()->routeIs('kitchen.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('kitchen.dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-fire"></i>
                                <span>Dashboard Dapur</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('kitchen.menus.*') ? 'active' : '' }}">
                            <a href="{{ route('kitchen.menus.index') }}" class='sidebar-link'>
                                <i class="bi bi-list-check"></i>
                                <span>Menu Availability</span>
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->role == 'cashier' || auth()->user()->role == 'admin')
                        <li class="sidebar-item {{ request()->routeIs('cashier.*') ? 'active' : '' }}">
                            <a href="{{ route('cashier.dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-cash-coin"></i>
                                <span>Dashboard Cashier</span>
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->role == 'admin')
                        <li class="sidebar-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.staff.index') }}" class='sidebar-link'>
                                <i class="bi bi-people"></i>
                                <span>Manajemen Staf</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.reports.index') }}" class='sidebar-link'>
                                <i class="bi bi-bar-chart"></i>
                                <span>Laporan Penjualan</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.tables.index') }}" class='sidebar-link'>
                                <i class="bi bi-table"></i>
                                <span>Manajemen Meja</span>
                            </a>
                        </li>
                        @endif
                        
                        <li class="sidebar-title">Pengaturan</li>
                        
                        @if(auth()->user()->role == 'admin')
                        <li class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.index') }}" class='sidebar-link'>
                                <i class="bi bi-gear"></i>
                                <span>Pengaturan</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.profile.edit') }}" class='sidebar-link'>
                                <i class="bi bi-person"></i>
                                <span>Profil</span>
                            </a>
                        </li>
                        @endif
                        
                        <li class="sidebar-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div id="main" class='layout-navbar'>
            <header class='mb-3'>
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">{{ Auth::user()->name }}</h6>
                                            <p class="mb-0 text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="{{ asset('assets/mazer/png/1.png') }}">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="width: 11rem;">
                                    <li>
                                        <h6 class="dropdown-header">Hello, {{ Auth::user()->name }}!</h6>
                                    </li>
                                    @if(auth()->user()->role == 'admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                            <i class="icon-mid bi bi-person me-2"></i> My Profile
                                        </a>
                                    </li>
                                    @endif
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            
            <!-- Page Content -->
            <div id="main-content">
                <div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>@yield('page-title', 'Dashboard')</h3>
                                <p class="text-subtitle text-muted">@yield('page-subtitle', 'Selamat datang di dashboard admin')</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        @if(auth()->user()->role == 'admin')
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        @elseif(auth()->user()->role == 'kitchen')
                                            <li class="breadcrumb-item"><a href="{{ route('kitchen.dashboard') }}">Dashboard</a></li>
                                        @elseif(auth()->user()->role == 'cashier')
                                            <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Dashboard</a></li>
                                        @endif
                                        @yield('breadcrumb')
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                
                <section class="section">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </section>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('assets/mazer/js/initTheme.js') }}"></script>
    <script src="{{ asset('assets/mazer/js/app.js') }}"></script>
    
    <!-- Dark Mode Toggle Script -->
    <script>
    // Enhanced dark mode toggle using Mazer's approach
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('toggle-dark');
        const darkCSS = document.getElementById('dark');
        
        if (!toggle || !darkCSS) {
            console.error('Dark mode elements not found');
            return;
        }
        
        // Function to enable dark mode
        function enableDarkMode() {
            darkCSS.disabled = false;
            toggle.checked = true;
            document.body.classList.add('dark');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            console.log('Dark mode enabled');
        }
        
        // Function to disable dark mode
        function disableDarkMode() {
            darkCSS.disabled = true;
            toggle.checked = false;
            document.body.classList.remove('dark');
            document.documentElement.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('theme', 'light');
            console.log('Dark mode disabled');
        }
        
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Apply theme
        if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
        
        // Add event listener for toggle
        toggle.addEventListener('change', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (this.checked) {
                enableDarkMode();
            } else {
                disableDarkMode();
            }
        });
        
        // Add click event as backup
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        console.log('Dark mode initialized successfully');
    });
    </script>
    
    @stack('scripts')
</body>
</html>