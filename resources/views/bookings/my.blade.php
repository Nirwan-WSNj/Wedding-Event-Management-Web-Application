@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="w-full" style="background-color: #200b01f6; height: 6rem;"></div>

<section class="py-12 bg-gradient-to-b from-[#fef8f5] to-white min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-4xl font-playfair font-bold text-[#333] mb-2">My Bookings</h2>
                <p class="text-gray-600">Manage your wedding event reservations and track their progress.</p>
            </div>
            <a href="{{ route('booking') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-300">
                + New Booking
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Bookings Content -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            @if($bookings->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16 px-8">
                    <div class="mb-6">
                        <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6-4h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">No Bookings Yet</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">
                        You haven't made any wedding bookings yet. Start planning your dream wedding by creating your first booking.
                    </p>
                    <a href="{{ route('booking') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Your First Booking
                    </a>
                </div>
            @else
                <!-- Bookings Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Booking Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Event Info</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <!-- Booking Details -->
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900">
                                                Booking #{{ $booking->id }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $booking->hall->name ?? 'Hall Not Selected' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $booking->package->name ?? 'Package Not Selected' }}
                                            </p>
                                            @if($booking->hall_booking_date)
                                                <p class="text-xs text-gray-500">
                                                    Booked: {{ \Carbon\Carbon::parse($booking->hall_booking_date)->format('M d, Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Event Info -->
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        @if($booking->wedding_groom_name && $booking->wedding_bride_name)
                                            <p class="font-medium text-gray-900">
                                                {{ $booking->wedding_groom_name }} & {{ $booking->wedding_bride_name }}
                                            </p>
                                        @elseif($booking->contact_name)
                                            <p class="font-medium text-gray-900">{{ $booking->contact_name }}</p>
                                        @else
                                            <p class="font-medium text-gray-900">{{ $booking->user->first_name ?? 'Unknown' }} {{ $booking->user->last_name ?? '' }}</p>
                                        @endif
                                        
                                        @if($booking->wedding_date)
                                            <p class="text-gray-600">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6-4h6"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($booking->wedding_date)->format('M d, Y') }}
                                            </p>
                                        @elseif($booking->event_date)
                                            <p class="text-gray-600">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6-4h6"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}
                                            </p>
                                        @endif
                                        
                                        @if($booking->start_time && $booking->end_time)
                                            <p class="text-gray-500 text-xs">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                            </p>
                                        @endif
                                        
                                        @if($booking->guest_count || $booking->customization_guest_count)
                                            <p class="text-gray-500 text-xs">
                                                {{ $booking->guest_count ?? $booking->customization_guest_count }} guests
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-2">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg-yellow-100', 'text-yellow-800', 'Pending Review'],
                                                'confirmed' => ['bg-green-100', 'text-green-800', 'Confirmed'],
                                                'cancelled' => ['bg-red-100', 'text-red-800', 'Cancelled'],
                                                'completed' => ['bg-blue-100', 'text-blue-800', 'Completed'],
                                                'visit_requested' => ['bg-orange-100', 'text-orange-800', 'Visit Requested'],
                                                'visit_approved' => ['bg-purple-100', 'text-purple-800', 'Visit Approved'],
                                                'deposit_paid' => ['bg-indigo-100', 'text-indigo-800', 'Deposit Paid'],
                                            ];
                                            $config = $statusConfig[$booking->status] ?? ['bg-gray-100', 'text-gray-800', ucfirst($booking->status)];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }} {{ $config[1] }}">
                                            {{ $config[2] }}
                                        </span>
                                        
                                        <!-- Next Steps -->
                                        <div class="text-xs text-gray-500">
                                            @switch($booking->status)
                                                @case('pending')
                                                    Awaiting confirmation
                                                    @break
                                                @case('visit_requested')
                                                    Awaiting manager approval
                                                    @break
                                                @case('visit_approved')
                                                    Visit and pay deposit
                                                    @break
                                                @case('deposit_paid')
                                                    Complete booking details
                                                    @break
                                                @case('confirmed')
                                                    All set for your event!
                                                    @break
                                                @default
                                                    -
                                            @endswitch
                                        </div>
                                    </div>
                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        @if($booking->total_amount)
                                            <p class="font-semibold text-gray-900">
                                                Rs. {{ number_format($booking->total_amount, 2) }}
                                            </p>
                                        @elseif($booking->package_price)
                                            <p class="font-semibold text-gray-900">
                                                Rs. {{ number_format($booking->package_price, 2) }}
                                            </p>
                                            <p class="text-xs text-gray-500">Base package</p>
                                        @else
                                            <p class="text-gray-500">-</p>
                                        @endif
                                        
                                        <!-- Customizations indicator -->
                                        @if($booking->customization_decorations_additional || $booking->customization_catering_custom || $booking->customization_additional_services_selected)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                                Customized
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- View Details -->
                                        <a href="{{ route('bookings.show', $booking->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>

                                        <!-- Edit (only for pending bookings) -->
                                        @if($booking->status === 'pending')
                                            <a href="{{ route('bookings.edit', $booking->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                        @endif

                                        <!-- Cancel/Delete -->
                                        @if(in_array($booking->status, ['pending', 'visit_requested']))
                                            <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination if needed -->
                @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Summary Cards -->
        @if(!$bookings->isEmpty())
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $totalBookings = $bookings->count();
                    $pendingBookings = $bookings->where('status', 'pending')->count();
                    $confirmedBookings = $bookings->where('status', 'confirmed')->count();
                    $totalAmount = $bookings->sum('total_amount') ?: $bookings->sum('package_price');
                @endphp

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $pendingBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Confirmed</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $confirmedBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Value</p>
                            <p class="text-2xl font-semibold text-gray-900">Rs. {{ number_format($totalAmount, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<script>
    // Add any JavaScript for enhanced functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh status every 30 seconds for pending bookings
        const pendingBookings = document.querySelectorAll('[data-status="pending"]');
        if (pendingBookings.length > 0) {
            setInterval(() => {
                // You can implement AJAX refresh here if needed
                console.log('Checking for booking updates...');
            }, 30000);
        }
    });
</script>
@endsection