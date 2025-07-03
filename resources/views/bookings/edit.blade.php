@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<section class="py-24 bg-gradient-to-b from-[#fef8f5] to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-[#333] mb-6">Edit Booking</h2>
        <div class="bg-white shadow-md rounded-xl p-8 max-w-lg mx-auto">
            <form method="POST" action="{{ route('bookings.update', $booking->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Event Date</label>
                    <input type="date" name="event_date" value="{{ old('event_date', $booking->event_date) }}" class="form-input w-full" required>
                    @error('event_date')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Hall Name</label>
                    <input type="text" name="hall_name" value="{{ old('hall_name', $booking->hall_name) }}" class="form-input w-full" required>
                    @error('hall_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Package Price</label>
                    <input type="number" step="0.01" name="package_price" value="{{ old('package_price', $booking->package_price) }}" class="form-input w-full" required>
                    @error('package_price')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Guest Count</label>
                    <input type="number" name="guest_count" value="{{ old('guest_count', $booking->guest_count) }}" class="form-input w-full" required>
                    @error('guest_count')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Customization Decorations (JSON)</label>
                    <textarea name="customization_decorations_additional" class="form-input w-full">{{ old('customization_decorations_additional', $booking->customization_decorations_additional) }}</textarea>
                    @error('customization_decorations_additional')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Customization Catering (JSON)</label>
                    <textarea name="customization_catering_custom" class="form-input w-full">{{ old('customization_catering_custom', $booking->customization_catering_custom) }}</textarea>
                    @error('customization_catering_custom')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Customization Additional Services (JSON)</label>
                    <textarea name="customization_additional_services_selected" class="form-input w-full">{{ old('customization_additional_services_selected', $booking->customization_additional_services_selected) }}</textarea>
                    @error('customization_additional_services_selected')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <!-- Add more fields as needed -->
                <div class="flex justify-between mt-6">
                    <a href="{{ route('bookings.my') }}" class="px-6 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Booking</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
