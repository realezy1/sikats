<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiKats') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            body {
                font-family: 'Montserrat', sans-serif;
                background-color: #f3f4f6; /* Latar belakang abu muda seperti gambar */
                overflow-x: hidden;
            }
            
            /* Sidebar Layout */
            .sidebar {
                width: 260px;
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                background-color: #ffffff;
                border-right: 1px solid #e5e7eb;
                overflow-y: auto;
                z-index: 1000;
                display: flex;
                flex-direction: column;
            }
            
            .main-content {
                margin-left: 260px;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                    transition: transform 0.3s ease;
                }
                .sidebar.show {
                    transform: translateX(0);
                }
                .main-content {
                    margin-left: 0;
                }
            }

            /* Sidebar Overlay */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            /* Topbar */
            .topbar {
                background-color: #ffffff;
                border-bottom: 1px solid #e5e7eb;
                padding: 1rem 1.5rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 999;
            }

            .search-box {
                background-color: #f3f4f6;
                border-radius: 0.5rem;
                padding: 0.5rem 1rem;
                display: flex;
                align-items: center;
                width: 300px;
            }
            .search-box input {
                border: none;
                background: transparent;
                outline: none;
                width: 100%;
                font-size: 0.875rem;
                margin-left: 0.5rem;
            }

            /* Card Utilities */
            .custom-card {
                background: #ffffff;
                border-radius: 1rem;
                border: 1px solid #f3f4f6;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            }
        </style>
    </head>
    <body>
        
        <!-- Sidebar -->
        <aside class="sidebar py-4 px-3" id="sidebar">
            @include('layouts.navigation')
        </aside>

        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebarOverlay" class="sidebar-overlay d-md-none"></div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="d-flex align-items-center">
                    <button class="btn btn-link d-md-none text-dark me-2" id="sidebarToggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    @hasSection('header')
                        <div class="fs-4 fw-bold text-dark mb-0">
                            @yield('header')
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Profile Menu -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: 600;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="profileDropdown">
                            <li><h6 class="dropdown-header">{{ Auth::user()->name }}</h6></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 p-md-5">
                @yield('content')
            </main>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            // Sidebar Toggle Logic for Mobile
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarToggle = document.getElementById('sidebarToggle');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                
                function toggleSidebar() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                }

                if(sidebarToggle && sidebar && overlay) {
                    sidebarToggle.addEventListener('click', toggleSidebar);
                    overlay.addEventListener('click', toggleSidebar);
                }
            });
        </script>

        @include('sweetalert::alert')
        @stack('scripts')
    </body>
</html>
