<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wedding Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
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
    </style>
</head>
<body>

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
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
                        <div class="relative">
                            <button onclick="showMessages()" class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5"></path>
                                </svg>
                            </button>
                            <span id="notification-badge" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white hidden"></span>
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
                                <button onclick="showSettings()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</button>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visit Requests Section -->
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
                                <button onclick="location.reload()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $allPendingVisits = \App\Models\Booking::where('visit_submitted', true)
                                ->where('visit_confirmed', false)
                                ->with(['user', 'hall'])
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp
                        @if($allPendingVisits->count() > 0)
                            <div class="space-y-4">
                                @foreach($allPendingVisits as $booking)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-medium text-gray-900">{{ $booking->user->full_name }}</h3>
                                                <p class="text-sm text-gray-600">{{ $booking->hall->name ?? $booking->hall_name }}</p>
                                                <p class="text-sm text-gray-500">
                                                    Visit: {{ $booking->visit_date ? $booking->visit_date->format('M d, Y') : 'N/A' }} 
                                                    at {{ $booking->visit_time ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Event Date: {{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Submitted: {{ $booking->created_at->diffForHumans() }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Contact: {{ $booking->user->email }}
                                                </p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button onclick="approveVisit({{ $booking->id }})" 
                                                        class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                                    Approve
                                                </button>
                                                <button onclick="rejectVisit({{ $booking->id }})" 
                                                        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                                    Reject
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No pending visit requests</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Wedding Requests Section -->
            <div id="wedding-requests-section" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Wedding Date Approvals</h2>
                    <p class="text-gray-600">Manage confirmed visits awaiting advance payment.</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Confirmed Visits Awaiting Payment</h3>
                            <div class="flex space-x-2">
                                <button onclick="location.reload()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $confirmedVisits = \App\Models\Booking::where('visit_confirmed', true)
                                ->where('advance_payment_paid', false)
                                ->with(['user', 'hall', 'package'])
                                ->orderBy('visit_confirmed_at', 'desc')
                                ->get();
                        @endphp
                        @if($confirmedVisits->count() > 0)
                            <div class="space-y-4">
                                @foreach($confirmedVisits as $booking)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-medium text-gray-900">{{ $booking->user->full_name }}</h3>
                                                <p class="text-sm text-gray-600">{{ $booking->hall->name ?? $booking->hall_name }}</p>
                                                <p class="text-sm text-gray-500">
                                                    Event Date: {{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Advance Payment: Rs. {{ number_format($booking->advance_payment_amount, 2) }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Visit Confirmed: {{ $booking->visit_confirmed_at ? $booking->visit_confirmed_at->diffForHumans() : 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Package: {{ $booking->package->name ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Contact: {{ $booking->user->email }}
                                                </p>
                                            </div>
                                            <div class="flex flex-col space-y-2">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Visit Confirmed
                                                </span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Payment Pending
                                                </span>
                                                <button onclick="markPaymentPaid({{ $booking->id }})" 
                                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                                    Mark Paid
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No confirmed visits awaiting payment</p>
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
                            <div class="flex space-x-2">
                                <select id="filter-hall" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Filter by Hall</option>
                                    @foreach(\App\Models\Hall::all() as $hall)
                                        <option value="{{ $hall->id }}">{{ $hall->name }}</option>
                                    @endforeach
                                </select>
                                <select id="filter-status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Filter by Status</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="pending">Pending Payment</option>
                                    <option value="completed">Completed</option>
                                </select>
                                <button onclick="exportBookings()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hall</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Advance Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="bookings-table-body">
                                @php
                                    $allBookings = \App\Models\Booking::with(['user', 'hall', 'package'])
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                                @endphp
                                @foreach($allBookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>
                                            <div class="font-medium">{{ $booking->user->full_name }}</div>
                                            <div class="text-gray-500">{{ $booking->user->email }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->hall->name ?? $booking->hall_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->package->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rs. {{ number_format($booking->advance_payment_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->advance_payment_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $booking->advance_payment_paid ? 'Paid' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($booking->visit_confirmed && $booking->advance_payment_paid) bg-green-100 text-green-800
                                            @elseif($booking->visit_confirmed) bg-blue-100 text-blue-800
                                            @elseif($booking->visit_submitted) bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($booking->visit_confirmed && $booking->advance_payment_paid) Confirmed
                                            @elseif($booking->visit_confirmed) Visit Confirmed
                                            @elseif($booking->visit_submitted) Visit Pending
                                            @else Draft @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="viewBookingDetails({{ $booking->id }})" class="text-blue-600 hover:text-blue-900 mr-2">View</button>
                                        @if($booking->advance_payment_paid)
                                            <button onclick="generateInvoice({{ $booking->id }})" class="text-green-600 hover:text-green-900">Invoice</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                        $halls = \App\Models\Hall::all();
                        $colors = ['blue', 'green', 'purple', 'yellow', 'red', 'indigo'];
                    @endphp
                    @foreach($halls as $index => $hall)
                    @php
                        $color = $colors[$index % count($colors)];
                        $bookedDatesCount = \App\Models\Booking::where('hall_id', $hall->id)
                            ->where('advance_payment_paid', true)
                            ->where('event_date', '>=', now())
                            ->count();
                        $isAvailable = $bookedDatesCount == 0;
                        $totalRevenue = \App\Models\Booking::where('hall_id', $hall->id)
                            ->where('advance_payment_paid', true)
                            ->sum('advance_payment_amount');
                    @endphp
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="h-48 bg-gradient-to-r from-{{ $color }}-400 to-{{ $color }}-600 flex items-center justify-center">
                            <span class="text-white text-lg font-semibold">{{ $hall->name }}</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $hall->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $hall->description ?? 'Premium wedding venue with elegant decor' }}</p>
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span>Capacity: {{ $hall->capacity ?? 'N/A' }} guests</span>
                                <span class="text-{{ $isAvailable ? 'green' : 'red' }}-600">
                                    {{ $isAvailable ? 'Available' : $bookedDatesCount . ' Bookings' }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Base Price: Rs. {{ number_format($hall->base_price ?? 0, 2) }}</p>
                                <p class="text-sm text-gray-600">Total Revenue: Rs. {{ number_format($totalRevenue, 2) }}</p>
                                @if($hall->amenities)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($hall->amenities, 50) }}</p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="viewHallDetails({{ $hall->id }})" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    View Details
                                </button>
                                <button onclick="viewHallBookings({{ $hall->id }})" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
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
                                <div class="w-4 h-4 bg-blue-500 rounded"></div>
                                <span class="text-sm text-gray-600">Confirmed Visits</span>
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
                                <button onclick="filterMessages('all')" class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">All Messages</button>
                                <button onclick="filterMessages('unread')" class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-blue-600 font-medium">Unread</button>
                                <button onclick="filterMessages('system')" class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">System Notifications</button>
                                <button onclick="filterMessages('customer')" class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700">Customer Inquiries</button>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <button onclick="composeMessage()" class="w-full text-left px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Compose New</button>
                                <button onclick="archiveOldMessages()" class="w-full text-left px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Archive Old</button>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Messages</h3>
                            <div class="space-y-4" id="messages-list">
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-blue-800">System Notification</span>
                                        <span class="text-sm text-gray-500">{{ now()->subHours(2)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">Dashboard updated with new booking management features.</p>
                                </div>
                                @php
                                    $pendingVisitsCount = \App\Models\Booking::where('visit_submitted', true)->where('visit_confirmed', false)->count();
                                @endphp
                                @if($pendingVisitsCount > 0)
                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-yellow-800">Pending Visit Alert</span>
                                        <span class="text-sm text-gray-500">{{ now()->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">You have {{ $pendingVisitsCount }} pending visit request(s) awaiting approval.</p>
                                </div>
                                @endif
                                @php
                                    $paymentPendingCount = \App\Models\Booking::where('visit_confirmed', true)->where('advance_payment_paid', false)->count();
                                @endphp
                                @if($paymentPendingCount > 0)
                                <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-orange-800">Payment Pending Alert</span>
                                        <span class="text-sm text-gray-500">{{ now()->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $paymentPendingCount }} confirmed visit(s) are awaiting advance payment.</p>
                                </div>
                                @endif
                                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-green-800">Revenue Update</span>
                                        <span class="text-sm text-gray-500">{{ now()->subDay()->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">Total revenue this month: Rs. {{ number_format(\App\Models\Booking::where('advance_payment_paid', true)->whereMonth('created_at', now()->month)->sum('advance_payment_amount'), 2) }}</p>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-center">
                                <button onclick="loadMoreMessages()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Load More</button>
                            </div>
                        </div>
                    </div>
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

<!-- Settings Modal -->
<div id="settingsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full p-6 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Profile Settings</h3>
                <button onclick="closeModal('settingsModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="settingsForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <input type="text" value="{{ auth()->user()->role ?? 'Manager' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-lg font-medium mb-4">Change Password</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="new_password" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('settingsModal')" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
        
        if (sectionId === 'calendar') {
            updateCalendar();
        }
        
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
            
            // Add sample events (in real app, this would come from database)
            if (day % 7 === 0) {
                const event = document.createElement('div');
                event.className = 'text-xs rounded px-1 mt-1 bg-green-500 text-white';
                event.textContent = 'Wedding';
                dayCell.appendChild(event);
            } else if (day % 5 === 0) {
                const event = document.createElement('div');
                event.className = 'text-xs rounded px-1 mt-1 bg-yellow-400 text-gray-800';
                event.textContent = 'Visit';
                dayCell.appendChild(event);
            }
            
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
    });

    document.addEventListener('click', function() {
        dropdownMenu.classList.add('hidden');
    });

    // Initialize calendar
    updateCalendar();
});

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
    
    closeModal('approveModal');
});

// Handle reject form submission
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
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
        if (data.success) {
            alert('Visit rejected successfully.');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while rejecting the visit.');
    });
    
    closeModal('rejectModal');
});

// Handle payment form submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
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
    
    closeModal('paymentModal');
});

// Hall management functions
function viewHallDetails(hallId) {
    alert(`Viewing details for Hall ID: ${hallId}`);
    // In a real application, this would open a modal with hall details
}

function viewHallBookings(hallId) {
    // Switch to bookings section and filter by hall
    showSection('bookings');
    alert(`Showing bookings for Hall ID: ${hallId}`);
}

// Booking functions
function viewBookingDetails(bookingId) {
    alert(`Viewing booking details for ID: ${bookingId}`);
    // In a real application, this would open a modal with booking details
}

function generateInvoice(bookingId) {
    alert(`Generating invoice for booking ID: ${bookingId}`);
    // In a real application, this would generate and download an invoice
}

function exportBookings() {
    alert('Exporting bookings data...');
    // In a real application, this would export bookings to CSV/Excel
}

// Message functions
function showMessages() {
    showSection('messages');
}

function showSettings() {
    document.getElementById('settingsModal').classList.remove('hidden');
}

function filterMessages(type) {
    alert(`Filtering messages by: ${type}`);
    // In a real application, this would filter the messages list
}

function composeMessage() {
    alert('Opening compose message dialog...');
    // In a real application, this would open a compose message modal
}

function archiveOldMessages() {
    if (confirm('Are you sure you want to archive old messages?')) {
        alert('Old messages archived successfully!');
        // In a real application, this would archive old messages
    }
}

function loadMoreMessages() {
    alert('Loading more messages...');
    // In a real application, this would load more messages via AJAX
}

// Settings form submission
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Settings saved successfully!');
    closeModal('settingsModal');
    // In a real application, this would save the settings via AJAX
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-gray-600')) {
        const modals = ['approveModal', 'rejectModal', 'paymentModal', 'settingsModal'];
        modals.forEach(modalId => {
            if (!document.getElementById(modalId).classList.contains('hidden')) {
                closeModal(modalId);
            }
        });
    }
});

// Auto-refresh stats every 30 seconds
setInterval(() => {
    // Update stats without page reload
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
            
            // Update notification badge
            const badge = document.getElementById('notification-badge');
            if (data.stats.pending_visits > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    })
    .catch(error => {
        console.error('Error updating stats:', error);
    });
}, 30000);

// Initialize enhanced features
console.log('Wedding Manager Dashboard - Ready');
</script>

</body>
</html>
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
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
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
                                <p class="text-2xl font-bold text-gray-900" id="total-halls">{{ \App\Models\Hall::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
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
                                <p class="text-sm font-medium text-gray-500">Confirmed Bookings</p>
                                <p class="text-2xl font-bold text-gray-900" id="confirmed-bookings">{{ \App\Models\Booking::where('advance_payment_paid', true)->count() }}</p>
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
                                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-revenue">Rs. {{ number_format(\App\Models\Booking::where('advance_payment_paid', true)->sum('advance_payment_amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Visit Requests</h3>
                        <div class="space-y-3" id="recent-visits">
                            @php
                                $pendingVisits = \App\Models\Booking::where('visit_submitted', true)
                                    ->where('visit_confirmed', false)
                                    ->with(['user', 'hall'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(3)
                                    ->get();
                            @endphp
                            @if($pendingVisits->count() > 0)
                                @foreach($pendingVisits as $booking)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <p class="font-semibold text-gray-800">{{ $booking->user->full_name }} - {{ $booking->visit_date ? $booking->visit_date->format('M d, Y') : 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Hall: {{ $booking->hall->name ?? $booking->hall_name }}</p>
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
                        <div class="space-y-3" id="upcoming-bookings">
                            @php
                                $upcomingBookings = \App\Models\Booking::where('advance_payment_paid', true)
                                    ->where('event_date', '>=', now())
                                    ->with(['user', 'hall'])
                                    ->orderBy('event_date')
                                    ->take(3)
                                    ->get();
                            @endphp
                            @if($upcomingBookings->count() > 0)
                                @foreach($upcomingBookings as $booking)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <p class="font-semibold text-gray-800">{{ $booking->user->full_name }} - {{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Hall: {{ $booking->hall->name ?? $booking->hall_name }}</p>
                                    <p class="text-sm text-gray-600">Advance: Rs. {{ number_format($booking->advance_payment_amount, 2) }}</p>
                                </div>
                                @endforeach
                            @else
                                <div class="text-gray-500 text-sm">No upcoming bookings</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>