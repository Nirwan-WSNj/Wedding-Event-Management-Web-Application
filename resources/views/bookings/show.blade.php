@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="w-full" style="background-color: #200b01f6; height: 6rem;"></div>

<section class="py-12 bg-gradient-to-b from-[#fef8f5] to-white min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-4xl font-playfair font-bold text-[#333] mb-2">Booking Details</h2>
                <p class="text-gray-600">Complete information about your wedding booking</p>
            </div>
            <a href="{{ route('bookings.my') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-300">
                ← Back to My Bookings
            </a>
        </div>

        @if(isset($booking))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Booking Overview -->
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">Booking Overview</h3>
                            @php
                                $statusConfig = [
                                    'pending' => ['bg-yellow-100', 'text-yellow-800', 'Pending Review'],
                                    'confirmed' => ['bg-green-100', 'text-green-800', 'Confirmed'],
                                    'cancelled' => ['bg-red-100', 'text-red-800', 'Cancelled'],
                                    'completed' => ['bg-blue-100', 'text-blue-800', 'Completed'],
                                ];
                                $config = $statusConfig[$booking->status] ?? ['bg-gray-100', 'text-gray-800', ucfirst($booking->status)];
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $config[0] }} {{ $config[1] }}">
                                {{ $config[2] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Basic Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Booking ID:</span>
                                        <span class="font-medium">#{{ $booking->id }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Created:</span>
                                        <span class="font-medium">{{ $booking->created_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Hall:</span>
                                        <span class="font-medium">{{ $booking->hall->name ?? 'Not Selected' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Package:</span>
                                        <span class="font-medium">{{ $booking->package->name ?? 'Not Selected' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Event Details</h4>
                                <div class="space-y-2 text-sm">
                                    @if($booking->wedding_date)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Wedding Date:</span>
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($booking->wedding_date)->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                    @if($booking->event_date)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Event Date:</span>
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                    @if($booking->start_time && $booking->end_time)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Time:</span>
                                            <span class="font-medium">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($booking->guest_count || $booking->customization_guest_count)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Guests:</span>
                                            <span class="font-medium">{{ $booking->guest_count ?? $booking->customization_guest_count }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Couple Information -->
                    @if($booking->wedding_groom_name || $booking->wedding_bride_name)
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Couple Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Groom -->
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        Groom
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Name:</span>
                                            <span class="font-medium">{{ $booking->wedding_groom_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Email:</span>
                                            <span class="font-medium">{{ $booking->wedding_groom_email ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Phone:</span>
                                            <span class="font-medium">{{ $booking->wedding_groom_phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bride -->
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        Bride
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Name:</span>
                                            <span class="font-medium">{{ $booking->wedding_bride_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Email:</span>
                                            <span class="font-medium">{{ $booking->wedding_bride_email ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Phone:</span>
                                            <span class="font-medium">{{ $booking->wedding_bride_phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Contact Name:</span>
                                    <span class="font-medium">{{ $booking->contact_name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ $booking->contact_email ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phone:</span>
                                    <span class="font-medium">{{ $booking->contact_phone ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="space-y-2 text-sm">
                                @if($booking->visit_date)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Visit Date:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($booking->visit_date)->format('M d, Y') }}</span>
                                    </div>
                                @endif
                                @if($booking->visit_time)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Visit Time:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($booking->visit_time)->format('g:i A') }}</span>
                                    </div>
                                @endif
                                @if($booking->visit_purpose)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Visit Purpose:</span>
                                        <span class="font-medium">{{ $booking->visit_purpose }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($booking->special_requests)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="font-semibold text-gray-700 mb-2">Special Requests</h4>
                                <p class="text-sm text-gray-600 bg-gray-50 p-4 rounded-lg">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Customizations -->
                    @if($booking->customization_decorations_additional || $booking->customization_catering_custom || $booking->customization_additional_services_selected)
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Customizations</h3>
                            
                            @if($booking->customization_decorations_additional)
                                <div class="mb-6">
                                    <h4 class="font-semibold text-gray-700 mb-3">Additional Decorations</h4>
                                    @php
                                        $decorations = is_string($booking->customization_decorations_additional) 
                                            ? json_decode($booking->customization_decorations_additional, true) 
                                            : $booking->customization_decorations_additional;
                                    @endphp
                                    @if(is_array($decorations) && !empty($decorations))
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($decorations as $decorationId)
                                                @php
                                                    $decoration = \App\Models\Decoration::find($decorationId);
                                                @endphp
                                                @if($decoration)
                                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                        <span class="text-sm font-medium">{{ $decoration->name }}</span>
                                                        <span class="text-sm text-gray-600">Rs. {{ number_format($decoration->price, 2) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">No additional decorations selected</p>
                                    @endif
                                </div>
                            @endif

                            @if($booking->customization_additional_services_selected)
                                <div class="mb-6">
                                    <h4 class="font-semibold text-gray-700 mb-3">Additional Services</h4>
                                    @php
                                        $services = is_string($booking->customization_additional_services_selected) 
                                            ? json_decode($booking->customization_additional_services_selected, true) 
                                            : $booking->customization_additional_services_selected;
                                    @endphp
                                    @if(is_array($services) && !empty($services))
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($services as $serviceId)
                                                @php
                                                    $service = \App\Models\AdditionalService::find($serviceId);
                                                @endphp
                                                @if($service)
                                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                        <span class="text-sm font-medium">{{ $service->name }}</span>
                                                        <span class="text-sm text-gray-600">Rs. {{ number_format($service->price, 2) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">No additional services selected</p>
                                    @endif
                                </div>
                            @endif

                            @if($booking->customization_catering_custom)
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-3">Custom Catering Items</h4>
                                    @php
                                        $catering = is_string($booking->customization_catering_custom) 
                                            ? json_decode($booking->customization_catering_custom, true) 
                                            : $booking->customization_catering_custom;
                                    @endphp
                                    @if(is_array($catering) && !empty($catering))
                                        @foreach($catering as $category => $items)
                                            @if(is_array($items) && !empty($items))
                                                <div class="mb-4">
                                                    <h5 class="font-medium text-gray-600 mb-2">{{ $category }}</h5>
                                                    <div class="space-y-2">
                                                        @foreach($items as $item)
                                                            @if(is_array($item) && isset($item['name']))
                                                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                                                    <span class="text-sm">{{ $item['name'] }}</span>
                                                                    <span class="text-sm text-gray-600">Rs. {{ number_format($item['price'] ?? 0, 2) }}</span>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500">No custom catering items selected</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Pricing Summary -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Pricing Summary</h3>
                        <div class="space-y-3">
                            @if($booking->package_price)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Package Price:</span>
                                    <span class="font-medium">Rs. {{ number_format($booking->package_price, 2) }}</span>
                                </div>
                            @endif
                            
                            @if($booking->hall && $booking->hall->price)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Hall Charge:</span>
                                    <span class="font-medium">Rs. {{ number_format($booking->hall->price, 2) }}</span>
                                </div>
                            @endif

                            @if($booking->total_amount)
                                <div class="border-t border-gray-200 pt-3 mt-3">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-900">Total Amount:</span>
                                        <span class="font-bold text-xl text-blue-600">Rs. {{ number_format($booking->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            @if($booking->status === 'pending')
                                <a href="{{ route('bookings.edit', $booking->id) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Booking
                                </a>
                            @endif

                            <button onclick="window.print()" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Print Details
                            </button>

                            @if(in_array($booking->status, ['pending', 'visit_requested']))
                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Cancel Booking
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Booking Timeline</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-1"></div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">Booking Created</p>
                                    <p class="text-xs text-gray-500">{{ $booking->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            
                            @if($booking->status !== 'pending')
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-1"></div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Status Updated</p>
                                        <p class="text-xs text-gray-500">{{ $booking->updated_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($booking->status === 'confirmed')
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-3 h-3 bg-green-600 rounded-full mt-1"></div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">Booking Confirmed</p>
                                        <p class="text-xs text-gray-500">Ready for your event!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="text-red-600 mb-4">
                    <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Booking Not Found</h3>
                <p class="text-gray-600 mb-6">The booking you're looking for doesn't exist or you don't have permission to view it.</p>
                <a href="{{ route('bookings.my') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-300">
                    ← Back to My Bookings
                </a>
            </div>
        @endif
    </div>
</section>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
        .shadow-xl { box-shadow: none !important; }
    }
</style>
@endsection