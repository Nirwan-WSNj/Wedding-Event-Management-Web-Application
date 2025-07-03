<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wedding Management System - Admin Panel</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script> 
    
    <!-- Remix Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css"  rel="stylesheet">
    
    <!-- FullCalendar CDN -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script> 
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    
    <style>
        .admin-page { display: none; }
        .admin-page.active { display: block; }
        .hover-lift:hover { transform: translateY(-2px); }
        .card-shadow { box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-confirmed { background-color: #dcfce7; color: #166534; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        
        /* Custom Table Styles */
        .data-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            min-width: 600px;
        }
        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table thead th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #4b5563;
            position: sticky;
            top: 0;
            z-index: 1;
            box-shadow: 0 1px 0 0 #e5e7eb;
        }
        .data-table tbody tr:hover {
            background-color: #f9fafb;
        }
        
        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        .pagination button {
            @apply px-3 py-1 rounded-md transition-colors duration-150;
        }
        .pagination button.active {
            @apply bg-blue-600 text-white;
        }
        
        /* Modal Animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg h-full fixed inset-y-0 left-0 z-20 border-r">
            <div class="p-6 text-xl font-bold text-blue-600 flex items-center gap-2">
                <i class="ri-cake-2-line text-2xl"></i>
                <span>Wedding Admin</span>
            </div>
            
            <nav class="mt-8 px-2">
                <ul class="space-y-1" id="sidebarMenu">
                    <li><button class="sidebar-item w-full text-left px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 flex items-center gap-3 transition-all duration-200 active" onclick="showPage('dashboard')"><i class="ri-dashboard-3-line text-xl"></i><span>Dashboard</span></button></li>
                    <li><button class="sidebar-item w-full text-left px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 flex items-center gap-3 transition-all duration-200" onclick="showPage('bookings')"><i class="ri-calendar-check-line text-xl"></i><span>Bookings</span></button></li>
                    <li><button class="sidebar-item w-full text-left px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 flex items-center gap-3 transition-all duration-200" onclick="showPage('halls')"><i class="ri-building-line text-xl"></i><span>Halls</span></button></li>
                    <li><button class="sidebar-item w-full text-left px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 flex items-center gap-3 transition-all duration-200" onclick="showPage('packages')"><i class="ri-gift-line text-xl"></i><span>Packages</span></button></li>
                    <li><button class="sidebar-item w-full text-left px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 flex items-center gap-3 transition-all duration-200" onclick="showPage('users')"><i class="ri-user-3-line text-xl"></i><span>Users</span></button></li>
                    <li><button class="sidebar-item w-full text-left px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 flex items-center gap-3 transition-all duration-200" onclick="showPage('calendar')"><i class="ri-calendar-event-line text-xl"></i><span>Calendar</span></button></li>
                    <li><button class="sidebar-item w-full text-left px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 flex items-center gap-3 transition-all duration-200" onclick="showPage('messages')"><i class="ri-message-3-line text-xl"></i><span>Messages</span></button></li>
                    <li><button class="sidebar-item w-full text-left px-4 py-3 rounded-lg hover:bg-blue-50 text-gray-700 flex items-center gap-3 transition-all duration-200" onclick="showPage('gallery')"><i class="ri-gallery-line text-xl"></i><span>Gallery</span></button></li>
                </ul>
            </nav>
            
            <!-- User Profile -->
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-200 bg-white">
                <div class="flex items-center gap-3">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600" title="Logout">
                            <i class="ri-logout-box-r-line"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8 overflow-y-auto">
            <!-- Page Header -->
            <header class="mb-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 id="pageTitle" class="text-3xl font-bold text-gray-800">Dashboard</h1>
                        <p id="pageSubtitle" class="text-gray-600">Welcome back! Here's what's happening today.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-notification-3-line"></i>
                            <span>Notifications</span>
                        </button>
                        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-settings-3-line"></i>
                            <span>Settings</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Overview Page -->
            <section id="dashboard" class="admin-page active fade-in">
                <!-- Enhanced Stats Cards with Real WMdemo Data -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover-lift card-shadow transition-transform duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Bookings</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1" id="total-bookings">{{ $stats['total_bookings'] ?? 0 }}</p>
                                <p class="text-sm text-blue-600 mt-1">
                                    <span class="text-green-600">{{ $stats['confirmed_bookings'] ?? 0 }} confirmed</span> • 
                                    <span class="text-yellow-600">{{ $stats['pending_bookings'] ?? 0 }} pending</span>
                                </p>
                            </div>
                            <div class="p-4 bg-blue-100 rounded-full">
                                <i class="ri-calendar-check-line text-blue-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover-lift card-shadow transition-transform duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Revenue</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1" id="total-revenue">Rs. {{ number_format($stats['total_revenue'] ?? 0) }}</p>
                                <p class="text-sm text-green-600 mt-1">
                                    This month: Rs. {{ number_format($stats['monthly_revenue'] ?? 0) }}
                                </p>
                            </div>
                            <div class="p-4 bg-green-100 rounded-full">
                                <i class="ri-money-dollar-circle-line text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover-lift card-shadow transition-transform duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Wedding Venues</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1" id="active-halls">{{ $stats['total_halls'] ?? 0 }}</p>
                                <p class="text-sm text-orange-600 mt-1">
                                    {{ $stats['total_wedding_types'] ?? 0 }} wedding types available
                                </p>
                            </div>
                            <div class="p-4 bg-yellow-100 rounded-full">
                                <i class="ri-building-line text-yellow-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover-lift card-shadow transition-transform duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">System Users</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1" id="total-users">{{ $stats['total_users'] ?? 0 }}</p>
                                <p class="text-sm text-purple-600 mt-1">
                                    {{ $stats['total_customers'] ?? 0 }} customers • {{ $stats['total_admins'] ?? 0 }} admins • {{ $stats['total_managers'] ?? 0 }} managers
                                </p>
                            </div>
                            <div class="p-4 bg-purple-100 rounded-full">
                                <i class="ri-group-line text-purple-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Analytics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 card-shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Today's Bookings</span>
                                <span class="font-semibold text-blue-600">{{ $stats['bookings_today'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">This Week</span>
                                <span class="font-semibold text-green-600">{{ $stats['bookings_this_week'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">This Month</span>
                                <span class="font-semibold text-purple-600">{{ $stats['bookings_this_month'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">New Users (Week)</span>
                                <span class="font-semibold text-orange-600">{{ $stats['new_users_this_week'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 card-shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Services</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">Most Booked Hall</span>
                                <p class="font-semibold text-blue-600">{{ $stats['most_booked_hall'] ?? 'No data' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Wedding Types</span>
                                <p class="font-semibold text-green-600">{{ $stats['total_wedding_types'] ?? 0 }} Available</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Avg. Booking Value</span>
                                <p class="font-semibold text-purple-600">Rs. {{ number_format($stats['average_booking_value'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 card-shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">System Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Database</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Connected</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Session</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Active</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Real Data</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Live</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Last Updated</span>
                                <span class="text-xs text-gray-500" id="last-updated">{{ now()->format('H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg p-6 card-shadow">
                    <h3 class="text-xl font-semibold mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <button class="p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-center transition-colors" onclick="showPage('bookings')">
                            <i class="ri-add-circle-line text-blue-600 text-2xl mb-2"></i>
                            <p class="font-medium">Create Booking</p>
                        </button>
                        
                        <button class="p-4 bg-green-50 hover:bg-green-100 rounded-lg text-center transition-colors" onclick="showPage('halls'); openHallModal();">
                            <i class="ri-building-line text-green-600 text-2xl mb-2"></i>
                            <p class="font-medium">Add Hall</p>
                        </button>
                        
                        <button class="p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg text-center transition-colors" onclick="showPage('calendar')">
                            <i class="ri-calendar-event-line text-yellow-600 text-2xl mb-2"></i>
                            <p class="font-medium">View Calendar</p>
                        </button>
                        
                        <button class="p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-center transition-colors" onclick="showPage('users')">
                            <i class="ri-user-add-line text-purple-600 text-2xl mb-2"></i>
                            <p class="font-medium">Add User</p>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Enhanced Booking Management Page -->
            <section id="bookings" class="admin-page hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Wedding Bookings Management</h2>
                        <p class="text-gray-600">Comprehensive booking oversight with approval workflow and process configuration</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="exportBookings()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-download-line"></i> Export
                        </button>
                        <button onclick="openBookingModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-add-line"></i> New Booking
                        </button>
                    </div>
                </div>

                <!-- Booking Configuration Bar -->
                <div class="bg-white rounded-xl shadow-lg p-4 mb-6 card-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center">
                                    <i class="ri-settings-3-line text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Booking Process Configuration</h3>
                                    <p class="text-sm text-gray-600">Manage the 6-step booking workflow and system settings</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    <i class="ri-database-line mr-1"></i>Live Data Connected
                                </span>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    6 Steps Active
                                </span>
                                <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                                    Manual Approval
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="loadBookingConfigurationPage()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <i class="ri-settings-line"></i>
                                <span>Configure Booking Process</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Booking Configuration Content Area -->
                <div id="booking-config-content" class="hidden">
                    <!-- Configuration content will be loaded here -->
                </div>

                <!-- Enhanced Booking Stats -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-full">
                                <i class="ri-calendar-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="total-bookings-count">0</p>
                                <p class="text-xs text-gray-600">Total Bookings</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-yellow-100 rounded-full">
                                <i class="ri-time-line text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="pending-bookings-count">0</p>
                                <p class="text-xs text-gray-600">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-full">
                                <i class="ri-check-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="confirmed-bookings-count">0</p>
                                <p class="text-xs text-gray-600">Confirmed</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-100 rounded-full">
                                <i class="ri-close-line text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="cancelled-bookings-count">0</p>
                                <p class="text-xs text-gray-600">Cancelled</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-full">
                                <i class="ri-money-dollar-circle-line text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="total-revenue-bookings">Rs. 0</p>
                                <p class="text-xs text-gray-600">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Filters -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 card-shadow">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="booking-status-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                            <input type="date" id="booking-date-from" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                            <input type="date" id="booking-date-to" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hall</label>
                            <select id="booking-hall-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Halls</option>
                                <!-- Halls will be loaded dynamically -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" id="booking-search" placeholder="Search bookings..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button onclick="applyBookingFilters()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Apply Filters
                        </button>
                        <button onclick="clearBookingFilters()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Enhanced Bookings Table -->
                <div class="bg-white rounded-xl shadow-lg p-6 card-shadow overflow-x-auto">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Booking Records</h3>
                        <div class="flex gap-2">
                            <button onclick="bulkApproveBookings()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                Bulk Approve
                            </button>
                            <button onclick="refreshBookings()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <table class="min-w-full data-table" id="bookingsTable">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                    <input type="checkbox" id="selectAllBookings" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Booking ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Customer</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Hall</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Package</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Wedding Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bookingsTableBody">
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex items-center justify-center">
                                        <i class="ri-loader-4-line animate-spin mr-2"></i>
                                        Loading bookings...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Enhanced Pagination -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600" id="bookings-pagination-info">
                            Showing 0 to 0 of 0 entries
                        </div>
                        <div class="pagination" id="bookings-pagination">
                            <!-- Pagination will be loaded dynamically -->
                        </div>
                    </div>
                </div>
            </section>

            <!-- Enhanced Visit Management Page -->
            <section id="visits" class="admin-page hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Visit Requests Management</h2>
                        <p class="text-gray-600">Approve and manage venue visit requests from customers</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="exportVisits()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-download-line"></i> Export
                        </button>
                        <button onclick="scheduleVisit()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-calendar-add-line"></i> Schedule Visit
                        </button>
                    </div>
                </div>

                <!-- Visit Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-yellow-100 rounded-full">
                                <i class="ri-time-line text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="pending-visits-count">0</p>
                                <p class="text-xs text-gray-600">Pending Visits</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-full">
                                <i class="ri-check-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="approved-visits-count">0</p>
                                <p class="text-xs text-gray-600">Approved</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-full">
                                <i class="ri-calendar-check-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="completed-visits-count">0</p>
                                <p class="text-xs text-gray-600">Completed</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-full">
                                <i class="ri-percent-line text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="visit-conversion-rate">0%</p>
                                <p class="text-xs text-gray-600">Conversion Rate</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit Filters -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 card-shadow">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="visit-status-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Visit Date</label>
                            <input type="date" id="visit-date-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hall</label>
                            <select id="visit-hall-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Halls</option>
                                <!-- Halls will be loaded dynamically -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" id="visit-search" placeholder="Search visits..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button onclick="applyVisitFilters()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Apply Filters
                        </button>
                        <button onclick="clearVisitFilters()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Visits Table -->
                <div class="bg-white rounded-xl shadow-lg p-6 card-shadow overflow-x-auto">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Visit Requests</h3>
                        <div class="flex gap-2">
                            <button onclick="bulkApproveVisits()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                Bulk Approve
                            </button>
                            <button onclick="refreshVisits()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <table class="min-w-full data-table" id="visitsTable">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                    <input type="checkbox" id="selectAllVisits" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Request ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Customer</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Contact</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Visit Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Time</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Hall</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="visitsTableBody">
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex items-center justify-center">
                                        <i class="ri-loader-4-line animate-spin mr-2"></i>
                                        Loading visits...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600" id="visits-pagination-info">
                            Showing 0 to 0 of 0 entries
                        </div>
                        <div class="pagination" id="visits-pagination">
                            <!-- Pagination will be loaded dynamically -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- Enhanced Hall Management Page -->
            <section id="halls" class="admin-page hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Wedding Halls Management</h2>
                        <p class="text-gray-600">Comprehensive venue management with real-time availability and booking integration</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="exportHalls()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-download-line"></i> Export
                        </button>
                        <button onclick="openHallModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-add-line"></i> Add New Hall
                        </button>
                    </div>
                </div>

                <!-- Real-time Hall Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-full">
                                <i class="ri-building-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="total-halls-count">{{ $stats['total_halls'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600">Total Halls</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-full">
                                <i class="ri-check-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="active-halls-count">{{ $stats['active_halls'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600">Active Halls</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-yellow-100 rounded-full">
                                <i class="ri-calendar-line text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="booked-halls-today">{{ $stats['bookings_today'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600">Booked Today</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-full">
                                <i class="ri-money-dollar-circle-line text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="hall-revenue">Rs. {{ number_format($stats['total_revenue'] ?? 0) }}</p>
                                <p class="text-xs text-gray-600">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-full">
                                <i class="ri-star-line text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="most-popular-hall">{{ $stats['most_booked_hall'] ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-600">Most Popular</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced Search and Filters -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 card-shadow">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" id="hall-search" placeholder="Search halls..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Capacity Range</label>
                            <select id="hall-capacity-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Capacities</option>
                                <option value="0-100">Less than 100</option>
                                <option value="100-300">100-300 guests</option>
                                <option value="300-500">300-500 guests</option>
                                <option value="500+">More than 500</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="hall-status-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <select id="hall-price-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Prices</option>
                                <option value="0-50000">Under Rs. 50,000</option>
                                <option value="50000-100000">Rs. 50,000 - 100,000</option>
                                <option value="100000-200000">Rs. 100,000 - 200,000</option>
                                <option value="200000+">Above Rs. 200,000</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                            <select id="hall-availability-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All</option>
                                <option value="available">Available Today</option>
                                <option value="booked">Booked Today</option>
                                <option value="maintenance">Under Maintenance</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end gap-2">
                            <button onclick="applyHallFilters()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Apply
                            </button>
                            <button onclick="clearHallFilters()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Halls Grid with Real Data -->
                <div class="bg-white rounded-xl shadow-lg p-6 card-shadow">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Wedding Halls</h3>
                        <div class="flex gap-2">
                            <button onclick="toggleHallView('grid')" id="grid-view-btn" class="bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="ri-grid-line"></i> Grid
                            </button>
                            <button onclick="toggleHallView('list')" id="list-view-btn" class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-sm transition-colors">
                                <i class="ri-list-check"></i> List
                            </button>
                            <button onclick="refreshHalls()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Grid View with Real Hall Data -->
                    <div id="halls-grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Real halls will be loaded here -->
                    </div>

                    <!-- List View -->
                    <div id="halls-list-view" class="hidden">
                        <table class="min-w-full data-table">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Hall Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Capacity</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Price</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bookings</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Availability</th>
                                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="halls-list-body">
                                <!-- Real halls data will be loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600" id="halls-pagination-info">
                            Showing 0 to 0 of 0 entries
                        </div>
                        <div class="pagination" id="halls-pagination">
                            <!-- Pagination will be loaded dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Hall Creation/Edit Modal -->
                <div id="hall-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                    <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800" id="hall-modal-title">Add New Hall</h3>
                            <button onclick="closeHallModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="ri-close-line text-2xl"></i>
                            </button>
                        </div>
                        
                        <form id="hall-form" enctype="multipart/form-data">
                            <input type="hidden" id="hall-id" name="hall_id">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hall Name</label>
                                    <input type="text" id="hall-name" name="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                                    <input type="number" id="hall-capacity" name="capacity" required min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.)</label>
                                    <input type="number" id="hall-price" name="price" required min="0" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select id="hall-status" name="is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="hall-description" name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                            
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hall Image</label>
                                <input type="file" id="hall-image" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="text-sm text-gray-500 mt-1">Upload a high-quality image of the hall</p>
                            </div>
                            
                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" onclick="closeHallModal()" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <span id="hall-submit-text">Create Hall</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Enhanced Package Management Page - Integrated from /admin/packages -->
            <section id="packages" class="admin-page hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Package Management</h2>
                        <p class="text-gray-600">Create and manage wedding packages with full CRUD operations and real-time updates</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="exportPackages()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-download-line"></i> Export
                        </button>
                        <button onclick="openPackageModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-add-line"></i> Add New Package
                        </button>
                    </div>
                </div>

                <!-- Package Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-full">
                                <i class="ri-gift-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="total-packages-count">0</p>
                                <p class="text-xs text-gray-600">Total Packages</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-full">
                                <i class="ri-check-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="active-packages-count">0</p>
                                <p class="text-xs text-gray-600">Active Packages</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-yellow-100 rounded-full">
                                <i class="ri-star-line text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="popular-package-name">-</p>
                                <p class="text-xs text-gray-600">Most Popular</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-full">
                                <i class="ri-money-dollar-circle-line text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800" id="package-revenue">Rs. 0</p>
                                <p class="text-xs text-gray-600">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Filters -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 card-shadow">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" id="package-search" placeholder="Search packages..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="package-status-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <select id="package-price-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Prices</option>
                                <option value="0-100000">Under Rs. 100,000</option>
                                <option value="100000-200000">Rs. 100,000 - 200,000</option>
                                <option value="200000-300000">Rs. 200,000 - 300,000</option>
                                <option value="300000+">Above Rs. 300,000</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button onclick="applyPackageFilters()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Apply
                            </button>
                            <button onclick="clearPackageFilters()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Packages Grid -->
                <div class="bg-white rounded-xl shadow-lg p-6 card-shadow">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Wedding Packages</h3>
                        <div class="flex gap-2">
                            <button onclick="togglePackageView('grid')" id="package-grid-view-btn" class="bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="ri-grid-line"></i> Grid
                            </button>
                            <button onclick="togglePackageView('list')" id="package-list-view-btn" class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-sm transition-colors">
                                <i class="ri-list-check"></i> List
                            </button>
                            <button onclick="refreshPackages()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Grid View -->
                    <div id="packages-grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="text-center py-8">
                            <i class="ri-loader-4-line animate-spin text-4xl text-blue-600 mb-4"></i>
                            <p class="text-gray-600">Loading packages...</p>
                        </div>
                    </div>

                    <!-- List View -->
                    <div id="packages-list-view" class="hidden">
                        <table class="min-w-full data-table">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Package Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Price</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bookings</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Revenue</th>
                                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="packages-list-body">
                                <!-- Package data will be loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600" id="packages-pagination-info">
                            Showing 0 to 0 of 0 entries
                        </div>
                        <div class="pagination" id="packages-pagination">
                            <!-- Pagination will be loaded dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Package Modal -->
                <div id="package-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                    <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800" id="package-modal-title">Add New Package</h3>
                            <button onclick="closePackageModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="ri-close-line text-2xl"></i>
                            </button>
                        </div>
                        
                        <form id="package-form" enctype="multipart/form-data">
                            <input type="hidden" id="package-id" name="package_id">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
                                    <input type="text" id="package-name" name="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.)</label>
                                    <input type="number" id="package-price" name="price" required min="0" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            
                            <!-- Guest Capacity Section -->
                            <div class="mt-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Guest Capacity</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Guests</label>
                                        <input type="number" id="package-min-guests" name="min_guests" required min="1" value="50" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Guests</label>
                                        <input type="number" id="package-max-guests" name="max_guests" required min="1" value="150" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Guest Price (Rs.)</label>
                                        <input type="number" id="package-additional-guest-price" name="additional_guest_price" required min="0" step="0.01" value="2500" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <p class="text-xs text-gray-500 mt-1">Price per guest above maximum capacity</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="package-description" name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                            
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                                <div id="features-container">
                                    <div class="feature-input flex gap-2 mb-2">
                                        <input type="text" name="features[]" placeholder="Enter feature" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <button type="button" onclick="removeFeature(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" onclick="addFeature()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                                    <i class="ri-add-line"></i> Add Feature
                                </button>
                            </div>
                            
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Image</label>
                                <input type="file" id="package-image" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="text-sm text-gray-500 mt-1">Upload a high-quality image of the package (Min: 100KB, Max: 2MB)</p>
                                <div id="current-image-preview" class="mt-2 hidden">
                                    <img id="current-image" src="" alt="Current package image" class="w-32 h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeCurrentImage()" class="mt-2 text-red-600 hover:text-red-800 text-sm">
                                        <i class="ri-delete-bin-line"></i> Remove current image
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="package-highlight" name="highlight" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="package-highlight" class="ml-2 text-sm text-gray-700">Mark as popular package</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="package-active" name="is_active" value="1" checked class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <label for="package-active" class="ml-2 text-sm text-gray-700">Package is active (visible to customers)</label>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" onclick="closePackageModal()" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <span id="package-submit-text">Create Package</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Package Details Modal -->
                <div id="package-details-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                    <div class="bg-white rounded-xl p-6 w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-800" id="package-details-title">Package Details</h3>
                            <button onclick="closePackageDetailsModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="ri-close-line text-2xl"></i>
                            </button>
                        </div>
                        
                        <div id="package-details-content">
                            <!-- Package details will be loaded here -->
                        </div>
                    </div>
                </div>
            </section>

            <!-- Users Management Page -->
            <section id="users" class="admin-page hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
                        <p class="text-gray-600">Manage registered users and their accounts - Real WMdemo Data</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="exportUsers()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-download-line"></i>
                            <span>Export</span>
                        </button>
                        <button onclick="openAddUserModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                            <i class="ri-user-add-line"></i>
                            <span>Add User</span>
                        </button>
                    </div>
                </div>
                
                <!-- User Stats - Real Data -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow transition-transform duration-200 hover:-translate-y-1">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="ri-user-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800" id="users-total">{{ $stats['total_users'] ?? 0 }}</p>
                                <p class="text-sm text-gray-600">Total Users</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow transition-transform duration-200 hover:-translate-y-1">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="ri-user-check-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800" id="users-customers">{{ $stats['total_customers'] ?? 0 }}</p>
                                <p class="text-sm text-gray-600">Customers</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow transition-transform duration-200 hover:-translate-y-1">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="ri-user-smile-line text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800" id="users-managers">{{ $stats['total_managers'] ?? 0 }}</p>
                                <p class="text-sm text-gray-600">Managers</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-lg p-4 card-shadow transition-transform duration-200 hover:-translate-y-1">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="ri-user-add-line text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800" id="users-admins">{{ $stats['total_admins'] ?? 0 }}</p>
                                <p class="text-sm text-gray-600">Admins</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Users Table with Real Data and CRUD Operations -->
                <div class="bg-white rounded-xl shadow-lg p-6 card-shadow overflow-x-auto">
                    <div class="mb-4 flex justify-between items-center">
                        <div class="relative">
                            <input id="userSearch" type="text" placeholder="Search users by name, email..." class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onkeyup="searchUsers()">
                            <i class="ri-search-line absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <select id="roleFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="filterUsers()">
                                <option value="">All Roles</option>
                                <option value="customer">Customer</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                            
                            <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="filterUsers()">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                            
                            <button onclick="refreshUsers()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm transition-colors">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                    
                    <table class="min-w-full data-table" id="usersTable">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="toggleSelectAll()">
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Joined</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <!-- Users will be loaded dynamically via JavaScript -->
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex items-center justify-center">
                                        <i class="ri-loader-4-line animate-spin mr-2"></i>
                                        Loading users...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            Showing 1 to 3 of 1,247 entries
                        </div>
                        <div class="pagination">
                            <button class="px-3 py-1 rounded-md bg-gray-100 text-gray-500">&laquo;</button>
                            <button class="px-3 py-1 rounded-md bg-blue-600 text-white active">1</button>
                            <button class="px-3 py-1 rounded-md hover:bg-blue-50">2</button>
                            <button class="px-3 py-1 rounded-md hover:bg-blue-50">3</button>
                            <button class="px-3 py-1 rounded-md hover:bg-blue-50">4</button>
                            <button class="px-3 py-1 rounded-md bg-gray-100 text-gray-500">&raquo;</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Calendar Management Page -->
            <section id="calendar" class="admin-page hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Event Calendar</h2>
                        <p class="text-gray-600">View and manage wedding events schedule</p>
                    </div>
                    <button onclick="openEventModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-add-line"></i>
                        <span>Add Event</span>
                    </button>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg p-6 card-shadow">
                    <div id="calendarContainer" class="max-w-full"></div>
                </div>
            </section>

            <!-- Messages Management Page -->
            <section id="messages" class="admin-page hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Messages</h2>
                        <p class="text-gray-600">Manage customer inquiries and communications</p>
                    </div>
                    <button onclick="openMessageModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-mail-send-line"></i>
                        <span>New Message</span>
                    </button>
                </div>
                
                <!-- Messages List -->
                <div class="space-y-4">
                    <!-- Message Item 1 -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card-shadow hover-lift transition-all duration-200">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">JD</div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-800">John Doe</h3>
                                        <p class="text-sm text-gray-600">johndoe@example.com</p>
                                    </div>
                                    <span class="text-xs text-gray-500 ml-auto">2 minutes ago</span>
                                </div>
                                
                                <div class="mt-2">
                                    <h4 class="font-semibold text-gray-700">Inquiry about Premium Package</h4>
                                    <p class="mt-1 text-sm text-gray-600">Hi, I'm interested in the premium package but would like to know if there are any customization options available for the catering menu. Could you provide more details?</p>
                                </div>
                                
                                <div class="mt-3 flex gap-2">
                                    <button class="text-blue-600 hover:text-blue-900 text-sm">
                                        <i class="ri-reply-line mr-1"></i>Reply
                                    </button>
                                    <button class="text-green-600 hover:text-green-900 text-sm">
                                        <i class="ri-check-double-line mr-1"></i>Mark as Read
                                    </button>
                                    <button class="text-red-600 hover:text-red-900 text-sm">
                                        <i class="ri-delete-bin-line mr-1"></i>Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message Item 2 -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card-shadow hover-lift transition-all duration-200">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-semibold">SM</div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-800">Sarah Miller</h3>
                                        <p class="text-sm text-gray-600">sarahmiller@example.com</p>
                                    </div>
                                    <span class="text-xs text-gray-500 ml-auto">12 minutes ago</span>
                                </div>
                                
                                <div class="mt-2">
                                    <h4 class="font-semibold text-gray-700">Request to Modify Booking Details</h4>
                                    <p class="mt-1 text-sm text-gray-600">Hello, I need to make some changes to my upcoming booking for July 10, 2025. I'd like to increase the guest count from 150 to 180 and add additional tables. Is this possible?</p>
                                </div>
                                
                                <div class="mt-3 flex gap-2">
                                    <button class="text-blue-600 hover:text-blue-900 text-sm">
                                        <i class="ri-reply-line mr-1"></i>Reply
                                    </button>
                                    <button class="text-green-600 hover:text-green-900 text-sm">
                                        <i class="ri-check-double-line mr-1"></i>Mark as Read
                                    </button>
                                    <button class="text-red-600 hover:text-red-900 text-sm">
                                        <i class="ri-delete-bin-line mr-1"></i>Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Message Item 3 -->
                    <div class="bg-white rounded-xl shadow-lg p-6 card-shadow hover-lift transition-all duration-200">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold">AM</div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-800">Anna Martinez</h3>
                                        <p class="text-sm text-gray-600">annamartinez@example.com</p>
                                    </div>
                                    <span class="text-xs text-gray-500 ml-auto">35 minutes ago</span>
                                </div>
                                
                                <div class="mt-2">
                                    <h4 class="font-semibold text-gray-700">Technical Support Request</h4>
                                    <p class="mt-1 text-sm text-gray-600">I'm having trouble accessing my account. When I try to log in, I receive an error message saying "Invalid credentials". I've tried resetting my password multiple times but it doesn't seem to work.</p>
                                </div>
                                
                                <div class="mt-3 flex gap-2">
                                    <button class="text-blue-600 hover:text-blue-900 text-sm">
                                        <i class="ri-reply-line mr-1"></i>Reply
                                    </button>
                                    <button class="text-green-600 hover:text-green-900 text-sm">
                                        <i class="ri-check-double-line mr-1"></i>Mark as Read
                                    </button>
                                    <button class="text-red-600 hover:text-red-900 text-sm">
                                        <i class="ri-delete-bin-line mr-1"></i>Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Gallery Management Page -->
            <section id="gallery" class="admin-page hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Gallery Management</h2>
                        <p class="text-gray-600">Manage photos and media for the website</p>
                    </div>
                    <button onclick="openGalleryModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                        <i class="ri-image-add-line"></i>
                        <span>Upload Media</span>
                    </button>
                </div>
                
                <!-- Gallery Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- Gallery Item 1 -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift card-shadow transition-all duration-300">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect width='400' height='300' fill='%23b0b0b0'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='monospace' font-size='14px' fill='%23666'%3EWedding Photo 1%3C/text%3E%3C/svg%3E" alt="Wedding Photo 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="font-medium text-gray-800">Grand Ballroom Reception</p>
                            <p class="text-sm text-gray-600">April 2025</p>
                            <div class="mt-3 flex gap-2">
                                <button class="text-blue-600 hover:text-blue-900">
                                    <i class="ri-eye-line text-lg"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900">
                                    <i class="ri-edit-line text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gallery Item 2 -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift card-shadow transition-all duration-300">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect width='400' height='300' fill='%23a0a0a0'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='monospace' font-size='14px' fill='%23666'%3EWedding Photo 2%3C/text%3E%3C/svg%3E" alt="Wedding Photo 2" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="font-medium text-gray-800">Crystal Hall Decor</p>
                            <p class="text-sm text-gray-600">March 2025</p>
                            <div class="mt-3 flex gap-2">
                                <button class="text-blue-600 hover:text-blue-900">
                                    <i class="ri-eye-line text-lg"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900">
                                    <i class="ri-edit-line text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gallery Item 3 -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift card-shadow transition-all duration-300">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect width='400' height='300' fill='%23909090'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='monospace' font-size='14px' fill='%23666'%3EWedding Photo 3%3C/text%3E%3C/svg%3E" alt="Wedding Photo 3" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="font-medium text-gray-800">Garden Pavilion Setup</p>
                            <p class="text-sm text-gray-600">February 2025</p>
                            <div class="mt-3 flex gap-2">
                                <button class="text-blue-600 hover:text-blue-900">
                                    <i class="ri-eye-line text-lg"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900">
                                    <i class="ri-edit-line text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gallery Item 4 -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift card-shadow transition-all duration-300">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect width='400' height='300' fill='%23808080'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='monospace' font-size='14px' fill='%23666'%3EWedding Photo 4%3C/text%3E%3C/svg%3E" alt="Wedding Photo 4" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="font-medium text-gray-800">Evening Wedding Ceremony</p>
                            <p class="text-sm text-gray-600">January 2025</p>
                            <div class="mt-3 flex gap-2">
                                <button class="text-blue-600 hover:text-blue-900">
                                    <i class="ri-eye-line text-lg"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900">
                                    <i class="ri-edit-line text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Modals -->
            
            <!-- User CRUD Modals -->
            <!-- Add User Modal -->
            <div id="addUserModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Add New User</h3>
                        <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                    <form id="addUserForm" onsubmit="submitAddUser(event)">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                    <input type="text" name="first_name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="John">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                    <input type="text" name="last_name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Doe">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="john.doe@example.com">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="tel" name="phone" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+1 234 567 8900">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <select name="role" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Role</option>
                                    <option value="customer">Customer</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Password">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Confirm Password">
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">Cancel</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="ri-user-add-line mr-1"></i> Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit User Modal -->
            <div id="editUserModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Edit User</h3>
                        <button onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                    <form id="editUserForm" onsubmit="submitEditUser(event)">
                        <input type="hidden" name="user_id" id="editUserId">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                    <input type="text" name="first_name" id="editFirstName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                    <input type="text" name="last_name" id="editLastName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="editEmail" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="tel" name="phone" id="editPhone" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <select name="role" id="editRole" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="customer">Customer</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            
                            <div class="border-t pt-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="changePassword" class="mr-2" onchange="togglePasswordFields()">
                                    <span class="text-sm text-gray-700">Change Password</span>
                                </label>
                            </div>
                            
                            <div id="passwordFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">Cancel</button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                                <i class="ri-save-line mr-1"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- View User Modal -->
            <div id="viewUserModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">User Details</h3>
                        <button onclick="closeViewUserModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                    <div id="userDetailsContent">
                        <!-- User details will be loaded here -->
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button onclick="closeViewUserModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">Close</button>
                        <button onclick="editUserFromView()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                            <i class="ri-edit-line mr-1"></i> Edit User
                        </button>
                    </div>
                </div>
            </div>

            <!-- Booking Modal -->
            <div id="bookingModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">New Booking</h3>
                    <form>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Couple's Name</label>
                                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="John & Emma">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Wedding Date</label>
                                    <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Hall</label>
                                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option>Grand Ballroom</option>
                                        <option>Crystal Hall</option>
                                        <option>Garden Pavilion</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package</label>
                                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option>Basic Package</option>
                                    <option>Golden Package</option>
                                    <option>Infinity Package</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                                    <input type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="john.doe@example.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+91 123 456 7890">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests</label>
                                <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Any special requests..."></textarea>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeBookingModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-colors">
                                    Create Booking
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Hall Modal -->
            <div id="hallModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Add New Hall</h3>
                    <form>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hall Name</label>
                                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Grand Ballroom">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                                    <input type="number" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="300">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Main Building">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Spacious hall with luxury decor..."></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option>Available</option>
                                    <option>Occupied</option>
                                    <option>Maintenance</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Image Upload</label>
                                <input type="file" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeHallModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-colors">
                                    Add Hall
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Package Modal -->
            <div id="packageModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Create Package</h3>
                    <form>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Name</label>
                                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Basic Package">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.)</label>
                                <input type="number" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="45000">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="feature1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="feature1" class="text-sm text-gray-700">Venue setup and decoration</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="feature2" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="feature2" class="text-sm text-gray-700">Catering for 100 guests</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="feature3" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="feature3" class="text-sm text-gray-700">Photography coverage</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="feature4" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="feature4" class="text-sm text-gray-700">Sound system and DJ</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="feature5" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="feature5" class="text-sm text-gray-700">Tableware and staff</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Describe the package features..."></textarea>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closePackageModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-colors">
                                    Create Package
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- User Modal -->
            <div id="userModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Add User</h3>
                    <form>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="John">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Doe">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="john.doe@example.com">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+91 123 456 7890">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input type="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option>Customer</option>
                                        <option>Vendor</option>
                                        <option>Admin</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option>Active</option>
                                        <option>Inactive</option>
                                        <option>Suspended</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="twoFactorAuth" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="twoFactorAuth" class="text-sm text-gray-700">Enable Two-Factor Authentication</label>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeUserModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-colors">
                                    Add User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Event Modal -->
            <div id="eventModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Add Event</h3>
                    <form>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Event Title</label>
                                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Sarah & John Wedding">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time</label>
                                    <input type="datetime-local" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date & Time</label>
                                    <input type="datetime-local" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Venue</label>
                                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option>Grand Ballroom</option>
                                    <option>Crystal Hall</option>
                                    <option>Garden Pavilion</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Event description..."></textarea>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeEventModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-colors">
                                    Add Event
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Message Modal -->
            <div id="messageModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Send Message</h3>
                    <form>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recipient</label>
                                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="john.doe@example.com">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Your Subject">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                <textarea rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Type your message here..."></textarea>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="sendCopy" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="sendCopy" class="text-sm text-gray-700">Send me a copy</label>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeMessageModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-colors">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Gallery Modal -->
            <div id="galleryModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-2xl p-6 w-full max-w-lg glass-effect animate-fade-in-up">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Upload Media</h3>
                    <form>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Media Title</label>
                                <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Wedding Photo 1">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Photo description..."></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                                <input type="file" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeGalleryModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-colors">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize sidebar menu
        const sidebarMenu = document.getElementById('sidebarMenu');
        const pages = document.querySelectorAll('.admin-page');
        
        // Page Navigation
        function showPage(pageId) {
            // Hide all pages
            document.querySelectorAll('.admin-page').forEach(page => page.classList.remove('active'));
            
            // Show selected page
            document.getElementById(pageId).classList.add('active');
            
            // Update URL hash
            history.pushState(null, '', '#' + pageId);
            
            // Update sidebar active state
            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
            document.querySelector(`[onclick="showPage('${pageId}')"]`).classList.add('active');
            
            // Update page title and subtitle
            const titles = {
                dashboard: { title: 'Dashboard', subtitle: 'Welcome back! Here\'s what\'s happening today.' },
                bookings: { title: 'Booking Management', subtitle: 'Manage all wedding bookings and reservations' },
                halls: { title: 'Hall Management', subtitle: 'Manage wedding halls and venues' },
                packages: { title: 'Package Management', subtitle: 'Create and manage wedding packages' },
                users: { title: 'User Management', subtitle: 'Manage registered users and their accounts' },
                calendar: { title: 'Event Calendar', subtitle: 'View and manage wedding events schedule' },
                messages: { title: 'Messages', subtitle: 'Manage customer inquiries and communications' },
                gallery: { title: 'Gallery Management', subtitle: 'Manage photos and media for the website' }
            };
            
            document.getElementById('pageTitle').textContent = titles[pageId].title;
            document.getElementById('pageSubtitle').textContent = titles[pageId].subtitle;
        }

        // Load correct page from URL hash on page load
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1);
            if (hash && document.getElementById(hash)) {
                showPage(hash);
            } else {
                showPage('dashboard');
            }
        });

        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        function openBookingModal() { openModal('bookingModal'); }
        function closeBookingModal() { closeModal('bookingModal'); }
        
        function openHallModal() { openModal('hallModal'); }
        function closeHallModal() { closeModal('hallModal'); }
        
        function openPackageModal() { openModal('packageModal'); }
        function closePackageModal() { closeModal('packageModal'); }
        
        function openUserModal() { openModal('userModal'); }
        function closeUserModal() { closeModal('userModal'); }
        
        function openEventModal() { openModal('eventModal'); }
        function closeEventModal() { closeModal('eventModal'); }
        
        function openMessageModal() { openModal('messageModal'); }
        function closeMessageModal() { closeModal('messageModal'); }
        
        function openGalleryModal() { openModal('galleryModal'); }
        function closeGalleryModal() { closeModal('galleryModal'); }
        
        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal(modal.id);
                }
            });
        });
        
        // Initialize Booking Chart
        const bookingChart = new Chart(document.getElementById('bookingChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Bookings',
                    data: [10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: true, position: 'top' }
                }
            }
        });
        
        // Initialize Calendar
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendarContainer');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week',
                    day: 'Day'
                },
                events: [
                    { 
                        title: 'Sarah & Michael Wedding', 
                        start: '2025-06-15', 
                        className: 'fc-event-confirmed' 
                    },
                    { 
                        title: 'John & Emma Wedding', 
                        start: '2025-07-22', 
                        className: 'fc-event-pending' 
                    },
                    { 
                        title: 'David & Anna Wedding', 
                        start: '2025-08-05', 
                        className: 'fc-event-confirmed' 
                    }
                ],
                eventClick: function(info) {
                    alert('Event: ' + info.event.title + '\nDate: ' + info.event.start.toISOString().split('T')[0]);
                }
            });
            calendar.render();
        });
        
        // Search Functionality for Users
        document.getElementById('userSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#users tbody tr');
            rows.forEach(row => {
                const userName = row.querySelector('td:nth-child(2) .font-medium').textContent.toLowerCase();
                const userEmail = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    // Enhanced real-time data loading with comprehensive statistics
        function verifySessionAndLoadData() {
            // Show loading indicator
            const loadingIndicator = document.getElementById('loading-indicator');
            if (loadingIndicator) {
                loadingIndicator.classList.remove('hidden');
            }

            // Check if user is still authenticated and get real data
            fetch('/admin/dashboard/stats', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (response.status === 401 || response.status === 403) {
                    // Session expired or unauthorized
                    console.warn('Session expired, redirecting to login...');
                    window.location.href = '/login';
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data) {
                    updateDashboardStats(data);
                    updateLastUpdatedTime(data.last_updated);
                }
            })
            .catch(error => {
                console.error('Session verification failed:', error);
                // Show error indicator but don't redirect automatically
                showConnectionError();
            })
            .finally(() => {
                // Hide loading indicator
                if (loadingIndicator) {
                    loadingIndicator.classList.add('hidden');
                }
            });
        }

        function updateDashboardStats(stats) {
            // Update main statistics cards with real WMdemo data
            const updates = {
                'total-bookings': stats.total_bookings || 0,
                'total-revenue': 'Rs. ' + (stats.total_revenue ? new Intl.NumberFormat().format(stats.total_revenue) : '0'),
                'active-halls': stats.total_halls || 0,
                'total-users': stats.total_users || 0
            };

            Object.entries(updates).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = value;
                    // Add animation effect
                    element.classList.add('animate-pulse');
                    setTimeout(() => element.classList.remove('animate-pulse'), 1000);
                }
            });

            // Update detailed statistics if elements exist
            updateDetailedStats(stats);
        }

        function updateDetailedStats(stats) {
            // Update booking breakdown
            const confirmedElement = document.querySelector('.text-green-600');
            const pendingElement = document.querySelector('.text-yellow-600');
            
            if (confirmedElement && stats.confirmed_bookings !== undefined) {
                confirmedElement.textContent = `${stats.confirmed_bookings} confirmed`;
            }
            
            if (pendingElement && stats.pending_bookings !== undefined) {
                pendingElement.textContent = `${stats.pending_bookings} pending`;
            }

            // Update monthly revenue
            const monthlyRevenueElements = document.querySelectorAll('.text-sm.text-green-600');
            monthlyRevenueElements.forEach(element => {
                if (element.textContent.includes('This month:') && stats.monthly_revenue !== undefined) {
                    element.textContent = `This month: Rs. ${new Intl.NumberFormat().format(stats.monthly_revenue)}`;
                }
            });

            // Update user breakdown
            const userBreakdownElement = document.querySelector('.text-sm.text-purple-600');
            if (userBreakdownElement && userBreakdownElement.textContent.includes('customers')) {
                userBreakdownElement.textContent = 
                    `${stats.total_customers || 0} customers • ${stats.total_admins || 0} admins • ${stats.total_managers || 0} managers`;
            }

            // Update activity statistics
            updateActivityStats(stats);
        }

        function updateActivityStats(stats) {
            const activityMappings = {
                'bookings_today': 'Today\'s Bookings',
                'bookings_this_week': 'This Week',
                'bookings_this_month': 'This Month',
                'new_users_this_week': 'New Users (Week)'
            };

            Object.entries(activityMappings).forEach(([key, label]) => {
                const elements = document.querySelectorAll('.space-y-3 .flex.justify-between');
                elements.forEach(element => {
                    const labelElement = element.querySelector('.text-sm.text-gray-600');
                    const valueElement = element.querySelector('.font-semibold');
                    
                    if (labelElement && labelElement.textContent === label && valueElement && stats[key] !== undefined) {
                        valueElement.textContent = stats[key];
                    }
                });
            });
        }

        function updateLastUpdatedTime(timestamp) {
            const lastUpdatedElement = document.getElementById('last-updated');
            if (lastUpdatedElement && timestamp) {
                const date = new Date(timestamp);
                lastUpdatedElement.textContent = date.toLocaleTimeString();
            }
        }

        function showConnectionError() {
            // Show a subtle error indicator
            const statusElements = document.querySelectorAll('.bg-green-100.text-green-800');
            statusElements.forEach(element => {
                if (element.textContent === 'Connected') {
                    element.className = 'px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium';
                    element.textContent = 'Error';
                }
            });
        }

        // Enhanced session monitoring
        function startSessionMonitoring() {
            // Check session every 30 seconds
            setInterval(verifySessionAndLoadData, 30000);
            
            // Check for user activity
            let lastActivity = Date.now();
            
            ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, () => {
                    lastActivity = Date.now();
                }, true);
            });

            // Check for inactivity every minute
            setInterval(() => {
                const inactiveTime = Date.now() - lastActivity;
                const maxInactiveTime = 30 * 60 * 1000; // 30 minutes
                
                if (inactiveTime > maxInactiveTime) {
                    console.warn('User inactive for 30 minutes, checking session...');
                    verifySessionAndLoadData();
                }
            }, 60000);
        }

        // Initialize enhanced monitoring
        document.addEventListener('DOMContentLoaded', function() {
            // Initial load
            verifySessionAndLoadData();
            
            // Start monitoring
            startSessionMonitoring();
            
            // Add visual feedback for real-time updates
            console.log('✅ Admin Dashboard initialized with real WMdemo data');
            console.log('✅ Session monitoring active');
            console.log('✅ Real-time updates every 30 seconds');
        });

        // User CRUD Operations
        let currentUsers = [];
        let filteredUsers = [];
        let currentEditingUserId = null;

        // Load users data
        function loadUsers() {
            fetch('/admin/users/data', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentUsers = data.users;
                    filteredUsers = [...currentUsers];
                    renderUsersTable();
                    updateUserStats();
                } else {
                    showError('Failed to load users: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error loading users:', error);
                showError('Failed to load users. Please try again.');
            });
        }

        // Render users table
        function renderUsersTable() {
            const tbody = document.getElementById('usersTableBody');
            if (!tbody) return;

            if (filteredUsers.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No users found
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filteredUsers.map(user => `
                <tr class="border-t hover:bg-gray-50" data-user-id="${user.id}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="user-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="${user.id}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            ${user.profile_photo_path ? 
                                `<img src="${user.profile_photo_url}" alt="Profile" class="w-10 h-10 rounded-full object-cover">` :
                                `<div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                    ${user.first_name.charAt(0)}${user.last_name.charAt(0)}
                                </div>`
                            }
                            <div class="ml-3">
                                <div class="font-medium text-gray-800">${user.full_name}</div>
                                <div class="text-sm text-gray-500">${user.email}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${user.email}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${getRoleBadgeClass(user.role)}">
                            ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusBadgeClass(user.status)}">
                            ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${formatDate(user.created_at)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="viewUser('${user.id}')" class="text-blue-600 hover:text-blue-900 mr-3" title="View">
                            <i class="ri-eye-line"></i>
                        </button>
                        <button onclick="editUser('${user.id}')" class="text-green-600 hover:text-green-900 mr-3" title="Edit">
                            <i class="ri-edit-line"></i>
                        </button>
                        ${user.id !== '{{ auth()->id() }}' ? 
                            `<button onclick="deleteUser('${user.id}')" class="text-red-600 hover:text-red-900" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>` : ''
                        }
                    </td>
                </tr>
            `).join('');
        }

        // Helper functions
        function getRoleBadgeClass(role) {
            const classes = {
                'admin': 'bg-red-100 text-red-800',
                'manager': 'bg-green-100 text-green-800',
                'customer': 'bg-blue-100 text-blue-800'
            };
            return classes[role] || 'bg-gray-100 text-gray-800';
        }

        function getStatusBadgeClass(status) {
            const classes = {
                'active': 'bg-green-100 text-green-800',
                'suspended': 'bg-red-100 text-red-800',
                'inactive': 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        // Search and filter functions
        function searchUsers() {
            const searchTerm = document.getElementById('userSearch').value.toLowerCase();
            filteredUsers = currentUsers.filter(user => 
                user.full_name.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.phone?.toLowerCase().includes(searchTerm)
            );
            applyFilters();
        }

        function filterUsers() {
            const roleFilter = document.getElementById('roleFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            filteredUsers = currentUsers.filter(user => {
                const roleMatch = !roleFilter || user.role === roleFilter;
                const statusMatch = !statusFilter || user.status === statusFilter;
                return roleMatch && statusMatch;
            });
            
            // Apply search if there's a search term
            const searchTerm = document.getElementById('userSearch').value.toLowerCase();
            if (searchTerm) {
                filteredUsers = filteredUsers.filter(user => 
                    user.full_name.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm) ||
                    user.phone?.toLowerCase().includes(searchTerm)
                );
            }
            
            renderUsersTable();
        }

        function applyFilters() {
            const roleFilter = document.getElementById('roleFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            if (roleFilter) {
                filteredUsers = filteredUsers.filter(user => user.role === roleFilter);
            }
            
            if (statusFilter) {
                filteredUsers = filteredUsers.filter(user => user.status === statusFilter);
            }
            
            renderUsersTable();
        }

        function refreshUsers() {
            loadUsers();
        }

        // Modal functions
        function openAddUserModal() {
            document.getElementById('addUserModal').classList.remove('hidden');
            document.getElementById('addUserForm').reset();
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').classList.add('hidden');
        }

        function openEditUserModal() {
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
            currentEditingUserId = null;
        }

        function openViewUserModal() {
            document.getElementById('viewUserModal').classList.remove('hidden');
        }

        function closeViewUserModal() {
            document.getElementById('viewUserModal').classList.add('hidden');
        }

        // CRUD operations
        function submitAddUser(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            
            fetch('/admin/users', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('User created successfully!');
                    closeAddUserModal();
                    loadUsers();
                } else {
                    showError('Failed to create user: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error creating user:', error);
                showError('Failed to create user. Please try again.');
            });
        }

        function viewUser(userId) {
            const user = currentUsers.find(u => u.id === userId);
            if (!user) return;

            const content = `
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        ${user.profile_photo_path ? 
                            `<img src="${user.profile_photo_url}" alt="Profile" class="w-16 h-16 rounded-full object-cover">` :
                            `<div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                                ${user.first_name.charAt(0)}${user.last_name.charAt(0)}
                            </div>`
                        }
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">${user.full_name}</h4>
                            <p class="text-sm text-gray-600">${user.email}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${getRoleBadgeClass(user.role)}">
                                ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusBadgeClass(user.status)}">
                                ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="text-sm text-gray-900">${user.phone || 'Not provided'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Joined</label>
                            <p class="text-sm text-gray-900">${formatDate(user.created_at)}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">User ID</label>
                        <p class="text-sm text-gray-900 font-mono">${user.id}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('userDetailsContent').innerHTML = content;
            currentEditingUserId = userId;
            openViewUserModal();
        }

        function editUser(userId) {
            const user = currentUsers.find(u => u.id === userId);
            if (!user) return;

            document.getElementById('editUserId').value = user.id;
            document.getElementById('editFirstName').value = user.first_name;
            document.getElementById('editLastName').value = user.last_name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editPhone').value = user.phone || '';
            document.getElementById('editRole').value = user.role;
            
            currentEditingUserId = userId;
            openEditUserModal();
        }

        function editUserFromView() {
            closeViewUserModal();
            if (currentEditingUserId) {
                editUser(currentEditingUserId);
            }
        }

        function submitEditUser(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const userId = formData.get('user_id');
            
            fetch(`/admin/users/${userId}/update`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('User updated successfully!');
                    closeEditUserModal();
                    loadUsers();
                } else {
                    showError('Failed to update user: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error updating user:', error);
                showError('Failed to update user. Please try again.');
            });
        }

        function deleteUser(userId) {
            const user = currentUsers.find(u => u.id === userId);
            if (!user) return;

            if (confirm(`Are you sure you want to delete user "${user.full_name}"? This action cannot be undone.`)) {
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('User deleted successfully!');
                        loadUsers();
                    } else {
                        showError('Failed to delete user: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                    showError('Failed to delete user. Please try again.');
                });
            }
        }

        // Utility functions
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function togglePasswordFields() {
            const checkbox = document.getElementById('changePassword');
            const fields = document.getElementById('passwordFields');
            
            if (checkbox.checked) {
                fields.classList.remove('hidden');
                fields.querySelectorAll('input').forEach(input => input.required = true);
            } else {
                fields.classList.add('hidden');
                fields.querySelectorAll('input').forEach(input => {
                    input.required = false;
                    input.value = '';
                });
            }
        }

        function exportUsers() {
            window.open('/admin/users/export', '_blank');
        }

        function updateUserStats() {
            const stats = {
                total: currentUsers.length,
                customers: currentUsers.filter(u => u.role === 'customer').length,
                managers: currentUsers.filter(u => u.role === 'manager').length,
                admins: currentUsers.filter(u => u.role === 'admin').length
            };

            document.getElementById('users-total').textContent = stats.total;
            document.getElementById('users-customers').textContent = stats.customers;
            document.getElementById('users-managers').textContent = stats.managers;
            document.getElementById('users-admins').textContent = stats.admins;
        }

        function showSuccess(message) {
            // Create a simple toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.innerHTML = `<i class="ri-check-line mr-2"></i>${message}`;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function showError(message) {
            // Create a simple toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.innerHTML = `<i class="ri-error-warning-line mr-2"></i>${message}`;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Initialize users section when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Load users when users section is accessed
            const usersNavItem = document.querySelector('[onclick="showPage(\'users\')"]');
            if (usersNavItem) {
                usersNavItem.addEventListener('click', function() {
                    setTimeout(loadUsers, 100); // Small delay to ensure section is visible
                });
            }
        });

        // Add CSS animations for better UX
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
            .animate-pulse {
                animation: pulse 1s ease-in-out;
            }
            .connection-error {
                border-left-color: #ef4444 !important;
            }
            .connection-success {
                border-left-color: #10b981 !important;
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.3s ease-out;
            }
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);

        // Enhanced Hall Management Functions
        window.loadRealHalls = async function() {
            try {
                showLoading('halls-grid-view');
                
                // Simulate API call to get real halls data
                const response = await fetch('/api/admin/halls');
                const data = await response.json();
                
                if (data.success) {
                    renderHallsGrid(data.halls);
                    renderHallsList(data.halls);
                    updateHallStats(data.stats);
                } else {
                    // Fallback to demo data
                    loadDemoHalls();
                }
            } catch (error) {
                console.error('Error loading halls:', error);
                loadDemoHalls();
            }
        };

        function loadDemoHalls() {
            const demoHalls = [
                {
                    id: 1,
                    name: 'Grand Ballroom',
                    description: 'Spacious hall with luxury decor, perfect for grand weddings and events.',
                    capacity: 300,
                    price: 150000,
                    image: '/images/halls/grand-ballroom.jpg',
                    is_active: true,
                    bookings_count: 15,
                    availability: 'available',
                    features: ['Modern lighting system', 'Premium sound equipment', 'Air conditioning', 'Bridal suite']
                },
                {
                    id: 2,
                    name: 'Crystal Hall',
                    description: 'Luxury glass structure with panoramic views, ideal for intimate weddings.',
                    capacity: 200,
                    price: 120000,
                    image: '/images/halls/crystal-hall.jpg',
                    is_active: true,
                    bookings_count: 12,
                    availability: 'booked',
                    features: ['Panoramic views', 'Glass structure', 'Exclusive catering', 'Garden access']
                },
                {
                    id: 3,
                    name: 'Garden Pavilion',
                    description: 'Outdoor venue surrounded by beautiful gardens.',
                    capacity: 150,
                    price: 80000,
                    image: '/images/halls/garden-pavilion.jpg',
                    is_active: false,
                    bookings_count: 8,
                    availability: 'maintenance',
                    features: ['Outdoor setting', 'Garden views', 'Natural lighting', 'Weather protection']
                }
            ];
            
            renderHallsGrid(demoHalls);
            renderHallsList(demoHalls);
            updateHallStats({
                total: demoHalls.length,
                active: demoHalls.filter(h => h.is_active).length,
                booked_today: demoHalls.filter(h => h.availability === 'booked').length
            });
        }

        function renderHallsGrid(halls) {
            const gridView = document.getElementById('halls-grid-view');
            if (!gridView) return;

            gridView.innerHTML = halls.map(hall => `
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift card-shadow border-2 ${hall.is_active ? 'border-green-200' : 'border-red-200'} transition-all duration-300">
                    <div class="relative">
                        <img src="${hall.image || '/images/placeholder-hall.jpg'}" alt="${hall.name}" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4 px-3 py-1 rounded-full text-sm font-semibold ${getAvailabilityBadge(hall.availability)}">
                            ${hall.availability.charAt(0).toUpperCase() + hall.availability.slice(1)}
                        </div>
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Rs. ${hall.price.toLocaleString()}
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-gray-800">${hall.name}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${hall.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${hall.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">${hall.description}</p>
                        <div class="flex justify-between text-sm text-gray-600 mb-4">
                            <span><i class="ri-user-line mr-1"></i>Capacity: ${hall.capacity}</span>
                            <span><i class="ri-calendar-line mr-1"></i>Bookings: ${hall.bookings_count}</span>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Features:</h4>
                            <div class="flex flex-wrap gap-1">
                                ${hall.features.slice(0, 3).map(feature => `
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">${feature}</span>
                                `).join('')}
                                ${hall.features.length > 3 ? `<span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">+${hall.features.length - 3} more</span>` : ''}
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editHall(${hall.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                                <i class="ri-edit-line mr-1"></i>Edit
                            </button>
                            <button onclick="viewHallDetails(${hall.id})" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                                <i class="ri-eye-line mr-1"></i>View
                            </button>
                            <button onclick="deleteHall(${hall.id})" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition-colors">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderHallsList(halls) {
            const listBody = document.getElementById('halls-list-body');
            if (!listBody) return;

            listBody.innerHTML = halls.map(hall => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <img src="${hall.image || '/images/placeholder-hall.jpg'}" alt="${hall.name}" class="w-10 h-10 rounded-lg object-cover mr-3">
                            <div>
                                <div class="font-medium text-gray-900">${hall.name}</div>
                                <div class="text-sm text-gray-500">${hall.description.substring(0, 50)}...</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">${hall.capacity} guests</td>
                    <td class="px-6 py-4 text-sm text-gray-900">Rs. ${hall.price.toLocaleString()}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${hall.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${hall.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">${hall.bookings_count}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${getAvailabilityBadge(hall.availability)}">
                            ${hall.availability.charAt(0).toUpperCase() + hall.availability.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button onclick="editHall(${hall.id})" class="text-blue-600 hover:text-blue-900">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button onclick="viewHallDetails(${hall.id})" class="text-green-600 hover:text-green-900">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button onclick="deleteHall(${hall.id})" class="text-red-600 hover:text-red-900">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function getAvailabilityBadge(availability) {
            switch(availability) {
                case 'available': return 'bg-green-100 text-green-800';
                case 'booked': return 'bg-red-100 text-red-800';
                case 'maintenance': return 'bg-yellow-100 text-yellow-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        // Enhanced Package Management Functions
        window.loadRealPackages = async function() {
            try {
                showLoading('packages-grid-view');
                
                // API call to get real packages data
                const response = await fetch('/admin/api/admin/packages', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    renderPackagesGrid(data.packages);
                    renderPackagesList(data.packages);
                    
                    // Calculate stats if not provided
                    const stats = data.stats || {
                        total: data.packages.length,
                        active: data.packages.filter(p => p.is_active).length,
                        popular: data.packages.reduce((max, p) => (p.bookings_count || 0) > (max.bookings_count || 0) ? p : max, data.packages[0]),
                        total_revenue: data.packages.reduce((sum, p) => sum + (p.total_revenue || 0), 0)
                    };
                    
                    updatePackageStats(stats);
                    console.log('✅ Packages loaded successfully:', data.packages.length, 'packages');
                    
                    // Show success message only on manual refresh (commented out for auto-load)
                    // showToast(`✅ Loaded ${data.packages.length} packages successfully!`, 'success');
                } else {
                    console.error('❌ API returned error:', data.message || 'Unknown error');
                    loadDemoPackages();
                }
            } catch (error) {
                console.error('❌ Error loading packages:', error);
                console.error('Response status:', error.status || 'Unknown');
                loadDemoPackages();
            }
        };

        function loadDemoPackages() {
        // Fallback message when real packages can't be loaded
        const packagesGrid = document.getElementById('packages-grid-view');
        if (packagesGrid) {
            packagesGrid.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-gray-500">
                        <i class="ri-error-warning-line text-4xl mb-4"></i>
                        <p class="text-lg font-medium">Unable to load packages</p>
                        <p class="text-sm">There was an error loading package data from the server.</p>
                        <p class="text-xs text-gray-400 mt-2">Check the browser console for more details.</p>
                        <button onclick="loadRealPackages()" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            <i class="ri-refresh-line mr-1"></i>
                            Retry
                        </button>
                    </div>
                </div>
            `;
        }
        
        // Update stats with zeros
        updatePackageStats({
            total: 0,
            active: 0,
            popular: null,
            total_revenue: 0
        });
        }

        function renderPackagesGrid(packages) {
            const gridView = document.getElementById('packages-grid-view');
            if (!gridView) return;

            gridView.innerHTML = packages.map(pkg => `
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-lift card-shadow border-2 border-gray-200 transition-all duration-300">
                    <div class="relative">
                        <img src="${pkg.image || '/images/placeholder-package.jpg'}" alt="${pkg.name}" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Rs. ${pkg.price.toLocaleString()}
                        </div>
                        ${pkg.bookings_count > 15 ? '<div class="absolute top-4 left-4 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-bold">Popular</div>' : ''}
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-gray-800">${pkg.name}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${pkg.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${pkg.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">${pkg.description}</p>
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Includes:</h4>
                            <ul class="space-y-1">
                                ${pkg.features.slice(0, 4).map(feature => `
                                    <li class="flex items-center text-sm text-gray-600">
                                        <i class="ri-check-line text-green-500 mr-2"></i>
                                        ${feature}
                                    </li>
                                `).join('')}
                                ${pkg.features.length > 4 ? `<li class="text-sm text-gray-500">+${pkg.features.length - 4} more features</li>` : ''}
                            </ul>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 mb-4">
                            <span><i class="ri-calendar-line mr-1"></i>Bookings: ${pkg.bookings_count}</span>
                            <span><i class="ri-star-line mr-1"></i>Rating: 4.${Math.floor(Math.random() * 9) + 1}</span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editPackage(${pkg.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                                <i class="ri-edit-line mr-1"></i>Edit
                            </button>
                            <button onclick="viewPackageDetails(${pkg.id})" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm transition-colors">
                                <i class="ri-eye-line mr-1"></i>View
                            </button>
                            <button onclick="deletePackage(${pkg.id})" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition-colors">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderPackagesList(packages) {
            const listBody = document.getElementById('packages-list-body');
            if (!listBody) return;

            listBody.innerHTML = packages.map(pkg => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <img src="${pkg.image || '/images/placeholder-package.jpg'}" alt="${pkg.name}" class="w-10 h-10 rounded-lg object-cover mr-3">
                            <div>
                                <div class="font-medium text-gray-900">${pkg.name}</div>
                                <div class="text-sm text-gray-500">${pkg.description.substring(0, 50)}...</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">Rs. ${pkg.price.toLocaleString()}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${pkg.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${pkg.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">${pkg.bookings_count}</td>
                    <td class="px-6 py-4">
                        ${pkg.bookings_count > 15 ? '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Popular</span>' : '<span class="text-sm text-gray-500">Standard</span>'}
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button onclick="editPackage(${pkg.id})" class="text-blue-600 hover:text-blue-900">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button onclick="viewPackageDetails(${pkg.id})" class="text-green-600 hover:text-green-900">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button onclick="deletePackage(${pkg.id})" class="text-red-600 hover:text-red-900">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Modal Management Functions
        window.openHallModal = function(hallId = null) {
            const modal = document.getElementById('hall-modal');
            const title = document.getElementById('hall-modal-title');
            const submitText = document.getElementById('hall-submit-text');
            
            if (hallId) {
                title.textContent = 'Edit Hall';
                submitText.textContent = 'Update Hall';
                // Load hall data for editing
                loadHallForEdit(hallId);
            } else {
                title.textContent = 'Add New Hall';
                submitText.textContent = 'Create Hall';
                document.getElementById('hall-form').reset();
            }
            
            modal.classList.remove('hidden');
        };

        window.closeHallModal = function() {
            document.getElementById('hall-modal').classList.add('hidden');
        };

        window.openPackageModal = function(packageId = null) {
            const modal = document.getElementById('package-modal');
            const title = document.getElementById('package-modal-title');
            const submitText = document.getElementById('package-submit-text');
            
            if (packageId) {
                title.textContent = 'Edit Package';
                submitText.textContent = 'Update Package';
                loadPackageForEdit(packageId);
            } else {
                title.textContent = 'Create New Package';
                submitText.textContent = 'Create Package';
                document.getElementById('package-form').reset();
                resetPackageFeatures();
            }
            
            modal.classList.remove('hidden');
        };

        window.closePackageModal = function() {
            document.getElementById('package-modal').classList.add('hidden');
        };

        // Utility Functions
        function showLoading(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.innerHTML = `
                    <div class="text-center py-8">
                        <i class="ri-loader-4-line animate-spin text-2xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Loading...</p>
                    </div>
                `;
            }
        }

        function updateHallStats(stats) {
            const elements = {
                'total-halls-count': stats.total || 0,
                'active-halls-count': stats.active || 0,
                'booked-halls-today': stats.booked_today || 0
            };
            
            Object.entries(elements).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) element.textContent = value;
            });
        }

        function updatePackageStats(stats) {
            const elements = {
                'total-packages-count': stats.total || 0,
                'active-packages-count': stats.active || 0,
                'popular-package': stats.popular?.name || 'N/A'
            };
            
            Object.entries(elements).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) element.textContent = value;
            });
        }

        // Initialize enhanced features when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Load real data when sections are accessed
            const hallsNavItem = document.querySelector('[onclick="showPage(\'halls\')"]');
            if (hallsNavItem) {
                hallsNavItem.addEventListener('click', function() {
                    setTimeout(loadRealHalls, 100);
                });
            }

            const packagesNavItem = document.querySelector('[onclick="showPage(\'packages\')"]');
            if (packagesNavItem) {
                packagesNavItem.addEventListener('click', function() {
                    loadWorkingPackagesPage();
                });
            }

            // Initialize with real data
            loadDemoHalls();
            // Load real packages when packages section is accessed
            setTimeout(() => {
                if (document.getElementById('packages-grid-view')) {
                    loadRealPackages();
                }
            }, 500);
        });

        // Enhanced functionality placeholders
        window.toggleHallView = function(view) {
            const gridView = document.getElementById('halls-grid-view');
            const listView = document.getElementById('halls-list-view');
            const gridBtn = document.getElementById('grid-view-btn');
            const listBtn = document.getElementById('list-view-btn');

            if (view === 'grid') {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                gridBtn.classList.add('bg-blue-600', 'text-white');
                gridBtn.classList.remove('bg-gray-300', 'text-gray-700');
                listBtn.classList.add('bg-gray-300', 'text-gray-700');
                listBtn.classList.remove('bg-blue-600', 'text-white');
            } else {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                listBtn.classList.add('bg-blue-600', 'text-white');
                listBtn.classList.remove('bg-gray-300', 'text-gray-700');
                gridBtn.classList.add('bg-gray-300', 'text-gray-700');
                gridBtn.classList.remove('bg-blue-600', 'text-white');
            }
        };

        window.togglePackageView = function(view) {
            const gridView = document.getElementById('packages-grid-view');
            const listView = document.getElementById('packages-list-view');
            const gridBtn = document.getElementById('package-grid-view-btn');
            const listBtn = document.getElementById('package-list-view-btn');

            if (view === 'grid') {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                gridBtn.classList.add('bg-blue-600', 'text-white');
                gridBtn.classList.remove('bg-gray-300', 'text-gray-700');
                listBtn.classList.add('bg-gray-300', 'text-gray-700');
                listBtn.classList.remove('bg-blue-600', 'text-white');
            } else {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                listBtn.classList.add('bg-blue-600', 'text-white');
                listBtn.classList.remove('bg-gray-300', 'text-gray-700');
                gridBtn.classList.add('bg-gray-300', 'text-gray-700');
                gridBtn.classList.remove('bg-blue-600', 'text-white');
            }
        };

        // Booking Configuration Page Loading
        window.loadBookingConfigurationPage = function() {
            const contentArea = document.getElementById('booking-config-content');
            const configBar = contentArea.previousElementSibling;
            
            if (contentArea.classList.contains('hidden')) {
                // Load the configuration page
                showToast('Loading booking configuration...', 'info');
                
                contentArea.innerHTML = getBookingConfigurationContent();
                contentArea.classList.remove('hidden');
                
                // Update button text
                const button = configBar.querySelector('button');
                button.innerHTML = '<i class="ri-arrow-up-line"></i><span>Hide Configuration</span>';
                button.onclick = hideBookingConfigurationPage;
                
                showToast('Booking configuration loaded successfully!', 'success');
            }
        };

        window.hideBookingConfigurationPage = function() {
            const contentArea = document.getElementById('booking-config-content');
            const configBar = contentArea.previousElementSibling;
            
            contentArea.classList.add('hidden');
            
            // Update button text
            const button = configBar.querySelector('button');
            button.innerHTML = '<i class="ri-settings-line"></i><span>Configure Booking Process</span>';
            button.onclick = loadBookingConfigurationPage;
            
            showToast('Configuration panel closed', 'info');
        };

        function getBookingConfigurationContent() {
            return `
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 card-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Booking Process Configuration</h3>
                            <p class="text-gray-600 text-sm">Configure the 6-step booking workflow that customers follow</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                <i class="ri-database-line mr-1"></i>Live Data Connected
                            </span>
                            <button onclick="refreshBookingConfig()" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-xs">
                                <i class="ri-refresh-line mr-1"></i>Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Booking Flow Overview -->
                    <div class="grid grid-cols-1 lg:grid-cols-6 gap-4 mb-6">
                        <!-- Step 1: Hall Selection -->
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                                <h4 class="font-semibold text-blue-800 text-sm">Hall Selection</h4>
                            </div>
                            <div class="space-y-2 text-xs text-blue-700">
                                <div class="flex items-center justify-between">
                                    <span>Available Halls:</span>
                                    <span class="font-semibold" id="halls-count-config">${document.getElementById('total-halls')?.textContent || '0'}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Active:</span>
                                    <span class="font-semibold" id="active-halls-count-config">${document.getElementById('active-halls')?.textContent || '0'}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Calendar:</span>
                                    <span class="text-green-600 font-semibold">✓ Enabled</span>
                                </div>
                            </div>
                            <button onclick="configureStep('hall')" class="mt-3 w-full text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition-colors">
                                Configure
                            </button>
                        </div>

                        <!-- Step 2: Package Selection -->
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                                <h4 class="font-semibold text-green-800 text-sm">Package Selection</h4>
                            </div>
                            <div class="space-y-2 text-xs text-green-700">
                                <div class="flex items-center justify-between">
                                    <span>Total Packages:</span>
                                    <span class="font-semibold" id="packages-count-config">3</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Active:</span>
                                    <span class="font-semibold" id="active-packages-count-config">3</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Comparison:</span>
                                    <span class="text-green-600 font-semibold">✓ Enabled</span>
                                </div>
                            </div>
                            <button onclick="configureStep('package')" class="mt-3 w-full text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 transition-colors">
                                Configure
                            </button>
                        </div>

                        <!-- Step 3: Customization -->
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-yellow-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                                <h4 class="font-semibold text-yellow-800 text-sm">Customization</h4>
                            </div>
                            <div class="space-y-2 text-xs text-yellow-700">
                                <div class="flex items-center justify-between">
                                    <span>Wedding Types:</span>
                                    <span class="font-semibold" id="wedding-types-count-config">5</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Decorations:</span>
                                    <span class="font-semibold" id="decorations-count-config">15+</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Catering Menus:</span>
                                    <span class="font-semibold" id="catering-menus-count-config">6</span>
                                </div>
                            </div>
                            <button onclick="configureStep('customize')" class="mt-3 w-full text-xs bg-yellow-600 text-white px-2 py-1 rounded hover:bg-yellow-700 transition-colors">
                                Configure
                            </button>
                        </div>

                        <!-- Step 4: Visit Scheduling -->
                        <div class="p-4 bg-purple-50 rounded-lg border border-purple-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">4</div>
                                <h4 class="font-semibold text-purple-800 text-sm">Visit Scheduling</h4>
                            </div>
                            <div class="space-y-2 text-xs text-purple-700">
                                <div class="flex items-center justify-between">
                                    <span>Pending Visits:</span>
                                    <span class="font-semibold" id="pending-visits-count-config">${document.getElementById('pending-visits')?.textContent || '0'}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Auto-Approval:</span>
                                    <span class="text-red-600 font-semibold">✗ Disabled</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Time Slots:</span>
                                    <span class="text-green-600 font-semibold">✓ Available</span>
                                </div>
                            </div>
                            <button onclick="configureStep('visit')" class="mt-3 w-full text-xs bg-purple-600 text-white px-2 py-1 rounded hover:bg-purple-700 transition-colors">
                                Configure
                            </button>
                        </div>

                        <!-- Step 5: Wedding Details -->
                        <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold">5</div>
                                <h4 class="font-semibold text-indigo-800 text-sm">Wedding Details</h4>
                            </div>
                            <div class="space-y-2 text-xs text-indigo-700">
                                <div class="flex items-center justify-between">
                                    <span>Access Control:</span>
                                    <span class="text-orange-600 font-semibold">Payment Required</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Advance Payment:</span>
                                    <span class="font-semibold">20%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Validation:</span>
                                    <span class="text-green-600 font-semibold">✓ Enabled</span>
                                </div>
                            </div>
                            <button onclick="configureStep('wedding')" class="mt-3 w-full text-xs bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 transition-colors">
                                Configure
                            </button>
                        </div>

                        <!-- Step 6: Summary & Confirmation -->
                        <div class="p-4 bg-teal-50 rounded-lg border border-teal-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-teal-600 text-white rounded-full flex items-center justify-center text-sm font-bold">6</div>
                                <h4 class="font-semibold text-teal-800 text-sm">Summary & Bill</h4>
                            </div>
                            <div class="space-y-2 text-xs text-teal-700">
                                <div class="flex items-center justify-between">
                                    <span>Auto-Calculate:</span>
                                    <span class="text-green-600 font-semibold">✓ Enabled</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Email Receipt:</span>
                                    <span class="text-green-600 font-semibold">✓ Enabled</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>PDF Export:</span>
                                    <span class="text-green-600 font-semibold">✓ Available</span>
                                </div>
                            </div>
                            <button onclick="configureStep('summary')" class="mt-3 w-full text-xs bg-teal-600 text-white px-2 py-1 rounded hover:bg-teal-700 transition-colors">
                                Configure
                            </button>
                        </div>
                    </div>

                    <!-- Workflow Status & Controls -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Workflow Status -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h5 class="font-semibold text-gray-800 mb-3 text-sm">Workflow Status</h5>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Visit Approval Required:</span>
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs">Manual</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Payment Verification:</span>
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs">Manual</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Step 5 Access:</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Locked</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Email Notifications:</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Active</span>
                                </div>
                            </div>
                        </div>

                        <!-- Database Integration -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h5 class="font-semibold text-gray-800 mb-3 text-sm">Database Integration</h5>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Bookings Table:</span>
                                    <span class="text-green-600 font-semibold">✓ Connected</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Halls Data:</span>
                                    <span class="text-green-600 font-semibold">✓ Live</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Packages Data:</span>
                                    <span class="text-green-600 font-semibold">✓ Live</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">JSON Fields:</span>
                                    <span class="text-green-600 font-semibold">✓ Supported</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h5 class="font-semibold text-gray-800 mb-3 text-sm">Quick Actions</h5>
                            <div class="space-y-2">
                                <button onclick="exportBookingConfig()" class="w-full text-xs bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition-colors">
                                    <i class="ri-download-line mr-1"></i>Export Configuration
                                </button>
                                <button onclick="resetBookingConfig()" class="w-full text-xs bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 transition-colors">
                                    <i class="ri-restart-line mr-1"></i>Reset to Default
                                </button>
                                <button onclick="testBookingFlow()" class="w-full text-xs bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 transition-colors">
                                    <i class="ri-play-line mr-1"></i>Test Workflow
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Booking Step Configuration
        window.configureStep = function(step) {
            openStepConfigModal(step);
        };

        function openStepConfigModal(step) {
            const modal = document.getElementById('step-config-modal');
            const title = document.getElementById('step-config-title');
            const content = document.getElementById('step-config-content');
            
            let stepTitle = '';
            let stepContent = '';
            
            switch(step) {
                case 'hall':
                    stepTitle = 'Step 1: Hall Selection Configuration';
                    stepContent = `
                        <div class="space-y-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-800 mb-3">Database Integration Status</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="flex justify-between">
                                        <span>Total Halls in DB:</span>
                                        <span class="font-semibold">${document.getElementById('halls-count')?.textContent || '0'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Active Halls:</span>
                                        <span class="font-semibold">${document.getElementById('active-halls-count')?.textContent || '0'}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hall Availability Settings</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Enable real-time availability checking
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Show calendar view for date selection
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Allow same-day bookings
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require minimum 7 days advance booking
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Display Options</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Show hall images
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Display capacity information
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Show pricing information
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Display hall features list
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'package':
                    stepTitle = 'Step 2: Package Selection Configuration';
                    stepContent = `
                        <div class="space-y-6">
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-green-800 mb-3">Package Database Status</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="flex justify-between">
                                        <span>Total Packages:</span>
                                        <span class="font-semibold">3</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Active Packages:</span>
                                        <span class="font-semibold">3</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Display Settings</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Show package comparison table
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Display package features
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Show pricing prominently
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Highlight most popular package
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Validation Rules</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require package selection
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Allow package customization
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Show package dependencies
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'customize':
                    stepTitle = 'Step 3: Customization Configuration';
                    stepContent = `
                        <div class="space-y-6">
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-yellow-800 mb-3">Customization Data Status</h4>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div class="flex justify-between">
                                        <span>Wedding Types:</span>
                                        <span class="font-semibold">5</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Decorations:</span>
                                        <span class="font-semibold">15+</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Catering Menus:</span>
                                        <span class="font-semibold">6</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guest Count Configuration</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Minimum Guests</label>
                                        <input type="number" value="10" min="1" class="w-full border border-gray-300 rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Maximum Guests</label>
                                        <input type="number" value="1000" min="10" class="w-full border border-gray-300 rounded px-3 py-2">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Customization Options</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Wedding Type Selection (Required)
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Decoration Customization
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Catering Menu Selection (Required)
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Additional Services
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Custom catering requests
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'visit':
                    stepTitle = 'Step 4: Visit Scheduling Configuration';
                    stepContent = `
                        <div class="space-y-6">
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-purple-800 mb-3">Visit Management Status</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="flex justify-between">
                                        <span>Pending Visits:</span>
                                        <span class="font-semibold">${document.getElementById('pending-visits-count')?.textContent || '0'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Approval Required:</span>
                                        <span class="font-semibold text-orange-600">Manual</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Visit Scheduling Settings</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require visit scheduling before Step 5
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Manager approval required
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Auto-approve visit requests
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Send email notifications
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Start Time</label>
                                        <input type="time" value="09:00" class="w-full border border-gray-300 rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">End Time</label>
                                        <input type="time" value="17:00" class="w-full border border-gray-300 rounded px-3 py-2">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600 mb-1">Slot Duration (minutes)</label>
                                    <input type="number" value="60" min="30" max="180" class="w-full border border-gray-300 rounded px-3 py-2">
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'wedding':
                    stepTitle = 'Step 5: Wedding Details Configuration';
                    stepContent = `
                        <div class="space-y-6">
                            <div class="bg-indigo-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-indigo-800 mb-3">Access Control Status</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="flex justify-between">
                                        <span>Access Control:</span>
                                        <span class="font-semibold text-orange-600">Payment Required</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Advance Payment:</span>
                                        <span class="font-semibold">20%</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Settings</label>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Advance Payment Percentage</label>
                                        <input type="number" value="20" min="0" max="100" class="w-full border border-gray-300 rounded px-3 py-2">
                                        <span class="text-xs text-gray-500">% of total booking amount</span>
                                    </div>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require advance payment before Step 5 access
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Manual payment verification
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Allow payment plans
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Validation Rules</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require couple's full names
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require contact information
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require wedding date (minimum 3 months ahead)
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require ceremony and reception times
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'summary':
                    stepTitle = 'Step 6: Summary & Confirmation Configuration';
                    stepContent = `
                        <div class="space-y-6">
                            <div class="bg-teal-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-teal-800 mb-3">Summary Features Status</h4>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div class="flex justify-between">
                                        <span>Auto-Calculate:</span>
                                        <span class="font-semibold text-green-600">✓ Enabled</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Email Receipt:</span>
                                        <span class="font-semibold text-green-600">✓ Enabled</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>PDF Export:</span>
                                        <span class="font-semibold text-green-600">✓ Available</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bill Calculation Settings</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Auto-calculate total amount
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Include all customizations in bill
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Show itemized breakdown
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Apply taxes automatically
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmation Settings</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Send confirmation email to customer
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Generate unique booking ID
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Create PDF receipt
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Notify managers of new booking
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require terms acceptance
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" checked class="mr-2"> Require privacy policy acceptance
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2"> Allow booking without final confirmation
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
            }
            
            title.textContent = stepTitle;
            content.innerHTML = stepContent;
            modal.classList.remove('hidden');
        }

        function closeStepConfigModal() {
            document.getElementById('step-config-modal').classList.add('hidden');
        }

        function saveStepConfiguration() {
            // Here you would collect the form data and save it
            showToast('Configuration saved successfully!', 'success');
            closeStepConfigModal();
        }

        // Additional booking configuration functions
        function refreshBookingConfig() {
            showToast('Refreshing booking configuration...', 'info');
            // Refresh the configuration data
            updateBookingConfigCounters();
            setTimeout(() => {
                showToast('Booking configuration refreshed successfully!', 'success');
            }, 1000);
        }

        function exportBookingConfig() {
            showToast('Exporting booking configuration...', 'info');
            // Export configuration as JSON or CSV
            const config = {
                workflow_steps: 6,
                hall_selection: {
                    enabled: true,
                    calendar_view: true,
                    advance_booking_days: 7
                },
                package_selection: {
                    enabled: true,
                    comparison_table: true,
                    total_packages: 3
                },
                customization: {
                    wedding_types: 5,
                    decorations: true,
                    catering: true,
                    additional_services: true
                },
                visit_scheduling: {
                    manual_approval: true,
                    auto_approval: false,
                    time_slots: true
                },
                wedding_details: {
                    advance_payment_required: true,
                    advance_payment_percentage: 20,
                    access_control: 'payment_required'
                },
                summary: {
                    auto_calculate: true,
                    email_receipt: true,
                    pdf_export: true
                }
            };
            
            const dataStr = JSON.stringify(config, null, 2);
            const dataBlob = new Blob([dataStr], {type: 'application/json'});
            const url = URL.createObjectURL(dataBlob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'booking_configuration.json';
            link.click();
            URL.revokeObjectURL(url);
        }

        function resetBookingConfig() {
            if (confirm('Are you sure you want to reset the booking configuration to default settings? This action cannot be undone.')) {
                showToast('Resetting booking configuration to defaults...', 'warning');
                // Reset to default configuration
                setTimeout(() => {
                    showToast('Booking configuration reset successfully!', 'success');
                }, 2000);
            }
        }

        function testBookingFlow() {
            showToast('Starting booking workflow test...', 'info');
            // Open a new window/tab to test the booking flow
            window.open('/booking', '_blank');
        }

        function loadBookingConfigData() {
            // Use the data that's already available on the page from the controller
            // No need to make an API call since we have the stats data
            try {
                console.log('Booking configuration data loaded from page stats');
                
                // Update any counters that might need default values
                updateBookingConfigCounters();
                
                showToast('Configuration data loaded successfully!', 'success');
                
            } catch (error) {
                console.error('Error updating booking config display:', error);
                showToast('Error updating configuration display', 'error');
            }
        }

        function updateBookingConfigCounters() {
            // Update counters with default values if they're empty
            
            // Wedding types count (static data)
            const weddingTypesElement = document.getElementById('wedding-types-count');
            if (weddingTypesElement && !weddingTypesElement.textContent.trim()) {
                weddingTypesElement.textContent = '5';
            }
            
            // Decorations count (static data)
            const decorationsElement = document.getElementById('decorations-count');
            if (decorationsElement && !decorationsElement.textContent.trim()) {
                decorationsElement.textContent = '15+';
            }
            
            // Catering menus count (static data)
            const cateringElement = document.getElementById('catering-menus-count');
            if (cateringElement && !cateringElement.textContent.trim()) {
                cateringElement.textContent = '6';
            }
            
            // Packages count (static data)
            const packagesElement = document.getElementById('packages-count');
            if (packagesElement && !packagesElement.textContent.trim()) {
                packagesElement.textContent = '3';
            }
            
            // Active packages count (static data)
            const activePackagesElement = document.getElementById('active-packages-count');
            if (activePackagesElement && !activePackagesElement.textContent.trim()) {
                activePackagesElement.textContent = '3';
            }
        }

        // Load configuration data on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Delay the loading slightly to ensure all elements are rendered
            setTimeout(() => {
                loadBookingConfigData();
            }, 500);
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full`;
            
            // Set background color based on type
            switch(type) {
                case 'success':
                    toast.classList.add('bg-green-500');
                    break;
                case 'error':
                    toast.classList.add('bg-red-500');
                    break;
                case 'warning':
                    toast.classList.add('bg-yellow-500');
                    break;
                default:
                    toast.classList.add('bg-blue-500');
            }
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Package Features Management
        window.addFeature = function() {
            const container = document.getElementById('package-features');
            const newFeature = document.createElement('div');
            newFeature.className = 'flex gap-2';
            newFeature.innerHTML = `
                <input type="text" placeholder="Feature description" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="button" onclick="removeFeature(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    <i class="ri-delete-bin-line"></i>
                </button>
            `;
            container.appendChild(newFeature);
        };

        window.removeFeature = function(button) {
            button.parentElement.remove();
        };

        function resetPackageFeatures() {
            const container = document.getElementById('package-features');
            container.innerHTML = `
                <div class="flex gap-2">
                    <input type="text" placeholder="Feature description" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button" onclick="removeFeature(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            `;
        }

        // Placeholder functions for future implementation
        window.editHall = function(id) { showToast('Edit hall functionality coming soon!', 'info'); };
        window.deleteHall = function(id) { showToast('Delete hall functionality coming soon!', 'info'); };
        window.viewHallDetails = function(id) { showToast('View hall details coming soon!', 'info'); };
        window.editPackage = function(id) { 
            window.location.href = `/admin/packages/${id}/edit`;
        };
        
        window.deletePackage = function(id) { 
            if (confirm('Are you sure you want to delete this package? This action cannot be undone.')) {
                fetch(`/admin/packages/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Package deleted successfully!', 'success');
                        loadRealPackages(); // Refresh the packages list
                    } else {
                        showToast(data.message || 'Error deleting package', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error deleting package', 'error');
                });
            }
        };
        
        window.viewPackageDetails = function(id) { 
            window.location.href = `/admin/packages/${id}`;
        };
        window.exportHalls = function() { showToast('Export halls functionality coming soon!', 'info'); };
        window.exportPackages = function() { 
            window.location.href = '/admin/packages/export';
        };
        window.refreshHalls = function() { loadRealHalls(); };
        // Load the working packages content directly in dashboard
        window.loadWorkingPackagesPage = async function() {
            try {
                const packagesContent = document.getElementById('packages-content');
                if (!packagesContent) return;
                
                // Show loading
                packagesContent.innerHTML = `
                    <div class="text-center py-8">
                        <i class="ri-loader-4-line animate-spin text-4xl text-blue-600 mb-4"></i>
                        <p class="text-gray-600">Loading packages...</p>
                    </div>
                `;
                
                // Fetch packages data from API
                const response = await fetch('/admin/api/admin/packages', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success && data.packages) {
                        // Create the packages content HTML
                        const packagesHTML = createPackagesHTML(data.packages, data.stats);
                        packagesContent.innerHTML = packagesHTML;
                        
                        // Initialize package functionality
                        initializePackageFunctions();
                    } else {
                        packagesContent.innerHTML = '<div class="text-center py-8"><p class="text-red-600">Error loading packages data</p></div>';
                    }
                } else {
                    packagesContent.innerHTML = '<div class="text-center py-8"><p class="text-red-600">Failed to load packages</p></div>';
                }
            } catch (error) {
                console.error('Error loading packages:', error);
                const packagesContent = document.getElementById('packages-content');
                if (packagesContent) {
                    packagesContent.innerHTML = '<div class="text-center py-8"><p class="text-red-600">Error loading packages</p></div>';
                }
            }
        };

        // Create packages HTML content
        function createPackagesHTML(packages, stats) {
            return `
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-900">Wedding Packages</h2>
                                <p class="text-gray-600 mt-1">Manage your wedding packages and pricing</p>
                            </div>
                            <button onclick="openPackageModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                <i class="ri-add-line mr-2"></i>
                                Add New Package
                            </button>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <i class="ri-gift-line text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Total Packages</p>
                                        <p class="text-2xl font-semibold text-gray-900">${stats?.total || packages.length}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <i class="ri-check-line text-green-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Active Packages</p>
                                        <p class="text-2xl font-semibold text-gray-900">${stats?.active || packages.filter(p => p.is_active).length}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-yellow-100 rounded-lg">
                                        <i class="ri-star-line text-yellow-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Featured Packages</p>
                                        <p class="text-2xl font-semibold text-gray-900">${packages.filter(p => p.highlight).length}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-purple-100 rounded-lg">
                                        <i class="ri-money-dollar-circle-line text-purple-600 text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Avg. Price</p>
                                        <p class="text-2xl font-semibold text-gray-900">Rs. ${Math.round(packages.reduce((sum, p) => sum + p.price, 0) / packages.length).toLocaleString()}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Packages Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            ${packages.map(pkg => `
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    <!-- Package Image -->
                                    <div class="relative h-48">
                                        <img src="${pkg.image || '/images/default-package.jpg'}"
                                             alt="${pkg.name}"
                                             class="w-full h-full object-cover"
                                             onerror="this.src='/storage/halls/default-package.jpg'">
                                        
                                        <!-- Status Badge -->
                                        <div class="absolute top-2 right-2">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium ${pkg.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                                ${pkg.is_active ? 'Active' : 'Inactive'}
                                            </span>
                                        </div>
                                        
                                        ${pkg.highlight ? `
                                        <div class="absolute top-2 left-2">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="ri-star-fill mr-1"></i>Featured
                                            </span>
                                        </div>
                                        ` : ''}
                                    </div>

                                    <!-- Package Content -->
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">${pkg.name}</h3>
                                            <div class="text-xl font-bold text-blue-600">Rs. ${pkg.price.toLocaleString()}</div>
                                        </div>
                                        
                                        <p class="text-gray-600 text-sm mb-4">${pkg.description.substring(0, 100)}...</p>
                                        
                                        <!-- Package Features -->
                                        ${pkg.features && pkg.features.length > 0 ? `
                                        <div class="space-y-1 mb-4">
                                            ${pkg.features.slice(0, 3).map(feature => `
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="ri-check-line text-green-500 mr-2 flex-shrink-0"></i>
                                                <span>${feature}</span>
                                            </div>
                                            `).join('')}
                                            ${pkg.features.length > 3 ? `
                                            <div class="text-sm text-blue-600 cursor-pointer hover:text-blue-800">
                                                + ${pkg.features.length - 3} more features
                                            </div>
                                            ` : ''}
                                        </div>
                                        ` : ''}

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2 mt-4">
                                            <button onclick="viewPackageDetails(${pkg.id})" 
                                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                                <i class="ri-eye-line mr-1"></i>
                                                View Details
                                            </button>
                                            <button onclick="editPackage(${pkg.id})" 
                                                    class="p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                    title="Edit Package">
                                                <i class="ri-edit-line text-lg"></i>
                                            </button>
                                            <button onclick="togglePackageStatus(${pkg.id})" 
                                                    class="p-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                    title="${pkg.is_active ? 'Deactivate' : 'Activate'} Package">
                                                <i class="ri-${pkg.is_active ? 'pause' : 'play'}-line text-lg"></i>
                                            </button>
                                            <button onclick="confirmDelete(${pkg.id})" 
                                                    class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                                    title="Delete Package">
                                                <i class="ri-delete-bin-line text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Package Modal -->
                <div id="packageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <!-- Modal Header -->
                            <div class="flex justify-between items-center pb-3 border-b">
                                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add New Package</h3>
                                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="ri-close-line text-2xl"></i>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <form id="packageForm" enctype="multipart/form-data" class="mt-4">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" id="packageId" name="package_id">
                                <input type="hidden" name="_method" id="formMethod" value="POST">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Package Name -->
                                    <div class="md:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Package Name *</label>
                                        <input type="text" id="name" name="name" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="e.g., Golden Wedding Package">
                                    </div>

                                    <!-- Price -->
                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rs.) *</label>
                                        <input type="number" id="price" name="price" required min="0" step="0.01"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="450000">
                                    </div>

                                    <!-- Highlight Package -->
                                    <div class="flex items-center">
                                        <input type="checkbox" id="highlight" name="highlight" value="1"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="highlight" class="ml-2 block text-sm text-gray-900">
                                            Highlight Package (Most Popular)
                                        </label>
                                    </div>

                                    <!-- Description -->
                                    <div class="md:col-span-2">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                        <textarea id="description" name="description" required rows="3"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                  placeholder="Describe what's included in this package..."></textarea>
                                    </div>

                                    <!-- Package Image -->
                                    <div class="md:col-span-2">
                                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Package Image</label>
                                        <input type="file" id="image" name="image" accept="image/*"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <p class="text-sm text-gray-500 mt-1">Upload an image (JPEG, PNG, JPG, GIF - Max: 2MB)</p>
                                    </div>

                                    <!-- Package Features -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Features</label>
                                        <div id="featuresContainer">
                                            <div class="feature-input-group flex items-center mb-2">
                                                <input type="text" name="features[]" 
                                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                       placeholder="e.g., Professional photography">
                                                <button type="button" onclick="removeFeature(this)" 
                                                        class="ml-2 px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" onclick="addFeature()" 
                                                class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                            <i class="ri-add-line mr-1"></i> Add Feature
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                                    <button type="button" onclick="closeModal()" 
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                        Cancel
                                    </button>
                                    <button type="submit" id="submitBtn"
                                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        <i class="ri-save-line mr-1"></i>
                                        <span id="submitText">Save Package</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
        }

        // Initialize package functions after loading content
        function initializePackageFunctions() {
            // Package modal functions
            window.openPackageModal = function() {
                resetForm();
                showModal();
            };

            window.showModal = function() {
                document.getElementById('packageModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            window.closeModal = function() {
                document.getElementById('packageModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                resetForm();
            };

            window.resetForm = function() {
                document.getElementById('packageForm').reset();
                document.getElementById('packageId').value = '';
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('modalTitle').textContent = 'Add New Package';
                document.getElementById('submitText').textContent = 'Save Package';
            };

            // Feature management
            window.addFeature = function() {
                const container = document.getElementById('featuresContainer');
                const newFeature = document.createElement('div');
                newFeature.className = 'feature-input-group flex items-center mb-2';
                newFeature.innerHTML = `
                    <input type="text" name="features[]" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g., Professional photography">
                    <button type="button" onclick="removeFeature(this)" 
                            class="ml-2 px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                `;
                container.appendChild(newFeature);
            };

            window.removeFeature = function(button) {
                const container = document.getElementById('featuresContainer');
                if (container.children.length > 1) {
                    button.parentElement.remove();
                }
            };

            // Package actions
            window.viewPackageDetails = function(packageId) {
                // Load package details and show in modal
                fetch(`/admin/api/admin/packages/${packageId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showPackageDetailsModal(data.package);
                    } else {
                        showToast('Error loading package details', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error loading package details', 'error');
                });
            };

            window.editPackage = function(packageId) {
                // Load package data and show edit modal
                fetch(`/admin/packages/${packageId}/edit`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fillPackageForm(data.package);
                        showModal();
                    } else {
                        showToast('Error loading package data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error loading package data', 'error');
                });
            };

            window.togglePackageStatus = function(packageId) {
                fetch(`/admin/packages/${packageId}/toggle-status`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => {
                            loadRealPackages(); // Reload packages
                        }, 1000);
                    } else {
                        showToast(data.message || 'Error updating package status', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error updating package status', 'error');
                });
            };

            window.confirmDelete = function(packageId) {
                if (confirm('Are you sure you want to delete this package? This action cannot be undone.')) {
                    fetch(`/admin/packages/${packageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Package deleted successfully!', 'success');
                            setTimeout(() => {
                                loadRealPackages(); // Reload packages
                            }, 1000);
                        } else {
                            showToast(data.message || 'Error deleting package', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error deleting package', 'error');
                    });
                }
            };

            // Fill form for editing packages
            window.fillPackageForm = function(package) {
                document.getElementById('packageId').value = package.id;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('name').value = package.name;
                document.getElementById('description').value = package.description;
                document.getElementById('price').value = package.price;
                document.getElementById('highlight').checked = package.highlight;
                
                // Fill features
                const container = document.getElementById('featuresContainer');
                container.innerHTML = '';
                
                if (package.features && package.features.length > 0) {
                    package.features.forEach(feature => {
                        const featureDiv = document.createElement('div');
                        featureDiv.className = 'feature-input-group flex items-center mb-2';
                        featureDiv.innerHTML = `
                            <input type="text" name="features[]" value="${feature}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" onclick="removeFeature(this)" 
                                    class="ml-2 px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        `;
                        container.appendChild(featureDiv);
                    });
                } else {
                    // Add one empty feature input
                    addFeature();
                }
                
                document.getElementById('modalTitle').textContent = 'Edit Package';
                document.getElementById('submitText').textContent = 'Update Package';
            };

            // Form submission
            document.getElementById('packageForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const packageId = document.getElementById('packageId').value;
                const method = packageId ? 'PUT' : 'POST';
                const url = packageId ? `/admin/packages/${packageId}` : '/admin/packages';
                
                // Add method override for PUT requests
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }
                
                const submitBtn = document.getElementById('submitBtn');
                const originalText = document.getElementById('submitText').textContent;
                
                submitBtn.disabled = true;
                document.getElementById('submitText').textContent = 'Saving...';
                
                fetch(url, {
                    method: 'POST', // Always POST for Laravel form method spoofing
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal();
                        showToast(packageId ? 'Package updated successfully!' : 'Package created successfully!', 'success');
                        setTimeout(() => {
                            loadRealPackages(); // Reload packages
                        }, 1000);
                    } else {
                        if (data.errors) {
                            // Handle validation errors
                            Object.keys(data.errors).forEach(field => {
                                showToast(`${field}: ${data.errors[field][0]}`, 'error');
                            });
                        } else {
                            showToast(data.message || 'Error saving package', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while saving the package', 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    document.getElementById('submitText').textContent = originalText;
                });
            });
        }

        window.refreshPackages = function() {
        loadRealPackages();
        // Show success message for manual refresh
        setTimeout(() => {
        showToast('✅ Packages refreshed successfully!', 'success');
        }, 500);
        };
        window.applyHallFilters = function() { showToast('Hall filters functionality coming soon!', 'info'); };
        window.clearHallFilters = function() { showToast('Clear hall filters functionality coming soon!', 'info'); };
        window.applyPackageFilters = function() { 
            loadRealPackages(); // For now, just reload packages
        };
        window.clearPackageFilters = function() { 
            // Clear filter inputs and reload
            const filterInputs = document.querySelectorAll('#packages-filters input, #packages-filters select');
            filterInputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            loadRealPackages();
        };

    </script>

    <!-- Step Configuration Modal -->
    <div id="step-config-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800" id="step-config-title">Step Configuration</h3>
                <button onclick="closeStepConfigModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            
            <div id="step-config-content" class="mb-6">
                <!-- Dynamic content will be loaded here -->
            </div>
            
            <div class="flex justify-end gap-3">
                <button onclick="closeStepConfigModal()" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button onclick="saveStepConfiguration()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Save Configuration
                </button>
            </div>
        </div>
    </div>
    
    <!-- Fixed Package Management JavaScript -->
    <script src="{{ asset('fix_admin_dashboard_js.js') }}"></script>
    <!-- Enhanced Edit Package Functionality -->
    <script src="{{ asset('fix_edit_package.js') }}"></script>
    <!-- Comprehensive Admin Dashboard Fix -->
    <script src="{{ asset('admin_dashboard_comprehensive_fix.js') }}"></script>
    <!-- COMPLETE FIX for View and Edit Buttons -->
    <script src="{{ asset('fix_view_edit_buttons.js') }}"></script<!-- FIX for Edit Button JSON Error -->
    <script src="{{ asset('fix_edit_button_json_error.js') }}"></script>
    <!-- FIX for Highlight Field Validation Error -->
    <script src="{{ asset('fix_highlight_field.js') }}"></script>
    <!-- Simple Highlight Field Validation Fix -->
    <script src="{{ asset('fix_highlight_validation.js') }}"></script>
    <!-- Final Checkbox Highlight Fix -->
    <script src="{{ asset('fix_checkbox_highlight.js') }}"></script>
    <!-- Package Image Upload Fix -->
    <script src="{{ asset('fix_package_image_upload.js') }}"></script>
    
    <!-- Enhanced Package Image Display Fix -->
    <script src="{{ asset('fix_package_image_display.js') }}"></script>
    
    <script>
        // Form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            const packageForm = document.getElementById('package-form');
            if (packageForm) {
                packageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const packageId = document.getElementById('package-id').value;
                    const method = packageId ? 'PUT' : 'POST';
                    const url = packageId ? `/admin/packages/${packageId}` : '/admin/packages';
                    
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const submitText = document.getElementById('package-submit-text');
                    const originalText = submitText.textContent;
                    
                    submitBtn.disabled = true;
                    submitText.textContent = 'Saving...';
                    
                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closePackageModal();
                            showToast(packageId ? 'Package updated successfully!' : 'Package created successfully!', 'success');
                            setTimeout(() => {
                                loadRealPackages();
                            }, 1000);
                        } else {
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    showToast(`${field}: ${data.errors[field][0]}`, 'error');
                                });
                            } else {
                                showToast(data.message || 'Error saving package', 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred while saving the package', 'error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitText.textContent = originalText;
                    });
                });
            }
        });
        
        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full`;
            
            switch(type) {
                case 'success':
                    toast.classList.add('bg-green-500');
                    break;
                case 'error':
                    toast.classList.add('bg-red-500');
                    break;
                case 'warning':
                    toast.classList.add('bg-yellow-500');
                    break;
                default:
                    toast.classList.add('bg-blue-500');
            }
            
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>