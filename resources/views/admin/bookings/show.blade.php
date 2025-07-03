@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Booking Details</h1>
            <p class="text-gray-600 mt-1">Booking #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.bookings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="ri-arrow-left-line"></i> Back to Bookings
            </a>
            <button onclick="updateStatus({{ $booking->id }}, '{{ $booking->status }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="ri-edit-line"></i> Update Status
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="ri-user-line mr-2"></i> Customer Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Name</label>
                        <p class="text-gray-900">{{ $booking->contact_name ?? ($booking->user->full_name ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-gray-900">{{ $booking->contact_email ?? ($booking->user->email ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Phone</label>
                        <p class="text-gray-900">{{ $booking->contact_phone ?? ($booking->user->phone ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">User Role</label>
                        <p class="text-gray-900">{{ $booking->user->role ?? 'Guest' }}</p>
                    </div>
                </div>
            </div>

            <!-- Wedding Details -->
            @if($booking->wedding_groom_name || $booking->wedding_bride_name)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="ri-heart-line mr-2"></i> Wedding Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($booking->wedding_groom_name)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Groom</label>
                        <p class="text-gray-900">{{ $booking->wedding_groom_name }}</p>
                        @if($booking->wedding_groom_email)
                            <p class="text-sm text-gray-500">{{ $booking->wedding_groom_email }}</p>
                        @endif
                        @if($booking->wedding_groom_phone)
                            <p class="text-sm text-gray-500">{{ $booking->wedding_groom_phone }}</p>
                        @endif
                    </div>
                    @endif
                    @if($booking->wedding_bride_name)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Bride</label>
                        <p class="text-gray-900">{{ $booking->wedding_bride_name }}</p>
                        @if($booking->wedding_bride_email)
                            <p class="text-sm text-gray-500">{{ $booking->wedding_bride_email }}</p>
                        @endif
                        @if($booking->wedding_bride_phone)
                            <p class="text-sm text-gray-500">{{ $booking->wedding_bride_phone }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Event Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="ri-calendar-event-line mr-2"></i> Event Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Hall</label>
                        <p class="text-gray-900">{{ $booking->hall->name ?? $booking->hall_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Package</label>
                        <p class="text-gray-900">{{ $booking->package->name ?? 'Custom Package' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Event Date</label>
                        <p class="text-gray-900">{{ $booking->event_date ? $booking->event_date->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Guest Count</label>
                        <p class="text-gray-900">{{ $booking->guest_count ?? $booking->customization_guest_count ?? 'N/A' }}</p>
                    </div>
                    @if($booking->start_time)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Start Time</label>
                        <p class="text-gray-900">{{ $booking->start_time }}</p>
                    </div>
                    @endif
                    @if($booking->end_time)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">End Time</label>
                        <p class="text-gray-900">{{ $booking->end_time }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Visit Information -->
            @if($booking->visit_submitted)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="ri-map-pin-line mr-2"></i> Visit Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Visit Date</label>
                        <p class="text-gray-900">{{ $booking->visit_date ? \Carbon\Carbon::parse($booking->visit_date)->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Visit Time</label>
                        <p class="text-gray-900">{{ $booking->visit_time ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Visit Status</label>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($booking->visit_confirmed) bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $booking->visit_confirmed ? 'Confirmed' : 'Pending' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Visit Purpose</label>
                        <p class="text-gray-900">{{ $booking->visit_purpose ?? 'N/A' }}</p>
                    </div>
                    @if($booking->visit_confirmation_notes)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600">Manager Notes</label>
                        <p class="text-gray-900">{{ $booking->visit_confirmation_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Special Requests -->
            @if($booking->special_requests || $booking->wedding_additional_notes)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="ri-message-line mr-2"></i> Special Requests & Notes
                </h3>
                @if($booking->special_requests)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600">Special Requests</label>
                    <p class="text-gray-900">{{ $booking->special_requests }}</p>
                </div>
                @endif
                @if($booking->wedding_additional_notes)
                <div>
                    <label class="block text-sm font-medium text-gray-600">Additional Notes</label>
                    <p class="text-gray-900">{{ $booking->wedding_additional_notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status & Actions</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Current Status</label>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($booking->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Created</label>
                        <p class="text-gray-900">{{ $booking->created_at->format('F d, Y g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                        <p class="text-gray-900">{{ $booking->updated_at->format('F d, Y g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Total Amount</label>
                        <p class="text-xl font-bold text-gray-900">Rs. {{ number_format($booking->total_amount ?? 0) }}</p>
                    </div>
                    @if($booking->advance_payment_amount)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Advance Payment</label>
                        <p class="text-lg font-semibold text-gray-900">Rs. {{ number_format($booking->advance_payment_amount) }}</p>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($booking->advance_payment_paid) bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $booking->advance_payment_paid ? 'Paid' : 'Pending' }}
                        </span>
                    </div>
                    @endif
                    @if($booking->advance_payment_paid && $booking->advance_payment_paid_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Payment Date</label>
                        <p class="text-gray-900">{{ $booking->advance_payment_paid_at->format('F d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Services -->
            @if($booking->bookingDecorations->count() > 0 || $booking->bookingAdditionalServices->count() > 0 || $booking->bookingCatering->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Selected Services</h3>
                
                @if($booking->bookingDecorations->count() > 0)
                <div class="mb-4">
                    <h4 class="font-medium text-gray-700 mb-2">Decorations</h4>
                    <ul class="space-y-1">
                        @foreach($booking->bookingDecorations as $decoration)
                            <li class="text-sm text-gray-600">• {{ $decoration->decoration->name ?? 'N/A' }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($booking->bookingAdditionalServices->count() > 0)
                <div class="mb-4">
                    <h4 class="font-medium text-gray-700 mb-2">Additional Services</h4>
                    <ul class="space-y-1">
                        @foreach($booking->bookingAdditionalServices as $service)
                            <li class="text-sm text-gray-600">• {{ $service->service->name ?? 'N/A' }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($booking->bookingCatering->count() > 0)
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Catering</h4>
                    <ul class="space-y-1">
                        @foreach($booking->bookingCatering as $catering)
                            <li class="text-sm text-gray-600">• {{ $catering->menu->name ?? 'N/A' }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Booking Status</h3>
        <form id="statusForm">
            @csrf
            <input type="hidden" id="bookingId" name="booking_id" value="{{ $booking->id }}">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusSelect" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
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
</script>
@endsection