<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
                            <button class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a6 6 0 10-12 0v3l-5 5h5"></path>
                                </svg>
                            </button>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                        </div>
                        <div class="relative dropdown">
                            <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500" id="user-menu">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Manager&background=3B82F6&color=fff" alt="Profile">
                                <span class="ml-2 text-gray-700">Manager</span>
                                <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
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
                    <p class="text-gray-600">Oversee venue operations and manage wedding bookings efficiently. Today's Date: June 05, 2025 12:31 PM</p>
                </div>

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
                                <p class="text-sm font-medium text-gray-500">Assigned Halls</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-halls">4</p>
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
                                <p class="text-2xl font-bold text-gray-900" id="pending-visits">0</p>
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
                                <p class="text-2xl font-bold text-gray-900" id="confirmed-bookings">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Upcoming Events</p>
                                <p class="text-2xl font-bold text-gray-900" id="upcoming-events">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Visit Requests</h3>
                        <div class="space-y-3" id="recent-visits">
                            <div class="text-gray-500 text-sm">No pending visit requests</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Weddings</h3>
                        <div class="space-y-3" id="upcoming-weddings">
                            <div class="text-gray-500 text-sm">No upcoming weddings</div>
                        </div>
                    </div>
                </div>
            </div>

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
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" id="refresh-visits">
                                    Refresh
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
                            <tbody class="bg-white divide-y divide-gray-200" id="visits-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

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
                            <tbody class="bg-white divide-y divide-gray-200" id="wedding-requests-table-body"></tbody>
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
                    <p class="text-gray-600">System notifications and customer inquiries.</p>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Message Types</h3>
                            <div class="space-y-2">
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-message-type="all">All Messages</button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-blue-600 font-medium" data-message-type="unread">Unread</button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-message-type="system">System Notifications</button>
                                <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700" data-message-type="customer">Customer Inquiries</button>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <button class="w-full text-left px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Compose New</button>
                                <button class="w-full text-left px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Archive Old</button>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">All Messages</h3>
                            <div class="space-y-4" id="messages-list">
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-blue-800">System Notification</span>
                                        <span class="text-sm text-gray-500">2 hours ago</span>
                                    </div>
                                    <p class="text-gray-700">New update available for dashboard features. Please review.</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-gray-800">Customer Inquiry: John Doe</span>
                                        <span class="text-sm text-gray-500">Yesterday</span>
                                    </div>
                                    <p class="text-gray-700">"Hi, I would like to inquire about booking availability for late October..."</p>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-center">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Load More</button>
                            </div>
                        </div>
                    </div>
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
        // State Management
        let state = JSON.parse(localStorage.getItem('managerDashboardState')) || {
            halls: [
                { id: 'royal', name: 'Royal Ballroom', capacity: 200, availability: 'available' },
                { id: 'garden', name: 'Garden Paradise', capacity: 150, availability: 'available' },
                { id: 'crystal', name: 'Crystal Hall', capacity: 180, availability: 'available' },
                { id: 'sunset', name: 'Sunset Terrace', capacity: 120, availability: 'available' }
            ],
            visits: [],
            weddingRequests: [],
            bookings: [
                { id: 'BKG001', coupleName: 'Jane & John Doe', hall: 'Royal Ballroom', weddingDate: '2025-07-15', guests: 180, total: 650000, paymentStatus: 'Paid', status: 'Confirmed', package: 'Platinum' },
                { id: 'BKG002', coupleName: 'Alice & Bob Smith', hall: 'Garden Paradise', weddingDate: '2025-08-01', guests: 100, total: 400000, paymentStatus: 'Pending', status: 'Confirmed', package: 'Silver' },
                { id: 'BKG003', coupleName: 'Emily & David White', hall: 'Crystal Hall', weddingDate: '2025-09-10', guests: 160, total: 550000, paymentStatus: 'Paid', status: 'Confirmed', package: 'Golden' },
                { id: 'BKG004', coupleName: 'Sarah & Michael Brown', hall: 'Sunset Terrace', weddingDate: '2025-10-05', guests: 90, total: 350000, paymentStatus: 'Pending', status: 'Confirmed', package: 'Silver' },
                { id: 'BKG005', coupleName: 'Olivia & William Green', hall: 'Royal Ballroom', weddingDate: '2025-06-20', guests: 150, total: 580000, paymentStatus: 'Paid', status: 'Confirmed', package: 'Golden' }
            ],
            calendarEvents: [
                { date: '2025-06-20', type: 'wedding', status: 'confirmed' }
            ]
        };

        function saveState() {
            localStorage.setItem('managerDashboardState', JSON.stringify(state));
        }

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
            document.getElementById('total-halls').textContent = stats.totalHalls || state.halls.length;
            document.getElementById('pending-visits').textContent = stats.pendingVisits || state.visits.filter(v => v.status === 'pending').length;
            document.getElementById('confirmed-bookings').textContent = stats.confirmedBookings || state.bookings.filter(b => b.status === 'confirmed').length;
            document.getElementById('upcoming-events').textContent = stats.upcomingEvents || state.bookings.filter(b => new Date(b.weddingDate) > new Date()).length;
        };

        function updateRecentActivities() {
            const recentVisitsDiv = document.getElementById('recent-visits');
            const upcomingWeddingsDiv = document.getElementById('upcoming-weddings');

            const pendingVisits = state.visits.filter(v => v.status === 'pending');
            if (pendingVisits.length > 0) {
                recentVisitsDiv.innerHTML = pendingVisits.slice(0, 3).map(visit => `
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="font-semibold text-gray-800">${visit.couple} - ${visit.date}</p>
                        <p class="text-sm text-gray-600">Notes: ${visit.notes || 'N/A'}</p>
                    </div>
                `).join('');
            } else {
                recentVisitsDiv.innerHTML = '<div class="text-gray-500 text-sm">No pending visit requests</div>';
            }

            const upcomingWeddings = state.bookings.filter(b => new Date(b.weddingDate) > new Date() && b.status === 'Confirmed');
            if (upcomingWeddings.length > 0) {
                upcomingWeddingsDiv.innerHTML = upcomingWeddings.slice(0, 3).map(booking => `
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="font-semibold text-gray-800">${booking.coupleName} - ${booking.weddingDate} (${booking.hall})</p>
                        <p class="text-sm text-gray-600">Guests: ${booking.guests}</p>
                    </div>
                `).join('');
            } else {
                upcomingWeddingsDiv.innerHTML = '<div class="text-gray-500 text-sm">No upcoming weddings</div>';
            }
        }

        // Visit Requests Management
        window.loadVisitRequests = function() {
            const tbody = document.getElementById('visits-table-body');
            if (state.visits.length > 0) {
                tbody.innerHTML = state.visits.map(visit => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${visit.couple}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${visit.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${visit.time}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">${visit.notes || 'No notes'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${visit.status === 'pending' ? 'yellow' : visit.status === 'approved' ? 'green' : 'red'}-100 text-${visit.status === 'pending' ? 'yellow' : visit.status === 'approved' ? 'green' : 'red'}-800">
                                ${visit.status.charAt(0).toUpperCase() + visit.status.slice(1)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            ${visit.status === 'pending' ? `
                            <button onclick="showVisitModal('${visit.couple}', '${visit.date}', '${visit.notes}', 'approve')" class="text-green-600 hover:text-green-900 mr-2">
                                Approve
                            </button>
                            <button onclick="showVisitModal('${visit.couple}', '${visit.date}', '${visit.notes}', 'reject')" class="text-red-600 hover:text-red-900">
                                Reject
                            </button>
                            ` : ''}
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No visit requests at this time</td></tr>';
            }
            updateDashboardStats({});
            updateRecentActivities();
        };

        document.getElementById('refresh-visits').addEventListener('click', () => {
            loadVisitRequests();
        });

        // Wedding Requests Management
        window.loadWeddingRequests = function() {
            const tbody = document.getElementById('wedding-requests-table-body');
            if (state.weddingRequests.length > 0) {
                tbody.innerHTML = state.weddingRequests.map(request => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${request.couple}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${request.weddingDate}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${request.timeSlot}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${request.hall}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${request.bookingId}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${request.status === 'pending' ? 'yellow' : request.status === 'approved' ? 'green' : 'red'}-100 text-${request.status === 'pending' ? 'yellow' : request.status === 'approved' ? 'green' : 'red'}-800">
                                ${request.status.charAt(0).toUpperCase() + request.status.slice(1)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            ${request.status === 'pending' ? `
                            <button onclick="approveWeddingRequest('${request.bookingId}')" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                            <button onclick="rejectWeddingRequest('${request.bookingId}')" class="text-red-600 hover:text-red-900">Reject</button>
                            ` : ''}
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No wedding date requests at this time</td></tr>';
            }
            updateDashboardStats({});
        };

        document.getElementById('bulk-approve').addEventListener('click', () => {
            if (confirm('Are you sure you want to approve all pending wedding date requests?')) {
                state.weddingRequests = state.weddingRequests.map(request => {
                    if (request.status === 'pending') {
                        state.bookings.push({
                            id: `BKG${String(state.bookings.length + 1).padStart(3, '0')}`,
                            coupleName: request.couple,
                            hall: request.hall,
                            weddingDate: request.weddingDate,
                            guests: Math.floor(Math.random() * (200 - 50 + 1)) + 50, // Random guests for new bookings
                            total: Math.floor(Math.random() * (1000000 - 300000 + 1)) + 300000, // Random total amount
                            paymentStatus: 'Pending',
                            status: 'Confirmed',
                            package: 'Standard'
                        });
                        state.calendarEvents.push({
                            date: request.weddingDate,
                            type: 'wedding',
                            status: 'confirmed'
                        });
                        return { ...request, status: 'approved' };
                    }
                    return request;
                });
                saveState();
                loadWeddingRequests();
                updateDashboardStats({});
                alert('All pending wedding requests approved!');
            }
        });

        window.approveWeddingRequest = function(bookingId) {
            if (confirm('Are you sure you want to approve this wedding request?')) {
                state.weddingRequests = state.weddingRequests.map(request => {
                    if (request.bookingId === bookingId && request.status === 'pending') {
                        state.bookings.push({
                            id: `BKG${String(state.bookings.length + 1).padStart(3, '0')}`,
                            coupleName: request.couple,
                            hall: request.hall,
                            weddingDate: request.weddingDate,
                            guests: Math.floor(Math.random() * (200 - 50 + 1)) + 50, // Random guests for new bookings
                            total: Math.floor(Math.random() * (1000000 - 300000 + 1)) + 300000, // Random total amount
                            paymentStatus: 'Pending',
                            status: 'Confirmed',
                            package: 'Standard'
                        });
                        state.calendarEvents.push({
                            date: request.weddingDate,
                            type: 'wedding',
                            status: 'confirmed'
                        });
                        return { ...request, status: 'approved' };
                    }
                    return request;
                });
                saveState();
                loadWeddingRequests();
                updateDashboardStats({});
                alert('Wedding request approved!');
            }
        };

        window.rejectWeddingRequest = function(bookingId) {
            if (confirm('Are you sure you want to reject this wedding request?')) {
                state.weddingRequests = state.weddingRequests.map(request =>
                    request.bookingId === bookingId ? { ...request, status: 'rejected' } : request
                );
                saveState();
                loadWeddingRequests();
                updateDashboardStats({});
                alert('Wedding request rejected.');
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹${booking.total.toFixed(2)}</td>
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
                            ${booking.paymentStatus === 'Pending' ? `<button class="text-purple-600 hover:text-purple-900" onclick="showPaymentModal('${booking.id}')">Record Payment</button>` : ''}
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
            // In a real application, this would trigger a backend process to generate and export a bill.
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
                    <p class="col-span-2 text-2xl font-bold mt-4">Total Amount: ₹${booking.total.toFixed(2)}</p>
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
    // Find the booking by ID
    const booking = state.bookings.find(b => b.id === bookingId);
    if (!booking) {
        alert('Booking not found');
        return;
    }
    
    // Set the booking ID in the form
    document.getElementById('booking-id').value = bookingId;
    
    // Set the form action URL with the booking ID
    paymentForm.action = paymentForm.action.replace('/0', `/${bookingId}`);
    
    // Set default amount to the total amount of the booking
    document.querySelector('input[name="amount"]').value = booking.total;
    
    // Show the modal
    paymentModal.classList.remove('hidden');
};

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
                if (state.calendarEvents.some(e => e.date === eventDate)) {
                    const event = document.createElement('div');
                    event.className = 'text-xs rounded px-1 mt-1';
                    const eventData = state.calendarEvents.find(e => e.date === eventDate);
                    event.className += eventData.type === 'visit' ? ' bg-yellow-400 text-gray-800' : eventData.type === 'wedding' ? ' bg-green-500 text-white' : ' bg-gray-400 text-white';
                    event.textContent = eventData.type.charAt(0).toUpperCase() + eventData.type.slice(1);
                    dayCell.appendChild(event);
                    if (eventData.type === 'wedding' || eventData.type === 'visit') {
                        dayCell.classList.add('cursor-not-allowed', 'bg-gray-100');
                    }
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

        let currentVisitData = null; // To store data of the current visit being processed

        window.showVisitModal = function(couple, date, notes, actionType) {
            currentVisitData = { couple, date, notes, actionType };
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

        approveBtn.addEventListener('click', () => {
            if (!validateModal()) return;
            if (confirm('Are you sure you want to approve this visit request?')) {
                const { couple, date } = currentVisitData;
                state.visits = state.visits.map(v =>
                    v.couple === couple && v.date === date ? { ...v, status: 'approved', notes: modalNotes.value.trim() } : v
                );
                state.calendarEvents.push({ date, type: 'visit', status: 'approved' });
                saveState();
                loadVisitRequests();
                visitModal.classList.add('hidden');
                alert('Visit request approved!');
            }
        });

        rejectBtn.addEventListener('click', () => {
            if (!validateModal()) return;
            if (confirm('Are you sure you want to reject this visit request?')) {
                const { couple, date } = currentVisitData;
                state.visits = state.visits.filter(v => v.date !== date);
                state.calendarEvents = state.calendarEvents.filter(e => e.date !== date);
                loadVisitRequests();
                visitModal.classList.add('hidden');
                saveState();
                alert('Visit request rejected.');
            }
        });

        // Hall Management
        const hallsGrid = document.getElementById('halls-grid');
        const hallEditModal = document.getElementById('hall-edit-modal');
        const saveHallBtn = document.getElementById('save-hall-btn');
        const cancelHallBtn = document.getElementById('cancel-hall-btn');

        function loadHalls() {
            hallsGrid.innerHTML = state.halls.map(hall => `
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="h-48 bg-gradient-to-r from-${hall.id === 'royal' ? 'blue' : hall.id === 'garden' ? 'green' : hall.id === 'crystal' ? 'purple' : 'yellow'}-400 to-${hall.id === 'royal' ? 'purple' : hall.id === 'garden' ? 'blue' : hall.id === 'crystal' ? 'pink' : 'orange'}-500 flex items-center justify-center">
                        <span class="text-white text-lg font-semibold">${hall.name}</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">${hall.name}</h3>
                        <p class="text-gray-600 mb-4">${hall.name} with premium decor</p>
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>Capacity: ${hall.capacity} guests</span>
                            <span class="text-${hall.availability === 'available' ? 'green' : 'red'}-600">${hall.availability.charAt(0).toUpperCase() + hall.availability.slice(1)}</span>
                        </div>
                        <div class="flex space-x-2">
                            <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="showHallEditModal('${hall.id}')">
                                Edit Details
                            </button>
                            <button class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                View Bookings
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
            updateDashboardStats({});
        }

        window.showHallEditModal = function(hallId) {
            const hall = state.halls.find(h => h.id === hallId);
            document.getElementById('edit-hall-name').value = hall.name;
            document.getElementById('edit-hall-capacity').value = hall.capacity;
            document.getElementById('edit-hall-availability').value = hall.availability;
            document.getElementById('edit-modal-title').dataset.hallId = hallId;
            hallEditModal.classList.remove('hidden');
        };

        saveHallBtn.addEventListener('click', () => {
            const hallId = document.getElementById('edit-modal-title').dataset.hallId;
            const name = document.getElementById('edit-hall-name').value.trim();
            const capacity = parseInt(document.getElementById('edit-hall-capacity').value);
            const availability = document.getElementById('edit-hall-availability').value;

            if (!name || isNaN(capacity) || capacity < 50 || capacity > 500) {
                alert('Please enter a valid hall name and capacity (50-500).');
                return;
            }

            state.halls = state.halls.map(h =>
                h.id === hallId ? { ...h, name, capacity, availability } : h
            );
            loadHalls();
            hallEditModal.classList.add('hidden');
            saveState();
            alert('Hall details updated successfully!');
        });

        cancelHallBtn.addEventListener('click', () => hallEditModal.classList.add('hidden'));

        // Load Section Content
        function loadSectionContent(section) {
            switch (section) {
                case 'halls': loadHalls(); break;
                case 'visits': loadVisitRequests(); break;
                case 'wedding-requests': loadWeddingRequests(); break;
                case 'bookings': loadBookings(); break;
                case 'calendar': updateCalendar(); break;
            }
        }

        // Initial Load
        showSection('dashboard');
        updateDashboardStats({});
        updateRecentActivities();

        // Simulate new data for demo purposes
        setInterval(() => {
            // Simulate a new visit request every 15 seconds
            if (Math.random() < 0.2) { // 20% chance
                const newVisit = {
                    couple: `Couple ${Math.floor(Math.random() * 1000)}`,
                    date: `2025-0${Math.floor(Math.random() * 3) + 7}-${String(Math.floor(Math.random() * 28) + 1).padStart(2, '0')}`,
                    time: `${String(Math.floor(Math.random() * (17 - 9 + 1)) + 9).padStart(2, '0')}:00`,
                    notes: 'Interested in booking a wedding hall for next year.',
                    status: 'pending'
                };
                state.visits.push(newVisit);
                saveState();
                if (pageTitle.textContent === pageTitles['visits'] || pageTitle.textContent === pageTitles['dashboard']) {
                    loadVisitRequests();
                    updateRecentActivities();
                }
                // alert('New visit request received!');
            }

            // Simulate a new wedding request every 20 seconds
            if (Math.random() < 0.1) { // 10% chance
                const newWeddingRequest = {
                    couple: `Couple ${Math.floor(Math.random() * 1000)} Wedding`,
                    weddingDate: `2026-0${Math.floor(Math.random() * 6) + 1}-${String(Math.floor(Math.random() * 28) + 1).padStart(2, '0')}`,
                    timeSlot: 'Evening',
                    hall: state.halls[Math.floor(Math.random() * state.halls.length)].name,
                    bookingId: `WED${String(Math.floor(Math.random() * 9999) + 1).padStart(4, '0')}`,
                    status: 'pending'
                };
                state.weddingRequests.push(newWeddingRequest);
                saveState();
                if (pageTitle.textContent === pageTitles['wedding-requests'] || pageTitle.textContent === pageTitles['dashboard']) {
                    loadWeddingRequests();
                    updateRecentActivities();
                }
                // alert('New wedding date request received!');
            }

            // Update dashboard stats every 1 second
            updateDashboardStats({
                totalHalls: state.halls.length,
                pendingVisits: state.visits.filter(v => v.status === 'pending').length,
                confirmedBookings: state.bookings.filter(b => b.status === 'confirmed').length,
                upcomingEvents: state.bookings.filter(b => new Date(b.weddingDate) > new Date()).length
            });
        }, 1000);
    });
</script>

</body>
</html>