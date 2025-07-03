<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Wedding Management')</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('lio90.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('lio90.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('lio90.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#1E40AF',
                        accent: '#F59E0B'
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .admin-sidebar {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        .admin-nav-item {
            transition: all 0.3s ease;
        }
        
        .admin-nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        
        .admin-nav-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-right: 4px solid white;
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            background-color: #10B981;
        }
        
        .notification.error {
            background-color: #EF4444;
        }
        
        .notification.info {
            background-color: #3B82F6;
        }
        
        .notification.warning {
            background-color: #F59E0B;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Admin Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 fixed w-full top-0 z-40">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button id="sidebarToggle" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="ml-4 text-xl font-semibold text-gray-800">@yield('title', 'Admin Panel')</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- User Menu -->
                    <div class="relative">
                        <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" id="user-menu">
                            <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ auth()->user()->first_name ?? 'Admin' }}&background=3B82F6&color=fff" alt="Profile">
                            <span class="ml-2 text-gray-700">{{ auth()->user()->first_name ?? 'Admin' }}</span>
                            <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden border border-gray-200">
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="ri-dashboard-line mr-2"></i>Dashboard
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="ri-user-line mr-2"></i>Profile
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="ri-logout-box-line mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full" id="sidebar">
        <div class="flex items-center justify-center h-20 bg-blue-800">
            <h1 class="text-xl font-bold text-white">Admin Panel</h1>
        </div>
        
        <nav class="mt-8">
            <div class="px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="ri-dashboard-line mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.halls.index') }}" class="admin-nav-item flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.halls.*') ? 'active' : '' }}">
                    <i class="ri-building-line mr-3"></i>
                    Halls
                </a>
                <a href="{{ route('admin.packages.index') }}" class="admin-nav-item flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <i class="ri-gift-line mr-3"></i>
                    Packages
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="admin-nav-item flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <i class="ri-calendar-check-line mr-3"></i>
                    Bookings
                </a>
                <a href="{{ route('admin.visits.index') }}" class="admin-nav-item flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.visits.*') ? 'active' : '' }}">
                    <i class="ri-user-follow-line mr-3"></i>
                    Visit Requests
                </a>
                <a href="{{ route('admin.users') }}" class="admin-nav-item flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="ri-user-settings-line mr-3"></i>
                    Users
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64 pt-20">
        <main class="min-h-screen">
            @yield('content')
        </main>
    </div>

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <!-- Scripts -->
    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });

        // User menu dropdown
        document.getElementById('user-menu')?.addEventListener('click', function() {
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const dropdown = userMenu?.nextElementSibling;
            if (dropdown && !userMenu.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Notification system
        function showNotification(message, type = 'info') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }

        // Make showNotification globally available
        window.showNotification = showNotification;
    </script>

    @stack('scripts')
</body>
</html>