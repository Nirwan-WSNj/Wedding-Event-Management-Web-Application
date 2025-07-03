<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hotel Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Preserve existing styles */
        .nav-item.active {
            background-color: rgb(239 246 255);
            color: rgb(37 99 235);
            border-right: 4px solid rgb(37 99 235);
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .content-section {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mobile responsiveness */
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

            .main-content {
                width: 100%;
            }
        }

        /* Package card enhancements */
        .package-card {
            transition: all 0.3s ease;
        }

        .package-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Button hover effects */
        .package-card button {
            transition: all 0.2s ease;
        }

        .package-card button:hover {
            transform: translateY(-1px);
        }

        /* Feature input styling */
        .feature-row input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Loading states */
        .loading-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Toast notifications */
        .toast {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform transition-transform duration-300 ease-in-out" id="sidebar">
        <div class="flex items-center justify-center h-20 bg-gradient-to-r from-blue-600 to-purple-600">
            <h1 class="text-2xl font-bold text-white">Hotel Manager</h1>
        </div>
        
        <nav class="mt-8">
            <div class="px-4 space-y-2">
                <a href="#dashboard" class="nav-item active flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="#visits" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Visit Requests
                </a>
                <a href="#wedding-requests" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Wedding Dates
                </a>
                <a href="#halls" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 2 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Manage Halls
                </a>
                <a href="#bookings" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Bookings Tracker
                </a>
                <a href="#calendar" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Calendar View
                </a>
                <a href="#messages" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Messages
                </a>
                <a href="#packages" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Wedding Packages
                </a>
                <a href="#reports" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Reports
                </a>
                            </div>
        </nav>
    </div>

    <div class="ml-64">
        <header class="bg-white shadow-lg border-b border-gray-200">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="ml-4 text-2xl font-semibold text-gray-800" id="pageTitle">Dashboard Overview</h1>
                    </div>
                    <div class="flex items-center space-x-4">
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
                        <div class="relative dropdown">
                            <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" id="user-menu">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Manager&background=3B82F6&color=fff" alt="Profile">
                                <span class="ml-2 text-gray-700">Manager</span>
                                <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 hidden border border-gray-200">
                                <a href="#profile" class="nav-item flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile Settings
                                </a>
                                <a href="#settings" class="nav-item flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    System Settings
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

        <main class="p-6">
            <div id="dashboard-section" class="content-section">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome, Manager!</h2>
                    <p class="text-gray-600">Oversee venue operations and manage wedding bookings efficiently. Today's Date: {{ now()->format('F d, Y g:i A') }}</p>
                    <div class="mt-2 flex items-center space-x-4">
                        <div id="loading-indicator" class="text-blue-600 hidden">
                            <i class="fas fa-spinner fa-spin"></i> Loading real data...
                        </div>
                        <div class="text-green-600 text-sm">
                            <i class="fas fa-database"></i> Connected to live database
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Active Halls</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-halls">{{ \App\Models\Hall::where('is_active', true)->count() }}</p>
                                <p class="text-xs text-gray-400 mt-1">Available venues</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pending Visits</p>
                                <p class="text-2xl font-bold text-gray-900" id="pending-visits">{{ \App\Models\Booking::where('visit_submitted', true)->where('visit_confirmed', false)->count() }}</p>
                                <p class="text-xs text-gray-400 mt-1">Awaiting approval</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Confirmed Bookings</p>
                                <p class="text-2xl font-bold text-gray-900" id="confirmed-bookings">{{ \App\Models\Booking::where('advance_payment_paid', true)->count() }}</p>
                                <p class="text-xs text-gray-400 mt-1">Payment confirmed</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-900" id="monthly-revenue">Rs. {{ number_format(\App\Models\Booking::where('advance_payment_paid', true)->sum('advance_payment_amount'), 0) }}</p>
                                <p class="text-xs text-gray-400 mt-1">All time</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Recent Activities with Hall Occupancy -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Visit Requests</h3>
                        <div class="space-y-3" id="recent-visits">
                            @php
                                $recentVisits = \App\Models\Booking::where('visit_submitted', true)
                                    ->where('visit_confirmed', false)
                                    ->with(['user', 'hall'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($recentVisits as $visit)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $visit->user->name ?? $visit->contact_name ?? 'Unknown' }}</div>
                                        <div class="text-sm text-gray-600">{{ $visit->hall->name ?? $visit->hall_name ?? 'Unknown Hall' }}</div>
                                        <div class="text-xs text-gray-500">{{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'Date TBD' }}</div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="openCallModal({{ $visit->id }})" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                            Call Customer
                                        </button>
                                        <button onclick="approveVisit({{ $visit->id }})" class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                            Quick Approve
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-gray-500 text-sm">No pending visit requests</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Weddings</h3>
                        <div class="space-y-3" id="upcoming-weddings">
                            @php
                                $upcomingWeddings = \App\Models\Booking::where('advance_payment_paid', true)
                                    ->where('event_date', '>=', now())
                                    ->with(['user', 'hall'])
                                    ->orderBy('event_date', 'asc')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($upcomingWeddings as $wedding)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $wedding->user->name ?? $wedding->contact_name ?? 'Unknown' }}</div>
                                        <div class="text-sm text-gray-600">{{ $wedding->hall->name ?? $wedding->hall_name ?? 'Unknown Hall' }}</div>
                                        <div class="text-xs text-gray-500">{{ $wedding->event_date ? \Carbon\Carbon::parse($wedding->event_date)->format('M d, Y') : 'Date TBD' }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-green-600">Confirmed</div>
                                        <div class="text-xs text-gray-500">{{ $wedding->guest_count ?? $wedding->customization_guest_count ?? 'N/A' }} guests</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-gray-500 text-sm">No upcoming weddings</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Confirmations</h3>
                        <div class="space-y-3" id="payment-confirmations">
                            @php
                                $paymentPending = \App\Models\Booking::where('visit_confirmed', true)
                                    ->where('advance_payment_paid', false)
                                    ->with(['user', 'hall'])
                                    ->orderBy('visit_confirmed_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($paymentPending as $payment)
                                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $payment->user->name ?? $payment->contact_name ?? 'Unknown' }}</div>
                                        <div class="text-sm text-gray-600">{{ $payment->hall->name ?? $payment->hall_name ?? 'Unknown Hall' }}</div>
                                        <div class="text-xs text-gray-500">Rs. {{ number_format($payment->advance_payment_amount ?? 0, 0) }}</div>
                                    </div>
                                    <button onclick="markPaymentPaid({{ $payment->id }})" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                        Mark Paid
                                    </button>
                                </div>
                            @empty
                                <div class="text-gray-500 text-sm">No pending payments</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                            </div>

            <div id="visits-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Visit Requests Management</h2>
                    <p class="text-gray-600">Review and approve venue visit requests from potential customers. <strong>History is maintained</strong> - approved visits remain visible for tracking.</p>
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start space-x-2">
                            <div class="text-blue-600 mt-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="text-sm text-blue-800">
                                <strong>Workflow:</strong> Pending → Approve/Reject → Payment Confirmation → Step 5 Access
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Visit Requests Management</h3>
                            <div class="flex space-x-2">
                                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filter-visit-status">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending Approval</option>
                                    <option value="approved">✓ Approved</option>
                                    <option value="rejected">✗ Rejected</option>
                                </select>
                                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filter-visit-hall">
                                    <option value="">All Halls</option>
                                    <option value="Jubilee Ballroom">Jubilee Ballroom</option>
                                    <option value="Grand Ballroom">Grand Ballroom</option>
                                    <option value="Garden Pavilion">Garden Pavilion</option>
                                    <option value="Royal Heritage Hall">Royal Heritage Hall</option>
                                    <option value="Riverside Garden">Riverside Garden</option>
                                </select>
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" id="refresh-visits">
                                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Couple</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preferred Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="visits-table-body">
                                @php
                                    $allVisits = \App\Models\Booking::where('visit_submitted', true)
                                        ->with(['user', 'hall'])
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                                @endphp
                                @forelse($allVisits as $visit)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $visit->user->name ?? $visit->contact_name ?? 'Unknown' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $visit->user->email ?? $visit->contact_email ?? 'No email' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'Date TBD' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $visit->visit_time ?? 'Time TBD' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $visit->visit_purpose ?? 'General inquiry' }}</div>
                                            @if($visit->special_requests)
                                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($visit->special_requests, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($visit->visit_confirmed)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✓ Approved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending Approval
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if(!$visit->visit_confirmed)
                                                <button onclick="openCallModal({{ $visit->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                                    📞 Call
                                                </button>
                                                <button onclick="approveVisit({{ $visit->id }})" class="text-green-600 hover:text-green-900 mr-3">
                                                    Quick Approve
                                                </button>
                                                <button onclick="viewCallHistory({{ $visit->id }})" class="text-gray-600 hover:text-gray-900">
                                                    History
                                                </button>
                                            @else
                                                <span class="text-gray-500">
                                                    ✓ Approved on {{ $visit->visit_confirmed_at ? $visit->visit_confirmed_at->format('M d') : 'N/A' }}
                                                    @if($visit->visit_confirmation_method === 'phone_call')
                                                        <br><small class="text-blue-600">via phone call</small>
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No visit requests found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="wedding-requests-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Wedding Date Approvals</h2>
                    <p class="text-gray-600">Confirm advance payments for approved visits. <strong>Complete history maintained</strong> for all payment confirmations.</p>
                    <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex items-start space-x-2">
                            <div class="text-orange-600 mt-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="text-sm text-orange-800">
                                <strong>Payment Workflow:</strong> Visit Approved → Customer Pays Advance → Manager Confirms → Step 5 Unlocked
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Pending Wedding Date Requests</h3>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" id="bulk-approve">
                                    Bulk Approve
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Couple</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wedding Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Slot</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hall</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="wedding-requests-table-body">
                                @php
                                    $weddingRequests = \App\Models\Booking::where('visit_confirmed', true)
                                        ->where('advance_payment_paid', false)
                                        ->with(['user', 'hall'])
                                        ->orderBy('visit_confirmed_at', 'desc')
                                        ->get();
                                @endphp
                                @forelse($weddingRequests as $request)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $request->user->name ?? $request->contact_name ?? 'Unknown' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $request->user->email ?? $request->contact_email ?? 'No email' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $request->event_date ? \Carbon\Carbon::parse($request->event_date)->format('M d, Y') : ($request->hall_booking_date ? \Carbon\Carbon::parse($request->hall_booking_date)->format('M d, Y') : 'Date TBD') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $request->wedding_type_time_slot ?? ($request->start_time ? $request->start_time . ' - ' . $request->end_time : 'Time TBD') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $request->hall->name ?? $request->hall_name ?? 'Unknown Hall' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">WED{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Awaiting Payment
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">Rs. {{ number_format($request->advance_payment_amount ?? 0, 0) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="markPaymentPaid({{ $request->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                                Mark Paid
                                            </button>
                                            <button onclick="viewBookingDetails({{ $request->id }})" class="text-gray-600 hover:text-gray-900">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No pending wedding date requests
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="halls-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Manage Wedding Halls</h2>
                    <p class="text-gray-600">View and manage your assigned wedding halls and their details.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="halls-grid">
                    @php
                        $halls = \App\Models\Hall::where('is_active', true)->get();
                    @endphp
                    @forelse($halls as $hall)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="relative h-48">
                                <img src="{{ $hall->image ? asset('storage/halls/' . $hall->image) : asset('storage/halls/default-hall.jpg') }}" 
                                     alt="{{ $hall->name }}" 
                                     class="w-full h-full object-cover">
                                <div class="absolute top-4 right-4">
                                    @php
                                        $activeBookings = \App\Models\Booking::where('hall_id', $hall->id)
                                            ->where('advance_payment_paid', true)
                                            ->where('event_date', '>=', now())
                                            ->count();
                                    @endphp
                                    @if($activeBookings > 0)
                                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Busy</span>
                                    @else
                                        <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Available</span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $hall->name }}</h3>
                                    <span class="text-sm text-gray-500">ID: {{ $hall->id }}</span>
                                </div>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($hall->description ?? 'Beautiful wedding venue', 100) }}</p>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <div class="text-xs text-gray-500">Capacity</div>
                                        <div class="font-semibold">{{ $hall->capacity ?? 'N/A' }} guests</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Price</div>
                                        <div class="font-semibold">Rs. {{ number_format($hall->price ?? 0, 0) }}</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-2 mb-4 text-xs">
                                    @php
                                        $pendingVisits = \App\Models\Booking::where('hall_id', $hall->id)
                                            ->where('visit_submitted', true)
                                            ->where('visit_confirmed', false)
                                            ->count();
                                        $confirmedBookings = \App\Models\Booking::where('hall_id', $hall->id)
                                            ->where('advance_payment_paid', true)
                                            ->count();
                                        $totalRevenue = \App\Models\Booking::where('hall_id', $hall->id)
                                            ->where('advance_payment_paid', true)
                                            ->sum('advance_payment_amount');
                                    @endphp
                                    <div class="text-center">
                                        <div class="font-semibold text-yellow-600">{{ $pendingVisits }}</div>
                                        <div class="text-gray-500">Pending</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="font-semibold text-green-600">{{ $confirmedBookings }}</div>
                                        <div class="text-gray-500">Booked</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="font-semibold text-blue-600">{{ number_format($totalRevenue / 1000, 0) }}K</div>
                                        <div class="text-gray-500">Revenue</div>
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <button onclick="viewHallDetails({{ $hall->id }})" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                        View Details
                                    </button>
                                    <button onclick="editHall({{ $hall->id }})" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12">
                            <div class="text-gray-500">No halls available</div>
                        </div>
                    @endforelse
                </div>
                <div id="hall-edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-bold text-gray-900 text-center" id="edit-modal-title">Edit Hall Details</h3>
                            <div class="mt-4 px-7 py-3">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hall Name</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="edit-hall-name">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="edit-hall-capacity" min="50" max="500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="edit-hall-availability">
                                        <option value="available">Available</option>
                                        <option value="booked">Booked</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center px-4 py-3">
                                <button id="save-hall-btn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 mr-2">
                                    Save
                                </button>
                                <button id="cancel-hall-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bookings-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Bookings Tracker</h2>
                    <p class="text-gray-600">Monitor all confirmed bookings and their status.</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">All Bookings</h3>
                            <div class="flex space-x-2">
                                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filter-hall">
                                    <option value="">Filter by Hall</option>
                                    <option value="royal">Royal Ballroom</option>
                                    <option value="garden">Garden Paradise</option>
                                    <option value="crystal">Crystal Hall</option>
                                    <option value="sunset">Sunset Terrace</option>
                                </select>
                                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filter-status">
                                    <option value="">Filter by Status</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" id="export-bill">
                                    Export Bill
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Couple Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hall</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wedding Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="bookings-table-body"></tbody>
                        </table>
                    </div>
                    <div id="bill-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                        <div class="relative top-20 mx-auto p-5 border w-4/5 max-w-2xl shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-bold text-gray-900 text-center">Booking Invoice</h3>
                                <div class="mt-4 px-7 py-3" id="bill-content">
                                    </div>
                                <div class="flex items-center px-4 py-3 justify-end">
                                    <button id="print-bill-btn" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 mr-2">
                                        Print
                                    </button>
                                    <button id="close-bill-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Modal -->
                    <div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-bold text-gray-900 text-center">Record Payment</h3>
                                <form id="payment-form" action="{{ route('manager.deposit.paid', ['id' => 0]) }}" method="POST" class="mt-4 px-7 py-3">
                                    @csrf
                                    <input type="hidden" id="booking-id" name="booking_id" value="">
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                                        <input type="number" name="amount" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required min="0" step="0.01">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="cash">Cash</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cheque">Cheque</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                                        <input type="text" name="transaction_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                        <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2"></textarea>
                                    </div>
                                    
                                    <div class="flex items-center justify-end">
                                        <button type="submit" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 mr-2">
                                            Save Payment
                                        </button>
                                        <button type="button" id="cancel-payment-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="calendar-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Calendar View</h2>
                    <p class="text-gray-600">Visual overview of visits, bookings, and availability.</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" id="prev-month">
                                ← Previous
                            </button>
                            <h3 class="text-xl font-semibold text-gray-800" id="current-month">June 2025</h3>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" id="next-month">
                                Next →
                            </button>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-yellow-400 rounded"></div>
                                <span class="text-sm text-gray-600">Visits</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-green-500 rounded"></div>
                                <span class="text-sm text-gray-600">Weddings</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-gray-400 rounded"></div>
                                <span class="text-sm text-gray-600">Blocked</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-1 mb-4">
                        <div class="p-3 text-center font-semibold text-gray-600">Sun</div>
                        <div class="p-3 text-center font-semibold text-gray-600">Mon</div>
                        <div class="p-3 text-center font-semibold text-gray-600">Tue</div>
                        <div class="p-3 text-center font-semibold text-gray-600">Wed</div>
                        <div class="p-3 text-center font-semibold text-gray-600">Thu</div>
                        <div class="p-3 text-center font-semibold text-gray-600">Fri</div>
                        <div class="p-3 text-center font-semibold text-gray-600">Sat</div>
                    </div>
                    <div class="grid grid-cols-7 gap-1" id="calendar-grid"></div>
                </div>
            </div>

            <div id="messages-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Message Center</h2>
                    <p class="text-gray-600">Real-time system notifications and customer inquiries with full message management.</p>
                    
                    <!-- Message Statistics -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                            <div class="text-2xl font-bold text-blue-600" id="total-messages">0</div>
                            <div class="text-sm text-gray-600">Total Messages</div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                            <div class="text-2xl font-bold text-red-600" id="unread-messages">0</div>
                            <div class="text-sm text-gray-600">Unread</div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                            <div class="text-2xl font-bold text-green-600" id="customer-inquiries">0</div>
                            <div class="text-sm text-gray-600">Customer Inquiries</div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                            <div class="text-2xl font-bold text-yellow-600" id="urgent-messages">0</div>
                            <div class="text-sm text-gray-600">Urgent</div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                            <div class="text-2xl font-bold text-purple-600" id="system-messages">0</div>
                            <div class="text-sm text-gray-600">System</div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Message Filters Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Messages</h3>
                            <div class="space-y-2">
                                <button class="message-filter-btn w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-blue-600 font-medium bg-blue-50" data-type="all">
                                    <span class="flex items-center justify-between">
                                        <span>📧 All Messages</span>
                                        <span class="text-xs bg-blue-100 px-2 py-1 rounded" id="filter-all-count">0</span>
                                    </span>
                                </button>
                                <button class="message-filter-btn w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-type="unread">
                                    <span class="flex items-center justify-between">
                                        <span>🔴 Unread</span>
                                        <span class="text-xs bg-red-100 px-2 py-1 rounded" id="filter-unread-count">0</span>
                                    </span>
                                </button>
                                <button class="message-filter-btn w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-type="system">
                                    <span class="flex items-center justify-between">
                                        <span>⚙️ System</span>
                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded" id="filter-system-count">0</span>
                                    </span>
                                </button>
                                <button class="message-filter-btn w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-type="customer_inquiry">
                                    <span class="flex items-center justify-between">
                                        <span>💬 Customer Inquiries</span>
                                        <span class="text-xs bg-green-100 px-2 py-1 rounded" id="filter-customer-count">0</span>
                                    </span>
                                </button>
                                <button class="message-filter-btn w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-type="booking_update">
                                    <span class="flex items-center justify-between">
                                        <span>📅 Booking Updates</span>
                                        <span class="text-xs bg-purple-100 px-2 py-1 rounded" id="filter-booking-count">0</span>
                                    </span>
                                </button>
                                <button class="message-filter-btn w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-type="payment_notification">
                                    <span class="flex items-center justify-between">
                                        <span>💰 Payments</span>
                                        <span class="text-xs bg-yellow-100 px-2 py-1 rounded" id="filter-payment-count">0</span>
                                    </span>
                                </button>
                                <button class="message-filter-btn w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-type="visit_request">
                                    <span class="flex items-center justify-between">
                                        <span>🏨 Visit Requests</span>
                                        <span class="text-xs bg-indigo-100 px-2 py-1 rounded" id="filter-visit-count">0</span>
                                    </span>
                                </button>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <button onclick="markAllMessagesRead()" class="w-full text-left px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    ✓ Mark All Read
                                </button>
                                <button onclick="refreshMessages()" class="w-full text-left px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    🔄 Refresh
                                </button>
                                <button onclick="exportMessages()" class="w-full text-left px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                    📥 Export
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Messages List -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-xl shadow-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-800" id="messages-section-title">All Messages</h3>
                                    <div class="flex items-center space-x-2">
                                        <select id="message-sort" class="px-3 py-1 border border-gray-300 rounded text-sm">
                                            <option value="newest">Newest First</option>
                                            <option value="oldest">Oldest First</option>
                                            <option value="priority">By Priority</option>
                                            <option value="unread">Unread First</option>
                                        </select>
                                        <div class="text-sm text-gray-500" id="messages-count">Loading...</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto">
                                <div id="messages-list" class="divide-y divide-gray-200">
                                    <div class="p-6 text-center text-gray-500">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                                        Loading messages...
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-6 py-4 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-500" id="pagination-info">Page 1 of 1</div>
                                    <div class="flex space-x-2">
                                        <button id="prev-page" class="px-3 py-1 bg-gray-300 text-gray-700 rounded text-sm hover:bg-gray-400 disabled:opacity-50" disabled>
                                            Previous
                                        </button>
                                        <button id="next-page" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 disabled:opacity-50" disabled>
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="packages-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Wedding Packages Management</h2>
                    <p class="text-gray-600">Create, edit, and manage wedding packages with full CRUD operations and real-time updates.</p>
                </div>

                <!-- Package Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Packages</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-packages">0</p>
                                <p class="text-xs text-gray-400 mt-1">All packages</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Active Packages</p>
                                <p class="text-2xl font-bold text-gray-900" id="active-packages">0</p>
                                <p class="text-xs text-gray-400 mt-1">Currently available</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Featured Packages</p>
                                <p class="text-2xl font-bold text-gray-900" id="featured-packages">0</p>
                                <p class="text-xs text-gray-400 mt-1">Highlighted packages</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Avg. Package Price</p>
                                <p class="text-2xl font-bold text-gray-900" id="avg-package-price">Rs. 0</p>
                                <p class="text-xs text-gray-400 mt-1">Average pricing</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Management Controls -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Package Management</h3>
                            <p class="text-sm text-gray-600 mt-1">Create, edit, and manage wedding packages</p>
                        </div>
                        <div class="flex flex-wrap gap-3 mt-4 sm:mt-0">
                            <button onclick="openPackageModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add New Package
                            </button>
                            <button onclick="refreshPackages()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </button>
                            <button onclick="exportPackages()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export
                            </button>
                        </div>
                    </div>

                    <!-- Package Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Packages</label>
                            <input type="text" id="package-search" placeholder="Search by name..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                            <select id="package-status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <select id="package-price-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Prices</option>
                                <option value="0-100000">Under Rs. 100,000</option>
                                <option value="100000-200000">Rs. 100,000 - 200,000</option>
                                <option value="200000-300000">Rs. 200,000 - 300,000</option>
                                <option value="300000+">Above Rs. 300,000</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">View Mode</label>
                            <div class="flex rounded-md shadow-sm">
                                <button onclick="togglePackageView('grid')" id="grid-view-btn" class="flex-1 bg-blue-600 text-white px-3 py-2 text-sm rounded-l-md hover:bg-blue-700 transition-colors">
                                    Grid
                                </button>
                                <button onclick="togglePackageView('list')" id="list-view-btn" class="flex-1 bg-gray-300 text-gray-700 px-3 py-2 text-sm rounded-r-md hover:bg-gray-400 transition-colors">
                                    List
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
                    
                    <!-- Filter Action Buttons -->
                    <div class="flex justify-end gap-3 mb-6">
                    <button onclick="applyPackageFilters()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    Apply
                    </button>
                    <button onclick="clearPackageFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Clear
                    </button>
                    </div>
                    
                    <!-- Packages Grid View -->
                <div id="packages-grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Packages will be loaded here dynamically -->
                    <div class="col-span-full text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">Loading packages...</p>
                    </div>
                </div>

                <!-- Packages List View -->
                <div id="packages-list-view" class="bg-white rounded-xl shadow-lg overflow-hidden hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="packages-list-body">
                                <!-- Package list items will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Package Modal for Add/Edit -->
            <div id="package-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900" id="package-modal-title">Add New Package</h3>
                            <button onclick="closePackageModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <form id="package-form" enctype="multipart/form-data">
                            <input type="hidden" id="package-id" name="package_id">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Name *</label>
                                        <input type="text" id="package-name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (Rs.) *</label>
                                        <input type="number" id="package-price" name="price" required min="0" step="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Image</label>
                                        <input type="file" id="package-image" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <div id="current-image" class="mt-2 hidden">
                                            <img id="current-image-preview" src="" alt="Current image" class="w-32 h-24 object-cover rounded-md">
                                            <label class="flex items-center mt-2">
                                                <input type="checkbox" id="remove-image" name="remove_image" value="1" class="mr-2">
                                                <span class="text-sm text-red-600">Remove current image</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="package-highlight" name="highlight" value="1" class="mr-2">
                                        <label for="package-highlight" class="text-sm font-medium text-gray-700">Featured Package</label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" id="package-active" name="is_active" value="1" checked class="mr-2">
                                        <label for="package-active" class="text-sm font-medium text-gray-700">Active Package</label>
                                    </div>
                                </div>
                                
                                <!-- Right Column -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                                        <textarea id="package-description" name="description" required rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Package Features</label>
                                        <div id="features-container" class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <input type="text" name="features[]" placeholder="Enter feature" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" onclick="addFeature()" class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                            + Add Feature
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                                <button type="button" onclick="closePackageModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    <span id="package-submit-text">Save Package</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Package Details Modal -->
            <div id="package-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-10 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900" id="package-details-title">Package Details</h3>
                            <button onclick="closePackageDetailsModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div id="package-details-content" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Package details will be loaded here -->
                        </div>
                        
                        <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                            <button onclick="editPackageFromDetails()" id="edit-from-details-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Edit Package
                            </button>
                            <button onclick="togglePackageStatusFromDetails()" id="toggle-status-from-details-btn" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors">
                                Toggle Status
                            </button>
                            <button onclick="deletePackageFromDetails()" id="delete-from-details-btn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                Delete Package
                            </button>
                            <button onclick="closePackageDetailsModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="reports-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Business Reports & Analytics</h2>
                    <p class="text-gray-600">Generate comprehensive reports and insights for business analysis.</p>
                </div>

                <!-- Report Categories -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Reports</h3>
                        <div class="space-y-3">
                            <button class="w-full text-left px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors" onclick="generateReport('revenue', 'monthly')">
                                Monthly Revenue Trends
                            </button>
                            <button class="w-full text-left px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors" onclick="generateReport('revenue', 'yearly')">
                                Yearly Revenue Analysis
                            </button>
                            <button class="w-full text-left px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors" onclick="generateReport('revenue', 'package')">
                                Package Popularity
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Reports</h3>
                        <div class="space-y-3">
                            <button class="w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors" onclick="generateReport('booking', 'peak-seasons')">
                                Peak Seasons Analysis
                            </button>
                            <button class="w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors" onclick="generateReport('booking', 'cancellation')">
                                Cancellation Rates
                            </button>
                            <button class="w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors" onclick="generateReport('booking', 'hall-utilization')">
                                Hall Utilization
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Reports</h3>
                        <div class="space-y-3">
                            <button class="w-full text-left px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors" onclick="generateReport('customer', 'demographics')">
                                Customer Demographics
                            </button>
                            <button class="w-full text-left px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors" onclick="generateReport('customer', 'satisfaction')">
                                Visit Conversion Rate
                            </button>
                            <button class="w-full text-left px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors" onclick="generateReport('customer', 'repeat')">
                                Customer Retention
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Report Display Area -->
                <div class="bg-white rounded-xl shadow-lg p-6" id="report-display">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Report Selected</h3>
                        <p class="mt-1 text-sm text-gray-500">Choose a report type above to view analytics and insights.</p>
                    </div>
                </div>

                <!-- Enhanced Analytics Dashboard -->
                <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">This Month's Revenue</span>
                                <span class="font-semibold text-green-600" id="quick-monthly-revenue">Rs. 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Conversion Rate</span>
                                <span class="font-semibold text-blue-600" id="quick-conversion-rate">0%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Average Booking Value</span>
                                <span class="font-semibold text-purple-600" id="quick-avg-booking">Rs. 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Peak Season Bookings</span>
                                <span class="font-semibold text-orange-600" id="quick-peak-bookings">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Export Options -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Export Reports</h3>
                        <div class="space-y-3">
                            <button onclick="exportReport('bookings', 'csv')" class="w-full text-left px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                                <i class="fas fa-file-csv mr-2"></i>Export Bookings (CSV)
                            </button>
                            <button onclick="exportReport('revenue', 'pdf')" class="w-full text-left px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                                <i class="fas fa-file-pdf mr-2"></i>Revenue Report (PDF)
                            </button>
                            <button onclick="exportReport('customers', 'excel')" class="w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-file-excel mr-2"></i>Customer Data (Excel)
                            </button>
                            <button onclick="exportReport('analytics', 'pdf')" class="w-full text-left px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors">
                                <i class="fas fa-chart-line mr-2"></i>Analytics Summary (PDF)
                            </button>
                        </div>
                    </div>

                    <!-- Report Filters -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Report Filters</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                                <select id="report-date-range" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="this_month">This Month</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="this_quarter">This Quarter</option>
                                    <option value="this_year">This Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hall Filter</label>
                                <select id="report-hall-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All Halls</option>
                                    <option value="jubilee">Jubilee Ballroom</option>
                                    <option value="grand">Grand Ballroom</option>
                                    <option value="garden">Garden Pavilion</option>
                                    <option value="royal">Royal Heritage Hall</option>
                                    <option value="riverside">Riverside Garden</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Filter</label>
                                <select id="report-package-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All Packages</option>
                                    <option value="basic">Basic Package</option>
                                    <option value="golden">Golden Package</option>
                                    <option value="infinity">Infinity Package</option>
                                </select>
                            </div>
                            <button onclick="applyReportFilters()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Management Section -->
            <div id="profile-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Profile Management</h2>
                    <p class="text-gray-600">Manage your account information, password, and profile settings.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Profile Information -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile Information
                        </h3>
                        
                        <form id="profile-form" class="space-y-4">
                            @csrf
                            <!-- Profile Picture -->
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="relative">
                                    <img id="profile-preview" src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=Manager&background=3B82F6&color=fff' }}" 
                                         alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-blue-100">
                                    <label for="profile-photo" class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-2 cursor-pointer hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </label>
                                    <input type="file" id="profile-photo" name="profile_photo" accept="image/*" class="hidden">
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Profile Picture</h4>
                                    <p class="text-sm text-gray-500">Click the camera icon to change your profile picture</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <input type="text" value="{{ ucfirst(auth()->user()->role ?? 'Manager') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Change -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Change Password
                        </h3>
                        
                        <form id="password-form" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" name="password" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" name="password_confirmation" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Activity -->
                <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Account Activity
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">Last Login</div>
                            <div class="font-semibold">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y g:i A') : 'Never' }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">Account Created</div>
                            <div class="font-semibold">{{ auth()->user()->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">Profile Updated</div>
                            <div class="font-semibold">{{ auth()->user()->updated_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div id="settings-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">System Settings</h2>
                    <p class="text-gray-600">Configure system preferences, business information, and notification settings.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Business Information -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Business Information
                        </h3>
                        
                        <form id="business-form" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                                <input type="text" name="business_name" value="Wedding Management System" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                                <input type="email" name="business_email" value="info@weddingmanagement.com" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                                <input type="tel" name="business_phone" value="+94 11 234 5678" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                                <textarea name="business_address" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">123 Wedding Street, Colombo 07, Sri Lanka</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Update Business Info
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Working Hours -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Working Hours
                        </h3>
                        
                        <form id="hours-form" class="space-y-4">
                            @csrf
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Monday</span>
                                    <div class="flex items-center space-x-2">
                                        <input type="time" name="monday_open" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                        <span class="text-gray-500">to</span>
                                        <input type="time" name="monday_close" value="18:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Tuesday</span>
                                    <div class="flex items-center space-x-2">
                                        <input type="time" name="tuesday_open" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                        <span class="text-gray-500">to</span>
                                        <input type="time" name="tuesday_close" value="18:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Wednesday</span>
                                    <div class="flex items-center space-x-2">
                                        <input type="time" name="wednesday_open" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                        <span class="text-gray-500">to</span>
                                        <input type="time" name="wednesday_close" value="18:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Thursday</span>
                                    <div class="flex items-center space-x-2">
                                        <input type="time" name="thursday_open" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                        <span class="text-gray-500">to</span>
                                        <input type="time" name="thursday_close" value="18:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Friday</span>
                                    <div class="flex items-center space-x-2">
                                        <input type="time" name="friday_open" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                        <span class="text-gray-500">to</span>
                                        <input type="time" name="friday_close" value="18:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Saturday</span>
                                    <div class="flex items-center space-x-2">
                                        <input type="time" name="saturday_open" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                        <span class="text-gray-500">to</span>
                                        <input type="time" name="saturday_close" value="16:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Sunday</span>
                                    <div class="flex items-center space-x-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="sunday_closed" checked class="mr-2">
                                            <span class="text-sm text-gray-600">Closed</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Update Hours
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notification Preferences -->
                <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5"></path>
                        </svg>
                        Notification Preferences
                    </h3>
                    
                    <form id="notifications-form" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-900">Email Notifications</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_new_bookings" checked class="mr-3">
                                        <span class="text-sm">New booking requests</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_visit_requests" checked class="mr-3">
                                        <span class="text-sm">Visit requests</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_payment_confirmations" checked class="mr-3">
                                        <span class="text-sm">Payment confirmations</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_daily_summary" class="mr-3">
                                        <span class="text-sm">Daily summary reports</span>
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h4 class="font-medium text-gray-900">System Notifications</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="system_urgent_alerts" checked class="mr-3">
                                        <span class="text-sm">Urgent alerts</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="system_maintenance" checked class="mr-3">
                                        <span class="text-sm">Maintenance notifications</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="system_updates" class="mr-3">
                                        <span class="text-sm">System updates</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="system_reminders" checked class="mr-3">
                                        <span class="text-sm">Task reminders</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="visit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-bold text-gray-900 text-center" id="modal-title">Approve Visit Request</h3>
                        <div class="mt-4 px-7 py-3">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Customer:</label>
                                <p class="text-gray-900" id="modal-customer"></p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Requested Date:</label>
                                <p class="text-gray-900" id="modal-date"></p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes:</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" id="modal-notes" placeholder="Add approval notes or reason for rejection..." required></textarea>
                                <div id="modal-notes-error" class="text-red-500 text-sm"></div>
                            </div>
                        </div>
                        <div class="flex items-center px-4 py-3">
                            <button id="approve-btn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 mr-2">
                                Approve
                            </button>
                            <button id="reject-btn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 mr-2">
                                Reject
                            </button>
                            <button id="cancel-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real data from backend - no localStorage needed
        let state = {
            halls: [],
            visits: [],
            weddingRequests: [],
            bookings: [],
            calendarEvents: []
        };

        // Load real data from backend
        async function loadRealData() {
            const loadingIndicator = document.getElementById('loading-indicator');
            
            try {
                loadingIndicator.classList.remove('hidden');
                
                // Load dashboard stats
                const statsResponse = await fetch('{{ route("manager.dashboard.stats") }}');
                const statsData = await statsResponse.json();
                
                // Load pending visits
                const visitsResponse = await fetch('{{ route("manager.pending-visits") }}');
                const visitsData = await visitsResponse.json();
                
                // Load calendar events
                const eventsResponse = await fetch('{{ route("manager.calendar.events") }}');
                const eventsData = await eventsResponse.json();
                
                if (statsData.success) {
                    updateDashboardStats(statsData.stats);
                }
                
                // Process visit requests data
                if (visitsData.data) {
                    state.visits = visitsData.data.map(booking => {
                        // Handle user name and professional ID properly
                        let coupleName = 'Unknown Customer';
                        let customerProfessionalId = '';
                        
                        if (booking.user && booking.user.name) {
                            coupleName = booking.user.name;
                            customerProfessionalId = booking.user.id; // ID is now the professional ID
                        } else if (booking.user && (booking.user.first_name || booking.user.last_name)) {
                            coupleName = `${booking.user.first_name || ''} ${booking.user.last_name || ''}`.trim();
                            customerProfessionalId = booking.user.id; // ID is now the professional ID
                        } else if (booking.contact_name) {
                            coupleName = booking.contact_name;
                            customerProfessionalId = booking.user ? booking.user.id : 'Guest';
                        } else if (booking.wedding_groom_name && booking.wedding_bride_name) {
                            coupleName = `${booking.wedding_groom_name} & ${booking.wedding_bride_name}`;
                            customerProfessionalId = booking.user ? booking.user.id : 'Guest';
                        } else if (booking.wedding_groom_name) {
                            coupleName = booking.wedding_groom_name;
                            customerProfessionalId = booking.user ? booking.user.id : 'Guest';
                        } else if (booking.wedding_bride_name) {
                            coupleName = booking.wedding_bride_name;
                            customerProfessionalId = booking.user ? booking.user.id : 'Guest';
                        }

                        // Handle hall name
                        let hallName = 'Unknown Hall';
                        if (booking.hall && booking.hall.name) {
                            hallName = booking.hall.name;
                        } else if (booking.hall_id) {
                            // Map hall_id to hall name based on database
                            const hallMap = {
                                1: 'Jubilee Ballroom',
                                2: 'Grand Ballroom', 
                                3: 'Garden Pavilion',
                                4: 'Royal Heritage Hall',
                                5: 'Riverside Garden'
                            };
                            hallName = hallMap[booking.hall_id] || `Hall ${booking.hall_id}`;
                        }

                        return {
                            id: booking.id,
                            couple: coupleName,
                            customerProfessionalId: customerProfessionalId,
                            date: booking.visit_date ? new Date(booking.visit_date).toLocaleDateString() : 'Not set',
                            time: booking.visit_time || 'Not set',
                            notes: booking.special_requests || 'No notes',
                            status: booking.visit_confirmed ? 'approved' : 'pending',
                            hall: hallName,
                            bookingId: booking.id,
                            email: booking.contact_email || (booking.user ? booking.user.email : '') || booking.wedding_groom_email || '',
                            phone: booking.contact_phone || booking.wedding_groom_phone || ''
                        };
                    });
                    
                    // Also populate wedding requests from confirmed visits (both pending and paid)
                    state.weddingRequests = visitsData.data
                        .filter(booking => booking.visit_confirmed) // Include all confirmed visits
                        .map(booking => {
                            // Handle user name properly
                            let coupleName = 'Unknown Customer';
                            if (booking.user && booking.user.name) {
                                coupleName = booking.user.name;
                            } else if (booking.contact_name) {
                                coupleName = booking.contact_name;
                            } else if (booking.wedding_groom_name && booking.wedding_bride_name) {
                                coupleName = `${booking.wedding_groom_name} & ${booking.wedding_bride_name}`;
                            } else if (booking.wedding_groom_name) {
                                coupleName = booking.wedding_groom_name;
                            } else if (booking.wedding_bride_name) {
                                coupleName = booking.wedding_bride_name;
                            }

                            // Handle hall name
                            let hallName = 'Unknown Hall';
                            if (booking.hall && booking.hall.name) {
                                hallName = booking.hall.name;
                            } else if (booking.hall_id) {
                                const hallMap = {
                                    1: 'Jubilee Ballroom',
                                    2: 'Grand Ballroom', 
                                    3: 'Garden Pavilion',
                                    4: 'Royal Heritage Hall',
                                    5: 'Riverside Garden'
                                };
                                hallName = hallMap[booking.hall_id] || `Hall ${booking.hall_id}`;
                            }

                            // Determine payment status for wedding requests
                            let paymentStatus = 'pending';
                            if (booking.advance_payment_paid) {
                                paymentStatus = 'paid'; // Payment confirmed
                            } else if (booking.visit_confirmed && !booking.advance_payment_paid) {
                                paymentStatus = 'pending'; // Awaiting payment confirmation
                            }

                            return {
                                id: booking.id,
                                couple: coupleName,
                                weddingDate: booking.event_date ? new Date(booking.event_date).toLocaleDateString() : 
                                            (booking.wedding_date ? new Date(booking.wedding_date).toLocaleDateString() : 'Not set'),
                                timeSlot: booking.wedding_ceremony_time || booking.start_time || 'Not set',
                                hall: hallName,
                                bookingId: `WED${String(booking.id).padStart(4, '0')}`,
                                status: paymentStatus,
                                advanceAmount: booking.advance_payment_amount || 0,
                                visitConfirmedAt: booking.visit_confirmed_at,
                                paymentPaidAt: booking.advance_payment_paid_at
                            };
                        });
                }
                
                if (eventsData.success) {
                    state.calendarEvents = eventsData.events.map(event => ({
                        date: event.start,
                        type: event.type,
                        status: event.status,
                        title: event.title
                    }));
                }
                
                // Load halls data
                await loadHallsData();
                
                // Load bookings data
                await loadBookingsData();
                
                loadingIndicator.classList.add('hidden');
                
            } catch (error) {
                console.error('Error loading real data:', error);
                loadingIndicator.classList.add('hidden');
                
                // Show error message to user
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-2';
                errorDiv.innerHTML = '<strong>Error:</strong> Failed to load dashboard data. Please refresh the page.';
                document.querySelector('.mb-8').appendChild(errorDiv);
            }
        }

        async function loadHallsData() {
            try {
                // Load real halls data from the backend
                const hallsResponse = await fetch('{{ route("manager.halls.list") }}');
                const hallsData = await hallsResponse.json();
                
                if (hallsData.success && hallsData.halls) {
                    state.halls = hallsData.halls.map(hall => ({
                        id: hall.id,
                        name: hall.name,
                        description: hall.description,
                        capacity: hall.capacity,
                        price: hall.price,
                        image: hall.image,
                        features: hall.features || [],
                        availability: hall.stats.availability,
                        bookings: hall.stats.active_bookings,
                        confirmedBookings: hall.stats.confirmed_bookings,
                        pendingVisits: hall.stats.pending_visits,
                        is_active: hall.is_active
                    }));
                } else {
                    // Fallback to default halls if API fails
                    state.halls = [
                        { id: 1, name: 'Jubilee Ballroom', capacity: 200, availability: 'available', bookings: 0 },
                        { id: 2, name: 'Grand Ballroom', capacity: 500, availability: 'available', bookings: 0 },
                        { id: 3, name: 'Garden Pavilion', capacity: 300, availability: 'available', bookings: 0 },
                        { id: 4, name: 'Royal Heritage Hall', capacity: 200, availability: 'available', bookings: 0 },
                        { id: 5, name: 'Riverside Garden', capacity: 150, availability: 'available', bookings: 0 }
                    ];
                }
                
            } catch (error) {
                console.error('Error loading halls data:', error);
                // Fallback to default halls
                state.halls = [
                    { id: 1, name: 'Jubilee Ballroom', capacity: 200, availability: 'available', bookings: 0 },
                    { id: 2, name: 'Grand Ballroom', capacity: 500, availability: 'available', bookings: 0 },
                    { id: 3, name: 'Garden Pavilion', capacity: 300, availability: 'available', bookings: 0 },
                    { id: 4, name: 'Royal Heritage Hall', capacity: 200, availability: 'available', bookings: 0 },
                    { id: 5, name: 'Riverside Garden', capacity: 150, availability: 'available', bookings: 0 }
                ];
            }
        }

        async function loadBookingsData() {
            try {
                // Load confirmed bookings from visits data that have advance payment paid
                const confirmedBookings = state.visits
                    .filter(visit => visit.status === 'approved')
                    .map(visit => ({
                        id: `BKG${String(visit.id).padStart(3, '0')}`,
                        coupleName: visit.couple,
                        hall: visit.hall,
                        weddingDate: visit.date,
                        guests: 100, // Default value - in real implementation, get from booking data
                        total: 50000, // Default value - in real implementation, calculate from booking
                        paymentStatus: 'Pending',
                        status: 'Confirmed',
                        package: 'Standard',
                        bookingId: visit.id
                    }));
                
                // Add wedding requests that have advance payment paid as confirmed bookings
                const paidBookings = state.weddingRequests
                    .filter(req => req.advanceAmount > 0)
                    .map(req => ({
                        id: req.bookingId,
                        coupleName: req.couple,
                        hall: req.hall,
                        weddingDate: req.weddingDate,
                        guests: 150, // Default value
                        total: req.advanceAmount * 5, // Estimate total from advance (20%)
                        paymentStatus: 'Advance Paid',
                        status: 'Confirmed',
                        package: 'Premium',
                        bookingId: req.id
                    }));
                
                state.bookings = [...confirmedBookings, ...paidBookings];
                
            } catch (error) {
                console.error('Error loading bookings data:', error);
                state.bookings = [];
            }
        }

        // Initialize with real data
        loadRealData();

        // Notifications System
        let notificationsData = [];
        let unreadCount = 0;

        // Initialize notifications
        async function initializeNotifications() {
            await loadNotifications();
            setupNotificationEventListeners();
            startNotificationPolling();
        }

        // Load notifications from backend
        async function loadNotifications() {
            try {
                const response = await fetch('{{ route("manager.notifications") }}?limit=10');
                const data = await response.json();
                
                if (data.success) {
                    notificationsData = data.notifications;
                    unreadCount = data.unread_count;
                    updateNotificationBadge();
                    renderNotifications();
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Update notification badge
        function updateNotificationBadge() {
            const badge = document.getElementById('notification-badge');
            if (unreadCount > 0) {
                badge.classList.remove('hidden');
                badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                if (unreadCount > 9) {
                    badge.classList.remove('h-2', 'w-2');
                    badge.classList.add('h-5', 'w-5', 'text-xs', 'flex', 'items-center', 'justify-center', 'font-bold');
                }
            } else {
                badge.classList.add('hidden');
            }
        }

        // Render notifications in dropdown
        function renderNotifications() {
            const notificationsList = document.getElementById('notifications-list');
            
            if (notificationsData.length === 0) {
                notificationsList.innerHTML = `
                    <div class="px-4 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5"></path>
                        </svg>
                        <p class="text-sm">No notifications</p>
                    </div>
                `;
                return;
            }

            notificationsList.innerHTML = notificationsData.map(notification => `
                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 cursor-pointer transition-colors ${notification.action_required ? 'bg-blue-50' : ''}" 
                     onclick="handleNotificationClick('${notification.id}', '${notification.type}', ${notification.booking_id || 'null'})">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            ${getNotificationIcon(notification.type, notification.priority)}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                            <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-500">${notification.time}</p>
                                ${notification.action_required ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Action Required</span>' : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Get notification icon based on type and priority
        function getNotificationIcon(type, priority) {
            const iconClass = priority === 'high' ? 'text-red-500' : priority === 'medium' ? 'text-yellow-500' : 'text-blue-500';
            
            switch (type) {
                case 'visit_request':
                    return `<svg class="h-5 w-5 ${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>`;
                case 'payment_pending':
                    return `<svg class="h-5 w-5 ${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>`;
                default:
                    return `<svg class="h-5 w-5 ${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>`;
            }
        }

        // Handle notification click
        window.handleNotificationClick = function(notificationId, type, bookingId) {
            // Close notifications dropdown
            document.getElementById('notifications-dropdown').classList.add('hidden');
            
            // Navigate based on notification type
            switch (type) {
                case 'visit_request':
                    showSection('visits');
                    if (bookingId) {
                        // Highlight specific booking if possible
                        setTimeout(() => {
                            const row = document.querySelector(`[data-booking-id="${bookingId}"]`);
                            if (row) {
                                row.scrollIntoView({ behavior: 'smooth' });
                                row.classList.add('bg-yellow-100');
                                setTimeout(() => row.classList.remove('bg-yellow-100'), 3000);
                            }
                        }, 500);
                    }
                    break;
                case 'payment_pending':
                    showSection('wedding-requests');
                    if (bookingId) {
                        setTimeout(() => {
                            const row = document.querySelector(`[data-booking-id="${bookingId}"]`);
                            if (row) {
                                row.scrollIntoView({ behavior: 'smooth' });
                                row.classList.add('bg-yellow-100');
                                setTimeout(() => row.classList.remove('bg-yellow-100'), 3000);
                            }
                        }, 500);
                    }
                    break;
                default:
                    // For other notifications, just show dashboard
                    showSection('dashboard');
            }
            
            // Mark notification as read (optional)
            markNotificationAsRead(notificationId);
        };

        // Mark notification as read
        async function markNotificationAsRead(notificationId) {
            try {
                await fetch(`{{ url('/manager/notifications') }}/${notificationId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                // Reload notifications to update count
                await loadNotifications();
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        // Mark all notifications as read
        async function markAllNotificationsAsRead() {
            try {
                await fetch('{{ route("manager.notifications.mark-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                unreadCount = 0;
                updateNotificationBadge();
                await loadNotifications();
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        }

        // Setup notification event listeners
        function setupNotificationEventListeners() {
            const notificationsBtn = document.getElementById('notifications-btn');
            const notificationsDropdown = document.getElementById('notifications-dropdown');
            const markAllReadBtn = document.getElementById('mark-all-read');

            // Toggle notifications dropdown
            notificationsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationsDropdown.classList.toggle('hidden');
                
                // Close user dropdown if open
                const userDropdown = document.querySelector('.dropdown-menu');
                if (userDropdown && !userDropdown.classList.contains('hidden')) {
                    userDropdown.classList.add('hidden');
                }
            });

            // Mark all as read
            markAllReadBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                markAllNotificationsAsRead();
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notificationsBtn.contains(e.target) && !notificationsDropdown.contains(e.target)) {
                    notificationsDropdown.classList.add('hidden');
                }
            });
        }

        // Start polling for new notifications
        function startNotificationPolling() {
            // Poll every 30 seconds for new notifications
            setInterval(async () => {
                await loadNotifications();
            }, 30000);
        }

        // Initialize notifications system
        initializeNotifications();

        // Navigation Functionality
        const navItems = document.querySelectorAll('.nav-item');
        const contentSections = document.querySelectorAll('.content-section');
        const pageTitle = document.getElementById('pageTitle');
        const pageTitles = {
            'dashboard': 'Dashboard Overview',
            'visits': 'Visit Requests Management',
            'wedding-requests': 'Wedding Date Approvals',
            'halls': 'Manage Wedding Halls',
            'bookings': 'Bookings Tracker',
            'calendar': 'Calendar View',
            'messages': 'Message Center',
            'reports': 'Business Reports & Analytics',
            'profile': 'Profile Management',
            'settings': 'System Settings'
        };

        function showSection(sectionId) {
            contentSections.forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(`${sectionId}-section`).classList.remove('hidden');
            pageTitle.textContent = pageTitles[sectionId];
            navItems.forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`.nav-item[href="#${sectionId}"]`).classList.add('active');
            loadSectionContent(sectionId);
            if (sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        }

        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const sectionId = this.getAttribute('href').substring(1);
                showSection(sectionId);
            });
        });

        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Dashboard Stats Update
        window.updateDashboardStats = function(stats) {
            document.getElementById('total-halls').textContent = stats.total_halls || state.halls.length || 0;
            document.getElementById('pending-visits').textContent = stats.pending_visits || state.visits.filter(v => v.status === 'pending').length;
            document.getElementById('confirmed-bookings').textContent = stats.confirmed_visits || stats.completed_bookings || state.bookings.filter(b => b.status === 'Confirmed').length;
            
            // Calculate and display total revenue
            const totalRevenue = stats.total_revenue || state.weddingRequests.reduce((sum, req) => sum + (req.advanceAmount || 0), 0);
            document.getElementById('monthly-revenue').textContent = `Rs. ${totalRevenue.toLocaleString()}`;
        };

        // Hall Occupancy Update
        function updateHallOccupancy() {
            const hallOccupancyDiv = document.getElementById('hall-occupancy');
            const next7Days = [];
            const today = new Date();
            
            for (let i = 0; i < 7; i++) {
                const date = new Date(today);
                date.setDate(today.getDate() + i);
                next7Days.push(date.toISOString().split('T')[0]);
            }
            
            const occupancyData = state.halls.map(hall => {
                const occupiedDays = next7Days.filter(date => {
                    return state.weddingRequests.some(w => {
                        if (!w.weddingDate || w.weddingDate === 'Not set') return false;
                        const weddingDate = new Date(w.weddingDate).toISOString().split('T')[0];
                        return weddingDate === date && w.hall === hall.name;
                    });
                }).length;
                
                return {
                    name: hall.name,
                    occupiedDays,
                    occupancyRate: Math.round((occupiedDays / 7) * 100)
                };
            });
            
            if (occupancyData.length > 0) {
                hallOccupancyDiv.innerHTML = occupancyData.map(hall => `
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div>
                            <div class="font-medium text-sm">${hall.name}</div>
                            <div class="text-xs text-gray-500">${hall.occupiedDays}/7 days booked</div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-${hall.occupancyRate > 70 ? 'red' : hall.occupancyRate > 40 ? 'yellow' : 'green'}-500 h-2 rounded-full" style="width: ${hall.occupancyRate}%"></div>
                            </div>
                            <span class="text-xs font-medium">${hall.occupancyRate}%</span>
                        </div>
                    </div>
                `).join('');
            } else {
                hallOccupancyDiv.innerHTML = '<div class="text-gray-500 text-sm">No occupancy data available</div>';
            }
        }

        function updateRecentActivities() {
            const recentVisitsDiv = document.getElementById('recent-visits');
            const upcomingWeddingsDiv = document.getElementById('upcoming-weddings');

            const pendingVisits = state.visits.filter(v => v.status === 'pending');
            if (pendingVisits.length > 0) {
                recentVisitsDiv.innerHTML = pendingVisits.slice(0, 3).map(visit => `
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-semibold text-gray-800">${visit.couple}</p>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Pending</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">📅 ${visit.date} at ${visit.time}</p>
                        <p class="text-sm text-gray-600 mb-1">🏛️ ${visit.hall}</p>
                        <p class="text-sm text-gray-600">📝 ${visit.notes || 'No special requests'}</p>
                        <div class="mt-2 flex space-x-2">
                            <button onclick="showVisitModal(${visit.id}, '${visit.couple.replace(/'/g, "\\'")}', '${visit.date}', '${visit.notes ? visit.notes.replace(/'/g, "\\'") : ''}', 'approve')" class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                Quick Approve
                            </button>
                        </div>
                    </div>
                `).join('');
            } else {
                recentVisitsDiv.innerHTML = '<div class="text-gray-500 text-sm flex items-center justify-center py-8"><div class="text-center"><div class="text-2xl mb-2">✅</div><div>No pending visit requests</div></div></div>';
            }

            // Show upcoming weddings from wedding requests and confirmed bookings
            const upcomingWeddings = [...state.weddingRequests, ...state.bookings.filter(b => new Date(b.weddingDate) > new Date() && b.status === 'Confirmed')];
            if (upcomingWeddings.length > 0) {
                upcomingWeddingsDiv.innerHTML = upcomingWeddings.slice(0, 3).map(wedding => {
                    let statusBadge = '';
                    let statusIcon = '';
                    
                    switch(wedding.status) {
                        case 'pending':
                            statusBadge = 'bg-orange-100 text-orange-800';
                            statusIcon = '💰';
                            break;
                        case 'paid':
                        case 'approved':
                            statusBadge = 'bg-green-100 text-green-800';
                            statusIcon = '✅';
                            break;
                        default:
                            statusBadge = 'bg-gray-100 text-gray-800';
                            statusIcon = '📋';
                    }
                    
                    return `
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold text-gray-800">${wedding.couple || wedding.coupleName}</p>
                                <span class="px-2 py-1 ${statusBadge} text-xs rounded-full">
                                    ${statusIcon} ${wedding.status === 'pending' ? 'Payment Pending' : wedding.status === 'paid' ? 'Payment Confirmed' : 'Confirmed'}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">📅 ${wedding.weddingDate}</p>
                            <p class="text-sm text-gray-600 mb-1">🏛️ ${wedding.hall}</p>
                            ${wedding.advanceAmount ? `<p class="text-sm ${wedding.status === 'paid' ? 'text-green-600' : 'text-orange-600'}">💰 Rs. ${wedding.advanceAmount.toLocaleString()} ${wedding.status === 'paid' ? 'advance paid' : 'advance required'}</p>` : ''}
                            ${wedding.guests ? `<p class="text-sm text-gray-600">👥 ${wedding.guests} guests</p>` : ''}
                            ${wedding.status === 'pending' ? `<div class="mt-2"><button onclick="approveWeddingRequest(${wedding.id})" class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">Mark Paid</button></div>` : ''}
                        </div>
                    `;
                }).join('');
            } else {
                upcomingWeddingsDiv.innerHTML = '<div class="text-gray-500 text-sm flex items-center justify-center py-8"><div class="text-center"><div class="text-2xl mb-2">💒</div><div>No upcoming weddings</div></div></div>';
            }
            
            // Update hall occupancy
            updateHallOccupancy();
        }

        // Visit Requests Management - FIXED: Maintain history of all visits
        window.loadVisitRequests = function() {
            const tbody = document.getElementById('visits-table-body');
            let filteredVisits = [...state.visits];
            
            // Apply filters
            const statusFilter = document.getElementById('filter-visit-status')?.value;
            const hallFilter = document.getElementById('filter-visit-hall')?.value;
            
            if (statusFilter) {
                filteredVisits = filteredVisits.filter(v => v.status === statusFilter);
            }
            if (hallFilter) {
                filteredVisits = filteredVisits.filter(v => v.hall === hallFilter);
            }
            
            // Sort by status (pending first) then by date
            filteredVisits.sort((a, b) => {
                if (a.status === 'pending' && b.status !== 'pending') return -1;
                if (b.status === 'pending' && a.status !== 'pending') return 1;
                return new Date(b.date) - new Date(a.date);
            });
            
            if (filteredVisits.length > 0) {
                tbody.innerHTML = filteredVisits.map(visit => {
                    // Determine status display and actions
                    let statusBadge = '';
                    let actionButtons = '';
                    let rowClass = 'hover:bg-gray-50';
                    
                    switch(visit.status) {
                        case 'pending':
                            statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Approval</span>';
                            actionButtons = `
                                <div class="flex space-x-1 mb-1">
                                    <button onclick="showVisitModal(${visit.id}, '${visit.couple.replace(/'/g, "\\'")}', '${visit.date}', '${visit.notes ? visit.notes.replace(/'/g, "\\'") : ''}', 'approve')" class="text-green-600 hover:text-green-900 px-2 py-1 rounded border border-green-600 hover:bg-green-50 text-xs">
                                        Approve
                                    </button>
                                    <button onclick="showVisitModal(${visit.id}, '${visit.couple.replace(/'/g, "\\'")}', '${visit.date}', '${visit.notes ? visit.notes.replace(/'/g, "\\'") : ''}', 'reject')" class="text-red-600 hover:text-red-900 px-2 py-1 rounded border border-red-600 hover:bg-red-50 text-xs">
                                        Reject
                                    </button>
                                </div>
                            `;
                            rowClass = 'hover:bg-yellow-50 border-l-4 border-yellow-400';
                            break;
                        case 'approved':
                            statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Approved</span>';
                            actionButtons = `
                                <div class="text-green-600 text-xs font-medium mb-1">
                                    <i class="fas fa-check-circle mr-1"></i>Visit Confirmed
                                </div>
                                <div class="text-gray-500 text-xs">
                                    Awaiting payment confirmation
                                </div>
                            `;
                            rowClass = 'hover:bg-green-50 border-l-4 border-green-400';
                            break;
                        case 'rejected':
                            statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">✗ Rejected</span>';
                            actionButtons = `
                                <div class="text-red-600 text-xs font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>Visit Rejected
                                </div>
                            `;
                            rowClass = 'hover:bg-red-50 border-l-4 border-red-400';
                            break;
                        default:
                            statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
                            actionButtons = '<span class="text-gray-500 text-sm">Status Unknown</span>';
                    }
                    
                    return `
                        <tr class="${rowClass}" data-booking-id="${visit.id}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${visit.couple}</div>
                                <div class="text-blue-600 text-xs font-semibold">ID: ${visit.customerProfessionalId || 'Guest'}</div>
                                <div class="text-gray-500 text-xs">${visit.email || 'No email'}</div>
                                <div class="text-gray-500 text-xs">${visit.phone || 'No phone'}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${visit.date}</div>
                                <div class="text-gray-500 text-xs">Requested visit</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${visit.time}</div>
                                <div class="text-gray-500 text-xs">Preferred time</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    <div class="font-medium text-gray-700 mb-1">Hall: ${visit.hall}</div>
                                    <div class="text-gray-600 text-xs" title="${visit.notes || 'No special requests'}">${visit.notes || 'No special requests'}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${statusBadge}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-1">
                                    ${actionButtons}
                                    ${visit.email || visit.phone ? `
                                    <button onclick="contactCustomer('${visit.couple.replace(/'/g, "\\'")}', '${visit.email}', '${visit.phone}')" class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded border border-blue-600 hover:bg-blue-50 text-xs">
                                        <i class="fas fa-phone mr-1"></i>Contact
                                    </button>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500"><div class="py-8"><div class="text-4xl mb-2">📋</div><div>No visit requests found</div><div class="text-sm text-gray-400 mt-1">Visit requests will appear here when customers submit them</div></div></td></tr>';
            }
            updateDashboardStats({});
            updateRecentActivities();
        };

        document.getElementById('refresh-visits').addEventListener('click', async () => {
            await loadRealData();
            loadVisitRequests();
        });

        // Add filter event listeners for visit requests
        document.getElementById('filter-visit-status')?.addEventListener('change', loadVisitRequests);
        document.getElementById('filter-visit-hall')?.addEventListener('change', loadVisitRequests);

        // Contact Customer Function
        window.contactCustomer = function(customerName, email, phone) {
            let contactOptions = [];
            if (email) contactOptions.push(`Email: ${email}`);
            if (phone) contactOptions.push(`Phone: ${phone}`);
            
            const message = `Contact ${customerName}:\n\n${contactOptions.join('\n')}\n\nChoose your preferred method to contact the customer.`;
            
            if (confirm(message)) {
                if (email) {
                    window.open(`mailto:${email}?subject=Regarding Your Visit Request&body=Dear ${customerName},%0D%0A%0D%0AThank you for your interest in our wedding venue.%0D%0A%0D%0ABest regards,%0D%0AManager`);
                } else if (phone) {
                    window.open(`tel:${phone}`);
                }
            }
        };

        // Wedding Date Requests Management - FIXED: Show payment confirmation workflow
        window.loadWeddingRequests = function() {
            const tbody = document.getElementById('wedding-requests-table-body');
            
            // Include all wedding requests with proper status tracking
            let allWeddingRequests = [...state.weddingRequests];
            
            // Sort by status (pending first) then by date
            allWeddingRequests.sort((a, b) => {
                if (a.status === 'pending' && b.status !== 'pending') return -1;
                if (b.status === 'pending' && a.status !== 'pending') return 1;
                return new Date(b.weddingDate) - new Date(a.weddingDate);
            });
            
            if (allWeddingRequests.length > 0) {
                tbody.innerHTML = allWeddingRequests.map(request => {
                    let statusBadge = '';
                    let actionButtons = '';
                    let rowClass = 'hover:bg-gray-50';
                    
                    switch(request.status) {
                        case 'pending':
                            statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">💰 Payment Pending</span>';
                            actionButtons = `
                                <div class="flex space-x-1 mb-1">
                                    <button onclick="approveWeddingRequest(${request.id})" class="text-green-600 hover:text-green-900 px-2 py-1 rounded border border-green-600 hover:bg-green-50 text-xs">
                                        ✓ Mark Paid
                                    </button>
                                    <button onclick="rejectWeddingRequest(${request.id})" class="text-red-600 hover:text-red-900 px-2 py-1 rounded border border-red-600 hover:bg-red-50 text-xs">
                                        ✗ Reject
                                    </button>
                                </div>
                                <div class="text-orange-600 text-xs">
                                    Customer must pay Rs. ${request.advanceAmount ? request.advanceAmount.toLocaleString() : '0'} advance
                                </div>
                            `;
                            rowClass = 'hover:bg-orange-50 border-l-4 border-orange-400';
                            break;
                        case 'approved':
                        case 'paid':
                            statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">✓ Payment Confirmed</span>';
                            actionButtons = `
                                <div class="text-green-600 text-xs font-medium mb-1">
                                    <i class="fas fa-check-circle mr-1"></i>Advance Payment Received
                                </div>
                                <div class="text-gray-500 text-xs">
                                    Customer can now access Step 5
                                </div>
                            `;
                            rowClass = 'hover:bg-green-50 border-l-4 border-green-400';
                            break;
                        case 'rejected':
                            statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">✗ Rejected</span>';
                            actionButtons = `
                                <div class="text-red-600 text-xs font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>Payment Rejected
                                </div>
                            `;
                            rowClass = 'hover:bg-red-50 border-l-4 border-red-400';
                            break;
                        default:
                            statusBadge = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
                            actionButtons = '<span class="text-gray-500 text-sm">Status Unknown</span>';
                    }
                    
                    return `
                        <tr class="${rowClass}" data-booking-id="${request.id}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${request.couple}</div>
                                <div class="text-blue-600 text-xs font-semibold">ID: ${request.id}</div>
                                <div class="text-gray-500 text-xs">Visit approved ✓</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${request.weddingDate}</div>
                                <div class="text-gray-500 text-xs">Wedding date</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${request.timeSlot}</div>
                                <div class="text-gray-500 text-xs">Ceremony time</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${request.hall}</div>
                                <div class="text-gray-500 text-xs">Venue</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium">${request.bookingId}</div>
                                ${request.advanceAmount > 0 ? `
                                    <div class="text-green-600 text-xs font-semibold">
                                        Rs. ${request.advanceAmount.toLocaleString()} advance
                                    </div>
                                ` : ''}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${statusBadge}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-1">
                                    ${actionButtons}
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500"><div class="py-8"><div class="text-4xl mb-2">💒</div><div>No wedding date requests</div><div class="text-sm text-gray-400 mt-1">Wedding date requests appear here after visit approval</div></div></td></tr>';
            }
            updateDashboardStats({});
        };

        document.getElementById('bulk-approve').addEventListener('click', async () => {
            if (confirm('Are you sure you want to approve all pending wedding date requests?')) {
                try {
                    const response = await fetch('{{ route("manager.bulk.approve.payments") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert(data.message);
                        await loadRealData();
                        loadWeddingRequests();
                        updateDashboardStats({});
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error bulk approving requests:', error);
                    alert('Failed to bulk approve requests. Please try again.');
                }
            }
        });

        window.approveWeddingRequest = async function(bookingId) {
            if (confirm('Are you sure you want to approve this wedding request?')) {
                try {
                    const response = await fetch(`{{ url('/manager/booking') }}/${bookingId}/deposit-paid`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_method: 'cash',
                            notes: 'Wedding request approved by manager'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Wedding request approved!');
                        await loadRealData();
                        loadWeddingRequests();
                        updateDashboardStats({});
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error approving wedding request:', error);
                    alert('Failed to approve wedding request. Please try again.');
                }
            }
        };

        window.rejectWeddingRequest = async function(bookingId) {
            const reason = prompt('Please provide a reason for rejection:');
            if (reason && confirm('Are you sure you want to reject this wedding request?')) {
                try {
                    const response = await fetch(`{{ url('/manager/visit') }}/${bookingId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            reason: reason
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Wedding request rejected.');
                        await loadRealData();
                        loadWeddingRequests();
                        updateDashboardStats({});
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error rejecting wedding request:', error);
                    alert('Failed to reject wedding request. Please try again.');
                }
            }
        };

        // Bookings Tracker
        window.loadBookings = function() {
            const tbody = document.getElementById('bookings-table-body');
            let filteredBookings = [...state.bookings];

            const filterHall = document.getElementById('filter-hall').value;
            const filterStatus = document.getElementById('filter-status').value;

            if (filterHall) filteredBookings = filteredBookings.filter(b => b.hall.toLowerCase() === filterHall);
            if (filterStatus) filteredBookings = filteredBookings.filter(b => b.status.toLowerCase() === filterStatus);

            if (filteredBookings.length > 0) {
                tbody.innerHTML = filteredBookings.map(booking => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${booking.coupleName}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${booking.hall}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${booking.weddingDate}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${booking.guests}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rs. ${booking.total.toFixed(2)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                booking.paymentStatus === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                            }">
                                ${booking.paymentStatus}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                booking.status === 'Confirmed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
                            }">
                                ${booking.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-2">View</button>
                            <button class="text-green-600 hover:text-green-900 mr-2" onclick="generateBill(${JSON.stringify(booking).replace(/"/g, '&quot;')})">Invoice</button>
                            <button class="text-blue-600 hover:text-blue-900 mr-2" onclick="generateContract('${booking.id}')">Contract</button>
                            ${booking.paymentStatus === 'Pending' ? `<button class="text-purple-600 hover:text-purple-900" onclick="showPaymentModal('${booking.id}')">Record Payment</button>` : ''}
                            <button class="text-orange-600 hover:text-orange-900" onclick="trackPayment('${booking.id}')">Track Payment</button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">No bookings found</td></tr>';
            }
            updateDashboardStats({});
        };

        document.getElementById('filter-hall').addEventListener('change', loadBookings);
        document.getElementById('filter-status').addEventListener('change', loadBookings);
        document.getElementById('export-bill').addEventListener('click', () => {
            alert('Exporting bill (feature coming soon!).');
        });

        const billModal = document.getElementById('bill-modal');
        const printBillBtn = document.getElementById('print-bill-btn');
        const closeBillBtn = document.getElementById('close-bill-btn');

        window.generateBill = function(booking) {
            const billContent = document.getElementById('bill-content');
            billContent.innerHTML = `
                <h4 class="text-xl font-semibold mb-4">Booking ID: ${booking.id}</h4>
                <div class="grid grid-cols-2 gap-y-2">
                    <p><strong>Couple Name:</strong> ${booking.coupleName}</p>
                    <p><strong>Hall:</strong> ${booking.hall}</p>
                    <p><strong>Wedding Date:</strong> ${booking.weddingDate}</p>
                    <p><strong>Guests:</strong> ${booking.guests}</p>
                    <p><strong>Package:</strong> ${booking.package}</p>
                    <p><strong>Status:</strong> ${booking.status}</p>
                    <p><strong>Payment Status:</strong> ${booking.paymentStatus}</p>
                    <p class="col-span-2 text-2xl font-bold mt-4">Total Amount: Rs. ${booking.total.toFixed(2)}</p>
                </div>
                <hr class="my-4">
                <p class="text-sm text-gray-600">Note: Payment due 14 days prior</p>
            `;
            billModal.classList.remove('hidden');
        };

        printBillBtn.addEventListener('click', () => {
            window.print();
        });

        closeBillBtn.addEventListener('click', () => billModal.classList.add('hidden'));

        // Payment Modal Functionality
        const paymentModal = document.getElementById('payment-modal');
        const cancelPaymentBtn = document.getElementById('cancel-payment-btn');
        const paymentForm = document.getElementById('payment-form');

        window.showPaymentModal = function(bookingId) {
            const booking = state.bookings.find(b => b.id === bookingId);
            if (!booking) {
                alert('Booking not found');
                return;
            }
            
            document.getElementById('booking-id').value = bookingId;
            paymentForm.action = paymentForm.action.replace('/0', `/${bookingId}`);
            document.querySelector('input[name="amount"]').value = booking.total;
            paymentModal.classList.remove('hidden');
        };

        // Handle payment form submission
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(paymentForm);
            const bookingId = formData.get('booking_id');
            
            try {
                const response = await fetch(`{{ url('/manager/booking') }}/${bookingId}/deposit-paid`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_method: formData.get('payment_method'),
                        notes: formData.get('notes')
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Payment recorded successfully!');
                    paymentModal.classList.add('hidden');
                    await loadRealData();
                    loadBookings();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error recording payment:', error);
                alert('Failed to record payment. Please try again.');
            }
        });

        cancelPaymentBtn.addEventListener('click', () => {
            paymentModal.classList.add('hidden');
        });

        paymentModal.addEventListener('click', (e) => {
            if (e.target === paymentModal) {
                paymentModal.classList.add('hidden');
            }
        });

        // Calendar View
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        window.updateCalendar = function() {
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            document.getElementById('current-month').textContent = `${monthNames[currentMonth]} ${currentYear}`;

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const calendarGrid = document.getElementById('calendar-grid');
            calendarGrid.innerHTML = '';

            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'p-3 text-center text-gray-400';
                calendarGrid.appendChild(emptyCell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = document.createElement('div');
                dayCell.className = 'p-3 text-center hover:bg-gray-100 cursor-pointer border border-gray-200 min-h-[60px] relative';
                const date = new Date(currentYear, currentMonth, day);
                dayCell.innerHTML = `<span class="font-medium">${day}</span>`;

                const eventDate = date.toISOString().split('T')[0];
                
                // Check for visit events
                const visitEvents = state.visits.filter(v => {
                    if (!v.date || v.date === 'Not set') return false;
                    const visitDate = new Date(v.date).toISOString().split('T')[0];
                    return visitDate === eventDate;
                });
                
                // Check for wedding events
                const weddingEvents = state.weddingRequests.filter(w => {
                    if (!w.weddingDate || w.weddingDate === 'Not set') return false;
                    const weddingDate = new Date(w.weddingDate).toISOString().split('T')[0];
                    return weddingDate === eventDate;
                });
                
                // Check for calendar events from API
                const apiEvents = state.calendarEvents.filter(e => e.date === eventDate);
                
                // Add visit events
                visitEvents.forEach((visit, index) => {
                    if (index < 2) { // Limit to 2 events per day for space
                        const event = document.createElement('div');
                        event.className = 'text-xs rounded px-1 mt-1 bg-yellow-400 text-gray-800 truncate';
                        event.textContent = `Visit: ${visit.couple.split(' ')[0]}`;
                        event.title = `Visit Request: ${visit.couple} at ${visit.time} (${visit.hall})`;
                        dayCell.appendChild(event);
                    }
                });
                
                // Add wedding events
                weddingEvents.forEach((wedding, index) => {
                    if (index < 2 && visitEvents.length + index < 2) { // Limit total events
                        const event = document.createElement('div');
                        event.className = 'text-xs rounded px-1 mt-1 bg-green-500 text-white truncate';
                        event.textContent = `Wedding: ${wedding.couple.split(' ')[0]}`;
                        event.title = `Wedding: ${wedding.couple} (${wedding.hall})`;
                        dayCell.appendChild(event);
                    }
                });
                
                // Add API events
                apiEvents.forEach((apiEvent, index) => {
                    if (index < 2 && visitEvents.length + weddingEvents.length + index < 2) {
                        const event = document.createElement('div');
                        event.className = 'text-xs rounded px-1 mt-1';
                        event.className += apiEvent.type === 'visit' ? ' bg-yellow-400 text-gray-800' : 
                                          apiEvent.type === 'wedding' ? ' bg-green-500 text-white' : 
                                          ' bg-blue-400 text-white';
                        event.textContent = apiEvent.title || apiEvent.type.charAt(0).toUpperCase() + apiEvent.type.slice(1);
                        dayCell.appendChild(event);
                    }
                });
                
                // Mark busy days
                if (visitEvents.length > 0 || weddingEvents.length > 0 || apiEvents.length > 0) {
                    dayCell.classList.add('bg-gray-50');
                }
                calendarGrid.appendChild(dayCell);
            }
        };

        document.getElementById('prev-month').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateCalendar();
        });

        document.getElementById('next-month').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            updateCalendar();
        });

        // Modal Functionality
        const visitModal = document.getElementById('visit-modal');
        const cancelBtn = document.getElementById('cancel-btn');
        const approveBtn = document.getElementById('approve-btn');
        const rejectBtn = document.getElementById('reject-btn');
        const modalNotes = document.getElementById('modal-notes');
        const modalNotesError = document.getElementById('modal-notes-error');

        cancelBtn.addEventListener('click', () => visitModal.classList.add('hidden'));
        visitModal.addEventListener('click', (e) => {
            if (e.target === visitModal) visitModal.classList.add('hidden');
        });

        function validateModal() {
            if (!modalNotes.value.trim()) {
                modalNotesError.textContent = 'Notes are required for approval or rejection.';
                return false;
            }
            modalNotesError.textContent = '';
            return true;
        }

        let currentVisitData = null;

        window.showVisitModal = function(visitId, couple, date, notes, actionType) {
            currentVisitData = { visitId, couple, date, notes, actionType };
            document.getElementById('modal-title').textContent = `${actionType.charAt(0).toUpperCase() + actionType.slice(1)} Visit Request`;
            document.getElementById('modal-customer').textContent = couple;
            document.getElementById('modal-date').textContent = date;
            modalNotes.value = notes && notes !== 'No notes' ? notes : '';
            modalNotesError.textContent = '';

            approveBtn.classList.add('hidden');
            rejectBtn.classList.add('hidden');

            if (actionType === 'approve') {
                approveBtn.classList.remove('hidden');
            } else if (actionType === 'reject') {
                rejectBtn.classList.remove('hidden');
            }

            visitModal.classList.remove('hidden');
        };

        approveBtn.addEventListener('click', async () => {
            if (!validateModal()) return;
            if (confirm('Are you sure you want to approve this visit request?')) {
                try {
                    const response = await fetch(`{{ url('/manager/visit') }}/${currentVisitData.visitId}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            notes: modalNotes.value.trim()
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Visit request approved!');
                        await loadRealData();
                        loadVisitRequests();
                        visitModal.classList.add('hidden');
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error approving visit:', error);
                    alert('Failed to approve visit request. Please try again.');
                }
            }
        });

        rejectBtn.addEventListener('click', async () => {
            if (!validateModal()) return;
            if (confirm('Are you sure you want to reject this visit request?')) {
                try {
                    const response = await fetch(`{{ url('/manager/visit') }}/${currentVisitData.visitId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            reason: modalNotes.value.trim()
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Visit request rejected.');
                        await loadRealData();
                        loadVisitRequests();
                        visitModal.classList.add('hidden');
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error rejecting visit:', error);
                    alert('Failed to reject visit request. Please try again.');
                }
            }
        });

        // Hall Management
        const hallsGrid = document.getElementById('halls-grid');
        const hallEditModal = document.getElementById('hall-edit-modal');
        const saveHallBtn = document.getElementById('save-hall-btn');
        const cancelHallBtn = document.getElementById('cancel-hall-btn');

        function loadHalls() {
            hallsGrid.innerHTML = state.halls.map(hall => {
                const colorMap = {
                    'Jubilee Ballroom': { from: 'blue', to: 'purple' },
                    'Grand Ballroom': { from: 'purple', to: 'pink' },
                    'Garden Pavilion': { from: 'green', to: 'blue' },
                    'Royal Heritage Hall': { from: 'yellow', to: 'orange' },
                    'Riverside Garden': { from: 'teal', to: 'cyan' }
                };
                const colors = colorMap[hall.name] || { from: 'gray', to: 'slate' };
                
                return `
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-r from-${colors.from}-400 to-${colors.to}-500 flex items-center justify-center relative">
                        <span class="text-white text-lg font-semibold text-center px-4">${hall.name}</span>
                        ${hall.price ? `<div class="absolute top-2 right-2 bg-white bg-opacity-20 text-white text-xs px-2 py-1 rounded">Rs. ${hall.price.toLocaleString()}</div>` : ''}
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">${hall.name}</h3>
                        <p class="text-gray-600 mb-4 text-sm">${hall.description || 'Premium wedding venue with elegant decor'}</p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Capacity: ${hall.capacity} guests</span>
                                <span class="text-${hall.availability === 'available' ? 'green' : 'red'}-600 font-medium">${hall.availability.charAt(0).toUpperCase() + hall.availability.slice(1)}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Active Bookings:</span>
                                <span class="font-medium">${hall.bookings || 0}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Pending Visits:</span>
                                <span class="font-medium">${hall.pendingVisits || 0}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Status:</span>
                                <span class="px-2 py-1 rounded-full text-xs ${hall.bookings > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                                    ${hall.bookings > 0 ? 'In Use' : 'Available'}
                                </span>
                            </div>
                            ${hall.features && hall.features.length > 0 ? `
                            <div class="mt-2">
                                <div class="text-xs text-gray-500 mb-1">Features:</div>
                                <div class="flex flex-wrap gap-1">
                                    ${hall.features.slice(0, 2).map(feature => `<span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">${feature}</span>`).join('')}
                                    ${hall.features.length > 2 ? `<span class="text-xs text-gray-400">+${hall.features.length - 2} more</span>` : ''}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        <div class="flex space-x-2">
                            <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="showHallEditModal(${hall.id})">
                                Edit Details
                            </button>
                            <button class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors" onclick="viewHallBookings(${hall.id}, '${hall.name}')">
                                View Bookings
                            </button>
                        </div>
                    </div>
                </div>
            `;}).join('');
            updateDashboardStats({});
        }

        // Add function to view hall bookings
        window.viewHallBookings = function(hallId, hallName) {
            const hallBookings = state.visits.filter(v => v.hall === hallName);
            const hallWeddings = state.weddingRequests.filter(w => w.hall === hallName);
            
            let content = `<h4 class="font-bold mb-3">Bookings for ${hallName}</h4>`;
            
            if (hallBookings.length > 0) {
                content += `<div class="mb-4"><h5 class="font-semibold text-sm text-gray-700 mb-2">Visit Requests:</h5>`;
                hallBookings.forEach(booking => {
                    content += `<div class="text-sm p-2 bg-gray-50 rounded mb-1">
                        <strong>${booking.couple}</strong> - ${booking.date} at ${booking.time}
                        <span class="ml-2 px-2 py-1 rounded text-xs ${booking.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">${booking.status}</span>
                    </div>`;
                });
                content += `</div>`;
            }
            
            if (hallWeddings.length > 0) {
                content += `<div class="mb-4"><h5 class="font-semibold text-sm text-gray-700 mb-2">Wedding Requests:</h5>`;
                hallWeddings.forEach(wedding => {
                    content += `<div class="text-sm p-2 bg-gray-50 rounded mb-1">
                        <strong>${wedding.couple}</strong> - ${wedding.weddingDate}
                        <span class="ml-2 px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">${wedding.status}</span>
                    </div>`;
                });
                content += `</div>`;
            }
            
            if (hallBookings.length === 0 && hallWeddings.length === 0) {
                content += `<p class="text-gray-500 text-sm">No bookings for this hall.</p>`;
            }
            
            alert(content.replace(/<[^>]*>/g, '\n')); // Simple alert for now - in real app, use a modal
        };

        window.showHallEditModal = function(hallId) {
            const hall = state.halls.find(h => h.id === hallId);
            document.getElementById('edit-hall-name').value = hall.name;
            document.getElementById('edit-hall-capacity').value = hall.capacity;
            document.getElementById('edit-hall-availability').value = hall.availability;
            document.getElementById('edit-modal-title').dataset.hallId = hallId;
            hallEditModal.classList.remove('hidden');
        };

        saveHallBtn.addEventListener('click', async () => {
            const hallId = document.getElementById('edit-modal-title').dataset.hallId;
            const name = document.getElementById('edit-hall-name').value.trim();
            const capacity = parseInt(document.getElementById('edit-hall-capacity').value);
            const availability = document.getElementById('edit-hall-availability').value;

            if (!name || isNaN(capacity) || capacity < 50 || capacity > 500) {
                alert('Please enter a valid hall name and capacity (50-500).');
                return;
            }

            try {
                // For now, just update local state since we don't have a hall update API
                state.halls = state.halls.map(h =>
                    h.id === hallId ? { ...h, name, capacity, availability } : h
                );
                loadHalls();
                hallEditModal.classList.add('hidden');
                alert('Hall details updated successfully!');
            } catch (error) {
                console.error('Error updating hall:', error);
                alert('Failed to update hall details. Please try again.');
            }
        });

        cancelHallBtn.addEventListener('click', () => hallEditModal.classList.add('hidden'));

        // Reports Functionality
        window.generateReport = function(category, type) {
            const reportDisplay = document.getElementById('report-display');
            reportDisplay.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-600">Generating report...</p></div>';
            
            setTimeout(() => {
                let reportContent = '';
                
                switch (category) {
                    case 'revenue':
                        reportContent = generateRevenueReport(type);
                        break;
                    case 'booking':
                        reportContent = generateBookingReport(type);
                        break;
                    case 'customer':
                        reportContent = generateCustomerReport(type);
                        break;
                }
                
                reportDisplay.innerHTML = reportContent;
            }, 1500);
        };

        function generateRevenueReport(type) {
            const totalRevenue = state.weddingRequests.reduce((sum, req) => sum + (req.advanceAmount || 0), 0);
            const avgBookingValue = totalRevenue / Math.max(state.weddingRequests.length, 1);
            
            switch (type) {
                case 'monthly':
                    return `
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900">Monthly Revenue Trends</h3>
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="exportReport('revenue-monthly')">Export PDF</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-green-800">Total Revenue</h4>
                                    <p class="text-2xl font-bold text-green-600">Rs. ${totalRevenue.toLocaleString()}</p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-blue-800">Average Booking Value</h4>
                                    <p class="text-2xl font-bold text-blue-600">Rs. ${Math.round(avgBookingValue).toLocaleString()}</p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-purple-800">Total Bookings</h4>
                                    <p class="text-2xl font-bold text-purple-600">${state.weddingRequests.length}</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2">Revenue by Hall</h4>
                                ${generateHallRevenueChart()}
                            </div>
                        </div>
                    `;
                case 'package':
                    return `
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-900">Package Popularity Analysis</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-600">Package analysis based on current bookings and revenue data.</p>
                                <div class="mt-4 space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span>Premium Packages</span>
                                        <span class="font-semibold">${Math.round(state.weddingRequests.length * 0.6)} bookings</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span>Standard Packages</span>
                                        <span class="font-semibold">${Math.round(state.weddingRequests.length * 0.4)} bookings</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                default:
                    return '<p class="text-gray-500">Report type not implemented yet.</p>';
            }
        }

        function generateBookingReport(type) {
            switch (type) {
                case 'peak-seasons':
                    return `
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-900">Peak Seasons Analysis</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-yellow-800">Peak Season</h4>
                                    <p class="text-lg font-bold text-yellow-600">December - February</p>
                                    <p class="text-sm text-yellow-600">Wedding season in Sri Lanka</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-green-800">Off Season</h4>
                                    <p class="text-lg font-bold text-green-600">June - August</p>
                                    <p class="text-sm text-green-600">Monsoon period</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2">Booking Distribution</h4>
                                <p class="text-gray-600">Current bookings: ${state.weddingRequests.length} confirmed weddings</p>
                            </div>
                        </div>
                    `;
                case 'hall-utilization':
                    return `
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-900">Hall Utilization Report</h3>
                            <div class="space-y-4">
                                ${state.halls.map(hall => `
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <h4 class="font-semibold">${hall.name}</h4>
                                            <span class="text-sm text-gray-600">Capacity: ${hall.capacity} guests</span>
                                        </div>
                                        <div class="mt-2">
                                            <div class="flex justify-between text-sm">
                                                <span>Active Bookings: ${hall.bookings || 0}</span>
                                                <span>Utilization: ${Math.round((hall.bookings || 0) / 10 * 100)}%</span>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                default:
                    return '<p class="text-gray-500">Report type not implemented yet.</p>';
            }
        }

        function generateCustomerReport(type) {
            switch (type) {
                case 'demographics':
                    return `
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-900">Customer Demographics</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-blue-800">Total Customers</h4>
                                    <p class="text-2xl font-bold text-blue-600">${state.visits.length}</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-green-800">Conversion Rate</h4>
                                    <p class="text-2xl font-bold text-green-600">${Math.round((state.weddingRequests.length / Math.max(state.visits.length, 1)) * 100)}%</p>
                                </div>
                            </div>
                        </div>
                    `;
                case 'satisfaction':
                    return `
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-900">Visit Conversion Rate</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span>Total Visits:</span>
                                        <span class="font-semibold">${state.visits.length}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Confirmed Bookings:</span>
                                        <span class="font-semibold">${state.weddingRequests.length}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Conversion Rate:</span>
                                        <span class="font-semibold text-green-600">${Math.round((state.weddingRequests.length / Math.max(state.visits.length, 1)) * 100)}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                default:
                    return '<p class="text-gray-500">Report type not implemented yet.</p>';
            }
        }

        function generateHallRevenueChart() {
            const hallRevenue = {};
            state.weddingRequests.forEach(req => {
                if (!hallRevenue[req.hall]) {
                    hallRevenue[req.hall] = 0;
                }
                hallRevenue[req.hall] += req.advanceAmount || 0;
            });

            return Object.entries(hallRevenue).map(([hall, revenue]) => `
                <div class="flex justify-between items-center py-2">
                    <span>${hall}</span>
                    <span class="font-semibold">Rs. ${revenue.toLocaleString()}</span>
                </div>
            `).join('');
        }

        window.exportReport = function(reportType, format = 'pdf') {
            if (reportType === 'bookings' && format === 'csv') {
                // Use the existing export functionality
                window.open('{{ route("manager.export.bookings") }}', '_blank');
                return;
            }
            
            alert(`Exporting ${reportType} report in ${format.toUpperCase()} format... (Feature will be implemented with PDF generation)`);
        };

        // Enhanced Profile Management Functions
        window.setupProfileManagement = function() {
            // Profile photo preview
            document.getElementById('profile-photo').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('profile-preview').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Profile form submission
            document.getElementById('profile-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("manager.profile.update.manager") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Profile updated successfully!');
                        // Update the header profile image if it was changed
                        if (formData.has('profile_photo') && formData.get('profile_photo').size > 0) {
                            location.reload(); // Reload to show new profile image in header
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error updating profile:', error);
                    alert('Failed to update profile. Please try again.');
                }
            });

            // Password form submission
            document.getElementById('password-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("password.update") }}', {
                        method: 'PUT',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        alert('Password changed successfully!');
                        this.reset();
                    } else {
                        const data = await response.json();
                        alert('Error: ' + (data.message || 'Failed to change password'));
                    }
                } catch (error) {
                    console.error('Error changing password:', error);
                    alert('Failed to change password. Please try again.');
                }
            });
        };

        // Settings Management Functions
        window.setupSettingsManagement = function() {
            // Business form submission
            document.getElementById('business-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Business information updated successfully!');
                // In a real implementation, this would save to database
            });

            // Hours form submission
            document.getElementById('hours-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Working hours updated successfully!');
                // In a real implementation, this would save to database
            });

            // Notifications form submission
            document.getElementById('notifications-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Notification preferences saved successfully!');
                // In a real implementation, this would save to database
            });
        };

        // Enhanced Booking Functions
        window.generateContract = function(bookingId) {
            alert(`Generating contract for booking ${bookingId}... (Feature will be implemented with PDF generation)`);
            // In a real implementation, this would generate a contract PDF
        };

        window.trackPayment = function(bookingId) {
            // Show payment tracking modal or redirect to payment tracking page
            alert(`Opening payment tracking for booking ${bookingId}...`);
            // In a real implementation, this would show detailed payment history
        };

        // Report Filter Functions
        window.applyReportFilters = function() {
            const dateRange = document.getElementById('report-date-range').value;
            const hallFilter = document.getElementById('report-hall-filter').value;
            const packageFilter = document.getElementById('report-package-filter').value;
            
            alert(`Applying filters: Date Range: ${dateRange}, Hall: ${hallFilter}, Package: ${packageFilter}`);
            // In a real implementation, this would filter the reports
        };

        // Enhanced Analytics Functions
        window.updateQuickStats = function() {
            // Calculate and update quick statistics
            const monthlyRevenue = state.weddingRequests.reduce((sum, req) => sum + (req.advanceAmount || 0), 0);
            const conversionRate = state.visits.length > 0 ? Math.round((state.weddingRequests.length / state.visits.length) * 100) : 0;
            const avgBooking = state.weddingRequests.length > 0 ? Math.round(monthlyRevenue / state.weddingRequests.length) : 0;
            const peakBookings = state.weddingRequests.filter(req => {
                const month = new Date(req.weddingDate).getMonth();
                return month >= 11 || month <= 1; // Dec, Jan, Feb (peak season)
            }).length;

            document.getElementById('quick-monthly-revenue').textContent = `Rs. ${monthlyRevenue.toLocaleString()}`;
            document.getElementById('quick-conversion-rate').textContent = `${conversionRate}%`;
            document.getElementById('quick-avg-booking').textContent = `Rs. ${avgBooking.toLocaleString()}`;
            document.getElementById('quick-peak-bookings').textContent = peakBookings;
        };

        // Load Section Content
        function loadSectionContent(section) {
            switch (section) {
                case 'halls': loadHalls(); break;
                case 'visits': loadVisitRequests(); break;
                case 'wedding-requests': 
                    if (state.weddingRequests.length === 0) {
                        loadRealData().then(() => loadWeddingRequests());
                    } else {
                        loadWeddingRequests();
                    }
                    break;
                case 'bookings': loadBookings(); break;
                case 'calendar': updateCalendar(); break;
                case 'reports': updateQuickStats(); break; // Update quick stats when reports section is loaded
                case 'profile': setupProfileManagement(); break;
                case 'settings': setupSettingsManagement(); break;
            }
        }

        // Initial Load - Load real data first, then show dashboard
        showSection('dashboard');
        
        // Load real data and update dashboard
        loadRealData().then(() => {
            updateRecentActivities();
            updateQuickStats();
        });
    });

    // Manager action functions
    function approveVisit(visitId) {
        if (!confirm('Are you sure you want to approve this visit request?')) {
            return;
        }

        fetch(`/manager/visit/${visitId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notes: 'Approved by manager'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Visit approved successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while approving the visit.');
        });
    }

    function rejectVisit(visitId) {
        const reason = prompt('Please provide a reason for rejection:');
        if (!reason) {
            return;
        }

        fetch(`/manager/visit/${visitId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Visit rejected successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while rejecting the visit.');
        });
    }

    function markPaymentPaid(bookingId) {
        const paymentMethod = prompt('Enter payment method (cash, card, bank transfer, etc.):');
        if (!paymentMethod) {
            return;
        }

        fetch(`/manager/booking/${bookingId}/deposit-paid`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                payment_method: paymentMethod,
                notes: 'Payment confirmed by manager'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment marked as paid successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while marking payment as paid.');
        });
    }

    function viewBookingDetails(bookingId) {
        // For now, just show an alert. In a full implementation, you'd open a modal with booking details
        alert(`Viewing details for booking ID: ${bookingId}`);
    }

    function viewHallDetails(hallId) {
        // For now, just show an alert. In a full implementation, you'd open a modal with hall details
        alert(`Viewing details for hall ID: ${hallId}`);
    }

    function editHall(hallId) {
        // For now, just show an alert. In a full implementation, you'd open an edit modal
        alert(`Editing hall ID: ${hallId}`);
    }

    // Auto-refresh dashboard data every 30 seconds
    setInterval(function() {
        // Update notification count
        fetch('/manager/notifications/count')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.count > 0) {
                    document.getElementById('notification-badge').classList.remove('hidden');
                } else {
                    document.getElementById('notification-badge').classList.add('hidden');
                }
            })
            .catch(error => console.log('Error updating notifications:', error));
    }, 30000);

    // Package Management JavaScript Functions
    let currentPackages = [];
    let currentView = 'grid';

    // Load packages when packages section is accessed
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handler for packages navigation
        const packagesNavItem = document.querySelector('a[href="#packages"]');
        if (packagesNavItem) {
            packagesNavItem.addEventListener('click', function() {
                loadPackages();
            });
        }
    });

    // Load packages from API
    async function loadPackages() {
        try {
            showLoading('packages-grid-view');
            
            const response = await fetch('/admin/api/admin/packages', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                currentPackages = data.packages;
                updatePackageStats(data.stats);
                renderPackages();
                console.log('✅ Packages loaded successfully:', data.packages.length, 'packages');
            } else {
                console.error('❌ API returned error:', data.message || 'Unknown error');
                showError('Failed to load packages: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('❌ Error loading packages:', error);
            showError('Failed to load packages. Please check your connection.');
        }
    }

    // Update package statistics
    function updatePackageStats(stats) {
        document.getElementById('total-packages').textContent = stats.total || 0;
        document.getElementById('active-packages').textContent = stats.active || 0;
        document.getElementById('featured-packages').textContent = currentPackages.filter(p => p.highlight).length || 0;
        
        const avgPrice = stats.total > 0 ? Math.round(currentPackages.reduce((sum, p) => sum + p.price, 0) / stats.total) : 0;
        document.getElementById('avg-package-price').textContent = 'Rs. ' + avgPrice.toLocaleString();
    }

    // Render packages in current view
    function renderPackages() {
        if (currentView === 'grid') {
            renderPackagesGrid();
        } else {
            renderPackagesList();
        }
    }

    // Render packages grid view
    function renderPackagesGrid() {
        const gridView = document.getElementById('packages-grid-view');
        if (!gridView) return;

        if (currentPackages.length === 0) {
            gridView.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No packages found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first wedding package.</p>
                    <button onclick="openPackageModal()" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Add New Package
                    </button>
                </div>
            `;
            return;
        }

        gridView.innerHTML = currentPackages.map(pkg => renderPackageCard(pkg)).join('');
    }

    // Render packages list view
    function renderPackagesList() {
        const listBody = document.getElementById('packages-list-body');
        if (!listBody) return;

        if (currentPackages.length === 0) {
            listBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900">No packages found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first wedding package.</p>
                            <button onclick="openPackageModal()" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Add New Package
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        listBody.innerHTML = currentPackages.map(pkg => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <img class="h-10 w-10 rounded-lg object-cover" src="${pkg.image || '/images/default-package.jpg'}" alt="${pkg.name}">
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${pkg.name}</div>
                            <div class="text-sm text-gray-500">${pkg.description.substring(0, 50)}...</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">Rs. ${pkg.price.toLocaleString()}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${pkg.is_active ? 'green' : 'red'}-100 text-${pkg.is_active ? 'green' : 'red'}-800">
                        ${pkg.is_active ? 'Active' : 'Inactive'}
                    </span>
                    ${pkg.highlight ? '<span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>' : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${pkg.bookings_count || 0}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Rs. ${(pkg.total_revenue || 0).toLocaleString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="viewPackageDetails(${pkg.id})" class="text-gray-600 hover:text-gray-900 mr-3">View</button>
                    <button onclick="editPackage(${pkg.id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                    <button onclick="togglePackageStatus(${pkg.id})" class="text-${pkg.is_active ? 'yellow' : 'green'}-600 hover:text-${pkg.is_active ? 'yellow' : 'green'}-900 mr-3">
                        ${pkg.is_active ? 'Deactivate' : 'Activate'}
                    </button>
                    <button onclick="deletePackage(${pkg.id})" class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    // Toggle between grid and list view
    function togglePackageView(view) {
        currentView = view;
        const gridView = document.getElementById('packages-grid-view');
        const listView = document.getElementById('packages-list-view');
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
        
        renderPackages();
    }

    // Open package modal for add/edit
    function openPackageModal(packageId = null) {
        const modal = document.getElementById('package-modal');
        const title = document.getElementById('package-modal-title');
        const submitText = document.getElementById('package-submit-text');
        
        if (packageId) {
            title.textContent = 'Edit Package';
            submitText.textContent = 'Update Package';
            loadPackageForEdit(packageId);
        } else {
            title.textContent = 'Add New Package';
            submitText.textContent = 'Save Package';
            resetPackageForm();
        }
        
        modal.classList.remove('hidden');
    }

    // Close package modal
    function closePackageModal() {
        document.getElementById('package-modal').classList.add('hidden');
        resetPackageForm();
    }

    // Reset package form
    function resetPackageForm() {
        document.getElementById('package-form').reset();
        document.getElementById('package-id').value = '';
        document.getElementById('current-image').classList.add('hidden');
        
        // Reset features to one empty field
        const featuresContainer = document.getElementById('features-container');
        featuresContainer.innerHTML = `
            <div class="flex items-center space-x-2">
                <input type="text" name="features[]" placeholder="Enter feature" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
    }

    // Load package data for editing
    async function loadPackageForEdit(packageId) {
        try {
            const response = await fetch(`/admin/api/admin/packages/${packageId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                const pkg = data.package;
                
                document.getElementById('package-id').value = pkg.id;
                document.getElementById('package-name').value = pkg.name;
                document.getElementById('package-price').value = pkg.price;
                document.getElementById('package-description').value = pkg.description;
                document.getElementById('package-highlight').checked = pkg.highlight;
                document.getElementById('package-active').checked = pkg.is_active;
                
                // Show current image if exists
                if (pkg.image) {
                    document.getElementById('current-image').classList.remove('hidden');
                    document.getElementById('current-image-preview').src = pkg.image;
                }
                
                // Load features
                loadPackageFeatures(pkg.features || []);
            } else {
                showError('Failed to load package data');
            }
        } catch (error) {
            console.error('Error loading package for edit:', error);
            showError('Failed to load package data');
        }
    }

    // Load package features into form
    function loadPackageFeatures(features) {
        const featuresContainer = document.getElementById('features-container');
        featuresContainer.innerHTML = '';
        
        if (features.length === 0) {
            features = [''];
        }
        
        features.forEach(feature => {
            const featureDiv = document.createElement('div');
            featureDiv.className = 'flex items-center space-x-2';
            featureDiv.innerHTML = `
                <input type="text" name="features[]" value="${feature}" placeholder="Enter feature" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            featuresContainer.appendChild(featureDiv);
        });
    }

    // Add feature input
    function addFeature() {
        const featuresContainer = document.getElementById('features-container');
        const featureDiv = document.createElement('div');
        featureDiv.className = 'flex items-center space-x-2 feature-row';
        featureDiv.innerHTML = `
            <input type="text" name="features[]" placeholder="Enter feature (e.g., Premium floral arrangements)" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50 transition-colors" title="Remove feature">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        `;
        featuresContainer.appendChild(featureDiv);
        
        // Focus on the new input
        const newInput = featureDiv.querySelector('input');
        newInput.focus();
    }

    // Remove feature input
    function removeFeature(button) {
        const featuresContainer = document.getElementById('features-container');
        if (featuresContainer.children.length > 1) {
            button.parentElement.remove();
        } else {
            // If it's the last feature, just clear the input
            const input = button.parentElement.querySelector('input');
            input.value = '';
            input.focus();
        }
    }

    // Initialize package management when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize search and filter event listeners
        const packageSearch = document.getElementById('package-search');
        const packageStatusFilter = document.getElementById('package-status-filter');
        const packagePriceFilter = document.getElementById('package-price-filter');
        
        if (packageSearch) {
            packageSearch.addEventListener('input', debounce(filterPackages, 300));
        }
        
        if (packageStatusFilter) {
            packageStatusFilter.addEventListener('change', filterPackages);
        }
        
        if (packagePriceFilter) {
            packagePriceFilter.addEventListener('change', filterPackages);
        }
        
        // Initialize tooltips for buttons
        initializePackageTooltips();
    });

    // Initialize tooltips for package buttons
    function initializePackageTooltips() {
        // Add tooltips to package action buttons
        document.addEventListener('mouseover', function(e) {
            if (e.target.matches('[onclick*="viewPackageDetails"]')) {
                e.target.title = 'View detailed package information';
            } else if (e.target.matches('[onclick*="editPackage"]')) {
                e.target.title = 'Edit package details';
            } else if (e.target.matches('[onclick*="togglePackageStatus"]')) {
                e.target.title = 'Toggle package active/inactive status';
            } else if (e.target.matches('[onclick*="deletePackage"]')) {
                e.target.title = 'Delete package permanently';
            }
        });
    }

    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Enhanced package card rendering with better error handling
    function renderPackageCard(pkg) {
        const statusColor = pkg.is_active ? 'green' : 'red';
        const statusText = pkg.is_active ? 'Active' : 'Inactive';
        const toggleAction = pkg.is_active ? 'Deactivate' : 'Activate';
        const toggleColor = pkg.is_active ? 'yellow' : 'green';
        
        return `
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 package-card" data-package-id="${pkg.id}">
                <div class="relative h-48">
                    <img src="${pkg.image || '/images/default-package.jpg'}"
                    alt="${pkg.name}"
                         class="w-full h-full object-cover"
                         onerror="this.src='/images/default-package.jpg'">
                    <div class="absolute top-4 right-4">
                        ${pkg.highlight ? '<span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">Featured</span>' : ''}
                    </div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-${statusColor}-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                            ${statusText}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 truncate">${pkg.name}</h3>
                        <span class="text-lg font-bold text-blue-600">Rs. ${pkg.price.toLocaleString()}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">${pkg.description.substring(0, 100)}${pkg.description.length > 100 ? '...' : ''}</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                        <div class="text-center p-2 bg-gray-50 rounded">
                            <div class="text-xs text-gray-500">Bookings</div>
                            <div class="font-semibold text-blue-600">${pkg.bookings_count || 0}</div>
                        </div>
                        <div class="text-center p-2 bg-gray-50 rounded">
                            <div class="text-xs text-gray-500">Revenue</div>
                            <div class="font-semibold text-green-600">Rs. ${(pkg.total_revenue || 0).toLocaleString()}</div>
                        </div>
                    </div>

                    <div class="flex space-x-1">
                        <button onclick="viewPackageDetails(${pkg.id})" class="flex-1 bg-gray-600 text-white py-2 px-2 rounded-lg hover:bg-gray-700 transition-colors text-xs font-medium">
                            View
                        </button>
                        <button onclick="editPackage(${pkg.id})" class="flex-1 bg-blue-600 text-white py-2 px-2 rounded-lg hover:bg-blue-700 transition-colors text-xs font-medium">
                            Edit
                        </button>
                        <button onclick="togglePackageStatus(${pkg.id})" class="flex-1 bg-${toggleColor}-600 text-white py-2 px-2 rounded-lg hover:bg-${toggleColor}-700 transition-colors text-xs font-medium">
                            ${toggleAction}
                        </button>
                        <button onclick="deletePackage(${pkg.id})" class="bg-red-600 text-white py-2 px-2 rounded-lg hover:bg-red-700 transition-colors text-xs font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    // Handle package form submission
    document.addEventListener('DOMContentLoaded', function() {
        const packageForm = document.getElementById('package-form');
        if (packageForm) {
            packageForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Saving...';
                submitBtn.disabled = true;
                
                try {
                    // Validate form
                    if (!validatePackageForm()) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        return;
                    }
                    
                    const formData = new FormData(this);
                    const packageId = document.getElementById('package-id').value;
                    
                    // Collect features
                    const features = [];
                    document.querySelectorAll('input[name="features[]"]').forEach(input => {
                        if (input.value.trim()) {
                            features.push(input.value.trim());
                        }
                    });
                    
                    // Remove old features and add new ones
                    formData.delete('features[]');
                    features.forEach(feature => {
                        formData.append('features[]', feature);
                    });
                    
                    const url = packageId ? `/admin/packages/${packageId}` : '/admin/packages';
                    const method = packageId ? 'PUT' : 'POST';
                    
                    // For PUT requests, we need to add the method override
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }
                    
                    const response = await fetch(url, {
                        method: 'POST', // Always POST for FormData with file uploads
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        showSuccess(data.message || 'Package saved successfully');
                        closePackageModal();
                        loadPackages(); // Reload packages
                    } else {
                        showError(data.message || 'Failed to save package');
                        if (data.errors) {
                            displayFormErrors(data.errors);
                        }
                    }
                } catch (error) {
                    console.error('Error saving package:', error);
                    showError('Failed to save package. Please check your connection.');
                } finally {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        }
    });

    // Validate package form
    function validatePackageForm() {
        const name = document.getElementById('package-name').value.trim();
        const price = document.getElementById('package-price').value;
        const description = document.getElementById('package-description').value.trim();
        
        if (!name) {
            showError('Package name is required');
            document.getElementById('package-name').focus();
            return false;
        }
        
        if (!price || price <= 0) {
            showError('Valid package price is required');
            document.getElementById('package-price').focus();
            return false;
        }
        
        if (!description) {
            showError('Package description is required');
            document.getElementById('package-description').focus();
            return false;
        }
        
        return true;
    }

    // Display form validation errors
    function displayFormErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(`package-${field}`);
            if (input) {
                input.classList.add('border-red-500');
                // Remove error styling after user starts typing
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                }, { once: true });
            }
        });
    }

    // Edit package
    function editPackage(packageId) {
        openPackageModal(packageId);
    }

    // Toggle package status
    async function togglePackageStatus(packageId) {
        try {
            const response = await fetch(`/admin/packages/${packageId}/toggle-status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                showSuccess(data.message);
                loadPackages(); // Reload packages
            } else {
                showError(data.message || 'Failed to update package status');
            }
        } catch (error) {
            console.error('Error toggling package status:', error);
            showError('Failed to update package status');
        }
    }

    // Delete package
    async function deletePackage(packageId) {
        // Enhanced confirmation dialog
        const packageName = currentPackages.find(p => p.id == packageId)?.name || 'this package';
        
        if (!confirm(`Are you sure you want to delete "${packageName}"?\n\nThis action cannot be undone and will permanently remove the package and all its data.`)) {
            return false;
        }
        
        try {
            // Show loading state
            showSuccess('Deleting package...');
            
            const response = await fetch(`/admin/packages/${packageId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                showSuccess(data.message || 'Package deleted successfully');
                loadPackages(); // Reload packages
                return true;
            } else {
                showError(data.message || 'Failed to delete package');
                return false;
            }
        } catch (error) {
            console.error('Error deleting package:', error);
            showError('Failed to delete package. Please check your connection.');
            return false;
        }
    }

    // Refresh packages
    function refreshPackages() {
        loadPackages();
        showSuccess('Packages refreshed successfully');
    }

    // Export packages
    function exportPackages() {
        // Create a CSV export of current packages
        if (currentPackages.length === 0) {
            showError('No packages to export');
            return;
        }
        
        const csvContent = generatePackagesCSV(currentPackages);
        downloadCSV(csvContent, 'wedding-packages.csv');
        showSuccess('Packages exported successfully');
    }
    
    // Generate CSV content for packages
    function generatePackagesCSV(packages) {
        const headers = ['ID', 'Name', 'Description', 'Price', 'Status', 'Featured', 'Bookings', 'Revenue', 'Created Date'];
        const rows = packages.map(pkg => [
            pkg.id,
            `"${pkg.name}"`,
            `"${pkg.description.replace(/"/g, '""')}"`,
            pkg.price,
            pkg.is_active ? 'Active' : 'Inactive',
            pkg.highlight ? 'Yes' : 'No',
            pkg.bookings_count || 0,
            pkg.total_revenue || 0,
            new Date(pkg.created_at).toLocaleDateString()
        ]);
        
        return [headers, ...rows].map(row => row.join(',')).join('\n');
    }
    
    // Download CSV file
    function downloadCSV(content, filename) {
        const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    // Show loading state
    function showLoading(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">Loading packages...</p>
                </div>
            `;
        }
    }

    // Show success message
    function showSuccess(message) {
        createToast(message, 'success');
    }

    // Show error message
    function showError(message) {
        createToast(message, 'error');
    }

    // Show info message
    function showInfo(message) {
        createToast(message, 'info');
    }

    // Create toast notification
    function createToast(message, type = 'info') {
        const toast = document.createElement('div');
        const bgColor = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        }[type] || 'bg-blue-500';

        const icon = {
            success: '<i class="ri-check-line mr-2"></i>',
            error: '<i class="ri-error-warning-line mr-2"></i>',
            info: '<i class="ri-information-line mr-2"></i>',
            warning: '<i class="ri-alert-line mr-2"></i>'
        }[type] || '<i class="ri-information-line mr-2"></i>';

        toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 toast flex items-center max-w-md`;
        toast.innerHTML = `
            ${icon}
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <i class="ri-close-line"></i>
            </button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after delay
        const delay = type === 'error' ? 5000 : 3000;
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }
        }, delay);
    }

    // Add slideOutRight animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Search and filter functionality
    document.getElementById('package-search').addEventListener('input', function() {
        filterPackages();
    });

    document.getElementById('package-status-filter').addEventListener('change', function() {
        filterPackages();
    });

    document.getElementById('package-price-filter').addEventListener('change', function() {
        filterPackages();
    });

    function filterPackages() {
        const searchTerm = document.getElementById('package-search').value.toLowerCase();
        const statusFilter = document.getElementById('package-status-filter').value;
        const priceFilter = document.getElementById('package-price-filter').value;

        let filteredPackages = currentPackages.filter(pkg => {
            const matchesSearch = pkg.name.toLowerCase().includes(searchTerm) || 
                                pkg.description.toLowerCase().includes(searchTerm);
            
            const matchesStatus = !statusFilter || 
                                (statusFilter === 'active' && pkg.is_active) ||
                                (statusFilter === 'inactive' && !pkg.is_active);
            
            let matchesPrice = true;
            if (priceFilter) {
                if (priceFilter === '0-100000') {
                    matchesPrice = pkg.price >= 0 && pkg.price <= 100000;
                } else if (priceFilter === '100000-200000') {
                    matchesPrice = pkg.price > 100000 && pkg.price <= 200000;
                } else if (priceFilter === '200000-300000') {
                    matchesPrice = pkg.price > 200000 && pkg.price <= 300000;
                } else if (priceFilter === '300000+') {
                    matchesPrice = pkg.price > 300000;
                }
            }
            
            return matchesSearch && matchesStatus && matchesPrice;
        });

        // Temporarily replace currentPackages for rendering
        const originalPackages = currentPackages;
        currentPackages = filteredPackages;
        renderPackages();
        currentPackages = originalPackages;
    }

    // Apply package filters function
    function applyPackageFilters() {
        filterPackages();
        showSuccess('Filters applied successfully');
    }

    // Clear package filters function
    function clearPackageFilters() {
        // Reset all filter inputs
        document.getElementById('package-search').value = '';
        document.getElementById('package-status-filter').value = '';
        document.getElementById('package-price-filter').value = '';
        
        // Apply the cleared filters
        filterPackages();
        showSuccess('Filters cleared successfully');
    }

    // Package Details Modal Functions
    let currentPackageDetails = null;

    // View package details
    async function viewPackageDetails(packageId) {
        try {
            const response = await fetch(`/admin/api/admin/packages/${packageId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                currentPackageDetails = data.package;
                showPackageDetailsModal(data.package);
            } else {
                showError('Failed to load package details');
            }
        } catch (error) {
            console.error('Error loading package details:', error);
            showError('Failed to load package details');
        }
    }

    // Show package details modal
    function showPackageDetailsModal(packageData) {
        const modal = document.getElementById('package-details-modal');
        const title = document.getElementById('package-details-title');
        const content = document.getElementById('package-details-content');
        
        title.textContent = `${packageData.name} - Details`;
        
        // Create detailed content
        content.innerHTML = `
            <!-- Left Column - Package Information -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Package Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Package Name</label>
                            <p class="text-lg font-semibold text-gray-900">${packageData.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Price</label>
                            <p class="text-2xl font-bold text-blue-600">Rs. ${packageData.price.toLocaleString()}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${packageData.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${packageData.is_active ? 'Active' : 'Inactive'}
                            </span>
                            ${packageData.highlight ? '<span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Featured</span>' : ''}
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Description</label>
                            <p class="text-gray-900 mt-1">${packageData.description}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Statistics</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">${packageData.bookings_count || 0}</div>
                            <div class="text-sm text-gray-600">Total Bookings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">Rs. ${(packageData.total_revenue || 0).toLocaleString()}</div>
                            <div class="text-sm text-gray-600">Total Revenue</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Timestamps</h4>
                    <div class="space-y-2 text-sm">
                        <div>
                            <span class="font-medium text-gray-600">Created:</span>
                            <span class="text-gray-900">${new Date(packageData.created_at).toLocaleDateString()}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Last Updated:</span>
                            <span class="text-gray-900">${new Date(packageData.updated_at).toLocaleDateString()}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Package Image and Features -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Package Image</h4>
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                        <img src="${packageData.image || '/images/default-package.jpg'}"
                        alt="${packageData.name}"
                             class="w-full h-64 object-cover rounded-lg">
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Package Features</h4>
                    ${packageData.features && packageData.features.length > 0 ? `
                        <ul class="space-y-2">
                            ${packageData.features.map(feature => `
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-900">${feature}</span>
                                </li>
                            `).join('')}
                        </ul>
                    ` : '<p class="text-gray-500">No features listed for this package.</p>'}
                </div>

                ${packageData.recent_bookings && packageData.recent_bookings.length > 0 ? `
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Bookings</h4>
                        <div class="space-y-3">
                            ${packageData.recent_bookings.slice(0, 5).map(booking => `
                                <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-900">${booking.customer_name || 'Unknown'}</div>
                                        <div class="text-sm text-gray-600">${booking.event_date || 'Date TBD'}</div>
                                    </div>
                                    <span class="text-sm font-medium text-green-600">Rs. ${(booking.amount || 0).toLocaleString()}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
        
        // Update action buttons
        updateDetailsModalButtons(packageData);
        
        modal.classList.remove('hidden');
    }

    // Update details modal buttons
    function updateDetailsModalButtons(packageData) {
        const toggleBtn = document.getElementById('toggle-status-from-details-btn');
        const editBtn = document.getElementById('edit-from-details-btn');
        const deleteBtn = document.getElementById('delete-from-details-btn');
        
        if (toggleBtn) {
            toggleBtn.textContent = packageData.is_active ? 'Deactivate' : 'Activate';
            toggleBtn.className = `px-4 py-2 bg-${packageData.is_active ? 'yellow' : 'green'}-600 text-white rounded-md hover:bg-${packageData.is_active ? 'yellow' : 'green'}-700 transition-colors`;
        }
        
        // Store package ID for actions
        if (editBtn) editBtn.setAttribute('data-package-id', packageData.id);
        if (toggleBtn) toggleBtn.setAttribute('data-package-id', packageData.id);
        if (deleteBtn) deleteBtn.setAttribute('data-package-id', packageData.id);
    }

    // Close package details modal
    function closePackageDetailsModal() {
        document.getElementById('package-details-modal').classList.add('hidden');
        currentPackageDetails = null;
    }

    // Edit package from details modal
    function editPackageFromDetails() {
        if (currentPackageDetails) {
            closePackageDetailsModal();
            openPackageModal(currentPackageDetails.id);
        }
    }

    // Toggle package status from details modal
    async function togglePackageStatusFromDetails() {
        if (currentPackageDetails) {
            await togglePackageStatus(currentPackageDetails.id);
            // Refresh the details modal
            setTimeout(() => {
                viewPackageDetails(currentPackageDetails.id);
            }, 1000);
        }
    }

    // Delete package from details modal
    async function deletePackageFromDetails() {
        if (currentPackageDetails) {
            const confirmed = await deletePackage(currentPackageDetails.id);
            if (confirmed !== false) {
                closePackageDetailsModal();
            }
        }
    }
</script>

<!-- Call Confirmation Modal -->
    <div id="call-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 text-center">Call Customer for Visit Confirmation</h3>
                <div class="mt-4 px-7 py-3">
                    <div id="customer-info" class="mb-4 p-3 bg-gray-50 rounded">
                        <div class="text-sm">
                            <strong>Customer:</strong> <span id="customer-name"></span><br>
                            <strong>Phone:</strong> <span id="customer-phone"></span><br>
                            <strong>Visit Date:</strong> <span id="visit-date"></span><br>
                            <strong>Hall:</strong> <span id="hall-name"></span>
                        </div>
                    </div>
                    
                    <form id="call-form">
                        <input type="hidden" id="booking-id" name="booking_id">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Call Status</label>
                            <select name="call_status" id="call-status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select call result...</option>
                                <option value="successful">✓ Call Successful</option>
                                <option value="no_answer">📵 No Answer</option>
                                <option value="busy">📞 Line Busy</option>
                                <option value="invalid_number">❌ Invalid Number</option>
                            </select>
                        </div>
                        
                        <div id="successful-call-options" class="hidden">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Visit Confirmation</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="visit_confirmed" value="1" class="mr-2">
                                        <span class="text-green-600">✓ Customer confirmed visit</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="visit_confirmed" value="0" class="mr-2">
                                        <span class="text-red-600">✗ Customer declined visit</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div id="visit-reschedule" class="hidden">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Visit Date (if changed)</label>
                                    <input type="date" name="new_visit_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Visit Time (if changed)</label>
                                    <input type="time" name="new_visit_time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Call Notes</label>
                            <textarea name="call_notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Notes about the call..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Manager Notes</label>
                            <textarea name="manager_notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Additional notes for records..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="flex items-center px-4 py-3">
                    <button id="save-call-btn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 mr-2">
                        Record Call
                    </button>
                    <button id="schedule-callback-btn" class="px-4 py-2 bg-yellow-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300 mr-2">
                        Schedule Callback
                    </button>
                    <button id="cancel-call-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Call History Modal -->
    <div id="call-history-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 text-center">Call History</h3>
                <div class="mt-4 px-7 py-3">
                    <div id="call-history-content">
                        <div class="text-center text-gray-500">Loading call history...</div>
                    </div>
                </div>
                <div class="flex items-center px-4 py-3 justify-end">
                    <button id="close-history-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Callback Scheduling Modal -->
    <div id="callback-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 text-center">Schedule Callback</h3>
                <div class="mt-4 px-7 py-3">
                    <form id="callback-form">
                        <input type="hidden" id="callback-booking-id" name="booking_id">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Callback Date</label>
                            <input type="date" name="callback_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Callback Time</label>
                            <input type="time" name="callback_time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Callback Notes</label>
                            <textarea name="callback_notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Reason for callback, preferred time, etc."></textarea>
                        </div>
                    </form>
                </div>
                <div class="flex items-center px-4 py-3">
                    <button id="save-callback-btn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 mr-2">
                        Schedule Callback
                    </button>
                    <button id="cancel-callback-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Call Management Functions
        let currentBookingId = null;

        function openCallModal(bookingId) {
            currentBookingId = bookingId;
            
            // Find booking data from the page
            const bookingRow = document.querySelector(`button[onclick="openCallModal(${bookingId})"]`).closest('tr');
            const cells = bookingRow.querySelectorAll('td');
            
            // Extract customer info from the table row
            const customerName = cells[0].querySelector('.text-gray-900').textContent.trim();
            const customerEmail = cells[0].querySelector('.text-gray-500').textContent.trim();
            const visitDate = cells[1].querySelector('.text-gray-900').textContent.trim();
            const visitTime = cells[2].querySelector('.text-gray-900').textContent.trim();
            const hallName = cells[3].querySelector('.text-gray-900').textContent.trim();
            
            // Get phone number - we'll need to fetch this from the backend
            fetchBookingDetails(bookingId).then(booking => {
                document.getElementById('customer-name').textContent = booking.contact_name || customerName;
                document.getElementById('customer-phone').textContent = booking.contact_phone || 'Not available';
                document.getElementById('visit-date').textContent = visitDate + ' at ' + visitTime;
                document.getElementById('hall-name').textContent = hallName;
                document.getElementById('booking-id').value = bookingId;
                
                document.getElementById('call-modal').classList.remove('hidden');
            });
        }

        function closeCallModal() {
            document.getElementById('call-modal').classList.add('hidden');
            document.getElementById('call-form').reset();
            document.getElementById('successful-call-options').classList.add('hidden');
            document.getElementById('visit-reschedule').classList.add('hidden');
        }

        function viewCallHistory(bookingId) {
            document.getElementById('call-history-modal').classList.remove('hidden');
            
            fetch(`/manager/visit/${bookingId}/call-history`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayCallHistory(data.call_history, data.booking);
                    } else {
                        document.getElementById('call-history-content').innerHTML = 
                            '<div class="text-center text-red-500">Error loading call history</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('call-history-content').innerHTML = 
                        '<div class="text-center text-red-500">Error loading call history</div>';
                });
        }

        function displayCallHistory(callHistory, booking) {
            let html = `
                <div class="mb-4 p-3 bg-gray-50 rounded">
                    <h4 class="font-semibold">Customer: ${booking.customer_name}</h4>
                    <p class="text-sm text-gray-600">Phone: ${booking.customer_phone}</p>
                    <p class="text-sm text-gray-600">Total Call Attempts: ${booking.visit_call_attempts}</p>
                </div>
            `;

            if (callHistory.length === 0) {
                html += '<div class="text-center text-gray-500">No call history found</div>';
            } else {
                html += '<div class="space-y-3">';
                callHistory.forEach(call => {
                    const statusColor = {
                        'successful': 'green',
                        'no_answer': 'yellow',
                        'busy': 'orange',
                        'invalid_number': 'red'
                    }[call.call_status] || 'gray';

                    html += `
                        <div class="border border-gray-200 rounded p-3">
                            <div class="flex justify-between items-start mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${statusColor}-100 text-${statusColor}-800">
                                    ${call.call_status.replace('_', ' ').toUpperCase()}
                                </span>
                                <span class="text-sm text-gray-500">${new Date(call.call_attempted_at).toLocaleString()}</span>
                            </div>
                            ${call.call_notes ? `<p class="text-sm text-gray-700 mt-2">${call.call_notes}</p>` : ''}
                        </div>
                    `;
                });
                html += '</div>';
            }

            document.getElementById('call-history-content').innerHTML = html;
        }

        async function fetchBookingDetails(bookingId) {
            try {
                // For now, return mock data. In production, this would fetch from the backend
                return {
                    contact_name: 'Customer Name',
                    contact_phone: '+1234567890'
                };
            } catch (error) {
                console.error('Error fetching booking details:', error);
                return {
                    contact_name: 'Unknown',
                    contact_phone: 'Not available'
                };
            }
        }

        // Event Listeners
        document.getElementById('call-status').addEventListener('change', function() {
            const successfulOptions = document.getElementById('successful-call-options');
            const visitReschedule = document.getElementById('visit-reschedule');
            
            if (this.value === 'successful') {
                successfulOptions.classList.remove('hidden');
            } else {
                successfulOptions.classList.add('hidden');
                visitReschedule.classList.add('hidden');
            }
        });

        document.querySelectorAll('input[name="visit_confirmed"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const visitReschedule = document.getElementById('visit-reschedule');
                if (this.value === '1') {
                    visitReschedule.classList.remove('hidden');
                } else {
                    visitReschedule.classList.add('hidden');
                }
            });
        });

        document.getElementById('save-call-btn').addEventListener('click', function() {
            const form = document.getElementById('call-form');
            const formData = new FormData(form);
            
            if (!formData.get('call_status')) {
                alert('Please select call status');
                return;
            }

            if (formData.get('call_status') === 'successful' && !formData.get('visit_confirmed')) {
                alert('Please indicate if visit was confirmed');
                return;
            }

            fetch(`/manager/visit/${currentBookingId}/confirm-by-call`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    closeCallModal();
                    // Refresh the page or update the UI
                    location.reload();
                } else {
                    showToast(data.message || 'Error recording call', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error recording call', 'error');
            });
        });

        document.getElementById('schedule-callback-btn').addEventListener('click', function() {
            document.getElementById('callback-booking-id').value = currentBookingId;
            document.getElementById('callback-modal').classList.remove('hidden');
        });

        document.getElementById('save-callback-btn').addEventListener('click', function() {
            const form = document.getElementById('callback-form');
            const formData = new FormData(form);
            
            if (!formData.get('callback_date') || !formData.get('callback_time')) {
                alert('Please fill in callback date and time');
                return;
            }

            fetch(`/manager/visit/${formData.get('booking_id')}/schedule-callback`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    document.getElementById('callback-modal').classList.add('hidden');
                    closeCallModal();
                } else {
                    showToast(data.message || 'Error scheduling callback', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error scheduling callback', 'error');
            });
        });

        // Modal close handlers
        document.getElementById('cancel-call-btn').addEventListener('click', closeCallModal);
        document.getElementById('close-history-btn').addEventListener('click', function() {
            document.getElementById('call-history-modal').classList.add('hidden');
        });
        document.getElementById('cancel-callback-btn').addEventListener('click', function() {
            document.getElementById('callback-modal').classList.add('hidden');
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Messages System
        let currentMessageFilter = 'all';
        let currentMessagePage = 1;
        let messagesData = {
            messages: [],
            pagination: {},
            stats: {}
        };

        // Load messages when messages section is shown
        function loadMessages(type = 'all', page = 1) {
            currentMessageFilter = type;
            currentMessagePage = page;
            
            fetch(`/manager/messages?type=${type}&page=${page}&limit=10`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messagesData = data;
                        displayMessages(data.messages);
                        updateMessagesPagination(data.pagination);
                        updateMessagesCount(data.messages.length, data.pagination.total);
                    } else {
                        showToast('Error loading messages', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    showToast('Error loading messages', 'error');
                });
        }

        // Load message statistics
        function loadMessageStats() {
            fetch('/manager/messages/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateMessageStats(data.stats);
                    }
                })
                .catch(error => {
                    console.error('Error loading message stats:', error);
                });
        }

        // Display messages in the list
        function displayMessages(messages) {
            const messagesList = document.getElementById('messages-list');
            
            if (messages.length === 0) {
                messagesList.innerHTML = `
                    <div class="p-6 text-center text-gray-500">
                        <div class="text-4xl mb-2">📭</div>
                        <div>No messages found</div>
                    </div>
                `;
                return;
            }

            messagesList.innerHTML = messages.map(message => {
                const typeColors = {
                    'system': 'blue',
                    'customer_inquiry': 'green',
                    'booking_update': 'purple',
                    'payment_notification': 'yellow',
                    'visit_request': 'indigo'
                };

                const priorityColors = {
                    'urgent': 'red',
                    'high': 'orange',
                    'normal': 'blue',
                    'low': 'gray'
                };

                const typeColor = typeColors[message.type] || 'gray';
                const priorityColor = priorityColors[message.priority] || 'blue';
                const isUnread = !message.is_read;

                return `
                    <div class="p-4 hover:bg-gray-50 ${isUnread ? 'bg-blue-50 border-l-4 border-blue-500' : ''}" data-message-id="${message.id}">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg">${getMessageTypeIcon(message.type)}</span>
                                <span class="font-semibold text-gray-900 ${isUnread ? 'font-bold' : ''}">${message.subject}</span>
                                ${isUnread ? '<span class="w-2 h-2 bg-red-500 rounded-full"></span>' : ''}
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-${priorityColor}-100 text-${priorityColor}-800">
                                    ${message.priority.toUpperCase()}
                                </span>
                                <span class="text-sm text-gray-500">${formatMessageTime(message.created_at)}</span>
                            </div>
                        </div>
                        
                        <div class="text-gray-700 mb-3">${truncateMessage(message.message, 150)}</div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-${typeColor}-100 text-${typeColor}-800">
                                    ${message.type.replace('_', ' ').toUpperCase()}
                                </span>
                                ${message.from_user ? `<span>From: ${message.from_user.name}</span>` : ''}
                                ${message.booking ? `<span>Booking: #${message.booking.id}</span>` : ''}
                            </div>
                            
                            <div class="flex space-x-2">
                                ${!message.is_read ? `<button onclick="markMessageRead(${message.id})" class="text-blue-600 hover:text-blue-800 text-sm">Mark Read</button>` : ''}
                                ${message.type === 'customer_inquiry' ? `<button onclick="replyToMessage(${message.id})" class="text-green-600 hover:text-green-800 text-sm">Reply</button>` : ''}
                                <button onclick="deleteMessage(${message.id})" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Helper functions
        function getMessageTypeIcon(type) {
            const icons = {
                'system': '⚙️',
                'customer_inquiry': '💬',
                'booking_update': '📅',
                'payment_notification': '💰',
                'visit_request': '🏨'
            };
            return icons[type] || '📧';
        }

        function formatMessageTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffInHours = (now - date) / (1000 * 60 * 60);
            
            if (diffInHours < 1) {
                return 'Just now';
            } else if (diffInHours < 24) {
                return `${Math.floor(diffInHours)} hours ago`;
            } else if (diffInHours < 48) {
                return 'Yesterday';
            } else {
                return date.toLocaleDateString();
            }
        }

        function truncateMessage(message, length) {
            if (message.length <= length) return message;
            return message.substring(0, length) + '...';
        }

        // Update message statistics
        function updateMessageStats(stats) {
            document.getElementById('total-messages').textContent = stats.total;
            document.getElementById('unread-messages').textContent = stats.unread;
            document.getElementById('customer-inquiries').textContent = stats.by_type.customer_inquiry;
            document.getElementById('urgent-messages').textContent = stats.by_priority.urgent;
            document.getElementById('system-messages').textContent = stats.by_type.system;

            // Update filter counts
            document.getElementById('filter-all-count').textContent = stats.total;
            document.getElementById('filter-unread-count').textContent = stats.unread;
            document.getElementById('filter-system-count').textContent = stats.by_type.system;
            document.getElementById('filter-customer-count').textContent = stats.by_type.customer_inquiry;
            document.getElementById('filter-booking-count').textContent = stats.by_type.booking_update;
            document.getElementById('filter-payment-count').textContent = stats.by_type.payment_notification;
            document.getElementById('filter-visit-count').textContent = stats.by_type.visit_request;
        }

        // Update pagination
        function updateMessagesPagination(pagination) {
            document.getElementById('pagination-info').textContent = 
                `Page ${pagination.current_page} of ${pagination.last_page}`;
            
            document.getElementById('prev-page').disabled = pagination.current_page <= 1;
            document.getElementById('next-page').disabled = !pagination.has_more;
        }

        // Update messages count
        function updateMessagesCount(current, total) {
            document.getElementById('messages-count').textContent = `${current} of ${total} messages`;
        }

        // Message actions
        function markMessageRead(messageId) {
            fetch(`/manager/messages/${messageId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Message marked as read', 'success');
                    loadMessages(currentMessageFilter, currentMessagePage);
                    loadMessageStats();
                } else {
                    showToast('Error marking message as read', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error marking message as read', 'error');
            });
        }

        function markAllMessagesRead() {
            fetch('/manager/messages/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('All messages marked as read', 'success');
                    loadMessages(currentMessageFilter, currentMessagePage);
                    loadMessageStats();
                } else {
                    showToast('Error marking messages as read', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error marking messages as read', 'error');
            });
        }

        function deleteMessage(messageId) {
            if (!confirm('Are you sure you want to delete this message?')) return;
            
            fetch(`/manager/messages/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Message deleted', 'success');
                    loadMessages(currentMessageFilter, currentMessagePage);
                    loadMessageStats();
                } else {
                    showToast('Error deleting message', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error deleting message', 'error');
            });
        }

        function replyToMessage(messageId) {
            const reply = prompt('Enter your reply:');
            if (!reply) return;
            
            fetch(`/manager/messages/${messageId}/reply`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reply_message: reply })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Reply sent successfully', 'success');
                    loadMessages(currentMessageFilter, currentMessagePage);
                } else {
                    showToast('Error sending reply', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error sending reply', 'error');
            });
        }

        function refreshMessages() {
            loadMessages(currentMessageFilter, currentMessagePage);
            loadMessageStats();
            showToast('Messages refreshed', 'success');
        }

        function exportMessages() {
            showToast('Export functionality coming soon', 'info');
        }

        // Event listeners for message filters
        document.addEventListener('DOMContentLoaded', function() {
            // Message filter buttons
            document.querySelectorAll('.message-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const type = this.dataset.type;
                    
                    // Update active filter
                    document.querySelectorAll('.message-filter-btn').forEach(b => {
                        b.classList.remove('text-blue-600', 'font-medium', 'bg-blue-50');
                        b.classList.add('text-gray-700');
                    });
                    this.classList.add('text-blue-600', 'font-medium', 'bg-blue-50');
                    this.classList.remove('text-gray-700');
                    
                    // Update section title
                    const titles = {
                        'all': 'All Messages',
                        'unread': 'Unread Messages',
                        'system': 'System Notifications',
                        'customer_inquiry': 'Customer Inquiries',
                        'booking_update': 'Booking Updates',
                        'payment_notification': 'Payment Notifications',
                        'visit_request': 'Visit Requests'
                    };
                    document.getElementById('messages-section-title').textContent = titles[type] || 'Messages';
                    
                    // Load filtered messages
                    loadMessages(type, 1);
                });
            });

            // Pagination buttons
            document.getElementById('prev-page').addEventListener('click', function() {
                if (currentMessagePage > 1) {
                    loadMessages(currentMessageFilter, currentMessagePage - 1);
                }
            });

            document.getElementById('next-page').addEventListener('click', function() {
                loadMessages(currentMessageFilter, currentMessagePage + 1);
            });

            // Sort dropdown
            document.getElementById('message-sort').addEventListener('change', function() {
                // For now, just reload messages. In a full implementation, you'd add sorting to the API
                loadMessages(currentMessageFilter, currentMessagePage);
            });
        });

        // Load messages when messages section becomes active
        const originalShowSection = window.showSection;
        window.showSection = function(sectionId) {
            originalShowSection(sectionId);
            
            if (sectionId === 'messages') {
                loadMessages('all', 1);
                loadMessageStats();
            }
        };
    </script>

<!-- Manager Dashboard Integration Script -->
    <script src="{{ asset('js/manager-dashboard-integration.js') }}"></script>
</body>
</html>