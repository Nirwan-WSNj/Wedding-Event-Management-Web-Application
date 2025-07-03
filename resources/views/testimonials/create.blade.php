@extends('layouts.app')

@section('title', 'Share Your Wedding Experience')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-rose-50 via-pink-50 to-purple-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Share Your Wedding Experience</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Help future couples by sharing your beautiful wedding story and experience at Wet Water Resort
            </p>
            <div class="w-24 h-1 bg-rose-500 mx-auto mt-6"></div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="ri-check-circle-line mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="ri-error-warning-line mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Booking Info (if available) -->
        @if($booking)
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-l-4 border-rose-500">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Wedding Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-600">Venue:</span>
                    <p class="text-gray-800">{{ $booking->hall->name ?? $booking->hall_name }}</p>
                </div>
                <div>
                    <span class="font-medium text-gray-600">Package:</span>
                    <p class="text-gray-800">{{ $booking->package->name ?? 'Custom Package' }}</p>
                </div>
                <div>
                    <span class="font-medium text-gray-600">Wedding Date:</span>
                    <p class="text-gray-800">{{ $booking->event_date ? $booking->event_date->format('F j, Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Testimonial Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <form id="testimonialForm" action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($booking)
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                @endif

                <!-- Couple Information -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Couple Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="groom_name" class="block text-sm font-medium text-gray-700 mb-2">Groom's Full Name *</label>
                            <input type="text" id="groom_name" name="groom_name" value="{{ old('groom_name', $booking->wedding_groom_name ?? '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('groom_name') border-red-500 @enderror">
                            @error('groom_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="bride_name" class="block text-sm font-medium text-gray-700 mb-2">Bride's Full Name *</label>
                            <input type="text" id="bride_name" name="bride_name" value="{{ old('bride_name', $booking->wedding_bride_name ?? '') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('bride_name') border-red-500 @enderror">
                            @error('bride_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="groom_email" class="block text-sm font-medium text-gray-700 mb-2">Groom's Email</label>
                            <input type="email" id="groom_email" name="groom_email" value="{{ old('groom_email', $booking->wedding_groom_email ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('groom_email') border-red-500 @enderror">
                            @error('groom_email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="bride_email" class="block text-sm font-medium text-gray-700 mb-2">Bride's Email</label>
                            <input type="email" id="bride_email" name="bride_email" value="{{ old('bride_email', $booking->wedding_bride_email ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('bride_email') border-red-500 @enderror">
                            @error('bride_email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="groom_phone" class="block text-sm font-medium text-gray-700 mb-2">Groom's Phone</label>
                            <input type="tel" id="groom_phone" name="groom_phone" value="{{ old('groom_phone', $booking->wedding_groom_phone ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('groom_phone') border-red-500 @enderror">
                            @error('groom_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="bride_phone" class="block text-sm font-medium text-gray-700 mb-2">Bride's Phone</label>
                            <input type="tel" id="bride_phone" name="bride_phone" value="{{ old('bride_phone', $booking->wedding_bride_phone ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('bride_phone') border-red-500 @enderror">
                            @error('bride_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Photos Section -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Wedding Photos</h3>
                    <p class="text-gray-600 mb-6">Share your beautiful wedding photos with us! These will be displayed with your testimonial.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="groom_photo" class="block text-sm font-medium text-gray-700 mb-2">Groom's Photo</label>
                            <div class="relative">
                                <input type="file" id="groom_photo" name="groom_photo" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('groom_photo') border-red-500 @enderror">
                                <div id="groom_photo_preview" class="mt-2 hidden">
                                    <img src="" alt="Groom Preview" class="w-24 h-24 object-cover rounded-lg">
                                </div>
                            </div>
                            @error('groom_photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="bride_photo" class="block text-sm font-medium text-gray-700 mb-2">Bride's Photo</label>
                            <div class="relative">
                                <input type="file" id="bride_photo" name="bride_photo" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('bride_photo') border-red-500 @enderror">
                                <div id="bride_photo_preview" class="mt-2 hidden">
                                    <img src="" alt="Bride Preview" class="w-24 h-24 object-cover rounded-lg">
                                </div>
                            </div>
                            @error('bride_photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="couple_photo" class="block text-sm font-medium text-gray-700 mb-2">Couple Photo</label>
                            <div class="relative">
                                <input type="file" id="couple_photo" name="couple_photo" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('couple_photo') border-red-500 @enderror">
                                <div id="couple_photo_preview" class="mt-2 hidden">
                                    <img src="" alt="Couple Preview" class="w-24 h-24 object-cover rounded-lg">
                                </div>
                            </div>
                            @error('couple_photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Accepted formats: JPG, PNG. Max size: 2MB per photo.</p>
                </div>

                <!-- Review Content -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Your Review</h3>
                    
                    <!-- Overall Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Overall Rating *</label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only" {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors star-rating" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Review Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Review Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               placeholder="e.g., Perfect Wedding Day at Wet Water Resort"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Review Text -->
                    <div class="mb-6">
                        <label for="review_text" class="block text-sm font-medium text-gray-700 mb-2">Your Experience *</label>
                        <textarea id="review_text" name="review_text" rows="6" required
                                  placeholder="Tell us about your wedding experience at Wet Water Resort. What made your day special?"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('review_text') border-red-500 @enderror">{{ old('review_text') }}</textarea>
                        @error('review_text')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Service Ratings -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Service Ratings</h3>
                    <p class="text-gray-600 mb-6">Rate different aspects of our service (optional)</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $serviceCategories = [
                                'venue_quality' => 'Venue Quality',
                                'food_quality' => 'Food Quality',
                                'service_quality' => 'Service Quality',
                                'decoration' => 'Decoration',
                                'coordination' => 'Event Coordination',
                                'value_for_money' => 'Value for Money',
                                'cleanliness' => 'Cleanliness',
                                'staff_friendliness' => 'Staff Friendliness'
                            ];
                        @endphp
                        
                        @foreach($serviceCategories as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="service_ratings[{{ $key }}]" value="{{ $i }}" class="sr-only" {{ old("service_ratings.{$key}") == $i ? 'checked' : '' }}>
                                        <svg class="w-6 h-6 text-gray-300 hover:text-yellow-400 transition-colors service-star" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Additional Information</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="favorite_aspect" class="block text-sm font-medium text-gray-700 mb-2">What was your favorite aspect of our service?</label>
                            <textarea id="favorite_aspect" name="favorite_aspect" rows="3"
                                      placeholder="Tell us what you loved most about your experience..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">{{ old('favorite_aspect') }}</textarea>
                        </div>
                        
                        <div>
                            <label for="improvement_suggestions" class="block text-sm font-medium text-gray-700 mb-2">Any suggestions for improvement?</label>
                            <textarea id="improvement_suggestions" name="improvement_suggestions" rows="3"
                                      placeholder="Help us improve our service for future couples..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500">{{ old('improvement_suggestions') }}</textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="would_recommend" name="would_recommend" value="1" {{ old('would_recommend') ? 'checked' : '' }}
                                   class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-gray-300 rounded">
                            <label for="would_recommend" class="ml-2 block text-sm text-gray-700">
                                I would recommend Wet Water Resort to other couples
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Privacy & Display Settings</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="display_on_website" name="display_on_website" value="1" {{ old('display_on_website', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-gray-300 rounded">
                            <label for="display_on_website" class="ml-2 block text-sm text-gray-700">
                                Allow this review to be displayed on the Wet Water Resort website
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="social_media_consent" name="social_media_consent" value="1" {{ old('social_media_consent') ? 'checked' : '' }}
                                   class="h-4 w-4 text-rose-600 focus:ring-rose-500 border-gray-300 rounded">
                            <label for="social_media_consent" class="ml-2 block text-sm text-gray-700">
                                Allow Wet Water Resort to use this review and photos on social media
                            </label>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-4">
                        All reviews are subject to approval before being published. We respect your privacy and will only use your information as specified above.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="p-8">
                    <button type="submit" id="submitBtn"
                            class="w-full bg-rose-600 hover:bg-rose-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitText">Submit Your Review</span>
                        <span id="submitLoader" class="hidden">
                            <i class="ri-loader-4-line animate-spin mr-2"></i>
                            Submitting...
                        </span>
                    </button>
                    
                    <p class="text-center text-xs text-gray-500 mt-4">
                        By submitting this review, you agree to our terms of service and privacy policy.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star rating functionality
    const starRatings = document.querySelectorAll('.star-rating');
    const serviceStars = document.querySelectorAll('.service-star');
    
    // Handle overall rating
    starRatings.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = index + 1;
            updateStarDisplay(starRatings, rating);
        });
        
        star.addEventListener('mouseover', function() {
            const rating = index + 1;
            highlightStars(starRatings, rating);
        });
    });
    
    // Handle service ratings
    document.querySelectorAll('[name^="service_ratings"]').forEach(input => {
        input.addEventListener('change', function() {
            const container = this.closest('div');
            const stars = container.querySelectorAll('.service-star');
            const rating = parseInt(this.value);
            updateStarDisplay(stars, rating);
        });
    });
    
    function updateStarDisplay(stars, rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
    
    function highlightStars(stars, rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
            }
        });
    }
    
    // Photo preview functionality
    ['groom_photo', 'bride_photo', 'couple_photo'].forEach(photoType => {
        const input = document.getElementById(photoType);
        const preview = document.getElementById(photoType + '_preview');
        
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = preview.querySelector('img');
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    });
    
    // Form submission
    const form = document.getElementById('testimonialForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoader = document.getElementById('submitLoader');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoader.classList.remove('hidden');
    });
});
</script>

<style>
.star-rating:hover,
.service-star:hover {
    transform: scale(1.1);
}

.star-rating.text-yellow-400,
.service-star.text-yellow-400 {
    filter: drop-shadow(0 0 4px rgba(251, 191, 36, 0.5));
}
</style>
@endsection