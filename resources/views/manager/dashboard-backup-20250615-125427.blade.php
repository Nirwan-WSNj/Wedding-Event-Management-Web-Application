@extends('layouts.manager')

@section('title', 'Manager Dashboard')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .content-section {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    .stats-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .hall-card {
        transition: all 0.3s ease;
    }
    
    .hall-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    
    .booking-item {
        transition: background-color 0.2s ease;
    }
    
    .booking-item:hover {
        background-color: #f8fafc;
    }
    
    .modal-backdrop {
        backdrop-filter: blur(4px);
    }
</style>
@endpush

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform transition-transform duration-300 ease-in-out" id="sidebar">
        <div class="flex items-center justify-center h-20 bg-gradient-to-r from-blue-600 to-purple-600">
            <h1 class="text-2xl font-bold text-white">Wedding Manager</h1>
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
                <a href="#bookings" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Bookings Tracker
                </a>
                <a href="#halls" class="nav-item flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 2 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Manage Halls
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
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button id="notificationBell" class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full transition-colors relative">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5"></path>
                                </svg>
                                <!-- Notification Badge -->
                                <span id="notificationBadge" class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center hidden animate-pulse">
                                    <span id="notificationCount">0</span>
                                </span>
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div id="notificationDropdown" class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50 hidden">
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                                        <div class="flex items-center space-x-2">
                                            <button id="refreshNotifications" class="text-sm text-gray-500 hover:text-gray-700 p-1 rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </button>
                                            <button id="markAllRead" class="text-sm text-blue-600 hover:text-blue-800">Mark all read</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="notificationList" class="max-h-96 overflow-y-auto">
                                    <div class="p-4 text-center text-gray-500">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto mb-2"></div>
                                        Loading notifications...
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 text-center">
                                    <button onclick="showSection('visits')" class="text-sm text-blue-600 hover:text-blue-800">View all visit requests</button>
                                </div>
                            </div>
                        </div>
                        <div class="relative dropdown">
                            <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" id="user-menu">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Manager' }}&background=3B82F6&color=fff" alt="Profile">
                                <span class="ml-2 text-gray-700">{{ auth()->user()->name ?? 'Manager' }}</span>
                                <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6">
            <!-- Dashboard Section -->
            <div id="dashboard-section" class="content-section">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome, {{ auth()->user()->name ?? 'Manager' }}!</h2>
                    <p class="text-gray-600">Oversee venue operations and manage wedding bookings efficiently. Today's Date: {{ now()->format('F d, Y g:i A') }}</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stats-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Available Halls</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-halls">
                                    @php
                                        try {
                                            echo \App\Models\Hall::count();
                                        } catch (\Exception $e) {
                                            echo '0';
                                        }
                                    @endphp
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
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
                                <p class="text-2xl font-bold text-gray-900" id="pending-visits">{{ $stats['pending_visits'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
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
                                <p class="text-2xl font-bold text-gray-900" id="confirmed-bookings">{{ $stats['completed_bookings'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-revenue">Rs. {{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Visit Requests</h3>
                        <div class="space-y-3">
                            @if(isset($pendingVisits) && $pendingVisits->count() > 0)
                                @foreach($pendingVisits->take(3) as $booking)
                                <div class="booking-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <p class="font-semibold text-gray-800">{{ $booking->user->full_name ?? 'N/A' }} - {{ $booking->visit_date ? $booking->visit_date->format('M d, Y') : 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Hall: {{ $booking->hall->name ?? $booking->hall_name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Time: {{ $booking->visit_time ?? 'N/A' }}</p>
                                </div>
                                @endforeach
                            @else
                                <div class="text-gray-500 text-sm">No pending visit requests</div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Bookings</h3>
                        <div class="space-y-3">
                            @if(isset($confirmedVisits) && $confirmedVisits->count() > 0)
                                @foreach($confirmedVisits->take(3) as $booking)
                                <div class="booking-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <p class="font-semibold text-gray-800">{{ $booking->user->full_name ?? 'N/A' }} - {{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Hall: {{ $booking->hall->name ?? $booking->hall_name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Advance: Rs. {{ number_format($booking->advance_payment_amount ?? 0, 2) }}</p>
                                </div>
                                @endforeach
                            @else
                                <div class="text-gray-500 text-sm">No upcoming bookings</div>
                            @endif
                        </div>
                    </div>
                </div<!-- Visit Requests Section -->
            <div id="visits-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Visit Requests Management</h2>
                    <p class="text-gray-600">Review and approve venue visit requests from potential customers.</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Pending Visit Requests</h3>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="location.reload()">
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        @php
                            $pendingVisitsData = \App\Models\Booking::where('visit_submitted', true)
                                ->where('visit_confirmed', false)
                                ->with(['user', 'hall', 'package'])
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp
                        @if($pendingVisitsData->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Couple</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preferred Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hall</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingVisitsData as $booking)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->user->full_name ?? ($booking->contact_name ?? 'Couple ' . $booking->id) }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->contact_email ?? $booking->user->email ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->visit_date ? $booking->visit_date->format('Y-m-d') : ($booking->wedding_date ? $booking->wedding_date->format('Y-m-d') : 'N/A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->visit_time ?? ($booking->wedding_ceremony_time ?? '16:00') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->hall->name ?? $booking->hall_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                            {{ $booking->special_requests ?? $booking->visit_purpose ?? $booking->wedding_additional_notes ?? 'Interested in booking a wedding hall for next year.' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="approveVisit({{ $booking->id }})" 
                                                    class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                            <button onclick="rejectVisit({{ $booking->id }})" 
                                                    class="text-red-600 hover:text-red-900">Reject</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-6 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-lg font-medium">No pending visit requests</p>
                                <p class="text-sm">All visit requests have been processed.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Wedding Requests Section -->
            <div id="wedding-requests-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Wedding Date Approvals</h2>
                    <p class="text-gray-600">Approve wedding date requests after successful venue visits.</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Pending Wedding Date Requests</h3>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" onclick="bulkApprovePayments()">
                                    Bulk Approve
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        @php
                            $confirmedVisitsData = \App\Models\Booking::where('visit_confirmed', true)
                                ->where('advance_payment_paid', false)
                                ->with(['user', 'hall', 'package'])
                                ->orderBy('visit_confirmed_at', 'desc')
                                ->get();
                        @endphp
                        @if($confirmedVisitsData->count() > 0)
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
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($confirmedVisitsData as $booking)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->user->full_name ?? ($booking->contact_name ?? 'Couple ' . $booking->id) }} Wedding</div>
                                            <div class="text-sm text-gray-500">{{ $booking->contact_email ?? $booking->user->email ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->event_date ? $booking->event_date->format('Y-m-d') : ($booking->wedding_date ? $booking->wedding_date->format('Y-m-d') : 'N/A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->wedding_type_time_slot ?? ($booking->wedding_ceremony_time ? 'Custom' : 'Evening') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->hall->name ?? $booking->hall_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            WED{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="markPaymentPaid({{ $booking->id }})" 
                                                    class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                            <button onclick="rejectVisit({{ $booking->id }})" 
                                                    class="text-red-600 hover:text-red-900">Reject</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-6 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">No pending wedding date requests</p>
                                <p class="text-sm">All wedding dates have been approved.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bookings Section -->
            <div id="bookings-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Bookings Tracker</h2>
                    <p class="text-gray-600">Monitor all confirmed bookings and their payment status.</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">All Bookings</h3>
                            <div class="flex space-x-4">
                                <select id="hallFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm" onchange="filterBookings()">
                                    <option value="">Filter by Hall</option>
                                    @php
                                        try {
                                            $hallsForFilter = \App\Models\Hall::pluck('name')->toArray();
                                        } catch (\Exception $e) {
                                            $hallsForFilter = ['Royal Ballroom', 'Garden Paradise', 'Crystal Hall', 'Sunset Terrace'];
                                        }
                                    @endphp
                                    @foreach($hallsForFilter as $hall)
                                        <option value="{{ $hall }}">{{ $hall }}</option>
                                    @endforeach
                                </select>
                                <select id="statusFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm" onchange="filterBookings()">
                                    <option value="">Filter by Status</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" onclick="exportBookings()">
                                    Export Bill
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        @php
                            $allBookingsData = \App\Models\Booking::where('advance_payment_paid', true)
                                ->with(['user', 'hall', 'package'])
                                ->orderBy('event_date', 'desc')
                                ->get();
                        @endphp
                        @if($allBookingsData->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200" id="bookingsTable">
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
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($allBookingsData as $booking)
                                    <tr class="hover:bg-gray-50 booking-row" data-hall="{{ $booking->hall->name ?? $booking->hall_name ?? '' }}" data-status="confirmed">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->user->full_name ?? ($booking->contact_name ?? 'Jane & John Doe') }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->contact_email ?? $booking->user->email ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->hall->name ?? $booking->hall_name ?? 'Royal Ballroom' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->event_date ? $booking->event_date->format('Y-m-d') : ($booking->wedding_date ? $booking->wedding_date->format('Y-m-d') : '2025-07-15') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->guest_count ?? $booking->customization_guest_count ?? '180' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₹{{ number_format($booking->total_amount ?? ($booking->package_price ?? 650000), 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Paid
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Confirmed
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="viewInvoice({{ $booking->id }})" 
                                                    class="text-blue-600 hover:text-blue-900 mr-3">View Invoice</button>
                                            @if(!$booking->advance_payment_paid)
                                            <button onclick="recordPayment({{ $booking->id }})" 
                                                    class="text-green-600 hover:text-green-900">Record Payment</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-6 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-lg font-medium">No confirmed bookings</p>
                                <p class="text-sm">Confirmed bookings will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Halls Section -->
            <div id="halls-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Manage Wedding Halls</h2>
                    <p class="text-gray-600">View and manage available wedding halls and their booking status.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="halls-grid">
                    @php
                        // Get halls data from database or use fallback
                        try {
                            $hallsFromDB = \App\Models\Hall::where('is_active', true)->get();
                            if ($hallsFromDB->count() > 0) {
                                $hallsData = $hallsFromDB->map(function($hall) {
                                    return [
                                        'id' => $hall->id,
                                        'name' => $hall->name,
                                        'description' => $hall->description ?? $hall->name . ' with premium decor',
                                        'capacity' => $hall->capacity,
                                        'price' => $hall->price,
                                        'image' => $hall->image ?? 'https://images.unsplash.com/photo-1519167758481-83f29c1fe8ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                                        'features' => ['Premium Lighting', 'Sound System', 'AC', 'Parking']
                                    ];
                                })->toArray();
                            } else {
                                throw new \Exception('No halls found');
                            }
                        } catch (\Exception $e) {
                            // Fallback data from booking.blade.php step 1 structure
                            $hallsData = [
                                [
                                    'id' => 'jubilee-ballroom',
                                    'name' => 'Royal Ballroom',
                                    'description' => 'Royal Ballroom with premium decor',
                                    'capacity' => 200,
                                    'price' => 650000,
                                    'image' => 'https://images.unsplash.com/photo-1519167758481-83f29c1fe8ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                                    'features' => ['Premium Lighting', 'Sound System', 'AC', 'Parking']
                                ],
                                [
                                    'id' => 'grand-ballroom',
                                    'name' => 'Garden Paradise',
                                    'description' => 'Garden Paradise with premium decor',
                                    'capacity' => 150,
                                    'price' => 400000,
                                    'image' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                                    'features' => ['Garden Setting', 'Natural Lighting', 'Outdoor Space']
                                ],
                                [
                                    'id' => 'garden-pavilion',
                                    'name' => 'Crystal Hall',
                                    'description' => 'Crystal Hall with premium decor',
                                    'capacity' => 180,
                                    'price' => 550000,
                                    'image' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                                    'features' => ['Crystal Chandeliers', 'Marble Floors', 'VIP Lounge']
                                ],
                                [
                                    'id' => 'royal-heritage-hall',
                                    'name' => 'Sunset Terrace',
                                    'description' => 'Sunset Terrace with premium decor',
                                    'capacity' => 120,
                                    'price' => 350000,
                                    'image' => 'https://images.unsplash.com/photo-1465495976277-4387d4b0e4a6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                                    'features' => ['Terrace View', 'Sunset Views', 'Open Air']
                                ]
                            ];
                        }
                    @endphp
                    @foreach($hallsData as $index => $hall)
                        @php
                            // Check availability from database
                            try {
                                $bookedDatesCount = \App\Models\Booking::where('hall_name', $hall['name'])
                                    ->where('advance_payment_paid', true)
                                    ->where('event_date', '>=', now())
                                    ->count();
                                
                                // Also check by hall_id if it's a database hall
                                if (is_numeric($hall['id'])) {
                                    $bookedDatesCount += \App\Models\Booking::where('hall_id', $hall['id'])
                                        ->where('advance_payment_paid', true)
                                        ->where('event_date', '>=', now())
                                        ->count();
                                }
                            } catch (\Exception $e) {
                                $bookedDatesCount = 0;
                            }
                            $isAvailable = $bookedDatesCount == 0;
                        @endphp
                        <div class="hall-card bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="relative">
                                <img src="{{ $hall['image'] }}" alt="{{ $hall['name'] }}" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Rs. {{ number_format($hall['price']) }}
                                </div>
                                @if($isAvailable)
                                    <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        Available
                                    </div>
                                @else
                                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $bookedDatesCount }} Bookings
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $hall['name'] }}</h3>
                                <p class="text-gray-600 mb-4">{{ $hall['description'] }}</p>
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <span>Capacity: {{ $hall['capacity'] }} guests</span>
                                    <span class="text-{{ $isAvailable ? 'green' : 'red' }}-600">
                                        {{ $isAvailable ? 'Available' : 'Booked' }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600">Features:</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($hall['features'] as $feature)
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $feature }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="viewHallDetails('{{ $hall['id'] }}')" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        Edit Details
                                    </button>
                                    <button onclick="viewHallBookings('{{ $hall['id'] }}')" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                        View Bookings
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Calendar Section -->
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
                            <h3 class="text-xl font-semibold text-gray-800" id="current-month">{{ now()->format('F Y') }}</h3>
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
                    <div class="grid grid-cols-7 gap-1" id="calendar-grid">
                        <!-- Calendar will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Messages Section -->
            <div id="messages-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Message Center</h2>
                    <p class="text-gray-600">System notifications and customer inquiries.</p>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Message Types</h3>
                            <div class="space-y-2">
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">All Messages</button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-blue-600 font-medium">Unread</button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">System Notifications</button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">Customer Inquiries</button>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Messages</h3>
                            <div class="space-y-4">
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-blue-800">System Notification</span>
                                        <span class="text-sm text-gray-500">{{ now()->subHours(2)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">Dashboard updated with new booking management features.</p>
                                </div>
                                @if(isset($pendingVisits) && $pendingVisits->count() > 0)
                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-yellow-800">Pending Visit Alert</span>
                                        <span class="text-sm text-gray-500">{{ now()->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">You have {{ $pendingVisits->count() }} pending visit request(s) awaiting approval.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Loading...</span>
    </div>
</div>

<!-- Success Toast -->
<div id="successToast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg hidden z-50">
    <div class="flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="successMessage">Success!</span>
    </div>
</div>

<!-- Error Toast -->
<div id="errorToast" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg hidden z-50">
    <div class="flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span id="errorMessage">Error occurred!</span>
    </div>
</div>

<!-- Modals -->
<!-- Approve Visit Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold mb-4">Approve Visit Request</h3>
            <form id="approveForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmation Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2" 
                              placeholder="Add any notes for the customer..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('approveModal')" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Approve Visit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Visit Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold mb-4">Reject Visit Request</h3>
            <form id="rejectForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection *</label>
                    <textarea name="reason" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2" 
                              placeholder="Please provide a reason for rejection..." required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('rejectModal')" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Reject Visit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mark Payment Paid Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold mb-4">Mark Advance Payment as Paid</h3>
            <form id="paymentForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        <option value="">Select payment method</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="cheque">Cheque</option>
                        <option value="online">Online Payment</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2" 
                              placeholder="Add any payment details or notes..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('paymentModal')" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navigation Functionality
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');
    const pageTitle = document.getElementById('pageTitle');
    const pageTitles = {
        'dashboard': 'Dashboard Overview',
        'visits': 'Visit Requests Management',
        'wedding-requests': 'Wedding Date Approvals',
        'bookings': 'Bookings Tracker',
        'halls': 'Manage Wedding Halls',
        'calendar': 'Calendar View',
        'messages': 'Message Center'
    };

    // Make showSection globally available
    window.showSection = function(sectionId) {
        contentSections.forEach(section => {
            section.classList.add('hidden');
        });
        document.getElementById(`${sectionId}-section`).classList.remove('hidden');
        pageTitle.textContent = pageTitles[sectionId];
        
        // Update active navigation state
        const allNavItems = document.querySelectorAll('.nav-item');
        allNavItems.forEach(item => {
            item.classList.remove('active');
        });
        const activeNavItem = document.querySelector(`.nav-item[href="#${sectionId}"]`);
        if (activeNavItem) {
            activeNavItem.classList.add('active');
        }
        
        if (sectionId === 'calendar') {
            updateCalendar();
        }
        
        if (sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }

        // Close notification dropdown when navigating
        document.getElementById('notificationDropdown').classList.add('hidden');
    }

    // Navigation event listeners will be added after hash navigation setup

    // Sidebar Toggle for Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    // Calendar functionality
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    function updateCalendar() {
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        document.getElementById('current-month').textContent = `${monthNames[currentMonth]} ${currentYear}`;

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const calendarGrid = document.getElementById('calendar-grid');
        calendarGrid.innerHTML = '';

        // Empty cells for days before the first day of the month
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'p-3 text-center text-gray-400';
            calendarGrid.appendChild(emptyCell);
        }

        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('div');
            dayCell.className = 'p-3 text-center hover:bg-gray-100 cursor-pointer border border-gray-200 min-h-[60px] relative';
            dayCell.innerHTML = `<span class="font-medium">${day}</span>`;
            calendarGrid.appendChild(dayCell);
        }
    }

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

    // Dropdown functionality
    const userMenu = document.getElementById('user-menu');
    const dropdownMenu = userMenu.nextElementSibling;
    
    userMenu.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
        // Close notification dropdown when user menu opens
        document.getElementById('notificationDropdown').classList.add('hidden');
    });

    document.addEventListener('click', function() {
        dropdownMenu.classList.add('hidden');
    });

    // ===== REAL-TIME NOTIFICATION SYSTEM =====
    
    // Notification elements
    const notificationBell = document.getElementById('notificationBell');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationCount = document.getElementById('notificationCount');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationList = document.getElementById('notificationList');
    const refreshNotifications = document.getElementById('refreshNotifications');
    const markAllRead = document.getElementById('markAllRead');

    let notificationInterval;
    let lastNotificationCount = 0;

    // Toggle notification dropdown
    notificationBell.addEventListener('click', function(e) {
        e.stopPropagation();
        const isHidden = notificationDropdown.classList.contains('hidden');
        
        // Close user dropdown
        dropdownMenu.classList.add('hidden');
        
        if (isHidden) {
            notificationDropdown.classList.remove('hidden');
            loadNotifications();
        } else {
            notificationDropdown.classList.add('hidden');
        }
    });

    // Refresh notifications manually
    refreshNotifications.addEventListener('click', function(e) {
        e.stopPropagation();
        loadNotifications();
        // Add rotation animation
        this.classList.add('animate-spin');
        setTimeout(() => {
            this.classList.remove('animate-spin');
        }, 1000);
    });

    // Mark all notifications as read
    markAllRead.addEventListener('click', function(e) {
        e.stopPropagation();
        markAllNotificationsRead();
    });

    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationDropdown.contains(e.target) && !notificationBell.contains(e.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });

    // Load notifications from server
    function loadNotifications() {
        fetch('/manager/notifications', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayNotifications(data.notifications);
                updateNotificationCount(data.unread_count);
            } else {
                showNotificationError('Failed to load notifications');
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            showNotificationError('Error loading notifications');
        });
    }

    // Display notifications in dropdown
    function displayNotifications(notifications) {
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5"></path>
                    </svg>
                    <p class="text-sm">No new notifications</p>
                </div>
            `;
            return;
        }

        let notificationsHtml = '';
        notifications.forEach(notification => {
            const priorityColor = notification.priority === 'high' ? 'border-red-200 bg-red-50' : 
                                notification.priority === 'medium' ? 'border-yellow-200 bg-yellow-50' : 
                                'border-blue-200 bg-blue-50';
            
            const iconColor = notification.type === 'visit_request' ? 'text-red-600' : 'text-yellow-600';
            const icon = notification.type === 'visit_request' ? 
                `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>` :
                `<svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>`;

            let actionsHtml = '';
            if (notification.action_required && notification.actions) {
                if (notification.type === 'visit_request') {
                    actionsHtml = `
                        <div class="flex space-x-2 mt-2">
                            <button onclick="approveVisitFromNotification(${notification.booking_id})" 
                                    class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition-colors">
                                Approve
                            </button>
                            <button onclick="rejectVisitFromNotification(${notification.booking_id})" 
                                    class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition-colors">
                                Reject
                            </button>
                        </div>
                    `;
                } else if (notification.type === 'payment_pending') {
                    actionsHtml = `
                        <div class="flex space-x-2 mt-2">
                            <button onclick="markPaymentPaidFromNotification(${notification.booking_id})" 
                                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                Mark Paid
                            </button>
                        </div>
                    `;
                }
            }

            notificationsHtml += `
                <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors ${priorityColor} border-l-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-1">
                            ${icon}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-900">${notification.title}</p>
                                <span class="text-xs text-gray-500">${notification.time}</span>
                            </div>
                            <p class="text-sm text-gray-700 mt-1">${notification.message}</p>
                            ${notification.visit_date && notification.visit_date !== 'N/A' ? 
                                `<p class="text-xs text-gray-500 mt-1">Visit: ${notification.visit_date} at ${notification.visit_time}</p>` : ''}
                            ${notification.amount ? 
                                `<p class="text-xs text-gray-600 mt-1 font-medium">Amount: Rs. ${notification.amount.toLocaleString()}</p>` : ''}
                            ${actionsHtml}
                        </div>
                    </div>
                </div>
            `;
        });

        notificationList.innerHTML = notificationsHtml;
    }

    // Update notification count and badge
    function updateNotificationCount(count) {
        notificationCount.textContent = count;
        
        if (count > 0) {
            notificationBadge.classList.remove('hidden');
            
            // Add pulse animation for new notifications
            if (count > lastNotificationCount) {
                notificationBell.classList.add('animate-pulse');
                setTimeout(() => {
                    notificationBell.classList.remove('animate-pulse');
                }, 2000);
                
                // Show toast for new notifications
                if (lastNotificationCount > 0) { // Don't show on initial load
                    showToast('success', `${count - lastNotificationCount} new notification(s) received!`);
                }
            }
        } else {
            notificationBadge.classList.add('hidden');
        }
        
        lastNotificationCount = count;
        
        // Update dashboard stats
        document.getElementById('pending-visits').textContent = count;
    }

    // Show notification error
    function showNotificationError(message) {
        notificationList.innerHTML = `
            <div class="p-6 text-center text-red-500">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <p class="text-sm">${message}</p>
                <button onclick="loadNotifications()" class="mt-2 text-xs text-blue-600 hover:text-blue-800">Try again</button>
            </div>
        `;
    }

    // Mark all notifications as read
    function markAllNotificationsRead() {
        fetch('/manager/notifications/mark-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'All notifications marked as read');
                loadNotifications(); // Refresh notifications
            }
        })
        .catch(error => {
            console.error('Error marking notifications as read:', error);
        });
    }

    // Auto-refresh notifications every 15 seconds
    function startNotificationPolling() {
        // Initial load
        updateNotificationCountOnly();
        
        // Set up polling
        notificationInterval = setInterval(() => {
            updateNotificationCountOnly();
        }, 15000); // 15 seconds
    }

    // Update only notification count (lighter operation)
    function updateNotificationCountOnly() {
        fetch('/manager/notifications/count', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationCount(data.count);
            }
        })
        .catch(error => {
            console.error('Error updating notification count:', error);
        });
    }

    // Notification action handlers
    window.approveVisitFromNotification = function(bookingId) {
        approveVisit(bookingId);
        notificationDropdown.classList.add('hidden');
    };

    window.rejectVisitFromNotification = function(bookingId) {
        rejectVisit(bookingId);
        notificationDropdown.classList.add('hidden');
    };

    window.markPaymentPaidFromNotification = function(bookingId) {
        markPaymentPaid(bookingId);
        notificationDropdown.classList.add('hidden');
    };

    // Start notification polling
    startNotificationPolling();

    // Initialize calendar
    updateCalendar();

    // Hash-based navigation support
    function handleHashChange() {
        const hash = window.location.hash.substring(1); // Remove the # symbol
        if (hash && pageTitles[hash]) {
            showSection(hash);
        } else if (!hash) {
            // Default to dashboard if no hash
            showSection('dashboard');
        }
    }

    // Handle initial page load with hash
    handleHashChange();

    // Listen for hash changes
    window.addEventListener('hashchange', handleHashChange);

    // Update navigation click handlers to use hash navigation
    const updatedNavItems = document.querySelectorAll('.nav-item');
    updatedNavItems.forEach(item => {
        // Remove existing click listeners by cloning the element
        const newItem = item.cloneNode(true);
        item.parentNode.replaceChild(newItem, item);
        
        // Add new click handler that updates hash
        newItem.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('href').substring(1);
            window.location.hash = sectionId;
            // showSection will be called by hashchange event
        });
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (notificationInterval) {
            clearInterval(notificationInterval);
        }
    });
});

// Additional functionality for new sections

// Bulk approve payments
function bulkApprovePayments() {
    if (confirm('Are you sure you want to bulk approve all pending payment confirmations?')) {
        showLoading();
        // This would need to be implemented in the backend
        fetch('/manager/bulk-approve-payments', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('success', 'All payments approved successfully!');
                location.reload();
            } else {
                showToast('error', data.message || 'Failed to bulk approve payments');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('error', 'Error occurred while bulk approving payments');
        });
    }
}

// Filter bookings
function filterBookings() {
    const hallFilter = document.getElementById('hallFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.booking-row');
    
    rows.forEach(row => {
        const hallMatch = !hallFilter || row.dataset.hall === hallFilter;
        const statusMatch = !statusFilter || row.dataset.status === statusFilter;
        
        if (hallMatch && statusMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Export bookings
function exportBookings() {
    showLoading();
    // This would generate a CSV or PDF export
    fetch('/manager/export-bookings', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Export failed');
    })
    .then(blob => {
        hideLoading();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'bookings-export-' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        showToast('success', 'Bookings exported successfully!');
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Failed to export bookings');
    });
}

// View invoice
function viewInvoice(bookingId) {
    window.open('/manager/booking/' + bookingId + '/invoice', '_blank');
}

// Record payment
function recordPayment(bookingId) {
    markPaymentPaid(bookingId);
}

// View hall details
function viewHallDetails(hallId) {
    showLoading();
    fetch('/manager/hall/' + hallId + '/details')
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            // Show hall details modal or navigate to hall details page
            showHallDetailsModal(data.hall);
        } else {
            showToast('error', 'Failed to load hall details');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Error loading hall details');
    });
}

// View hall bookings
function viewHallBookings(hallId) {
    showLoading();
    fetch('/manager/hall/' + hallId + '/details')
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            // Show hall bookings modal
            showHallBookingsModal(data.hall, data.upcoming_bookings, data.pending_visits);
        } else {
            showToast('error', 'Failed to load hall bookings');
        }
    })
    .catch(error => {
        hideLoading();
        showToast('error', 'Error loading hall bookings');
    });
}

// Show hall details modal
function showHallDetailsModal(hall) {
    // This would show a modal with hall details for editing
    alert('Hall Details Modal - ' + hall.name + '\nCapacity: ' + hall.capacity + '\nPrice: Rs. ' + hall.price);
}

// Show hall bookings modal
function showHallBookingsModal(hall, bookings, visits) {
    let content = 'Hall: ' + hall.name + '\n\n';
    content += 'Upcoming Bookings: ' + bookings.length + '\n';
    content += 'Pending Visits: ' + visits.length + '\n\n';
    
    if (bookings.length > 0) {
        content += 'Recent Bookings:\n';
        bookings.slice(0, 3).forEach(booking => {
            content += '- ' + (booking.user ? booking.user.full_name : 'N/A') + ' on ' + booking.event_date + '\n';
        });
    }
    
    alert(content);
}
});

// Utility functions
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

function showToast(type, message) {
    const toast = document.getElementById(type + 'Toast');
    const messageElement = document.getElementById(type + 'Message');
    messageElement.textContent = message;
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 5000);
}

// Visit management functions
let currentBookingId = null;

function approveVisit(bookingId) {
    currentBookingId = bookingId;
    document.getElementById('approveModal').classList.remove('hidden');
}

function rejectVisit(bookingId) {
    currentBookingId = bookingId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function markPaymentPaid(bookingId) {
    currentBookingId = bookingId;
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    currentBookingId = null;
}

// Handle approve form submission
document.getElementById('approveForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    showLoading();
    const formData = new FormData(this);
    
    fetch(`/manager/visit/${currentBookingId}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('success', 'Visit approved successfully!');
            closeModal('approveModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', 'Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('error', 'An error occurred while approving the visit.');
    });
});

// Handle reject form submission
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    showLoading();
    const formData = new FormData(this);
    
    fetch(`/manager/visit/${currentBookingId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('success', 'Visit rejected successfully.');
            closeModal('rejectModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', 'Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('error', 'An error occurred while rejecting the visit.');
    });
});

// Handle payment form submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    showLoading();
    const formData = new FormData(this);
    
    fetch(`/manager/booking/${currentBookingId}/deposit-paid`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('success', 'Payment marked as paid successfully!');
            closeModal('paymentModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', 'Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('error', 'An error occurred while marking payment as paid.');
    });
});

// Hall management functions
function viewHallDetails(hallId) {
    fetch(`/manager/hall/${hallId}/details`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showHallDetailsModal(data.hall, data.stats, data.upcoming_bookings, data.pending_visits);
        } else {
            alert('Error loading hall details: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching hall details:', error);
        alert('Error loading hall details');
    });
}

function viewHallBookings(hallId) {
    // Switch to bookings section and filter by hall
    showSection('bookings');
    // This would filter the bookings table by hall ID
    alert(`Showing bookings for Hall ID: ${hallId}`);
}

function showHallDetailsModal(hall, stats, upcomingBookings, pendingVisits) {
    let bookingsHtml = '';
    if (upcomingBookings.length > 0) {
        bookingsHtml = upcomingBookings.map(booking => `
            <div class="border-b pb-2 mb-2">
                <p class="font-medium">${booking.user.full_name}</p>
                <p class="text-sm text-gray-600">Date: ${new Date(booking.event_date).toLocaleDateString()}</p>
                <p class="text-sm text-gray-600">Amount: Rs. ${booking.advance_payment_amount.toLocaleString()}</p>
            </div>
        `).join('');
    } else {
        bookingsHtml = '<p class="text-gray-500">No upcoming bookings</p>';
    }

    let visitsHtml = '';
    if (pendingVisits.length > 0) {
        visitsHtml = pendingVisits.map(visit => `
            <div class="border-b pb-2 mb-2">
                <p class="font-medium">${visit.user.full_name}</p>
                <p class="text-sm text-gray-600">Visit Date: ${visit.visit_date ? new Date(visit.visit_date).toLocaleDateString() : 'N/A'}</p>
            </div>
        `).join('');
    } else {
        visitsHtml = '<p class="text-gray-500">No pending visits</p>';
    }

    const modalHtml = `
        <div id="hallDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg max-w-2xl w-full p-6 max-h-screen overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">${hall.name} - Details</h3>
                        <button onclick="closeHallDetailsModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-blue-600">${stats.total_bookings}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Pending Visits</p>
                            <p class="text-2xl font-bold text-yellow-600">${stats.pending_visits}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Revenue This Year</p>
                            <p class="text-2xl font-bold text-green-600">Rs. ${stats.revenue_this_year.toLocaleString()}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold mb-3">Hall Information</h4>
                            <div class="space-y-2">
                                <p><strong>Capacity:</strong> ${hall.capacity || 'N/A'} guests</p>
                                <p><strong>Base Price:</strong> Rs. ${(hall.base_price || 0).toLocaleString()}</p>
                                <p><strong>Description:</strong> ${hall.description || 'N/A'}</p>
                                ${hall.amenities ? `<p><strong>Amenities:</strong> ${hall.amenities}</p>` : ''}
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold mb-3">Upcoming Bookings</h4>
                            <div class="max-h-40 overflow-y-auto">
                                ${bookingsHtml}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold mb-3">Pending Visits</h4>
                        <div class="max-h-32 overflow-y-auto">
                            ${visitsHtml}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

function closeHallDetailsModal() {
    const modal = document.getElementById('hallDetailsModal');
    if (modal) {
        modal.remove();
    }
}

// Update dashboard stats dynamically
function updateDashboardStats() {
    // This function can be called to refresh stats without page reload
    fetch('/manager/dashboard/stats', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('pending-visits').textContent = data.stats.pending_visits;
            document.getElementById('confirmed-bookings').textContent = data.stats.completed_bookings;
            document.getElementById('total-revenue').textContent = 'Rs. ' + new Intl.NumberFormat().format(data.stats.total_revenue);
        }
    })
    .catch(error => {
        console.error('Error updating stats:', error);
    });
}

// Auto-refresh stats every 30 seconds
setInterval(updateDashboardStats, 30000);

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-gray-600')) {
        const modals = ['approveModal', 'rejectModal', 'paymentModal'];
        modals.forEach(modalId => {
            if (!document.getElementById(modalId).classList.contains('hidden')) {
                closeModal(modalId);
            }
        });
    }
});

// Enhanced calendar functionality with real booking data
function updateCalendarWithBookings() {
    // This would fetch real booking data and display it on the calendar
    fetch('/manager/calendar/events', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update calendar with real events
            // This would be implemented based on the calendar library used
        }
    })
    .catch(error => {
        console.error('Error fetching calendar events:', error);
    });
}

// Initialize enhanced features
document.addEventListener('DOMContentLoaded', function() {
    // Add any additional initialization here
    console.log('Manager Dashboard Enhanced - Ready');
});
</script>
@endpush

@endsection
