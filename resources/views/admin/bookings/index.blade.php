@extends('layouts.admin')

@section('title', 'Booking Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Booking Management</h1>
            <p class="text-gray-600 mt-1">Manage all wedding bookings and reservations</p>
        </div>
        <div class="flex gap-3">
            <button onclick="exportBookings()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="ri-download-line"></i> Export
            </button>
            <button onclick="refreshBookings()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="ri-refresh-line"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-600">Total Bookings</p>
                </div>
                <i class="ri-calendar-line text-2xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['pending'] }}</p>
                    <p class="text-xs text-gray-600">Pending</p>
                </div>
                <i class="ri-time-line text-2xl text-yellow-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['confirmed'] }}</p>
                    <p class="text-xs text-gray-600">Confirmed</p>
                </div>
                <i class="ri-check-line text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['cancelled'] }}</p>
                    <p class="text-xs text-gray-600">Cancelled</p>
                </div>
                <i class="ri-close-line text-2xl text-red-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-gray-800">Rs. {{ number_format($stats['total_revenue']) }}</p>
                    <p class="text-xs text-gray-600">Total Revenue</p>
                </div>
                <i class="ri-money-dollar-circle-line text-2xl text-purple-500"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filters</h3>
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hall</label>
                <select name="hall_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Halls</option>
                    @foreach($halls as $hall)
                        <option value="{{ $hall->id }}" {{ request('hall_id') == $hall->id ? 'selected' : '' }}>
                            {{ $hall->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search bookings..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="md:col-span-5 flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Apply Filters
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Booking Records</h3>
        </div>
        
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Booking ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hall
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->contact_name ?? ($booking->user->full_name ?? 'N/A') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->contact_email ?? ($booking->user->email ?? 'N/A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $booking->hall->name ?? $booking->hall_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $booking->event_date ? $booking->event_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rs. {{ number_format($booking->total_amount ?? 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.bookings.details', $booking->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        <button onclick="updateStatus({{ $booking->id }}, '{{ $booking->status }}')" 
                                                class="text-green-600 hover:text-green-900">Update</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="ri-calendar-line text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings found</h3>
                <p class="text-gray-500">No bookings match your current filters.</p>
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Booking Status</h3>
        <form id="statusForm">
            @csrf
            <input type="hidden" id="bookingId" name="booking_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusSelect" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add any notes about this status change..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateStatus(bookingId, currentStatus) {
    document.getElementById('bookingId').value = bookingId;
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const bookingId = document.getElementById('bookingId').value;
    const formData = new FormData(this);
    
    fetch(`/admin/bookings/${bookingId}/status`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status: formData.get('status'),
            notes: formData.get('notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Booking status updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status.');
    });
    
    closeStatusModal();
});

function exportBookings() {
    // Add export functionality here
    alert('Export functionality will be implemented');
}

function refreshBookings() {
    location.reload();
}
</script>
@endsection