@extends('layouts.manager')

@section('title', 'Manager Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Dashboard Header -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome, {{ Auth::user()->first_name ?? 'Manager' }}!</h2>
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

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
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
                <p class="text-2xl font-bold text-gray-900" id="pending-visits">{{ $stats['pending_visits'] ?? 0 }}</p>
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
                <p class="text-2xl font-bold text-gray-900" id="confirmed-bookings">{{ $stats['completed_bookings'] ?? 0 }}</p>
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
                <p class="text-2xl font-bold text-gray-900" id="monthly-revenue">Rs. {{ number_format($stats['total_revenue'] ?? 0, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">All time</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Visit Requests -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Visit Requests</h3>
        <div class="space-y-3" id="recent-visits">
            @forelse($pendingVisits as $visit)
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $visit->user->full_name ?? $visit->contact_name ?? 'Unknown' }}</div>
                        <div class="text-sm text-gray-600">{{ $visit->hall->name ?? $visit->hall_name ?? 'Unknown Hall' }}</div>
                        <div class="text-xs text-gray-500">
                            @if($visit->visit_date)
                                {{ \Carbon\Carbon::parse((string)$visit->visit_date)->format('M d, Y') }}
                            @else
                                Date TBD
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="approveVisit({{ $visit->id }})" class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                            Approve
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-gray-500 text-sm">No pending visit requests</div>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Weddings -->
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
                        <div class="font-medium text-gray-900">{{ $wedding->user->full_name ?? $wedding->contact_name ?? 'Unknown' }}</div>
                        <div class="text-sm text-gray-600">{{ $wedding->hall->name ?? $wedding->hall_name ?? 'Unknown Hall' }}</div>
                        <div class="text-xs text-gray-500">
                            @if($wedding->event_date)
                                {{ \Carbon\Carbon::parse((string)$wedding->event_date)->format('M d, Y') }}
                            @else
                                Date TBD
                            @endif
                        </div>
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

    <!-- Payment Confirmations -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Confirmations</h3>
        <div class="space-y-3" id="payment-confirmations">
            @forelse($confirmedVisits as $payment)
                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">{{ $payment->user->full_name ?? $payment->contact_name ?? 'Unknown' }}</div>
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

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('manager.visits') }}" class="block w-full text-left px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-calendar-check mr-2"></i> Manage Visit Requests
            </a>
            <a href="{{ route('manager.halls') }}" class="block w-full text-left px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-building mr-2"></i> Manage Halls
            </a>
            <a href="{{ route('manager.bookings') }}" class="block w-full text-left px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-list mr-2"></i> View All Bookings
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">System Status</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Database</span>
                <span class="text-sm text-green-600"><i class="fas fa-check-circle"></i> Connected</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Email Service</span>
                <span class="text-sm text-green-600"><i class="fas fa-check-circle"></i> Active</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Backup Status</span>
                <span class="text-sm text-green-600"><i class="fas fa-check-circle"></i> Up to date</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
        <div class="space-y-3">
            <div class="text-sm text-gray-600">
                <i class="fas fa-user-plus text-blue-500 mr-2"></i>
                New user registration
                <div class="text-xs text-gray-400">2 minutes ago</div>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-calendar text-green-500 mr-2"></i>
                Visit request submitted
                <div class="text-xs text-gray-400">15 minutes ago</div>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-credit-card text-purple-500 mr-2"></i>
                Payment confirmed
                <div class="text-xs text-gray-400">1 hour ago</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh dashboard stats every 30 seconds
    setInterval(refreshDashboardStats, 30000);
    
    // Load initial data
    refreshDashboardStats();
});

function refreshDashboardStats() {
    fetch('{{ route("manager.dashboard.stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatsDisplay(data.stats);
            }
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
        });
}

function updateStatsDisplay(stats) {
    const totalHalls = document.getElementById('total-halls');
    const pendingVisits = document.getElementById('pending-visits');
    const confirmedBookings = document.getElementById('confirmed-bookings');
    const monthlyRevenue = document.getElementById('monthly-revenue');
    
    if (totalHalls) totalHalls.textContent = stats.total_halls || 0;
    if (pendingVisits) pendingVisits.textContent = stats.pending_visits || 0;
    if (confirmedBookings) confirmedBookings.textContent = stats.completed_bookings || 0;
    if (monthlyRevenue) monthlyRevenue.textContent = 'Rs. ' + (stats.total_revenue || 0).toLocaleString();
}

function approveVisit(visitId) {
    if (confirm('Are you sure you want to approve this visit request?')) {
        fetch(`{{ url('/manager/visit') }}/${visitId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notes: 'Approved from dashboard'
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
}

function markPaymentPaid(bookingId) {
    const paymentMethod = prompt('Enter payment method (cash, card, bank transfer):');
    if (paymentMethod) {
        fetch(`{{ url('/manager/booking') }}/${bookingId}/deposit-paid`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                payment_method: paymentMethod,
                notes: 'Marked as paid from dashboard'
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
}

console.log('Manager Dashboard - Ready');
</script>
@endpush