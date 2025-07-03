@extends('layouts.app')

@section('title', 'Visit Schedules')

@section('content')
<div class="w-full" style="background-color: #200b01f6; height: 6rem;"></div>

<div class="container mx-auto py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Visit Schedules</h1>
            <p class="text-gray-600 mt-2">Manage all visit requests and confirmations</p>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="filterBookings('all')" 
                            class="filter-tab active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        All Visits
                    </button>
                    <button onclick="filterBookings('pending')" 
                            class="filter-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Pending Approval
                    </button>
                    <button onclick="filterBookings('confirmed')" 
                            class="filter-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Confirmed
                    </button>
                    <button onclick="filterBookings('payment_pending')" 
                            class="filter-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Payment Pending
                    </button>
                    <button onclick="filterBookings('completed')" 
                            class="filter-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Completed
                    </button>
                </nav>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Visit Requests</h2>
            </div>
            
            @if($bookings->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <div class="booking-item p-6 hover:bg-gray-50" 
                             data-status="{{ $booking->getVisitStatus() }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-semibold text-sm">
                                                    @if($booking->user && $booking->user->first_name && $booking->user->last_name)
                                                        {{ substr($booking->user->first_name, 0, 1) }}{{ substr($booking->user->last_name, 0, 1) }}
                                                    @elseif($booking->user && $booking->user->name)
                                                        {{ substr($booking->user->name, 0, 2) }}
                                                    @elseif($booking->contact_name)
                                                        {{ substr($booking->contact_name, 0, 2) }}
                                                    @else
                                                        ??
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    {{ $booking->user->full_name ?? $booking->user->name ?? $booking->contact_name ?? 'Unknown Customer' }}
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if(!$booking->visit_confirmed && $booking->visit_submitted)
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif($booking->visit_confirmed && !$booking->advance_payment_paid)
                                                        bg-blue-100 text-blue-800
                                                    @elseif($booking->advance_payment_paid)
                                                        bg-green-100 text-green-800
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif
                                                ">
                                                    @if(!$booking->visit_confirmed && $booking->visit_submitted)
                                                        Pending Approval
                                                    @elseif($booking->visit_confirmed && !$booking->advance_payment_paid)
                                                        Payment Pending
                                                    @elseif($booking->advance_payment_paid)
                                                        Completed
                                                    @else
                                                        Draft
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                <span>{{ $booking->hall->name ?? $booking->hall_name }}</span>
                                                <span>•</span>
                                                <span>{{ $booking->package->name ?? 'Package ID: ' . $booking->package_id }}</span>
                                                <span>•</span>
                                                <span>{{ $booking->guest_count ?? $booking->customization_guest_count }} guests</span>
                                            </div>
                                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Visit: {{ $booking->visit_date ? $booking->visit_date->format('M d, Y') : 'Not set' }}
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $booking->visit_time ?? 'Not set' }}
                                                </div>
                                                @if($booking->advance_payment_amount)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                        </svg>
                                                        Advance: Rs. {{ number_format($booking->advance_payment_amount, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                            @if($booking->visit_confirmation_notes)
                                                <div class="mt-2 text-sm text-gray-600">
                                                    <strong>Notes:</strong> {{ $booking->visit_confirmation_notes }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    @if($booking->visit_submitted && !$booking->visit_confirmed)
                                        <!-- Pending approval actions -->
                                        <button onclick="approveVisit({{ $booking->id }})" 
                                                class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                            Approve
                                        </button>
                                        <button onclick="rejectVisit({{ $booking->id }})" 
                                                class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                            Reject
                                        </button>
                                    @elseif($booking->visit_confirmed && !$booking->advance_payment_paid)
                                        <!-- Payment pending actions -->
                                        <button onclick="markPaymentPaid({{ $booking->id }})" 
                                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                            Mark Paid
                                        </button>
                                    @elseif($booking->advance_payment_paid)
                                        <!-- Completed -->
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded">
                                            ✓ Completed
                                        </span>
                                    @endif
                                    
                                    <button onclick="viewDetails({{ $booking->id }})" 
                                            class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                                        Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No visit requests</h3>
                    <p class="mt-1 text-sm text-gray-500">No visit requests have been submitted yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include the same modals from dashboard -->
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

<!-- View Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full p-6 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Booking Details</h3>
                <button onclick="closeModal('detailsModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="detailsContent">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
let currentBookingId = null;

function filterBookings(status) {
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
    event.target.classList.remove('border-transparent', 'text-gray-500');

    // Filter booking items
    document.querySelectorAll('.booking-item').forEach(item => {
        const itemStatus = item.dataset.status;
        if (status === 'all' || itemStatus === status) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

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

function viewDetails(bookingId) {
    currentBookingId = bookingId;
    
    // Load booking details
    fetch(`/manager/visit/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('detailsContent').innerHTML = generateDetailsHTML(data);
            document.getElementById('detailsModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load booking details.');
        });
}

function generateDetailsHTML(booking) {
    return `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-900">Customer Information</h4>
                    <p class="text-sm text-gray-600">Name: ${booking.user?.full_name || booking.user?.name || booking.contact_name || 'Unknown'}</p>
                    <p class="text-sm text-gray-600">Email: ${booking.contact_email || booking.user?.email || 'Not provided'}</p>
                    <p class="text-sm text-gray-600">Phone: ${booking.contact_phone || 'Not provided'}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Event Details</h4>
                    <p class="text-sm text-gray-600">Hall: ${booking.hall ? booking.hall.name : booking.hall_name}</p>
                    <p class="text-sm text-gray-600">Package: ${booking.package ? booking.package.name : 'Package ID: ' + booking.package_id}</p>
                    <p class="text-sm text-gray-600">Guests: ${booking.guest_count || booking.customization_guest_count}</p>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold text-gray-900">Visit Information</h4>
                <p class="text-sm text-gray-600">Date: ${booking.visit_date || 'Not set'}</p>
                <p class="text-sm text-gray-600">Time: ${booking.visit_time || 'Not set'}</p>
                <p class="text-sm text-gray-600">Purpose: ${booking.visit_purpose || 'Not specified'}</p>
                ${booking.special_requests ? `<p class="text-sm text-gray-600">Special Requests: ${booking.special_requests}</p>` : ''}
            </div>
            
            ${booking.advance_payment_amount ? `
            <div>
                <h4 class="font-semibold text-gray-900">Payment Information</h4>
                <p class="text-sm text-gray-600">Advance Amount: Rs. ${parseFloat(booking.advance_payment_amount).toLocaleString()}</p>
                <p class="text-sm text-gray-600">Status: ${booking.advance_payment_paid ? 'Paid' : 'Pending'}</p>
                ${booking.advance_payment_method ? `<p class="text-sm text-gray-600">Method: ${booking.advance_payment_method}</p>` : ''}
            </div>
            ` : ''}
            
            ${booking.visit_confirmation_notes ? `
            <div>
                <h4 class="font-semibold text-gray-900">Manager Notes</h4>
                <p class="text-sm text-gray-600">${booking.visit_confirmation_notes}</p>
            </div>
            ` : ''}
        </div>
    `;
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    currentBookingId = null;
}

// Handle form submissions (same as dashboard)
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

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-gray-600')) {
        const modals = ['approveModal', 'rejectModal', 'paymentModal', 'detailsModal'];
        modals.forEach(modalId => {
            if (!document.getElementById(modalId).classList.contains('hidden')) {
                closeModal(modalId);
            }
        });
    }
}); 
</script>

<style>
.filter-tab.active {
    border-bottom-color: #3b82f6 !important;
    color: #2563eb !important;
}
</style>
@endsection