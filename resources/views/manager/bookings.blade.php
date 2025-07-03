@extends('layouts.manager')
@section('title', 'Bookings Tracker')
@section('page-title', 'All Bookings')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-list text-white text-sm"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-white text-sm"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_bookings'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Confirmed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['confirmed_bookings'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-sm"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">This Month Revenue</p>
                <p class="text-2xl font-bold text-gray-900">Rs. {{ number_format($stats['revenue_this_month'] ?? 0, 0) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">All Bookings</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hall</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->user->name ?? $booking->contact_name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->contact_email ?? $booking->user->email ?? 'No email' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $booking->hall->name ?? $booking->hall_name ?? 'Unknown Hall' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $booking->event_date ? $booking->event_date->format('M d, Y') : ($booking->wedding_date ? $booking->wedding_date->format('M d, Y') : 'Not set') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$booking->visit_submitted)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @elseif($booking->visit_submitted && !$booking->visit_confirmed)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending Visit
                                </span>
                            @elseif($booking->visit_confirmed && !$booking->advance_payment_paid)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Payment Pending
                                </span>
                            @elseif($booking->advance_payment_paid)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Unknown
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rs. {{ number_format($booking->advance_payment_amount ?? $booking->package_price ?? 0, 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($booking->visit_submitted && !$booking->visit_confirmed)
                                    <button onclick="approveVisit({{ $booking->id }})" class="text-green-600 hover:text-green-900">
                                        Approve Visit
                                    </button>
                                @endif
                                @if($booking->visit_confirmed && !$booking->advance_payment_paid)
                                    <button onclick="markPaymentPaid({{ $booking->id }})" class="text-blue-600 hover:text-blue-900">
                                        Mark Paid
                                    </button>
                                @endif
                                <button onclick="viewBookingDetails({{ $booking->id }})" class="text-indigo-600 hover:text-indigo-900">
                                    View Details
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No bookings found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function approveVisit(bookingId) {
    if (confirm('Are you sure you want to approve this visit request?')) {
        fetch(`{{ url('/manager/visit') }}/${bookingId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notes: 'Approved from bookings page'
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
                notes: 'Marked as paid from bookings page'
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

function viewBookingDetails(bookingId) {
    // For now, just show an alert. In a full implementation, you'd open a modal or navigate to a details page
    alert('Booking details for ID: ' + bookingId + '\n\nThis would open a detailed view of the booking.');
}
</script>
@endpush
