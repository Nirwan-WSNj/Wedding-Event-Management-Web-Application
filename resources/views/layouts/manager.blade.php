<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Manager Dashboard - Wedding Management')</title>

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
                        secondary: '#1E40AF'
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        .content-fade {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .nav-item.active {
            background-color: rgb(239 246 255);
            color: rgb(37 99 235);
            border-right: 4px solid rgb(37 99 235);
        }
        
        .nav-item:hover {
            background-color: rgb(249 250 251);
            color: rgb(37 99 235);
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        @media (max-width: 768px) {
            .ml-64 {
                margin-left: 0;
            }
            
            #sidebar {
                transform: translateX(-100%);
            }
            
            #sidebar.active {
                transform: translateX(0);
            }
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform transition-transform duration-300 ease-in-out sidebar-transition" id="sidebar">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-center h-20 bg-gradient-to-r from-blue-600 to-purple-600">
                <h1 class="text-2xl font-bold text-white">Manager Panel</h1>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-8 custom-scrollbar" style="height: calc(100vh - 5rem); overflow-y: auto;">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('manager.dashboard') }}" class="nav-item {{ request()->routeIs('manager.dashboard') ? 'active' : '' }} flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Visit Requests -->
                    <a href="{{ route('manager.visits') }}" class="nav-item {{ request()->routeIs('manager.visits') ? 'active' : '' }} flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Visit Requests
                    </a>

                    <!-- Wedding Requests -->
                    <a href="{{ route('manager.wedding_requests') }}" class="nav-item {{ request()->routeIs('manager.wedding_requests') ? 'active' : '' }} flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Wedding Dates
                    </a>

                    <!-- Manage Halls -->
                    <a href="{{ route('manager.halls') }}" class="nav-item {{ request()->routeIs('manager.halls') ? 'active' : '' }} flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Manage Halls
                    </a>

                    <!-- Bookings Tracker -->
                    <a href="{{ route('manager.bookings') }}" class="nav-item {{ request()->routeIs('manager.bookings') ? 'active' : '' }} flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Bookings Tracker
                    </a>

                    <!-- Calendar View -->
                    <a href="{{ route('manager.calendar') }}" class="nav-item {{ request()->routeIs('manager.calendar') ? 'active' : '' }} flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Calendar View
                    </a>

                    <!-- Messages -->
                    <a href="{{ route('manager.messages.list') }}" class="nav-item {{ request()->routeIs('manager.messages.list') ? 'active' : '' }} flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Messages
                        <span id="message-badge" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden">0</span>
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- Reports -->
                    <a href="#" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Reports
                    </a>

                    <!-- Settings -->
                    <a href="#" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="ml-64">
            <!-- Top Header -->
            <header class="bg-white shadow-lg border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button id="sidebarToggle" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <h1 class="ml-4 text-2xl font-semibold text-gray-800" id="pageTitle">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div class="relative">
                                <button id="notifications-btn" class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full transition-colors">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5"></path>
                                    </svg>
                                </button>
                                <span id="notification-badge" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white hidden"></span>
                                
                                <!-- Notifications Dropdown -->
                                <div id="notifications-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 hidden border border-gray-200 max-h-96 overflow-y-auto">
                                    <div class="px-4 py-3 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                            <button id="mark-all-read" class="text-xs text-blue-600 hover:text-blue-800">Mark all read</button>
                                        </div>
                                    </div>
                                    <div id="notifications-list" class="max-h-64 overflow-y-auto">
                                        <div class="px-4 py-3 text-center text-gray-500 text-sm">
                                            Loading notifications...
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 border-t border-gray-200">
                                        <button class="w-full text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            View All Notifications
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- User Profile Dropdown -->
                            <div class="relative dropdown">
                                <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" id="user-menu">
                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Manager' }}&background=3B82F6&color=fff" alt="Profile">
                                    <span class="ml-2 text-gray-700">{{ Auth::user()->name ?? 'Manager' }}</span>
                                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 hidden border border-gray-200">
                                    <a href="{{ route('manager.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profile Settings
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6 content-fade">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- JavaScript for Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }

            // User dropdown toggle
            const userMenu = document.getElementById('user-menu');
            const dropdownMenu = userMenu?.nextElementSibling;
            
            if (userMenu && dropdownMenu) {
                userMenu.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdownMenu.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }

            // Notifications dropdown toggle
            const notificationsBtn = document.getElementById('notifications-btn');
            const notificationsDropdown = document.getElementById('notifications-dropdown');
            
            if (notificationsBtn && notificationsDropdown) {
                notificationsBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    notificationsDropdown.classList.toggle('hidden');
                    
                    // Load notifications when opened
                    if (!notificationsDropdown.classList.contains('hidden')) {
                        loadNotifications();
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!notificationsBtn.contains(e.target) && !notificationsDropdown.contains(e.target)) {
                        notificationsDropdown.classList.add('hidden');
                    }
                });
            }

            // Load notification count
            loadNotificationCount();
            
            // Refresh notification count every 30 seconds
            setInterval(loadNotificationCount, 30000);
        });

        function loadNotificationCount() {
            fetch('{{ route("manager.notifications.count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    const messageBadge = document.getElementById('message-badge');
                    
                    if (data.success && data.count > 0) {
                        if (badge) {
                            badge.classList.remove('hidden');
                        }
                        if (messageBadge) {
                            messageBadge.textContent = data.count;
                            messageBadge.classList.remove('hidden');
                        }
                    } else {
                        if (badge) {
                            badge.classList.add('hidden');
                        }
                        if (messageBadge) {
                            messageBadge.classList.add('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading notification count:', error);
                });
        }

        function loadNotifications() {
            const notificationsList = document.getElementById('notifications-list');
            if (!notificationsList) return;

            notificationsList.innerHTML = '<div class="px-4 py-3 text-center text-gray-500 text-sm"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';

            fetch('{{ route("manager.notifications") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.notifications.length > 0) {
                        notificationsList.innerHTML = data.notifications.map(notification => `
                            <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                <div class="flex items-start">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                                        <p class="text-sm text-gray-600">${notification.message}</p>
                                        <p class="text-xs text-gray-400 mt-1">${notification.time}</p>
                                    </div>
                                    ${notification.action_required ? '<div class="ml-2"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Action Required</span></div>' : ''}
                                </div>
                            </div>
                        `).join('');
                    } else {
                        notificationsList.innerHTML = '<div class="px-4 py-3 text-center text-gray-500 text-sm">No new notifications</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationsList.innerHTML = '<div class="px-4 py-3 text-center text-red-500 text-sm">Error loading notifications</div>';
                });
        }

        console.log('Manager Layout - Navigation Ready');
    </script>

    @stack('scripts')
</body>
</html>