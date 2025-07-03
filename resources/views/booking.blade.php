@if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
@if (session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@extends('layouts.app')

@section('title', 'Enhanced Wedding Booking')

@section('content')
<div class="w-full" style="background-color: #200b01f6; height: 6rem;"></div>
<main class="container mx-auto py-8 px-4 font-sans pt-3 md:pt-7">
      {{-- Moving inline styles to a proper CSS file is recommended for production. --}}
  {{-- Keeping here as per original structure, with potential additions. --}}
  <style>
    :root {
        --color-primary: #3b82f6; /* blue-500 */
        --color-primary-dark: #2563eb; /* blue-600 */
        --color-secondary: #10b981; /* green-500 */
        --color-error: #ef4444; /* red-500 */
        --color-warning: #f59e0b; /* amber-500 */
    }

    .step-container {
      display: none;
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
    }
    .step-container.active {
      display: block;
      opacity: 1;
    }
    .progress-step {
      width: 16.66%; /* 100 / 6 steps */
      position: relative;
    }
    .progress-step::after {
      content: "";
      position: absolute;
      top: 50%;
      right: -50%;
      width: 100%;
      height: 2px;
      background-color: #e5e7eb; /* gray-200 */
      z-index: 0;
    }
    .progress-step:last-child::after {
      display: none;
    }
    .progress-step .step-circle {
      transition: all 0.3s ease-in-out;
      position: relative;
      z-index: 1; /* Ensure circle is above the line */
    }
    .progress-step.active .step-circle {
      background-color: var(--color-primary);
      color: white;
      font-weight: 600;
      transform: scale(1.05);
    }
    .progress-step.completed .step-circle {
      background-color: var(--color-secondary);
      color: white;
    }
    .progress-step.completed::after {
      background-color: var(--color-secondary);
    }
    .progress-step.active::after {
      background-color: #e5e7eb; /* gray-200, for the line connecting to an active step not yet completed */
    }
    /* Custom style for selected hall card ring */
    .hall-card {
        transition: all 0.3s ease-in-out;
    }
    .hall-card.selected, .wedding-type-card.selected {
        border-color: var(--color-primary); /* primary blue */
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.5); /* blue glow */
    }
    .package-card.selected {
        border-color: var(--color-primary);
        background-color: #eff6ff; /* blue-50 */
    }
    .catering-menu-card.selected {
        border-color: var(--color-primary); /* blue-500 */
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
    }

    /* Calendar Styles */
    .calendar { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 4px; }
    .calendar-day {
        display: flex; align-items: center; justify-content: center; height: 40px;
        border: 1px solid #e5e7eb; cursor: pointer;
        border-radius: 0.375rem; /* rounded-md */
        transition: all 0.2s ease-in-out;
    }
    .calendar-day:not(.disabled):hover { background-color: #f3f4f6; }
    .calendar-day.disabled { background-color: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
    .calendar-day.booked { background-color: #fecaca; color: #b91c1c; cursor: not-allowed; text-decoration: line-through; }
    .calendar-day.selected { background-color: var(--color-primary); color: white; }
    .calendar-header { text-align: center; font-weight: 600; padding: 8px 0; }

    /* Tab styling */
    .tab-button {
        padding: 0.75rem 1.25rem;
        font-weight: 600; /* changed from font-medium: 600 */
        border-radius: 0.375rem 0.375rem 0 0; /* rounded-t-md */
        transition: all 0.3s ease-in-out;
        color: #4b5563; /* gray-600 */
        background-color: #f9fafb; /* gray-50 */
        border-bottom: 2px solid transparent;
        white-space: nowrap; /* Prevent wrapping */
    }
    .tab-button.active {
        color: var(--color-primary-dark); /* blue-600 */
        background-color: white;
        border-bottom-color: var(--color-primary-dark); /* blue-600 */
        box-shadow: 0 -2px 4px rgba(0,0,0,0.05);
    }
    .tab-button:hover:not(.active) {
        background-color: #f3f4f6; /* gray-100 */
    }

    /* Form control styling */
    .form-input, .form-select, .form-textarea {
        border-color: #d1d5db; /* gray-300 */
        border-radius: 0.375rem; /* rounded-md */
        padding: 0.625rem 1rem; /* p-2.5 */
        font-size: 1rem; /* text-base */
        line-height: 1.5;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); /* blue-500 with opacity */
        outline: none;
    }
    .form-input.is-invalid, .form-select.is-invalid, .form-textarea.is-invalid {
        border-color: var(--color-error);
    }

    .error-message {
        color: var(--color-error);
        font-size: 0.875rem; /* text-sm */
        margin-top: 0.25rem;
    }
  </style>

  <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-xl p-8" x-data="weddingBooking()">
    <h1 class="text-4xl font-bold text-center mb-3 text-gray-800 tracking-tight">Wedding Booking</h1>
    @if(session('success'))
      <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg text-center text-lg font-semibold">
        {{ session('success') }}
      </div>
    @endif
    <div x-show="showSuccessMessage" x-transition class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg text-center text-lg font-semibold" x-text="successMessage"></div>
    <p class="text-gray-600 text-center mb-10 text-sm md:text-base">
      Follow the steps to book your dream wedding at <span class="font-medium text-blue-600">Wet Water Resort</span>
    </p>

    {{-- Progress Bar --}}
    <div class="flex justify-between items-start gap-1 mb-12">
      @php
        // Updated steps array
        $steps = ['Hall', 'Package', 'Customize', 'Visit Date', 'Wedding Date', 'Summary'];
      @endphp
      @foreach($steps as $index => $step)
        <div class="progress-step {{ $index === 0 ? 'active' : '' }}" id="progress-step-{{ $index + 1 }}" :class="{ 'active': Alpine.store('booking').currentStep === {{ $index + 1 }}, 'completed': Alpine.store('booking').currentStep > {{ $index + 1 }} }">
          <div class="step-circle w-11 h-11 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center mx-auto mb-2 text-sm shadow-md">
            {{ $index + 1 }}
          </div>
          <div class="text-xs text-center font-medium text-gray-700">{{ $step }}</div>
        </div>
      @endforeach
    </div>

<form id="booking-form" x-ref="bookingForm" method="POST" action="{{ route('booking.submit') }}" novalidate @submit.prevent="submitBookingForm()">
    @csrf
    <!-- Hidden inputs for all formData fields (cleaned up, no duplicates) -->
    <input type="hidden" name="hall_id" :value="formData.hallId">
    <input type="hidden" name="hall_name" :value="formData.hallName">
    <input type="hidden" name="hall_booking_date" :value="formData.hallBookingDate">
    <input type="hidden" name="package_id" :value="formData.package.id">
    <input type="hidden" name="package_price" :value="formData.package.price">
    <input type="hidden" name="customization_guest_count" :value="formData.customization.guestCount">
    <input type="hidden" name="customization_wedding_type" :value="formData.customization.weddingType">
    <input type="hidden" name="wedding_type_time_slot" :value="formData.customization.weddingTypeTimeSlot">
    <input type="hidden" name="catholic_day1_date" :value="formData.customization.catholicDay1Date">
    <input type="hidden" name="catholic_day2_date" :value="formData.customization.catholicDay2Date">
    <input type="hidden" name="customization_decorations_additional" :value="JSON.stringify(formData.customization.decorations.additional)">
    <input type="hidden" name="customization_catering_selected_menu_id" :value="formData.customization.catering.selectedMenuId">
    <input type="hidden" name="customization_catering_custom" :value="JSON.stringify(formData.customization.catering.custom)">
    <input type="hidden" name="customization_additional_services_selected" :value="JSON.stringify(formData.customization.additionalServices.selected)">
    <input type="hidden" name="contact_name" :value="formData.contact.name">
    <input type="hidden" name="contact_email" :value="formData.contact.email">
    <input type="hidden" name="contact_phone" :value="formData.contact.phone">
    <input type="hidden" name="visit_purpose" :value="formData.contact.visitPurpose">
    <input type="hidden" name="visit_purpose_other" :value="formData.contact.visitPurposeOther">
    <input type="hidden" name="special_requests" :value="formData.contact.specialRequests">
    <input type="hidden" name="visit_date" :value="formData.visitDate">
    <input type="hidden" name="visit_time" :value="formData.visitTime">
      <input type="hidden" name="wedding_groom_name" :value="formData.weddingDetails.groomName">
      <input type="hidden" name="wedding_bride_name" :value="formData.weddingDetails.brideName">
      <input type="hidden" name="wedding_groom_email" :value="formData.weddingDetails.groomEmail">
      <input type="hidden" name="wedding_bride_email" :value="formData.weddingDetails.brideEmail">
      <input type="hidden" name="wedding_groom_phone" :value="formData.weddingDetails.groomPhone">
      <input type="hidden" name="wedding_bride_phone" :value="formData.weddingDetails.bridePhone">
      <input type="hidden" name="wedding_date" :value="formData.weddingDetails.weddingDate">
      <input type="hidden" name="wedding_alternative_date1" :value="formData.weddingDetails.alternativeDate1">
      <input type="hidden" name="wedding_alternative_date2" :value="formData.weddingDetails.alternativeDate2">
      <input type="hidden" name="wedding_ceremony_time" :value="formData.weddingDetails.ceremonyTime">
      <input type="hidden" name="wedding_reception_time" :value="formData.weddingDetails.receptionTime">
      <input type="hidden" name="wedding_additional_notes" :value="formData.weddingDetails.additionalNotes">
      <input type="hidden" name="terms_agreed" :value="formData.weddingDetails.termsAgreed">
      <input type="hidden" name="privacy_agreed" :value="formData.weddingDetails.privacyAgreed">

      <!-- Backend-required fields for validation -->
      <input type="hidden" name="event_date" :value="formData.weddingDetails.weddingDate || formData.hallBookingDate">
      <input type="hidden" name="start_time" :value="formData.weddingDetails.ceremonyTime">
      <input type="hidden" name="end_time" :value="formData.weddingDetails.receptionTime">
      <input type="hidden" name="guest_count" :value="formData.customization.guestCount">
      <input type="hidden" name="selected_menu_id" :value="formData.customization.catering.selectedMenuId">

      {{-- Step 1: Hall Selection with Full Calendar --}}
      <div id="step-1" class="step-container" :class="{ 'active': currentStep === 1 }" x-cloak>
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Choose Your Wedding Hall</h2>
        <div class="error-message text-red-600 text-sm my-2 hidden" id="error-step-1"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          {{-- Loop halls --}}
          <template x-for="hall in hallsData" :key="hall.id">
            <div
              class="bg-white rounded-lg shadow-md hall-card cursor-pointer transition hover:scale-105 border-2"
              :class="formData.hallId === hall.id ? 'border-blue-500 shadow-lg' : 'border-transparent'"
              @click="selectHall(hall.id, hall.name)"
            >
              <div class="relative">
                <img
                  :src="hall.image"
                  :alt="hall.name"
                  class="w-full h-60 object-cover rounded-t-lg"
                />
                <div class="absolute top-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                  <span x-text="`Rs.${hall.price.toLocaleString()}`"></span>
                </div>
              </div>

              <div class="p-5">
                <h3 class="text-xl font-bold mb-1" x-text="hall.name"></h3>
                <p class="text-gray-600 text-sm mb-3" x-text="hall.description"></p>
                <p class="text-blue-600 font-medium mb-2" x-text="`Up to ${hall.capacity} guests`"></p>
                <ul class="text-sm text-gray-700 space-y-1">
                  <template x-for="feature in hall.features" :key="feature">
                    <li x-text="`• ${feature}`"></li>
                  </template>
                </ul>
                <button
                  type="button"
                  class="book-now-btn mt-4 w-full text-white py-2 rounded-lg transition-colors duration-300"
                  :class="formData.hallId === hall.id ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                  x-text="formData.hallId === hall.id ? 'Selected' : 'Select Hall'"
                  @click.stop="selectHall(hall.id, hall.name)"
                ></button>
              </div>
            </div>
          </template>
        </div>

        {{-- Booking Date Calendar --}}
        <template x-if="formData.hallId">
          <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mb-6">
            <h3 class="text-xl font-semibold mb-4">Select Booking Date for <span x-text="formData.hallName || 'Selected Hall'"></span></h3>

            <div class="flex justify-between items-center mb-4">
              <button type="button" @click="changeMonth(-1)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&lt;</button>
              <div class="font-semibold text-lg" x-text="monthNames[currentMonth] + ' ' + currentYear"></div>
              <button type="button" @click="changeMonth(1)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&gt;</button>
            </div>

            <div class="grid grid-cols-7 gap-1 text-center text-sm font-semibold text-gray-600 mb-2">
              <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']" :key="day">
                <div x-text="day"></div>
              </template>
            </div>

            <div class="grid grid-cols-7 gap-1 text-center">
              {{-- Blank days --}}
              <template x-for="blank in blanks" :key="'b'+blank">
                <div>&nbsp;</div>
              </template>

              {{-- Days --}}
              <template x-for="day in daysInMonth" :key="day">
                <button
                  type="button"
                  @click="selectHallDate(day)"
                  :disabled="isHallDateBooked(day) || !isFutureDate(day)"
                  :class="{
                    'bg-blue-500 text-white rounded': getFullDate(day) === formData.hallBookingDate,
                    'bg-red-200 text-red-600 cursor-not-allowed': isHallDateBooked(day),
                    'bg-gray-100 text-gray-400 cursor-not-allowed': !isFutureDate(day) && !isHallDateBooked(day),
                    'hover:bg-blue-100': !isHallDateBooked(day) && isFutureDate(day) && getFullDate(day) !== formData.hallBookingDate
                  }"
                  class="py-2 rounded calendar-day"
                  x-text="day"
                ></button>
              </template>
            </div>
             <p x-show="hallBookingError" class="error-message mt-2" x-text="hallBookingError"></p>
          </div>
        </template>

        <div class="flex justify-end">
          <button
            type="button"
            @click="handleNext(1)"
            :disabled="!formData.hallId || !formData.hallBookingDate"
            class="px-8 py-3 font-semibold rounded-lg text-white transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed bg-blue-600 hover:bg-blue-700"
          >
            Next
          </button>
        </div>
      </div>

      {{-- Step 2: Package Selection --}}
      <div id="step-2" class="step-container" :class="{ 'active': currentStep === 2 }" x-cloak>
        <h2 class="text-2xl font-semibold mb-2 text-gray-800">Select Your Wedding Package</h2>
        <p class="text-gray-600 text-center mb-6">
          Selected Hall: <span x-text="formData.hallName || 'None'" class="font-semibold text-blue-600"></span> on <span x-text="formData.hallBookingDate ? new Date(formData.hallBookingDate + 'T00:00:00').toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' }) : 'No Date Selected'" class="font-semibold text-blue-600"></span>
        </p>
        <div class="error-message text-red-600 text-sm my-2 hidden" id="error-step-2"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <template x-for="pkg in packagesData" :key="pkg.id">
            <div class="rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer package-card border-2"
                 :class="{ 'selected': formData.package.id === pkg.id, 'border-blue-500 bg-blue-50': formData.package.id === pkg.id, 'bg-white border-gray-200': formData.package.id !== pkg.id }"
                 @click="selectPackage(pkg.id, pkg.price)">
              <input type="radio" :name="'package_option'" :id="pkg.id" :value="pkg.id" class="sr-only package-radio" x-model="formData.package.id" :checked="formData.package.id === pkg.id" required :data-price="pkg.price">
              <label :for="pkg.id" class="block cursor-pointer p-0">
                <template x-if="pkg.highlight">
                  <div class="bg-yellow-400 text-yellow-800 text-center py-1.5 text-sm font-semibold">Most Popular</div>
                </template>
                <img :src="pkg.image" :alt="pkg.name + ' Package'" class="w-full h-48 object-cover">
                <div class="p-5">
                  <h3 class="font-bold text-xl mb-2 text-gray-800" x-text="pkg.name"></h3>
                  <div class="mb-4">
                    <p class="text-gray-600 text-sm mb-3 min-h-[2.5rem] leading-relaxed" x-text="pkg.desc"></p>
                    <div class="text-3xl font-bold text-blue-600 mb-2">
                      Rs.<span x-text="pkg.price.toLocaleString()"></span>
                    </div>
                  </div>
                  <ul class="text-left text-sm space-y-2 text-gray-700 mb-4 h-32 overflow-y-auto">
                    <template x-for="feature in pkg.features" :key="feature">
                      <li class="flex items-start"><span class="text-green-500 mr-2 mt-1">✓</span> <span x-text="feature"></span></li>
                    </template>
                  </ul>
                  <button type="button" class="w-full text-white py-2.5 rounded-lg transition-colors duration-300 text-sm font-semibold"
                          :class="formData.package.id === pkg.id ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-600 hover:bg-blue-700'">
                    <span x-text="formData.package.id === pkg.id ? 'Selected' : 'Choose Package'"></span>
                  </button>
                </div>
              </label>
            </div>
          </template>
        </div>
        <div class="mt-8 flex justify-between">
          <button type="button" class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-300 font-semibold" @click="prevStep()">Back</button>
          <button type="button" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 font-semibold" @click="handleNext(2)">Next</button>
        </div>
      </div>

      {{-- Step 3: Package Customization --}}
      <div id="step-3" class="step-container p-2 md:p-6" :class="{ 'active': currentStep === 3 }" x-cloak>
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Customize Your Package</h2>
        <div class="error-message text-red-600 text-sm my-2 hidden" id="error-step-3"></div>

        {{-- Guest Count Input --}}
        <div class="mb-8 p-6 bg-gray-50 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-3 text-gray-700">Guest Count</h3>
            <div class="flex items-center gap-4">
                <label for="guest-count" class="text-gray-700">Number of Guests:</label>
                <input type="number" id="guest-count" name="guest_count_input" min="10" max="1000"
                       x-model.number="formData.customization.guestCount"
                       @input="updateCustomizationCosts()"
                       class="form-input w-24 shadow-sm" required aria-describedby="guest-count-hint">
                <p id="guest-count-hint" class="text-sm text-gray-500">Min: 10, Max: 1000</p>
            </div>
             <p x-show="guestCountError" class="error-message mt-2" x-text="guestCountError"></p>
        </div>

        {{-- Tabs Navigation --}}
        <div class="mb-6 overflow-x-auto">
          <ul class="flex flex-wrap border-b border-gray-200 -mb-px">
            <li class="mr-1">
              <button type="button" @click="formData.customization.activeTab = 'wedding-type'" :class="{ 'active': formData.customization.activeTab === 'wedding-type' }" class="tab-button">Wedding Type</button>
            </li>
            <li class="mr-1">
              <button type="button" @click="formData.customization.activeTab = 'decoration'" :class="{ 'active': formData.customization.activeTab === 'decoration' }" class="tab-button">Decoration</button>
            </li>
            <li class="mr-1">
              <button type="button" @click="formData.customization.activeTab = 'catering'" :class="{ 'active': formData.customization.activeTab === 'catering' }" class="tab-button">Catering</button>
            </li>
            <li class="mr-1">
              <button type="button" @click="formData.customization.activeTab = 'additional-services'" :class="{ 'active': formData.customization.activeTab === 'additional-services' }" class="tab-button">Additional Services</button>
            </li>
            <li>
              <button type="button" @click="formData.customization.activeTab = 'customization-summary'" :class="{ 'active': formData.customization.activeTab === 'customization-summary' }" class="tab-button">Customization Summary</button>
            </li>
          </ul>
        </div>

        {{-- Tabs Content --}}
        <div class="space-y-8 py-6">
          {{-- Tab 1: Wedding Type --}}
          <div x-show="formData.customization.activeTab === 'wedding-type'" class="transition-all duration-300 ease-in-out">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Choose Your Wedding Type</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <template x-for="type in weddingTypesData" :key="type.value">
                    <label :for="'type-' + type.value.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/--+/g, '-').replace(/^-|-$/g, '')"
                            class="wedding-type-card cursor-pointer border rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 bg-white group"
                           :class="{ 'selected': formData.customization.weddingType === type.value }">
                        <input type="radio" :id="'type-' + type.value.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/--+/g, '-').replace(/^-|-$/g, '')" name="wedding_type" :value="type.value" class="sr-only peer" x-model="formData.customization.weddingType" @change="updateCustomizationCosts()" required>
                        <div class="relative">
                            <img :src="type.image" :alt="type.label" class="h-48 w-full object-cover rounded-t-lg">
                            <div class="absolute bottom-0 left-0 right-0 bg-white bg-opacity-80 text-center py-2">
                                <span class="text-base font-semibold text-gray-800" x-text="type.label"></span>
                            </div>
                            <div x-show="formData.customization.weddingType === type.value" class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">Selected!</div>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-600 h-16" x-text="type.desc"></p>
                        </div>
                    </label>
                </template>
            </div>
            {{-- Time Slot / Date Inputs for Wedding Type --}}
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
              <div x-show="['Kandyan Wedding', 'Low-Country Wedding', 'European Wedding', 'Indian Wedding'].includes(formData.customization.weddingType)" class="transition-all duration-300 ease-in-out">
                <label for="one_day_time" class="block mb-2 text-sm font-medium text-gray-700">Choose Time Slot:</label>
                <select id="one_day_time" name="one_day_time" class="form-select w-full" x-model="formData.customization.weddingTypeTimeSlot" :required="['Kandyan Wedding', 'Low-Country Wedding', 'European Wedding', 'Indian Wedding'].includes(formData.customization.weddingType)">
                  <option value="">-- Select Time Slot --</option>
                  <option value="morning">Morning Ceremony (e.g., 9 AM - 1 PM)</option>
                  <option value="evening">Evening Ceremony (e.g., 5 PM - 9 PM)</option>
                </select>
                <p x-show="weddingTypeTimeSlotError" class="error-message mt-2" x-text="weddingTypeTimeSlotError"></p>
              </div>
              <div x-show="formData.customization.weddingType === 'Catholic Wedding'" class="transition-all duration-300 ease-in-out space-y-4">
                <div>
                  <label for="day1_date" class="block mb-1 text-sm font-medium text-gray-700">Day 1 - Church Ceremony Date:</label>
                  <input id="day1_date" type="date" name="day1_date" class="form-input w-full" x-model="formData.customization.catholicDay1Date" :required="formData.customization.weddingType === 'Catholic Wedding'" :min="getMinDateForBooking(0)">
                  <p x-show="catholicDay1DateError" class="error-message mt-2" x-text="catholicDay1DateError"></p>
                </div>
                <div>
                  <label for="day2_date" class="block mb-1 text-sm font-medium text-gray-700">Day 2 - Reception Date:</label>
                  <input id="day2_date" type="date" name="day2_date" class="form-input w-full" x-model="formData.customization.catholicDay2Date" :required="formData.customization.weddingType === 'Catholic Wedding'" :min="formData.customization.catholicDay1Date ? getMinDateForCatholicDay2() : getMinDateForBooking(0)">
                  <p x-show="catholicDay2DateError" class="error-message mt-2" x-text="catholicDay2DateError"></p>
                </div>
              </div>
               <p x-show="weddingTypeError" class="error-message mt-2" x-text="weddingTypeError"></p>
            </div>
          </div>

          {{-- Tab 2: Decoration --}}
          <div x-show="formData.customization.activeTab === 'decoration'" class="transition-all duration-300 ease-in-out">
            <h3 class="text-xl font-semibold mb-2 text-gray-700">Decoration Options</h3>

            {{-- Included Decorations (Based on Wedding Type) --}}
            <div class="mb-8 p-4 bg-blue-50 rounded-lg" x-show="formData.customization.weddingType">
                <h4 class="text-lg font-semibold mb-3 text-blue-700">Included Decorations for <span x-text="formData.customization.weddingType" class="font-bold"></span></h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-if="formData.customization.weddingType">
                        <template x-for="decor in getIncludedDecorations(formData.customization.weddingType)" :key="decor.name">
                            <div class="border rounded-lg p-4 bg-white shadow">
                                <img :src="decor.image" :alt="decor.name" class="w-full h-32 object-cover rounded-md mb-2">
                                <div class="font-medium text-gray-800" x-text="decor.name"></div>
                                <div class="text-sm text-gray-500 mt-1" x-text="decor.description"></div>
                            </div>
                        </template>
                    </template>
                    <p x-show="!getIncludedDecorations(formData.customization.weddingType).length" class="text-gray-600 col-span-full">No specific included decorations listed for this wedding type, or select a wedding type first.</p>
                </div>
            </div>
            <hr class="my-6">
            {{-- Additional Decoration Options --}}
            <h4 class="text-lg font-semibold mb-3 text-gray-700">Additional Decoration Options (Optional)</h4>
            
            <template x-if="formData.customization.weddingType">
                <div>
                    <div class="mb-8 p-4 bg-green-50 rounded-lg">
                        <h5 class="text-lg font-semibold mb-3 text-green-700">✅ Free Options (Included for <span x-text="formData.customization.weddingType"></span>)</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-8">
                            <template x-for="decor in getDecorationsForWeddingType(formData.customization.weddingType).free" :key="decor.id">
                                <div class="border rounded-xl p-5 bg-white shadow-sm flex flex-col justify-between">
                                    <div>
                                        <img :src="decor.image" :alt="decor.name" class="w-full h-40 object-cover rounded-lg mb-3">
                                        <div class="font-semibold text-gray-800 text-md mb-2" x-text="decor.name"></div>
                                        <p class="text-sm text-gray-600 mb-2" x-text="decor.description"></p>
                                    </div>
                                    <div class="text-md font-semibold text-green-600 mt-2">Free!</div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                        <h5 class="text-lg font-semibold mb-3 text-blue-700">💎 Paid Options</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-8">
                            <template x-for="decor in getDecorationsForWeddingType(formData.customization.weddingType).paid" :key="decor.id">
                                <div class="border rounded-xl p-5 hover:shadow-xl transition-shadow bg-white flex flex-col justify-between">
                                    <div>
                                        <img :src="decor.image" :alt="decor.name" class="w-full h-40 object-cover rounded-lg mb-3">
                                        <div class="flex items-start mb-2">
                                            <input type="checkbox" :id="'decor-' + decor.id" :value="decor.id"
                                                   x-model="formData.customization.decorations.additional" @change="updateCustomizationCosts()"
                                                   class="mt-1 h-5 w-5 accent-blue-600 focus:ring-blue-500 rounded border-gray-300">
                                            <label :for="'decor-' + decor.id" class="ml-3 cursor-pointer">
                                                <div class="font-semibold text-gray-800 text-md" x-text="decor.name"></div>
                                            </label>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2" x-text="decor.description"></p>
                                    </div>
                                    <div class="text-md font-semibold text-blue-600 mt-2">Rs. <span x-text="decor.price.toLocaleString()"></span></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="!formData.customization.weddingType" class="text-center p-8 bg-gray-50 rounded-lg">
                <p class="text-gray-600">Please select a wedding type first to see available decoration options.</p>
            </div>
          </div>

          {{-- Tab 3: Catering --}}
            <div x-show="formData.customization.activeTab === 'catering'" class="transition-all duration-300 ease-in-out">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Catering Selection</h3>
                <p class="text-sm text-gray-600 mb-6">Available menus are based on your selected package: <strong x-text="formData.package.id ? formData.package.id.charAt(0).toUpperCase() + formData.package.id.slice(1).replace('package-', '') : 'None'"></strong></p>
                <div class="error-message text-red-600 text-sm my-2 hidden" id="catering-error-message"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="menu in filteredCateringMenus()" :key="menu.id">
                        <div class="border rounded-xl p-5 hover:shadow-xl transition-shadow bg-white cursor-pointer catering-menu-card"
                             :class="{ 'selected': formData.customization.catering.selectedMenuId === menu.id }"
                             @click="selectCateringMenu(menu.id)">
                            <input type="radio" :id="'menu-' + menu.id" name="catering_menu_option" :value="menu.id" class="sr-only peer" x-model="formData.customization.catering.selectedMenuId" required>
                            <label :for="'menu-' + menu.id" class="block cursor-pointer">
                                <h4 class="font-bold text-lg mb-2" x-text="menu.name"></h4>
                                <p class="text-gray-600 text-sm mb-3" x-text="menu.description"></p>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <template x-for="(items, category) in menu.details" :key="category">
                                        <li>
                                            <strong x-text="category"></strong>:
                                            <ul class="list-disc list-inside ml-4">
                                                <template x-for="item in items" :key="item">
                                                    <li x-text="item"></li>
                                                </template>
                                            </ul>
                                        </li>
                                    </template>
                                </ul>
                                <div x-show="formData.customization.catering.selectedMenuId === menu.id" class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">Selected!</div>
                            </label>
                        </div>
                    </template>
                    <p x-show="!filteredCateringMenus().length" class="text-gray-600 col-span-full">No function menus are specifically listed for the selected package in the provided data, or data is loading.</p>
                </div>

                <hr class="my-8">

                {{-- Make Your Own Menu --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold mb-3 text-gray-700">Make Your Own Menu (Optional Add-ons)</h4>
                    <p class="text-sm text-gray-600 mb-4">Choose additional items to customize your catering.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="(items, category) in customCateringOptionsData" :key="category">
                            <div class="border rounded-xl p-4 bg-white shadow-sm">
                                <h5 class="font-bold text-md mb-3 text-gray-800" x-text="category"></h5>
                                <template x-for="item in items" :key="item.name">
                                    <div class="flex items-center justify-between mb-2">
                                        <label :for="'custom-catering-' + item.name.replace(/\s+/g, '-').toLowerCase()" class="flex items-center cursor-pointer">
                                            <input type="checkbox" :id="'custom-catering-' + item.name.replace(/\s+/g, '-').toLowerCase()"
                                                   @change="toggleCustomCateringItem(category, item)"
                                                   :checked="isCustomCateringItemSelected(category, item.name)"
                                                   class="h-4 w-4 accent-blue-600 rounded">
                                            <span class="ml-2 text-gray-700" x-text="item.name"></span>
                                        </label>
                                        <span class="text-gray-600 text-sm">Rs. <span x-text="item.price.toLocaleString()"></span> <template x-if="item.unit">/<span x-text="item.unit"></span></template></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Supplementary Charges/Notes --}}
                <div class="mb-8 p-4 bg-yellow-50 rounded-lg shadow">
                    <h4 class="text-lg font-semibold mb-3 text-yellow-700">Important Catering Notes & Charges</h4>
                    <ul class="text-sm text-yellow-800 space-y-2 list-disc list-inside">
                        <template x-for="note in supplementaryChargesData.notes.catering" :key="note">
                            <li x-text="note"></li>
                        </template>
                    </ul>
                </div>
            </div>

          {{-- Tab 4: Additional Services --}}
          <div x-show="formData.customization.activeTab === 'additional-services'" class="transition-all duration-300 ease-in-out">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Additional Services</h3>

            {{-- Compulsory Free Services --}}
            <div class="mb-8 p-4 bg-green-50 rounded-lg shadow">
                <h4 class="text-lg font-semibold mb-3 text-green-700">Compulsory Free Services (Included with all Packages)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="service in additionalServicesData.freeCompulsory" :key="service.id">
                        <div class="flex items-center p-3 border rounded-lg bg-white">
                            <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <div class="font-medium text-gray-800" x-text="service.name"></div>
                                <div class="text-sm text-gray-500" x-text="service.description"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <hr class="my-6">

            {{-- Optional Free Services --}}
            <h4 class="text-lg font-semibold mb-3 text-gray-700">Optional Free Services</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-8 mb-8">
              <template x-for="service in additionalServicesData.optionalFree" :key="service.id">
                <div class="border rounded-xl p-5 hover:shadow-xl transition-shadow bg-white flex flex-col justify-between">
                    <div>
                        <img :src="service.image" :alt="service.name" class="w-full h-40 object-cover rounded-lg mb-3">
                        <div class="flex items-start mb-2">
                            <input type="checkbox" :id="'service-free-' + service.id" :value="service.id"
                                   x-model="formData.customization.additionalServices.selected" @change="updateCustomizationCosts()"
                                   class="mt-1 h-5 w-5 accent-blue-600 focus:ring-blue-500 rounded border-gray-300">
                            <label :for="'service-free-' + service.id" class="ml-3 cursor-pointer">
                                <div class="font-semibold text-gray-800 text-md" x-text="service.name"></div>
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 mb-2" x-text="service.description"></p>
                    </div>
                    <div class="text-md font-semibold text-green-600 mt-2">Free!</div>
                </div>
              </template>
            </div>

            <hr class="my-6">

            {{-- Paid Services --}}
            <h4 class="text-lg font-semibold mb-3 text-gray-700">Paid Additional Services</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-8">
              <template x-for="service in additionalServicesData.paid" :key="service.id">
                <div class="border rounded-xl p-5 hover:shadow-xl transition-shadow bg-white flex flex-col justify-between">
                    <div>
                        <img :src="service.image" :alt="service.name" class="w-full h-40 object-cover rounded-lg mb-3">
                        <div class="flex items-start mb-2">
                            <input type="checkbox" :id="'service-paid-' + service.id" :value="service.id"
                                   x-model="formData.customization.additionalServices.selected" @change="updateCustomizationCosts()"
                                   class="mt-1 h-5 w-5 accent-blue-600 focus:ring-blue-500 rounded border-gray-300">
                            <label :for="'service-paid-' + service.id" class="ml-3 cursor-pointer">
                                <div class="font-semibold text-gray-800 text-md" x-text="service.name"></div>
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 mb-2" x-text="service.description"></p>
                    </div>
                    <div class="text-md font-semibold text-blue-600 mt-2">Rs. <span x-text="service.price.toLocaleString()"></span></div>
                </div>
              </template>
            </div>
          </div>

          {{-- Tab 5: Customization Summary --}}
          <div x-show="formData.customization.activeTab === 'customization-summary'" class="transition-all duration-300 ease-in-out">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Customization Summary</h3>

            <div class="bg-gray-50 p-6 rounded-xl shadow mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <h4 class="font-bold text-lg mb-2">Hall & Date:</h4>
                        <p><strong>Hall:</strong> <span x-text="formData.hallName || 'Not Selected'"></span></p>
                        <p><strong>Booking Date:</strong> <span x-text="formData.hallBookingDate ? new Date(formData.hallBookingDate + 'T00:00:00').toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not Selected'"></span></p>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2">Selected Package:</h4>
                        <p><strong>Package:</strong> <span x-text="formData.package.id ? formData.package.id.charAt(0).toUpperCase() + formData.package.id.slice(1).replace('package-', '') : 'Not Selected'"></span></p>
                        <p><strong>Price:</strong> Rs. <span x-text="formData.package.price.toLocaleString()"></span></p>
                        <p><strong>Guest Count:</strong> <span x-text="formData.customization.guestCount || 'Not Set'"></span></p>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2">Wedding Type:</h4>
                        <p><strong>Type:</strong> <span x-text="formData.customization.weddingType || 'Not selected'"></span></p>
                        <template x-if="['Kandyan Wedding', 'Low-Country Wedding', 'European Wedding', 'Indian Wedding'].includes(formData.customization.weddingType)">
                           <p><strong>Time Slot:</strong> <span x-text="formData.customization.weddingTypeTimeSlot || 'Not selected'"></span></p>
                        </template>
                        <template x-if="formData.customization.weddingType === 'Catholic Wedding'">
                           <p><strong>Day 1 Date:</strong> <span x-text="formData.customization.catholicDay1Date || 'Not selected'"></span></p>
                           <p><strong>Day 2 Date:</strong> <span x-text="formData.customization.catholicDay2Date || 'Not selected'"></span></p>
                        </template>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2">Decorations:</h4>
                        <p class="font-medium">Included:</p>
                        <ul class="list-disc list-inside ml-4">
                            <template x-for="decor in getIncludedDecorations(formData.customization.weddingType)" :key="decor.name">
                                <li x-text="decor.name"></li>
                            </template>
                            <li x-show="!getIncludedDecorations(formData.customization.weddingType).length" class="text-gray-500">None specific for selected type.</li>
                        </ul>
                        <p class="font-medium mt-2">Additional:</p>
                        <ul class="list-disc list-inside ml-4">
                            <template x-for="decorId in formData.customization.decorations.additional" :key="decorId">
                                <li x-text="findDecorationById(decorId)?.name || 'Unknown Decoration'"></li>
                            </template>
                            <li x-show="formData.customization.decorations.additional.length === 0" class="text-gray-500">No additional decorations selected.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2">Catering:</h4>
                        <p><strong>Selected Menu:</strong> <span x-text="cateringMenusData.find(m => m.id === formData.customization.catering.selectedMenuId)?.name || 'Not Selected'"></span></p>
                        <p class="font-medium mt-2">Custom Items:</p>
                        <ul class="list-disc list-inside ml-4">
                            <template x-for="(items, category) in formData.customization.catering.custom" :key="category">
                                <template x-for="item in items" :key="item.name">
                                    <li x-text="item.name + (item.price ? ` (Rs. ${item.price.toLocaleString()})` : '')"></li>
                                </template>
                            </template>
                             <li x-show="Object.keys(formData.customization.catering.custom).length === 0 || Object.values(formData.customization.catering.custom).flat().length === 0" class="text-gray-500">No custom catering items.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-2">Additional Services:</h4>
                        <p class="font-medium">Compulsory Free:</p>
                        <ul class="list-disc list-inside ml-4">
                            <template x-for="service in additionalServicesData.freeCompulsory" :key="service.id">
                                <li x-text="service.name"></li>
                            </template>
                        </ul>
                        <p class="font-medium mt-2">Selected Optional Services:</p>
                        <ul class="list-disc list-inside ml-4">
                            <template x-for="serviceId in formData.customization.additionalServices.selected" :key="serviceId">
                                <li x-text="getAdditionalServiceById(serviceId)?.name || 'Unknown Service'"></li>
                            </template>
                            <li x-show="formData.customization.additionalServices.selected.length === 0" class="text-gray-500">No optional services selected.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-6 rounded-xl shadow mb-8">
                <h4 class="text-lg font-semibold mb-3 text-blue-700">Important Information & Charges:</h4>
                <ul class="list-disc list-inside text-sm text-blue-800 space-y-2">
                    <template x-for="info in supplementaryChargesData.notes.summary" :key="info">
                        <li x-text="info"></li>
                    </template>
                </ul>
            </div>
            <div class="flex justify-between">
              <button type="button" class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-300 font-semibold" @click="prevStep()">Back</button>
              <button type="button" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 font-semibold" @click="handleNext(3)">Proceed to Visit Date</button>
            </div>
          </div>
        </div>
      </div>

      {{-- Step 4: Visit Date --}}
      <div id="step-4" class="step-container" :class="{ 'active': currentStep === 4 }" x-cloak>
          <h2 class="text-2xl font-semibold mb-6 text-gray-800">Schedule Your Visit</h2>
          <div class="error-message text-red-600 text-sm my-2 hidden" id="error-step-4"></div>

          <!-- Visit Scheduling Form (only show if not submitted) -->
          <div x-show="!visitSubmitted">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
              <div>
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Details</h3>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="contact-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text"
                           id="contact-name"
                           name="customer_name"
                           x-model="formData.contact.name"
                           x-init="formData.contact.name = '{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}'"
                           class="form-input block w-full rounded-md pr-10 bg-gray-50 text-gray-700 border-gray-300"
                           readonly>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Name from your account profile</p>
            </div>

            <div>
                <label for="contact-email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="email"
                           id="contact-email"
                           name="customer_email"
                           x-model="formData.contact.email"
                           x-init="formData.contact.email = '{{ auth()->user()->email }}'"
                           class="form-input block w-full rounded-md pr-10 bg-gray-50 text-gray-700 border-gray-300"
                           readonly>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Email from your account profile</p>
            </div>
        </div>

        <div>
            <label for="contact-phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <div class="mt-1">
                <input type="tel"
                       id="contact-phone"
                       name="customer_phone"
                       x-model="formData.contact.phone"
                       class="form-input block w-full rounded-md shadow-sm border-gray-300 focus:border-rose-500 focus:ring-rose-500"
                       :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': validationErrors['contact.phone'] }"
                       required
                       pattern="[0-9]{10}"
                       placeholder="0712345678"
                       @input="validateField('contact.phone')">
            </div>
            <p x-show="validationErrors['contact.phone']"
               class="mt-2 text-sm text-red-600"
               x-text="validationErrors['contact.phone']"></p>
            <p class="mt-1 text-sm text-gray-500">Enter your mobile number (10 digits)</p>
        </div>
    </div>
</div>
              </div>

              <div>
                  <h3 class="text-xl font-semibold mb-4 text-gray-700">Preferred Visit Details</h3>
                  <div class="space-y-4">
                      <div>
                          <label for="visit-purpose" class="block text-sm font-medium text-gray-700">Purpose of Visit</label>
                          <select id="visit-purpose" name="visit_purpose" x-model="formData.contact.visitPurpose" class="form-select w-full" required @change="validateField('contact.visitPurpose')">
                              <option value="">-- Select Purpose --</option>
                              <option value="Venue Tour">Venue Tour</option>
                              <option value="Package Discussion">Package Discussion</option>
                              <option value="Customization Consultation">Customization Consultation</option>
                              <option value="Other">Other (Please specify)</option>
                          </select>
                           <p x-show="validationErrors['contact.visitPurpose']" class="error-message" x-text="validationErrors['contact.visitPurpose']"></p>
                      </div>
                      <div x-show="formData.contact.visitPurpose === 'Other'">
                          <label for="visit-purpose-other" class="block text-sm font-medium text-gray-700">Specify Other Purpose</label>
                          <input type="text" id="visit-purpose-other" name="visit_purpose_other" x-model="formData.contact.visitPurposeOther" class="form-input w-full" :required="formData.contact.visitPurpose === 'Other'" placeholder="e.g., Follow-up on previous discussion">
                          <p x-show="validationErrors['contact.visitPurposeOther']" class="error-message" x-text="validationErrors['contact.visitPurposeOther']"></p>
                      </div>
                      <div>
                          <label for="visit-date" class="block mb-1 text-sm font-medium text-gray-700">Preferred Visit Date:</label>
                          <input type="date" id="visit-date" name="visit_date_input" x-model="formData.visitDate"
                                 @change="validateField('visitDate'); updateVisitTimeOptions()"
                                 :min="getMinDateForBooking(1)" class="form-input w-full" required>
                          <p x-show="validationErrors['visitDate']" class="error-message" x-text="validationErrors['visitDate']"></p>
                      </div>
                      <div>
                          <label for="visit-time" class="block mb-1 text-sm font-medium text-gray-700">Preferred Visit Time:</label>
                          <select id="visit-time" name="visit_time_input" x-model="formData.visitTime"
                                  @change="validateField('visitTime')" class="form-select w-full" required>
                              <option value="">-- Select Time --</option>
                              <template x-for="timeSlot in visitTimeSlots" :key="timeSlot">
                                  <option :value="timeSlot" :disabled="isVisitTimeBooked(formData.visitDate, timeSlot)" x-text="timeSlot + (isVisitTimeBooked(formData.visitDate, timeSlot) ? ' (Booked)' : '')"></option>
                              </template>
                          </select>
                           <p x-show="validationErrors['visitTime']" class="error-message" x-text="validationErrors['visitTime']"></p>
                      </div>
                      <div>
                          <label for="special-requests" class="block text-sm font-medium text-gray-700">Special Requests / Notes</label>
                          <textarea id="special-requests" name="special_requests_input" x-model="formData.contact.specialRequests" rows="3" class="form-textarea w-full" placeholder="e.g., specific setup needs, accessibility requirements"></textarea>
                      </div>
                  </div>
              </div>
              </div>
          </div>

          <div class="mt-8 flex justify-between">
              <button type="button" class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-300 font-semibold" @click="prevStep()">Back</button>
              
              <!-- Submit Visit Request Button -->
              <button x-show="!visitSubmitted" type="button" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 font-semibold" @click="handleNext(4)">Submit Visit Request</button>
              
              <!-- Proceed Button (Available after visit submission) -->
              <button x-show="visitSubmitted && (visitConfirmed && advancePaymentPaid)" type="button" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-300 font-semibold" @click="handleNext(4)">Proceed to Wedding Details</button>
              
              <!-- Continue Without Confirmation Button (NEW - Allows progression) -->
              <button x-show="visitSubmitted && (!visitConfirmed || !advancePaymentPaid)" type="button" class="px-8 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-300 font-semibold" @click="proceedWithoutConfirmation()">
                  Continue Booking (Confirmation Pending)
              </button>
              
              <!-- CRITICAL FIX: Add working continue button -->
              <button x-show="visitSubmitted && (!visitConfirmed || !advancePaymentPaid)" type="button" class="px-8 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-300 font-semibold ml-4" @click="nextStep()">
                  Continue Booking (Approval Pending)
              </button>
              
              <!-- Status Display -->
              <div x-show="visitSubmitted && (!visitConfirmed || !advancePaymentPaid)" class="text-sm text-gray-600 mt-2">
                  <span x-show="!visitConfirmed">⏳ Visit confirmation pending</span>
                  <span x-show="visitConfirmed && !advancePaymentPaid">💳 Advance payment pending</span>
              </div>
          </div>
          <!-- Visit Status Display -->
          <div x-show="visitSubmitted" class="mb-8">
              <!-- Visit Submitted but Not Confirmed -->
              <div x-show="!visitConfirmed" class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                  <div class="flex items-center">
                      <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      <div>
                          <h3 class="text-lg font-semibold text-yellow-800">Visit Request Submitted</h3>
                          <p class="text-yellow-700">Your visit request has been submitted. Our manager will call you to confirm the visit details.</p>
                          <div class="mt-3 p-3 bg-yellow-100 rounded-lg">
                              <p class="text-sm text-yellow-800">
                                  <strong>📞 Important:</strong> Please keep your phone <span x-text="formData.contact.phone"></span> available. Our manager will call you within 24 hours to confirm your visit.
                              </p>
                          </div>
                          <p class="text-sm text-yellow-600 mt-1">
                              Visit Date: <span x-text="formData.visitDate ? new Date(formData.visitDate + 'T00:00:00').toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not set'"></span> 
                              at <span x-text="formData.visitTime || 'Not set'"></span>
                          </p>
                      </div>
                  </div>
              </div>

              <!-- Visit Confirmed -->
              <div x-show="visitConfirmed" class="space-y-6">
                  <div class="p-6 bg-green-50 border border-green-200 rounded-lg">
                      <div class="flex items-center">
                          <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          <div>
                              <h3 class="text-lg font-semibold text-green-800">Visit Confirmed!</h3>
                              <p class="text-green-700">Your visit has been confirmed by our manager.</p>
                              <p class="text-sm text-green-600 mt-1">
                                  Visit Date: <span x-text="formData.visitDate ? new Date(formData.visitDate + 'T00:00:00').toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not set'"></span> 
                                  at <span x-text="formData.visitTime || 'Not set'"></span>
                              </p>
                              <p class="text-sm text-green-600">
                                  Venue: <span x-text="formData.hallName || 'Selected Hall'"></span>
                              </p>
                          </div>
                      </div>
                  </div>

                  <!-- Advance Payment Information -->
                  <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg">
                      <h4 class="text-lg font-semibold text-blue-800 mb-3">Advance Payment Required</h4>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <div>
                              <p class="text-blue-700">
                                  <strong>Amount:</strong> Rs. <span x-text="Math.round(grandTotalCost * 0.20).toLocaleString()"></span>
                                  <span class="text-sm">(20% of total cost)</span>
                              </p>
                              <p class="text-blue-700">
                                  <strong>Total Wedding Cost:</strong> Rs. <span x-text="grandTotalCost.toLocaleString()"></span>
                              </p>
                              <p class="text-blue-700">
                                  <strong>Remaining Balance:</strong> Rs. <span x-text="Math.round(grandTotalCost * 0.80).toLocaleString()"></span>
                              </p>
                          </div>
                          <div>
                              <p class="text-sm text-blue-600">
                                  Please pay the advance amount to proceed to Step 5 and finalize your wedding details.
                              </p>
                              <p class="text-sm text-blue-600 mt-2">
                                  Contact our office at <strong>+94 11 234 5678</strong> to make the payment.
                              </p>
                          </div>
                      </div>
                      
                      <!-- Payment Status -->
                      <div x-show="advancePaymentPaid" class="mt-4 p-4 bg-green-100 border border-green-300 rounded-lg">
                          <div class="flex items-center">
                              <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                              </svg>
                              <span class="text-green-800 font-semibold">Advance Payment Received!</span>
                          </div>
                          <p class="text-green-700 text-sm mt-1">You can now proceed to Step 5 to enter your final wedding details.</p>
                      </div>
                  </div>
              </div>
          </div>

          </div>

      {{-- Step 5: Wedding Date --}}
      <div id="step-5" class="step-container" :class="{ 'active': currentStep === 5 }" x-cloak>
          <h2 class="text-2xl font-semibold mb-6 text-gray-800">Confirm Wedding Details & Date</h2>
          <div class="error-message text-red-600 text-sm my-2 hidden" id="error-step-5"></div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
              <div>
                  <h3 class="text-xl font-semibold mb-4 text-gray-700">Couple's Information</h3>
                  <div class="space-y-4">
                      <div>
                          <label for="groom-name" class="block text-sm font-medium text-gray-700">Groom's Full Name</label>
                          <input type="text" id="groom-name" name="groom_name_input" x-model="formData.weddingDetails.groomName" class="form-input w-full" required placeholder="Groom's Name" @input="validateField('weddingDetails.groomName')">
                          <p x-show="validationErrors['weddingDetails.groomName']" class="error-message" x-text="validationErrors['weddingDetails.groomName']"></p>
                      </div>
                      <div>
                          <label for="bride-name" class="block text-sm font-medium text-gray-700">Bride's Full Name</label>
                          <input type="text" id="bride-name" name="bride_name_input" x-model="formData.weddingDetails.brideName" class="form-input w-full" required placeholder="Bride's Name" @input="validateField('weddingDetails.brideName')">
                          <p x-show="validationErrors['weddingDetails.brideName']" class="error-message" x-text="validationErrors['weddingDetails.brideName']"></p>
                      </div>
                       <div>
                          <label for="groom-email" class="block text-sm font-medium text-gray-700">Groom's Email</label>
                          <input type="email" id="groom-email" name="groom_email_input" x-model="formData.weddingDetails.groomEmail" class="form-input w-full" placeholder="groom@example.com" @input="validateField('weddingDetails.groomEmail')">
                          <p x-show="validationErrors['weddingDetails.groomEmail']" class="error-message" x-text="validationErrors['weddingDetails.groomEmail']"></p>
                      </div>
                      <div>
                          <label for="bride-email" class="block text-sm font-medium text-gray-700">Bride's Email</label>
                          <input type="email" id="bride-email" name="bride_email_input" x-model="formData.weddingDetails.brideEmail" class="form-input w-full" placeholder="bride@example.com" @input="validateField('weddingDetails.brideEmail')">
                          <p x-show="validationErrors['weddingDetails.brideEmail']" class="error-message" x-text="validationErrors['weddingDetails.brideEmail']"></p>
                      </div>
                      <div>
                          <label for="groom-phone" class="block text-sm font-medium text-gray-700">Groom's Phone</label>
                          <input type="tel" id="groom-phone" name="groom_phone_input" x-model="formData.weddingDetails.groomPhone" class="form-input w-full" required pattern="[0-9]{10}" placeholder="07XXXXXXXX" @input="validateField('weddingDetails.groomPhone')">
                          <p x-show="validationErrors['weddingDetails.groomPhone']" class="error-message" x-text="validationErrors['weddingDetails.groomPhone']"></p>
                      </div>
                      <div>
                          <label for="bride-phone" class="block text-sm font-medium text-gray-700">Bride's Phone</label>
                          <input type="tel" id="bride-phone" name="bride_phone_input" x-model="formData.weddingDetails.bridePhone" class="form-input w-full" pattern="[0-9]{10}" placeholder="07XXXXXXXX" @input="validateField('weddingDetails.bridePhone')">
                          <p x-show="validationErrors['weddingDetails.bridePhone']" class="error-message" x-text="validationErrors['weddingDetails.bridePhone']"></p>
                      </div>
                  </div>
              </div>

              <div>
                  <h3 class="text-xl font-semibold mb-4 text-gray-700">Wedding Date & Schedule</h3>
                  <div class="space-y-4">
                      <div>
                          <label for="wedding-date-input" class="block mb-1 text-sm font-medium text-gray-700">Preferred Wedding Date</label>
                          <input type="date" id="wedding-date-input" name="wedding_date_input_field" x-model="formData.weddingDetails.weddingDate"
                                 @change="validateField('weddingDetails.weddingDate')"
                                 :min="getMinDateForBooking(90)" class="form-input w-full" required>
                          <p x-show="validationErrors['weddingDetails.weddingDate']" class="error-message" x-text="validationErrors['weddingDetails.weddingDate']"></p>
                          <p class="text-xs text-gray-500 mt-1">Must be at least 3 months from today.</p>
                      </div>
                      <div>
                          <label for="alt-date1" class="block mb-1 text-sm font-medium text-gray-700">Alternative Date 1 (Optional)</label>
                          <input type="date" id="alt-date1" name="alt_date1_input" x-model="formData.weddingDetails.alternativeDate1"
                                 :min="formData.weddingDetails.weddingDate || getMinDateForBooking(90)" class="form-input w-full">
                      </div>
                      <div>
                          <label for="alt-date2" class="block mb-1 text-sm font-medium text-gray-700">Alternative Date 2 (Optional)</label>
                          <input type="date" id="alt-date2" name="alt_date2_input" x-model="formData.weddingDetails.alternativeDate2"
                                 :min="formData.weddingDetails.alternativeDate1 || formData.weddingDetails.weddingDate || getMinDateForBooking(90)" class="form-input w-full">
                      </div>
                      <div>
                          <label for="ceremony-time" class="block mb-1 text-sm font-medium text-gray-700">Ceremony Start Time</label>
                          <input type="time" id="ceremony-time" name="ceremony_time_input" x-model="formData.weddingDetails.ceremonyTime" class="form-input w-full" required @change="validateField('weddingDetails.ceremonyTime')">
                          <p x-show="validationErrors['weddingDetails.ceremonyTime']" class="error-message" x-text="validationErrors['weddingDetails.ceremonyTime']"></p>
                      </div>
                      <div>
                          <label for="reception-time" class="block mb-1 text-sm font-medium text-gray-700">Reception Start Time</label>
                          <input type="time" id="reception-time" name="reception_time_input" x-model="formData.weddingDetails.receptionTime" class="form-input w-full" required @change="validateField('weddingDetails.receptionTime')">
                          <p x-show="validationErrors['weddingDetails.receptionTime']" class="error-message" x-text="validationErrors['weddingDetails.receptionTime']"></p>
                      </div>
                  </div>
              </div>

              <div class="md:col-span-2">
                  <h3 class="text-xl font-semibold mb-4 text-gray-700">Review & Agreements</h3>
                   <div class="bg-gray-50 p-6 rounded-xl shadow mb-6">
                      <h4 class="font-bold text-lg mb-3">Venue & Guests:</h4>
                      <p><strong>Hall:</strong> <span x-text="formData.hallName || 'Not selected'"></span></p>
                      <p><strong>Booking Date:</strong> <span x-text="formData.hallBookingDate ? new Date(formData.hallBookingDate + 'T00:00:00').toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not Selected'"></span></p>
                      <p><strong>Guest Count:</strong> <span x-text="formData.customization.guestCount || 'Not Set'"></span></p>
                       <h4 class="font-bold text-lg mb-3 mt-4">Package Details:</h4>
                      <p><strong>Package:</strong> <span x-text="formData.package.id ? formData.package.id.charAt(0).toUpperCase() + formData.package.id.slice(1).replace('package-', '') : 'Not Selected'"></span></p>
                      <p><strong>Package Price:</strong> Rs. <span x-text="formData.package.price.toLocaleString()"></span></p>
                  </div>
                  <div>
                      <label for="additional-notes" class="block text-sm font-medium text-gray-700">Additional Requests / Notes for Wedding Day</label>
                      <textarea id="additional-notes" name="additional_notes_input" x-model="formData.weddingDetails.additionalNotes" rows="4" class="form-textarea w-full" placeholder="Any specific instructions for the wedding day setup, timings, etc."></textarea>
                  </div>

                  <div class="mt-6 space-y-3">
                      <div class="flex items-center">
                          <input type="checkbox" id="terms-conditions" name="terms_conditions" x-model="formData.weddingDetails.termsAgreed" class="h-4 w-4 accent-blue-600 rounded" required @change="validateField('weddingDetails.termsAgreed')">
                          <label for="terms-conditions" class="ml-2 block text-sm text-gray-900">
                              I agree to the <a href="#" class="text-blue-600 hover:underline" @click.prevent="openTermsModal()">Terms and Conditions</a>
                          </label>
                      </div>
                      <p x-show="validationErrors['weddingDetails.termsAgreed']" class="error-message" x-text="validationErrors['weddingDetails.termsAgreed']"></p>
                      <div class="flex items-center">
                          <input type="checkbox" id="privacy-policy" name="privacy_policy" x-model="formData.weddingDetails.privacyAgreed" class="h-4 w-4 accent-blue-600 rounded" required @change="validateField('weddingDetails.privacyAgreed')">
                          <label for="privacy-policy" class="ml-2 block text-sm text-gray-900">
                              I agree to the <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                          </label>
                      </div>
                       <p x-show="validationErrors['weddingDetails.privacyAgreed']" class="error-message" x-text="validationErrors['weddingDetails.privacyAgreed']"></p>
                  </div>
              </div>
          </div>

          <div class="mt-8 flex justify-between">
              <button type="button" class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-300 font-semibold" @click="prevStep()">Back</button>
              <button type="button" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 font-semibold" @click="handleNext(5)">Proceed to Summary</button>
          </div>
      </div>

      {{-- Step 6: Summary & Bill --}}
      <div id="step-6" class="step-container" :class="{ 'active': currentStep === 6 }" x-cloak>
          <h2 class="text-2xl font-semibold mb-6 text-gray-800">Booking Summary & Final Bill</h2>
          <div class="error-message text-red-600 text-sm my-2 hidden" id="error-step-6"></div>

          <div class="bg-gray-50 p-6 rounded-xl shadow mb-8">
              <h3 class="text-xl font-bold mb-4 text-center text-gray-800">Your Wedding Booking Details</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-gray-700">
                  <div>
                      <h4 class="font-bold text-lg mb-2">Hall & Dates:</h4>
                      <p><strong>Hall:</strong> <span x-text="formData.hallName || 'N/A'"></span></p>
                      <p><strong>Hall Booking Date:</strong> <span x-text="formData.hallBookingDate ? new Date(formData.hallBookingDate + 'T00:00:00').toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'"></span></p>
                      <p><strong>Wedding Date:</strong> <span x-text="formData.weddingDetails.weddingDate ? new Date(formData.weddingDetails.weddingDate + 'T00:00:00').toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'"></span></p>
                      <p><strong>Ceremony Time:</strong> <span x-text="formData.weddingDetails.ceremonyTime || 'N/A'"></span></p>
                      <p><strong>Ceremony Time:</strong> <span x-text="formData.weddingDetails.ceremonyTime || 'N/A'"></span></p>
                      <p><strong>Reception Time:</strong> <span x-text="formData.weddingDetails.receptionTime || 'N/A'"></span></p>
                                           <p><strong>Estimated Guests:</strong> <span x-text="formData.customization.guestCount || 'N/A'"></span></p>
                  </div>
                  <div>
                      <h4 class="font-bold text-lg mb-2">Couple & Contact Info:</h4>
                      <p><strong>Groom:</strong> <span x-text="formData.weddingDetails.groomName || 'N/A'"></span></p>
                      <p><strong>Bride:</strong> <span x-text="formData.weddingDetails.brideName || 'N/A'"></span></p>
                      <p><strong>Contact Person:</strong> <span x-text="formData.contact.name || 'N/A'"></span></p>
                      <p><strong>Contact Email:</strong> <span x-text="formData.contact.email || 'N/A'"></span></p>
                      <p><strong>Contact Phone:</strong> <span x-text="formData.contact.phone || 'N/A'"></span></p>
                  </div>
                  <div class="md:col-span-2">
                      <h4 class="font-bold text-lg mb-2">Package & Customizations:</h4>
                      <p><strong>Selected Package:</strong> <span x-text="formData.package.id ? formData.package.id.charAt(0).toUpperCase() + formData.package.id.slice(1).replace('package-', '') : 'N/A'"></span></p>
                      <p><strong>Wedding Type:</strong> <span x-text="formData.customization.weddingType || 'N/A'"></span></p>
                      <p><strong>Selected Catering Menu:</strong> <span x-text="cateringMenusData.find(m => m.id === formData.customization.catering.selectedMenuId)?.name || 'N/A'"></span></p>
                      <p class="font-medium mt-2">Selected Additional Decorations:</p>
                      <ul class="list-disc list-inside ml-4">
                          <template x-for="decorId in formData.customization.decorations.additional" :key="decorId">
                              <li x-text="findDecorationById(decorId)?.name || 'Unknown Decoration'"></li>
                          </template>
                          <li x-show="formData.customization.decorations.additional.length === 0" class="text-gray-500">None</li>
                      </ul>
                       <p class="font-medium mt-2">Selected Custom Catering Items:</p>
                        <ul class="list-disc list-inside ml-4">
                            <template x-for="(items, category) in formData.customization.catering.custom" :key="category">
                                <template x-for="item in items" :key="item.name">
                                    <li x-text="item.name"></li>
                                </template>
                            </template>
                             <li x-show="Object.keys(formData.customization.catering.custom).length === 0 || Object.values(formData.customization.catering.custom).flat().length === 0" class="text-gray-500">None</li>
                        </ul>
                      <p class="font-medium mt-2">Selected Optional Additional Services:</p>
                      <ul class="list-disc list-inside ml-4">
                          <template x-for="serviceId in formData.customization.additionalServices.selected" :key="serviceId">
                              <li x-text="getAdditionalServiceById(serviceId)?.name || 'Unknown Service'"></li>
                          </template>
                          <li x-show="formData.customization.additionalServices.selected.length === 0" class="text-gray-500">None</li>
                      </ul>
                  </div>
              </div>
          </div>

          {{-- Financial Summary --}}
          <div class="bg-white p-6 rounded-xl shadow-xl mb-8">
              <h3 class="text-xl font-bold mb-4 text-center text-gray-800">Itemized Bill</h3>
              <div class="border-b border-gray-200 pb-4 mb-4">
                  <div class="flex justify-between py-1">
                      <span class="text-gray-700">Package Cost:</span>
                      <span class="font-semibold text-gray-800">Rs. <span x-text="formData.package.price.toLocaleString()"></span></span>
                  </div>
                  <div class="flex justify-between py-1">
                      <span class="text-gray-700">Additional Decorations:</span>
                      <span class="font-semibold text-gray-800">Rs. <span x-text="totalAdditionalDecorationsCost.toLocaleString()"></span></span>
                  </div>
                  <div class="flex justify-between py-1">
                      <span class="text-gray-700">Custom Catering Items:</span>
                      <span class="font-semibold text-gray-800">Rs. <span x-text="totalCustomCateringCost.toLocaleString()"></span></span>
                  </div>
                  <div class="flex justify-between py-1">
                      <span class="text-gray-700">Paid Additional Services:</span>
                      <span class="font-semibold text-gray-800">Rs. <span x-text="totalPaidAdditionalServicesCost.toLocaleString()"></span></span>
                  </div>
                  <div class="flex justify-between py-1">
                      <span class="text-gray-700">Venue Charge (5 hours free):</span>
                      <span class="font-semibold text-gray-800">Rs. <span x-text="supplementaryChargesData.charges.venueCharge.toLocaleString()"></span></span>
                  </div>
                  <div class="flex justify-between py-1">
                      <span class="text-gray-700">Electricity Charge:</span>
                      <span class="font-semibold text-gray-800">Rs. <span x-text="supplementaryChargesData.charges.electricity.toLocaleString()"></span></span>
                  </div>
                   <div class="flex justify-between py-1">
                      <span class="text-gray-700">Multimedia Charge:</span>
                      <span class="font-semibold text-gray-800">Rs. <span x-text="supplementaryChargesData.charges.multimedia.toLocaleString()"></span></span>
                  </div>
                   <div class="flex justify-between py-1">
                      <span class="text-gray-700">Chair Covers (<span x-text="formData.customization.guestCount || 0"></span> covers):</span>
                      <span class="font-semibold text-gray-800">Rs. <span x-text="totalChairCoverCost.toLocaleString()"></span></span>
                  </div>
              </div>
              <div class="flex justify-between py-2 text-lg font-bold text-gray-800">
                  <span>Sub Total:</span>
                  <span>Rs. <span x-text="subtotalCost.toLocaleString()"></span></span>
              </div>
              <div class="flex justify-between py-1">
                  <span class="text-gray-700">Service Charge (10%):</span>
                  <span class="font-semibold text-gray-800">Rs. <span x-text="serviceCharge.toLocaleString()"></span></span>
              </div>
              <div class="flex justify-between py-1">
                  <span class="text-gray-700">Taxes (Based on Terms):</span>
                  <span class="font-semibold text-gray-800">Rs. <span x-text="totalTaxes.toLocaleString()"></span></span>
              </div>
              <div class="border-t border-gray-200 pt-4 mt-4 flex justify-between text-xl font-bold text-blue-700">
                  <span>Grand Total:</span>
                  <span>Rs. <span x-text="grandTotalCost.toLocaleString()"></span></span>
              </div>
              
              <!-- Advance Payment Deduction (if paid) -->
              <div x-show="advancePaymentPaid" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                  <h4 class="text-lg font-semibold text-green-800 mb-3">Payment Summary</h4>
                  <div class="space-y-2">
                      <div class="flex justify-between">
                          <span class="text-green-700">Advance Payment (20%):</span>
                          <span class="font-semibold text-green-800">Rs. <span x-text="Math.round(grandTotalCost * 0.20).toLocaleString()"></span></span>
                      </div>
                      <div class="flex justify-between text-lg font-bold text-green-800 border-t border-green-300 pt-2">
                          <span>Remaining Balance:</span>
                          <span>Rs. <span x-text="Math.round(grandTotalCost * 0.80).toLocaleString()"></span></span>
                      </div>
                  </div>
                  <p class="text-sm text-green-600 mt-2">
                      The advance payment has been deducted from your total. The remaining balance is due on your wedding day.
                  </p>
              </div>
              <div class="mt-6 text-center">
                  <button type="button" @click="printBill()" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-300 font-semibold">Print Bill</button>
              </div>

              <!-- Printable Bill Section -->
              <div id="printable-bill" class="hidden print:block bg-white p-10 rounded-xl shadow-xl max-w-2xl mx-auto text-black">
                  <div class="flex items-center justify-between mb-8">
                      <div>
                          <img src="{{ asset('storage/halls/logo45.png') }}" alt="Wet Water Resort Logo" class="h-16 mb-2">
                          <div class="font-bold text-xl text-blue-900">Wet Water Resort</div>
                          <div class="text-sm text-gray-700">123 Resort Lane, Colombo, Sri Lanka<br>+94 11 234 5678 | info@wetwaterresort.com</div>
                      </div>
                      <div class="text-right">
                          <div class="font-bold text-2xl text-gray-800">OFFICIAL BILL</div>
                          <div class="text-sm text-gray-600">Date: <span x-text="new Date().toLocaleDateString()"></span></div>
                          <div class="text-sm text-gray-600">Booking Ref: <span x-text="formData.bookingRef || 'N/A'"></span></div>
                      </div>
                  </div>
                  <hr class="mb-6">
                  <div class="mb-6">
                      <div class="font-semibold text-lg text-gray-800 mb-1">Customer/Event Details</div>
                      <div class="text-sm text-gray-700">
                          <div><b>Customer:</b> <span x-text="formData.contact.name"></span></div>
                          <div><b>Email:</b> <span x-text="formData.contact.email"></span></div>
                          <div><b>Phone:</b> <span x-text="formData.contact.phone"></span></div>
                          <div><b>Event:</b> Wedding</div>
                          <div><b>Hall:</b> <span x-text="formData.hallName"></span></div>
                          <div><b>Booking Date:</b> <span x-text="formData.hallBookingDate"></span></div>
                          <div><b>Wedding Date:</b> <span x-text="formData.weddingDetails.weddingDate"></span></div>
                          <div><b>Package:</b> <span x-text="formData.package.id ? formData.package.id.charAt(0).toUpperCase() + formData.package.id.slice(1).replace('package-', '') : 'N/A'"></span></div>
                          <div><b>Guest Count:</b> <span x-text="formData.customization.guestCount"></span></div>
                      </div>
                  </div>
                  <div class="mb-6">
                      <div class="font-semibold text-lg text-gray-800 mb-2">Itemized Charges</div>
                      <table class="w-full text-sm mb-4">
                          <thead>
                              <tr class="bg-gray-100">
                                  <th class="text-left py-2 px-2">Description</th>
                                  <th class="text-right py-2 px-2">Amount (LKR)</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td class="py-1 px-2">Venue Charge</td>
                                  <td class="py-1 px-2 text-right" x-text="supplementaryChargesData.charges.venueCharge.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Package (<span x-text="formData.package.id ? formData.package.id.charAt(0).toUpperCase() + formData.package.id.slice(1).replace('package-', '') : 'N/A'"></span>)</td>
                                  <td class="py-1 px-2 text-right" x-text="formData.package.price.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Additional Decorations</td>
                                  <td class="py-1 px-2 text-right" x-text="totalAdditionalDecorationsCost.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Custom Catering Items</td>
                                  <td class="py-1 px-2 text-right" x-text="totalCustomCateringCost.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Paid Additional Services</td>
                                  <td class="py-1 px-2 text-right" x-text="totalPaidAdditionalServicesCost.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Electricity Charge</td>
                                  <td class="py-1 px-2 text-right" x-text="supplementaryChargesData.charges.electricity.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Multimedia Charge</td>
                                  <td class="py-1 px-2 text-right" x-text="supplementaryChargesData.charges.multimedia.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Chair Covers</td>
                                  <td class="py-1 px-2 text-right" x-text="totalChairCoverCost.toLocaleString()"></td>
                              </tr>
                              <tr class="font-bold border-t">
                                  <td class="py-2 px-2">Sub Total</td>
                                  <td class="py-2 px-2 text-right" x-text="subtotalCost.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Service Charge (10%)</td>
                                  <td class="py-1 px-2 text-right" x-text="serviceCharge.toLocaleString()"></td>
                              </tr>
                              <tr>
                                  <td class="py-1 px-2">Taxes (5%)</td>
                                  <td class="py-1 px-2 text-right" x-text="totalTaxes.toLocaleString()"></td>
                              </tr>
                              <tr class="font-bold border-t text-lg">
                                  <td class="py-2 px-2">Grand Total</td>
                                  <td class="py-2 px-2 text-right text-blue-900" x-text="grandTotalCost.toLocaleString()"></td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
                  <div class="mb-6">
                      <div class="font-semibold text-lg text-gray-800 mb-1">Terms & Payment</div>
                      <ul class="text-xs text-gray-700 list-disc ml-6">
                          <template x-for="term in summaryTermsAndConditions" :key="term">
                              <li x-text="term"></li>
                          </template>
                      </ul>
                  </div>
                  <div class="flex justify-between items-end mt-8">
                      <div class="text-xs text-gray-600">Generated by Wet Water Resort Booking System</div>
                      <div class="text-right">
                          <div class="h-12 border-b border-gray-400 w-48 mb-1"></div>
                          <div class="text-xs text-gray-700">Authorized Signature</div>
                      </div>
                  </div>
              </div>

              <style>
                  @media print {
                      body * { visibility: hidden !important; }
                      #printable-bill, #printable-bill * { visibility: visible !important; }
                      #printable-bill { position: absolute; left: 0; top: 0; width: 100vw; background: white; z-index: 9999; }
                  }
              </style>
          </div>

          <div class="mt-8 p-6 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg text-center" role="alert">
              <h3 class="text-2xl font-bold text-blue-800 mb-3">Booking Confirmed!</h3>
              <p class="text-lg mb-4">Your wedding booking has been successfully placed.</p>
              <h4 class="font-semibold mb-2">Important Terms & Conditions:</h4>
              <ul class="list-disc list-inside text-sm text-blue-800 space-y-1 text-left inline-block">
                  <template x-for="term in summaryTermsAndConditions" :key="term">
                      <li x-text="term"></li>
                  </template>
              </ul>
          </div>

          <div class="mt-8 flex justify-between">
              <button type="button" class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors duration-300 font-semibold" @click="prevStep()">Back</button>
              <div class="flex space-x-4">
                  <button type="button" @click="testSubmission()" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-300 font-semibold">Test Submission</button>
                  <button type="button" @click="ajaxSubmission()" class="px-8 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-300 font-semibold">AJAX Submit</button>
                  <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 font-semibold">Finalize & Submit</button>
              </div>
          </div>
          
          <!-- Debug Info Panel -->
          <div class="mt-8 p-4 bg-gray-100 rounded-lg" x-show="debugInfo">
              <h3 class="text-lg font-semibold mb-2">Debug Information</h3>
              <pre class="text-xs overflow-auto max-h-60 bg-white p-2 rounded" x-text="JSON.stringify(debugInfo, null, 2)"></pre>
          </div>
      </div>
    </form>
  </div>
</main>

<script>
  // Helper to ensure a date is in YYYY-MM-DD format
  function formatDate(date) {
    const d = new Date(date);
    let month = '' + (d.getMonth() + 1);
    let day = '' + d.getDate();
    const year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
  }

  // Alpine.js main component
  function weddingBooking() {
    return {
      // Step Management
      currentStep: 1,
      totalSteps: 6,
      // Data Model
      formData: {
        hallId: null,
        hallName: null,
        hallBookingDate: null, // YYYY-MM-DD
        package: {
          id: null,
          price: 0,
        },
        customization: {
          guestCount: 100, // Default for easy testing
          activeTab: 'wedding-type',
          weddingType: null,
          weddingTypeTimeSlot: null,
          catholicDay1Date: null,
          catholicDay2Date: null,
          decorations: {
            additional: [], // Array of selected decoration IDs
          },
          catering: {
            selectedMenuId: null,
            custom: {}, // { category: [{id, name, price}] }
          },
          additionalServices: {
            selected: [], // Array of selected service IDs
          },
        },
        contact: {
          name: window.bookingUserName || '',
          email: window.bookingUserEmail || '',
          phone: '',
          visitPurpose: '',
          visitPurposeOther: '',
          specialRequests: '',
        },
        visitDate: null, // YYYY-MM-DD
        visitTime: null,
        weddingDetails: {
          groomName: '',
          brideName: '',
          groomEmail: '',
          brideEmail: '',
          groomPhone: '',
          bridePhone: '',
          weddingDate: null, // YYYY-MM-DD
          alternativeDate1: null, // YYYY-MM-DD
          alternativeDate2: null, // YYYY-MM-DD
          ceremonyTime: null, // HH:MM
          receptionTime: null, // HH:MM
          additionalNotes: '',
          termsAgreed: false,
          privacyAgreed: false,
        },
      },
      // Validation errors
      validationErrors: {},
      hallBookingError: '',
      guestCountError: '',
      weddingTypeError: '',
      weddingTypeTimeSlotError: '',
      catholicDay1DateError: '',
      catholicDay2DateError: '',
      cateringMenuError: '',
      visitPurposeError: '',
      visitDateError: '',
      visitTimeError: '',

      // Calendar for Hall Selection
      currentMonth: new Date().getMonth(),
      currentYear: new Date().getFullYear(),
      monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      // Simulated booked dates for halls (key: hallId, value: array of 'YYYY-MM-DD')
      hallBookings: {
        'grand-ballroom': ['2025-06-10', '2025-07-15'],
        'garden-pavilion': ['2025-06-12', '2025-07-20', '2025-08-05'],
        'jubilee-ballroom': ['2025-06-25', '2025-07-01'],
      },
      // Simulated booked times for visit dates (key: YYYY-MM-DD, value: array of 'HH:MM')
      visitBookings: {
        '2025-06-14': ['10:00', '11:00'],
        '2025-06-15': ['14:00'],
      },
      visitTimeSlots: ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'],
      visitSubmitted: false, // For Step 4 confirmation message
      visitConfirmed: false, // Real backend confirmation status
      advancePaymentPaid: false, // Real advance payment status
      bookingId: null, // Current booking ID for status checking
      statusCheckInterval: null, // For polling booking status
      
      // Data sources (can be fetched from API in production)
      hallsData: [
        {
          id: 'jubilee-ballroom',
          name: 'Jubilee Ballroom',
          image: '{{ asset('storage/halls/jublieeballroom.jpg') }}',
          price: 4200,
          capacity: 200,
          description: 'Transform your day into a fairytale with this octagonal, pillarless ballroom, adorned with Victorian skylights and colonial charm.',
          features: [
            'Indoor',
            '7,956 sq ft',
            'Up to 200 Guests'
          ]
        },
        {
          id: 'grand-ballroom',
          name: 'Grand Ballroom',
          image: '{{ asset('storage/halls/GrandBallroom.jpg') }}',
          price: 5500,
          capacity: 500,
          description: 'Celebrate in unparalleled luxury with crystal chandeliers, a grand stage, and cutting-edge acoustics for a majestic wedding.',
          features: [
            'Indoor',
            '10,000 sq ft',
            'Up to 500 Guests'
          ]
        },
        {
          id: 'garden-pavilion',
          name: 'Garden Pavilion',
          image: '{{ asset('storage/halls/GardenPavilion.jpg') }}',
          price: 3500,
          capacity: 300,
          description: 'Embrace nature’s embrace in this romantic outdoor pavilion, surrounded by lush gardens and twinkling string lights.',
          features: [
            'Outdoor',
            '7,500 sq ft',
            'Up to 300 Guests'
          ]
        },
        {
          id: 'royal-heritage-hall',
          name: 'Royal Heritage Hall',
          image: '{{ asset('storage/halls/RoyalHeritage.jpg') }}',
          price: 4800,
          capacity: 200,
          description: 'Honor tradition with this culturally rich hall, blending Sri Lankan heritage with modern elegance for a timeless wedding.',
          features: [
            'Indoor',
            '5,000 sq ft',
            'Up to 200 Guests'
          ]
        },
        {
          id: 'riverside-garden',
          name: 'Riverside Garden',
          image: '{{ asset('storage/halls/Riverside Garden.jpg') }}',
          price: 2500,
          capacity: 150,
          description: 'Celebrate your special day surrounded by nature\'s tranquility. The riverside garden offers a scenic backdrop of flowing water and greenery for a romantic outdoor ceremony.',
          features: [
            'Semi-outdoor',
            '4,000 sq ft',
            'Up to 150 Guests'
          ]
        }
      ],
      // DYNAMIC PACKAGES FROM DATABASE - Updated automatically when admin changes packages
      packagesData: @json($packagesData),
      weddingTypesData: [
        { value: 'Kandyan Wedding', label: 'Kandyan Wedding', image: "{{ asset('storage/halls/kandiayn_type.jpg') }}", desc: 'Traditional Sri Lankan elegance with cultural attire & rituals.' },
        { value: 'Low-Country Wedding', label: 'Low-Country Wedding', image: "{{ asset('storage/halls/low_country.jpg') }}", desc: 'Southern Sri Lankan traditions with cultural décor and attire.' },
        { value: 'European Wedding', label: 'European Wedding', image: "{{ asset('storage/halls/eurpian_type.jpg') }}", desc: 'Elegant white-themed wedding with floral arches and refined charm.' },
        { value: 'Indian Wedding', label: 'Indian Wedding', image: "{{ asset('storage/halls/Indian_type.jpg') }}", desc: 'Vibrant mandap setups, colorful drapery, traditional motifs.' },
        { value: 'Catholic Wedding', label: 'Catholic Wedding', image: "{{ asset('storage/halls/catholic_type.jpg') }}", desc: 'Church ceremonies with grace, roses, and white floral arrangements.' }
      ],
      // This data represents the parsed Catering.txt content
      cateringMenusData: [
          {
              id: 'menu-01',
              name: 'FUNCTION MENU NO. 01',
              description: 'A delightful selection for a standard function.',
              details: {
                  "Welcome Drink": ["Guava and Strawberry", "Sprite with Mint and Lemon Mocktail", "Mix Fruit Punch", "Orange Juice"],
                  "Starter": ["Creamy Sweet Corn Soup with Spring Roll"],
                  "Main Menu": {
                      "Rice Options": ["Suduru Samba Yellow Rice", "Basmati Vegetable Fried Rice"],
                      "Protein Options": {
                          "Chicken": ["Tandoori Chicken", "Nasi Chicken", "Black Bean Chicken"],
                          "Fish": ["Thai Style", "Devilled", "Jaffna or Indian Style"],
                          "Prawns": ["Grilled", "Carnation Milk Curry", "Crispy Batter Fried"],
                          "Cuttlefish": ["Hot Butter Cuttlefish"],
                          "Mutton": ["Spicy Curry", "Stew", "Indian Style or Sri Lankan Style"]
                      },
                      "Vegetarian Options": ["Cashew Nut Curry", "Grilled Vegetable Kuruma", "Dhal Curry"],
                      "Accompaniments": ["Fish Cutlet", "Brinjal Moju", "Green Bean Tempered", "Gotukola Sambol", "Pineapple Salsa", "Papadum"]
                  },
                  "Dessert": ["Fresh Fruit Platter", "Ice Cream with Chocolate Sauce", "Watalappan", "Caramel Pudding"]
              }
          },
          {
              id: 'menu-02',
              name: 'FUNCTION MENU NO. 02',
              description: 'An elevated experience with diverse culinary choices.',
              details: {
                  "Welcome Drink": ["Passion Fruit Fizz", "Blueberry Lemonade", "Ginger Beer with Lime", "Apple Cider"],
                  "Starter": ["Cream of Mushroom Soup", "Chicken and Corn Soup"],
                  "Main Menu": {
                      "Rice Options": ["Ghee Rice", "Biriyani Rice"],
                      "Protein Options": {
                          "Chicken": ["Butter Chicken", "Chicken Korma", "Chilli Chicken"],
                          "Fish": ["Baked Fish with Lemon Butter Sauce", "Spicy Fish Curry"],
                          "Prawns": ["Garlic Butter Prawns", "Devilled Prawns"],
                          "Lamb": ["Roasted Lamb with Mint Sauce"]
                      },
                      "Vegetarian Options": ["Paneer Butter Masala", "Mix Vegetable Curry", "Potato Tempered"],
                      "Accompaniments": ["Malay Pickle", "Cucumber Raita", "Mint Sambol", "Fried Cashew"]
                  },
                  "Dessert": ["Chocolate Mousse", "Fruit Trifle", "Jelly Pudding", "Curd and Treacle"]
              }
          },
          {
              id: 'menu-03',
              name: 'FUNCTION MENU NO. 03',
              description: 'A premium menu tailored for sophisticated tastes.',
              details: {
                  "Welcome Drink": ["Peach Iced Tea", "Watermelon Cooler", "Kiwi Delight"],
                  "Starter": ["Seafood Bisque", "Tomato Basil Soup with Croutons"],
                  "Main Menu": {
                      "Rice Options": ["Saffron Pilaf Rice", "Mediterranean Rice"],
                      "Protein Options": {
                          "Chicken": ["Chicken Cordon Bleu", "Grilled Chicken with Mushroom Sauce"],
                          "Fish": ["Grilled Salmon with Dill Sauce", "Fish Ambulthiyal"],
                          "Prawns": ["Garlic Butter Prawns", "Devilled Prawns", "Batter Fried Prawns"],
                          "Mutton": ["Spicy mutton black curry", "Devilled mutton"]
                      },
                      "Vegetarian Options": ["Vegetable Lasagna", "Roasted Vegetable Medley", "Green Salad with Vinaigrette"],
                      "Accompaniments": ["Assorted Bread Rolls with Butter", "Coleslaw", "Mixed Green Salad", "Crispy Poppadums"]
                  },
                  "Dessert": ["Black Forest Gateau", "Strawberry Cheesecake", "Profiteroles with Chocolate Sauce", "Assorted Pastries"]
              }
          },
          {
              id: 'wedding-package-04',
              name: 'Wedding Package 04',
              description: 'A comprehensive wedding catering package.',
              details: {
                  "Welcome Drink": ["Pineapple and Ginger", "Ginger infused sweet melon juice", "Tamarind juice"],
                  "Soups": ["Hot and sour chicken soup", "Butter-baked carrot and lentil soup"],
                  "Salads": ["Mediterranean salad", "Potato salad with spring onion", "Coleslaw", "Fresh garden salad"],
                  "Mains": {
                      "Rice Options": ["Yellow rice", "Basmati vegetable fried rice", "Arabian Arabic rice"],
                      "Protein Options": {
                          "Chicken": ["Devilled chicken", "Chicken black curry", "Roasted chicken"],
                          "Fish": ["Deep-fried fish with sweet and sour sauce", "Fish ambulthiyal"],
                          "Prawns": ["Devilled prawns", "Prawns black curry"],
                          "Cuttlefish": ["Hot butter cuttlefish"],
                          "Lamb": ["Roasted lamb with rosemary sauce"]
                      },
                      "Vegetarian": ["Dhal curry", "Brinjal moju", "Cashew nut curry", "Soya meat curry", "Mixed vegetable curry"],
                      "Accompaniments": ["Papadum", "Chutney", "Pickle"]
                  },
                  "Desserts": ["Cream caramel", "Watalappan", "Chocolate mousse", "Fruit trifle", "Fresh fruit platter"],
                  "Soft Drinks": ["Coca-Cola", "Sprite", "Fanta"],
              }
          },
          {
              id: 'wedding-package-05',
              name: 'Wedding Package 05',
              description: 'The ultimate culinary experience for your special day.',
              details: {
                  "Welcome Drink": ["Mixed fruit juice", "Passion fruit", "Fresh orange", "Mango", "Lime", "Guava", "Strawberry"],
                  "Soups": ["Spicy mutton broth", "Chicken, mushroom and egg drop soup", "Creamy chicken and leek soup"],
                  "Salads": ["Smoked fish salad", "Coleslaw", "Hot butter cuttlefish salad", "Potato salad", "Fresh garden salad"],
                  "Mains": {
                      "Rice Options": ["Saffron rice", "Vegetable fried rice", "Nasi goreng"],
                      "Protein Options": {
                          "Chicken": ["Black bean chicken", "Spicy chicken red curry", "Devilled chicken (boneless)"],
                          "Fish": ["Grilled fish with lemon sauce", "Fish and chips"],
                          "Prawns": ["Prawns tempura", "Devilled prawns (spicy)"],
                          "Cuttlefish": ["Spicy devilled cuttlefish"],
                          "Mutton": ["Mutton khorma", "Spicy mutton red curry"]
                      },
                      "Vegetarian": ["Paneer butter masala", "Mushroom curry", "Baby corn and carrot curry", "Tempered potatoes"],
                      "Accompaniments": ["Assorted naan bread", "Raitha", "Chutney platter"]
                  },
                  "Desserts": ["Strawberry cheesecake", "Black forest cake", "Pudding selection", "Ice cream with toppings", "Fresh fruit platter"],
                  "Soft Drinks": ["Coca-Cola", "Sprite", "Fanta", "Mineral Water"],
              }
          }
      ],
      customCateringOptionsData: { // Based on the "Make Your Own Menu" section
          "Welcome Drinks": [
              { name: "Guava and Strawberry", price: 500, unit:"glass" },
              { name: "Sprite with Mint and Lemon Mocktail", price: 520, unit:"glass" },
              { name: "Mix Fruit Punch", price: 480, unit:"glass" },
              { name: "Orange Juice", price: 450, unit:"glass" },
              { name: "Pineapple and Ginger", price: 500, unit:"glass" },
              { name: "Ginger infused sweet melon juice", price: 520, unit:"glass" },
              { name: "Tamarind juice", price: 480, unit:"glass" },
          ],
          "Fish & Seafood (per kg)": [
              { name: "Devilled Fish", price: 5000, unit:"kg" },
              { name: "Thai Style Fish", price: 5500, unit:"kg" },
              { name: "Jaffna Style Fish", price: 5500, unit:"kg" },
              { name: "Grilled Prawns", price: 7000, unit:"kg" },
              { name: "Hot Butter Cuttlefish", price: 6500, unit:"kg" },
          ],
          "Chicken (per kg)": [
              { name: "Devilled Chicken (with bone)", price: 4000, unit:"kg" },
              { name: "Tandoori Chicken", price: 4500, unit:"kg" },
              { name: "Nasi Chicken", price: 4200, unit:"kg" },
          ],
          "Mutton/Lamb (per kg)": [
              { name: "Spicy Mutton Black Curry", price: 7500, unit:"kg" },
              { name: "Roasted Lamb with Rosemary Sauce", price: 8000, unit:"kg" },
          ],
          "Desserts (per portion)": [
              { name: "Chocolate Mousse", price: 350, unit:"portion" },
              { name: "Strawberry Cheesecake", price: 400, unit:"portion" },
              { name: "Black Forest Cake", price: 400, unit:"portion" },
          ],
          "Miscellaneous": [
              { name: "Bombay Mixture (400g)", price: 600, unit:"pack" },
              { name: "Wade (each)", price: 100, unit:"each" },
          ]
      },
      // Wedding type-specific decorations (3 free + 5 paid per type)
      additionalDecorationsData: {
        'Kandyan Wedding': {
          free: [
            { id: 'kandyan-basic-floral', name: 'Basic Floral Arrangements', description: 'Rustic Kandyan-style bouquets for tables and aisles.', price: 0, image: 'https://images.unsplash.com/photo-1484676681417-64a0ea3475fd?q=80&w=5616&auto=format&fit=crop&ixlib=rb-4.0.3' },
            { id: 'kandyan-welcome-board', name: 'Personalized Welcome Board', description: 'Custom wooden sign with Kandyan motifs.', price: 0, image: 'https://images.unsplash.com/photo-1621217039534-3c8c7f2a1a4f?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'kandyan-aisle-runner', name: 'Aisle Runner', description: 'Traditional white or red runner, edged with fresh greenery.', price: 0, image: 'https://images.unsplash.com/photo-1587313077793-118182b88137?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ],
          paid: [
            { id: 'kandyan-floral-arch', name: 'Grand Floral Arch', description: 'Floral arch at entrance, using local temple-flowers.', price: 25000, image: 'https://images.unsplash.com/photo-1577708579089-a29d5b7a1e0b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'kandyan-chair-covers', name: 'Chair Covers (White/Cream/Black)', description: 'With Kandyan fabric bows.', price: 100, image: 'https://images.unsplash.com/photo-1559132145-128a1c910398?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'kandyan-lighting', name: 'Uplighting & Special Effects', description: 'Soft lantern-style LEDs emphasizing pillars and foliage.', price: 15000, image: 'https://images.unsplash.com/photo-1517441589140-5e365b2635fe?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'kandyan-centerpieces', name: 'Premium Table Centerpieces', description: 'Vases with orchids, jasmine, and betel leaves.', price: 8000, image: 'https://images.unsplash.com/photo-1513262834354-6b2bca9b5b8d?auto=format&fit=crop&w=800&q=80' },
            { id: 'kandyan-photo-booth', name: 'Custom Photo Booth Backdrop', description: 'With traditional Kandyan wood-carved door frame look.', price: 10000, image: 'https://images.unsplash.com/photo-1620894541999-9b43e86fcf7c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ]
        },
        'Low-Country Wedding': {
          free: [
            { id: 'lowcountry-basic-floral', name: 'Basic Floral Arrangements', description: 'Tropical bouquets of frangipani, lotus for tables/aisles.', price: 0, image: 'https://images.unsplash.com/photo-1484676681417-64a0ea3475fd?q=80&w=5616&auto=format&fit=crop&ixlib=rb-4.0.3' },
            { id: 'lowcountry-welcome-board', name: 'Personalized Welcome Board', description: 'Styled with coastal and Low‑Country motifs.', price: 0, image: 'https://images.unsplash.com/photo-1621217039534-3c8c7f2a1a4f?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'lowcountry-aisle-runner', name: 'Aisle Runner', description: 'Natural-fiber runner (jute or raffia), white or red.', price: 0, image: 'https://images.unsplash.com/photo-1587313077793-118182b88137?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ],
          paid: [
            { id: 'lowcountry-floral-arch', name: 'Grand Floral Arch', description: 'Arranged with tropical blooms and palm fronds.', price: 25000, image: 'https://images.unsplash.com/photo-1577708579089-a29d5b7a1e0b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'lowcountry-chair-covers', name: 'Chair Covers', description: 'White/cream/black with raffia bows.', price: 100, image: 'https://images.unsplash.com/photo-1559132145-128a1c910398?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'lowcountry-lighting', name: 'Uplighting & Special Effects', description: 'Warm yellow uplights to mimic sunset.', price: 15000, image: 'https://images.unsplash.com/photo-1517441589140-5e365b2635fe?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'lowcountry-centerpieces', name: 'Premium Table Centerpieces', description: 'Shells, driftwood, candles with tropical flowers.', price: 8000, image: 'https://images.unsplash.com/photo-1513262834354-6b2bca9b5b8d?auto=format&fit=crop&w=800&q=80' },
            { id: 'lowcountry-photo-booth', name: 'Custom Photo Booth Backdrop', description: 'Designed with a coastal port/Low‑Country village scene.', price: 10000, image: 'https://images.unsplash.com/photo-1620894541999-9b43e86fcf7c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ]
        },
        'European Wedding': {
          free: [
            { id: 'european-basic-floral', name: 'Basic Floral Arrangements', description: 'White roses and lilies for tables and aisles.', price: 0, image: 'https://images.unsplash.com/photo-1484676681417-64a0ea3475fd?q=80&w=5616&auto=format&fit=crop&ixlib=rb-4.0.3' },
            { id: 'european-welcome-board', name: 'Personalized Welcome Board', description: 'Elegant calligraphy on whiteboard or chalkboard.', price: 0, image: 'https://images.unsplash.com/photo-1621217039534-3c8c7f2a1a4f?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'european-aisle-runner', name: 'Aisle Runner', description: 'Crisp white or red runner.', price: 0, image: 'https://images.unsplash.com/photo-1587313077793-118182b88137?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ],
          paid: [
            { id: 'european-floral-arch', name: 'Grand Floral Arch', description: 'Luxurious rose and hydrangea arch.', price: 25000, image: 'https://images.unsplash.com/photo-1577708579089-a29d5b7a1e0b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'european-chair-covers', name: 'Chair Covers', description: 'Elegant covers with satin bows in soft pastels or black.', price: 100, image: 'https://images.unsplash.com/photo-1559132145-128a1c910398?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'european-lighting', name: 'Uplighting & Special Effects', description: 'Cool white or pastel LED uplighting.', price: 15000, image: 'https://images.unsplash.com/photo-1517441589140-5e365b2635fe?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'european-centerpieces', name: 'Premium Table Centerpieces', description: 'Silver vases, taper candles with soft white blooms.', price: 8000, image: 'https://images.unsplash.com/photo-1513262834354-6b2bca9b5b8d?auto=format&fit=crop&w=800&q=80' },
            { id: 'european-photo-booth', name: 'Custom Photo Booth Backdrop', description: 'A "European garden" themed wall with roses & greenery.', price: 10000, image: 'https://images.unsplash.com/photo-1620894541999-9b43e86fcf7c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ]
        },
        'Indian Wedding': {
          free: [
            { id: 'indian-basic-floral', name: 'Basic Floral Arrangements', description: 'Marigolds, roses for tables and aisles.', price: 0, image: 'https://images.unsplash.com/photo-1484676681417-64a0ea3475fd?q=80&w=5616&auto=format&fit=crop&ixlib=rb-4.0.3' },
            { id: 'indian-welcome-board', name: 'Personalized Welcome Board', description: 'With bright Indian patterns/hues.', price: 0, image: 'https://images.unsplash.com/photo-1621217039534-3c8c7f2a1a4f?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'indian-aisle-runner', name: 'Aisle Runner', description: 'Red or white runner, optionally with rangoli border.', price: 0, image: 'https://images.unsplash.com/photo-1587313077793-118182b88137?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ],
          paid: [
            { id: 'indian-floral-arch', name: 'Grand Floral Arch', description: 'Colorful marigold and jasmine arch.', price: 25000, image: 'https://images.unsplash.com/photo-1577708579089-a29d5b7a1e0b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'indian-chair-covers', name: 'Chair Covers', description: 'Covers in white/cream/black with colorful sash.', price: 100, image: 'https://images.unsplash.com/photo-1559132145-128a1c910398?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'indian-lighting', name: 'Uplighting & Special Effects', description: 'Rich amber/orange/red uplights.', price: 15000, image: 'https://images.unsplash.com/photo-1517441589140-5e365b2635fe?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'indian-centerpieces', name: 'Premium Table Centerpieces', description: 'Brass bowls with floating diyas and flowers.', price: 8000, image: 'https://images.unsplash.com/photo-1513262834354-6b2bca9b5b8d?auto=format&fit=crop&w=800&q=80' },
            { id: 'indian-photo-booth', name: 'Custom Photo Booth Backdrop', description: 'Designed like a mandap or rangoli arch.', price: 10000, image: 'https://images.unsplash.com/photo-1620894541999-9b43e86fcf7c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ]
        },
        'Catholic Wedding': {
          free: [
            { id: 'catholic-basic-floral', name: 'Basic Floral Arrangements', description: 'White roses or lilies for tables/pews.', price: 0, image: 'https://images.unsplash.com/photo-1484676681417-64a0ea3475fd?q=80&w=5616&auto=format&fit=crop&ixlib=rb-4.0.3' },
            { id: 'catholic-welcome-board', name: 'Personalized Welcome Board', description: 'Elegant church-style sign with Scripture or couple\'s names.', price: 0, image: 'https://images.unsplash.com/photo-1621217039534-3c8c7f2a1a4f?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'catholic-aisle-runner', name: 'Aisle Runner', description: 'White or red runner.', price: 0, image: 'https://images.unsplash.com/photo-1587313077793-118182b88137?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ],
          paid: [
            { id: 'catholic-floral-arch', name: 'Grand Floral Arch', description: 'Roses, baby\'s breath, greenery arch near altar.', price: 25000, image: 'https://images.unsplash.com/photo-1577708579089-a29d5b7a1e0b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'catholic-chair-covers', name: 'Chair Covers', description: 'White/cream/black with satin bows.', price: 100, image: 'https://images.unsplash.com/photo-1559132145-128a1c910398?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'catholic-lighting', name: 'Uplighting & Special Effects', description: 'Soft warm LED to accent building architecture.', price: 15000, image: 'https://images.unsplash.com/photo-1517441589140-5e365b2635fe?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
            { id: 'catholic-centerpieces', name: 'Premium Table Centerpieces', description: 'Tall glass vases, floating candles, white flowers.', price: 8000, image: 'https://images.unsplash.com/photo-1513262834354-6b2bca9b5b8d?auto=format&fit=crop&w=800&q=80' },
            { id: 'catholic-photo-booth', name: 'Custom Photo Booth Backdrop', description: 'Designed like church arches or stained‑glass windows.', price: 10000, image: 'https://images.unsplash.com/photo-1620894541999-9b43e86fcf7c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
          ]
        }
      },
      // Web-researched additional services
      additionalServicesData: {
          freeCompulsory: [
              { id: 'stay-room', name: 'Complimentary Stay Room', description: 'A comfortable room for the couple prior to the event.' },
              { id: 'changing-room', name: 'Complimentary Changing Room', description: 'Private space for bridal party preparations.' },
          ],
          optionalFree: [
              { id: 'basic-photography-locs', name: 'Basic Photography Locations', description: 'Access to scenic spots within the resort for photos.', image: 'https://images.unsplash.com/photo-1549419131-7b003a746565?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
              { id: 'guest-parking', name: 'Guest Parking', description: 'Ample parking space for all attendees.', image: 'https://images.unsplash.com/photo-1533590432-84381014a478?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
              { id: 'basic-sound-system', name: 'Basic Sound System for Speeches', description: 'Microphone and speakers for announcements and toasts.', image: 'https://images.unsplash.com/photo-1511202874139-299f7d3e0984?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
          ],
          paid: [
              { id: 'multimedia', name: 'Multimedia Projector & Screen', description: 'High-quality projector and screen for presentations or videos.', price: 7500, image: 'https://images.unsplash.com/photo-1526462723049-598d1a12d1b7?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
              { id: 'live-band', name: 'Live Band Performance', description: 'Professional live music for entertainment during reception.', price: 40000, image: 'https://images.unsplash.com/photo-1506157788613-a4ef6cc2c7ad?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
              { id: 'cultural-dancers', name: 'Cultural Dancers', description: 'Traditional Sri Lankan dance performance.', price: 20000, image: 'https://images.unsplash.com/photo-1582236319889-4e0e0f3e6f9e?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
              { id: 'fireworks', name: 'Fireworks Display', description: 'Spectacular fireworks show for a grand finale.', price: 50000, image: 'https://images.unsplash.com/photo-1507727138356-9a25b2d7c5f8?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
              { id: 'photo-video-package', name: 'Professional Photography & Videography Package', description: 'Comprehensive coverage of your event by expert photographers and videographers.', price: 75000, image: 'https://images.unsplash.com/photo-1502447954930-1b7f8f9b9f9b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
          ],
      },
      supplementaryChargesData: {
          charges: {
              advancePayment: 200000, // non-refundable
              fullPaymentDaysPrior: 14,
              childrenRate: 0.50, // 50%
              venueCharge: 100000, // 5 hours free
              extraVenueHourRate: 25000, // per hour
              chairCoverRate: 100, // per cover
              hardLiquorCorkage: 500, // per bottle
              multimedia: 7500,
              electricity: 6000,
              serviceChargePercentage: 0.10, // 10%
              taxesPercentage: 0.05 // Example tax rate (5%)
          },
          notes: {
              summary: [
                  'Advance payment of LKR 200,000 is required (non-refundable).',
                  'Full payment is due 14 days prior to the event date.',
                  'Children aged 6-12 years will be charged at 50% of the adult rate.',
                  'Venue charge is LKR 100,000, which includes 5 hours free. Each additional hour is LKR 25,000.',
                  'Chair covers are LKR 100 per cover (available in white, cream, black).',
                  'Hard liquor corkage is LKR 500 per bottle.',
                  'Multimedia services cost LKR 7,500.',
                  'Electricity charge is LKR 6,000.',
                  'No hotel flower arrangements are included unless specified in the package.',
              ],
              catering: [
                  'Additional bites from selected menus are allowed.',
                  'Non-cooked food items are allowed from outside the hotel.',
                  'Soft drinks must be purchased from the hotel.',
                  '50% payment is required to confirm the booking (menu valid for 1 month if unpaid).',
                  'Functions must end by 12:00 midnight. An extra charge of LKR 10,000 per hour will apply thereafter.',
                  'Corkage-free policy for hard liquor (note: this contradicts hardLiquorCorkage charge. Assuming corkage-free means no charge, I will use 0 for calculation. If not, it means hardLiquorCorkage applies). For now, following the prompt\'s explicit mention of LKR 500/bottle for hard liquor corkage.'
              ]
          }
      },

      init() {
        // Initialize current step from localStorage or default to 1
        this.currentStep = parseInt(localStorage.getItem('currentStep') || '1');
        this.$watch('currentStep', (value) => {
          localStorage.setItem('currentStep', value);
          this.updateProgress(value);
        });
        this.updateProgress(this.currentStep);

        // Load formData from localStorage if available
        const savedFormData = localStorage.getItem('formData');
        if (savedFormData) {
          this.formData = JSON.parse(savedFormData);
        }
        // Watch formData for changes and save to localStorage (debounced for performance)
        this.$watch('formData', (value) => {
          this.debounceSaveFormData(value);
        });

        // Initialize calendar for hall selection
        this.generateCalendarDays();
      },

      debounceSaveFormData: Alpine.debounce(function(value) {
          localStorage.setItem('formData', JSON.stringify(value));
          console.log('Form data saved to local storage.');
      }, 500), // Save every 500ms after last change

      // Progress bar methods
      updateProgress(step) {
        const progressSteps = document.querySelectorAll('.progress-step');
        progressSteps.forEach((s, index) => {
          if (index + 1 < step) {
            s.classList.add('completed');
            s.classList.remove('active');
          } else if (index + 1 === step) {
            s.classList.add('active');
            s.classList.remove('completed');
          } else {
            s.classList.remove('active', 'completed');
          }
        });
      },

      nextStep() {
        this.clearErrors(); // Clear errors on step change attempt
        if (this.validateStep(this.currentStep)) {
            this.currentStep++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            // If validation fails, display a generic error or specific ones
            const errorElement = document.getElementById(`error-step-${this.currentStep}`);
            if (errorElement) {
                errorElement.textContent = "Please fill in all required fields and correct any errors before proceeding.";
                errorElement.classList.remove('hidden');
            }
        }
      },

      prevStep() {
        this.clearErrors();
        if (this.currentStep > 1) {
          this.currentStep--;
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      },

      handleNext(step) {
        this.clearErrors();
        
        // Check Step 5 access control
        if (step === 4 && this.currentStep === 4) {
            // If trying to proceed from Step 4 to Step 5
            if (!this.visitSubmitted) {
                // Submit visit request to backend
                this.submitVisitRequest();
                return; // Don't proceed to next step yet
            } else if (!this.visitConfirmed || !this.advancePaymentPaid) {
                // Visit submitted but not confirmed or payment not made
                const errorElement = document.getElementById(`error-step-${this.currentStep}`);
                if (errorElement) {
                    if (!this.visitConfirmed) {
                        errorElement.textContent = "Please wait for manager to confirm your visit request.";
                    } else {
                        errorElement.textContent = "Please wait for advance payment confirmation before proceeding to Step 5.";
                    }
                    errorElement.classList.remove('hidden');
                }
                return; // Don't proceed
            }
        }
        
        if (this.validateStep(step)) {
            this.currentStep++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
             const errorElement = document.getElementById(`error-step-${this.currentStep}`);
            if (errorElement) {
                errorElement.textContent = "Please fill in all required fields and correct any errors before proceeding.";
                errorElement.classList.remove('hidden');
            }
        }
      },

      // Visit Request Management Methods
      async submitVisitRequest() {
        try {
          // Validate step 4 before submitting
          if (!this.validateStep(4)) {
            const errorElement = document.getElementById('error-step-4');
            if (errorElement) {
              errorElement.textContent = "Please fill in all required fields before submitting visit request.";
              errorElement.classList.remove('hidden');
            }
            return;
          }

          // Show loading state
          const submitButton = document.querySelector('button[onclick="handleNext(4)"]');
          if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
          }

          // Prepare visit request data
          const visitData = {
            hall_id: this.formData.hallId,
            hall_name: this.formData.hallName,
            hall_booking_date: this.formData.hallBookingDate,
            package_id: this.formData.package.id,
            package_price: this.formData.package.price,
            customization_guest_count: this.formData.customization.guestCount,
            customization_wedding_type: this.formData.customization.weddingType,
            wedding_type_time_slot: this.formData.customization.weddingTypeTimeSlot,
            catholic_day1_date: this.formData.customization.catholicDay1Date,
            catholic_day2_date: this.formData.customization.catholicDay2Date,
            customization_decorations_additional: JSON.stringify(this.formData.customization.decorations.additional),
            customization_catering_selected_menu_id: this.formData.customization.catering.selectedMenuId,
            customization_catering_custom: JSON.stringify(this.formData.customization.catering.custom),
            customization_additional_services_selected: JSON.stringify(this.formData.customization.additionalServices.selected),
            contact_name: this.formData.contact.name,
            contact_email: this.formData.contact.email,
            contact_phone: this.formData.contact.phone,
            visit_purpose: this.formData.contact.visitPurpose,
            visit_purpose_other: this.formData.contact.visitPurposeOther,
            special_requests: this.formData.contact.specialRequests,
            visit_date: this.formData.visitDate,
            visit_time: this.formData.visitTime,
            event_date: this.formData.hallBookingDate,
            start_time: this.formData.visitTime,
            guest_count: this.formData.customization.guestCount,
            selected_menu_id: this.formData.customization.catering.selectedMenuId
          };

          // Submit to backend
          const response = await fetch('/booking/submit-visit', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json'
            },
            body: JSON.stringify(visitData)
          });

          const result = await response.json();

          if (result.success) {
            this.visitSubmitted = true;
            this.bookingId = result.booking_id; // Store booking ID for status checks
            
            // Start polling for status updates
            this.startStatusPolling();
            
            // Show success message
            const successElement = document.createElement('div');
            successElement.className = 'p-4 bg-green-100 border border-green-400 text-green-700 rounded mb-4';
            successElement.textContent = 'Visit request submitted successfully! Our manager will call you within 24 hours to confirm your visit.';
            document.getElementById('step-4').prepend(successElement);
            
            // Remove success message after 5 seconds
            setTimeout(() => {
              if (successElement.parentNode) {
                successElement.parentNode.removeChild(successElement);
              }
            }, 5000);
            
          } else {
            throw new Error(result.message || 'Failed to submit visit request');
          }

        } catch (error) {
          console.error('Error submitting visit request:', error);
          const errorElement = document.getElementById('error-step-4');
          if (errorElement) {
            errorElement.textContent = 'Error submitting visit request: ' + error.message;
            errorElement.classList.remove('hidden');
          }
        } finally {
          // Reset button state
          const submitButton = document.querySelector('button[onclick="handleNext(4)"]');
          if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Visit Request';
          }
        }
      },

      // Poll backend for booking status updates
      startStatusPolling() {
        if (this.statusPollingInterval) {
          clearInterval(this.statusPollingInterval);
        }
        
        // Check status immediately
        this.checkBookingStatus();
        
        // Then check every 10 seconds
        this.statusPollingInterval = setInterval(() => {
          this.checkBookingStatus();
        }, 10000);
      },

      async checkBookingStatus() {
        if (!this.bookingId) return;
        
        try {
          const response = await fetch(`/booking/status/${this.bookingId}`, {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          
          const result = await response.json();
          
          if (result.success) {
            const oldVisitConfirmed = this.visitConfirmed;
            const oldAdvancePaymentPaid = this.advancePaymentPaid;
            
            // Update status
            this.visitConfirmed = result.visit_confirmed;
            this.advancePaymentPaid = result.advance_payment_paid;
            
            // Show notifications for status changes
            if (!oldVisitConfirmed && this.visitConfirmed) {
              this.showStatusNotification('Visit confirmed by manager! You can now proceed with advance payment.', 'success');
            }
            
            if (!oldAdvancePaymentPaid && this.advancePaymentPaid) {
              this.showStatusNotification('Advance payment confirmed! You can now proceed to wedding details.', 'success');
              // Stop polling once payment is confirmed
              if (this.statusPollingInterval) {
                clearInterval(this.statusPollingInterval);
                this.statusPollingInterval = null;
              }
            }
          }
        } catch (error) {
          console.error('Error checking booking status:', error);
        }
      },

      showStatusNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
          type === 'success' ? 'bg-green-500 text-white' : 
          type === 'error' ? 'bg-red-500 text-white' : 
          'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
          if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
          }
        }, 5000);
      },

      // Hall Selection (Step 1) Methods
      selectHall(hallId, hallName) {
        this.formData.hallId = hallId;
        this.formData.hallName = hallName;
        this.formData.hallBookingDate = null; // Reset date when hall changes
        this.hallBookingError = ''; // Clear error when hall changes
      },

      selectHallDate(day) {
        const fullDate = this.getFullDate(day);
        if (!this.isHallDateBooked(day) && this.isFutureDate(day)) {
          this.formData.hallBookingDate = fullDate;
          this.hallBookingError = ''; // Clear error on successful selection
        } else if (this.isHallDateBooked(day)) {
            this.hallBookingError = 'This date is already booked for the selected hall.';
        } else if (!this.isFutureDate(day)) {
            this.hallBookingError = 'Please select a future date.';
        }
      },

      isHallDateBooked(day) {
        if (!this.formData.hallId) return false;
        const dateStr = this.getFullDate(day);
        return this.hallBookings[this.formData.hallId]?.includes(dateStr);
      },

      isFutureDate(day) {
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Normalize to start of day
        const selected = new Date(this.currentYear, this.currentMonth, day);
        selected.setHours(0, 0, 0, 0);
        return selected >= today;
      },

      changeMonth(delta) {
        this.currentMonth += delta;
        if (this.currentMonth < 0) {
          this.currentMonth = 11;
          this.currentYear--;
        } else if (this.currentMonth > 11) {
          this.currentMonth = 0;
          this.currentYear++;
        }
        this.formData.hallBookingDate = null; // Reset selected date on month change
        this.generateCalendarDays();
        this.hallBookingError = ''; // Clear error on month change
      },

      generateCalendarDays() {
          // This ensures the calendar updates visually without full page reload.
          // The actual daysInMonth and blanks getters handle the calculations.
          // No explicit logic needed here, just a trigger if necessary (already reactive)
      },

      get daysInMonth() {
        return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
      },

      get blanks() {
        const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
        return Array(firstDay).fill(null);
      },

      getFullDate(day) {
        const month = String(this.currentMonth + 1).padStart(2, '0');
        const dayStr = String(day).padStart(2, '0');
        return `${this.currentYear}-${month}-${dayStr}`;
      },

      // Package Selection (Step 2) Methods
      selectPackage(packageId, price) {
        this.formData.package.id = packageId;
        this.formData.package.price = price;
        this.formData.customization.catering.selectedMenuId = null; // Reset catering on package change
        this.cateringMenuError = ''; // Clear catering error
      },

      // Customization (Step 3) Methods
      getIncludedDecorations(weddingType) {
          const includedDecorations = [];
          switch (weddingType) {
              case 'Kandyan Wedding':
                  includedDecorations.push(
                        { name: 'Traditional Poruwa Decor', description: 'Beautifully crafted Kandyan Poruwa with intricate woodwork and floral arrangements for the wedding ceremony.', image: '{{ asset("images/kandy_Poruwa.jpg") }}' },
                        { name: 'Traditional Setee-back', description: 'Elegant settee-back for the couple, adorned with Kandyan motifs and luxurious fabrics.', image: '{{ asset("images/kandyan_SetBack.jpg") }}' },
                        { name: 'Milk Fountain (Kiri Kala)', description: 'Symbolic milk fountain representing purity and prosperity, a highlight of Kandyan wedding rituals.', image: '{{ asset("images/Milk-Fountain.jpg") }}' },
                        { name: 'Traditional Oil Lamp', description: 'Ornately decorated oil lamp, lit to bless the couple and mark the auspicious beginning of the ceremony.', image: '{{ asset("images/kandy_oilLamp.jpg") }}' },
                        { name: 'Head Table Decor', description: 'Specially decorated head table for the couple, featuring traditional Kandyan floral and fabric arrangements.', image: '{{ asset("images/kandy_headtab.jpg") }}' },
                  );
                  break;
              case 'Low-Country Wedding':
                  includedDecorations.push(
                      { name: 'Traditional Poruwa Decor', description: 'Elaborate Poruwa setup with traditional elements.', image: 'https://images.unsplash.com/photo-1621217039534-3c8c7f2a1a4f?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'Traditional Oil Lamp', description: 'Ornately decorated traditional oil lamp.', image: 'https://images.unsplash.com/photo-1620894541999-9b43e86fcf7c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'Table for Wedding Cake', description: 'Decorated table dedicated for the wedding cake.', image: 'https://images.unsplash.com/photo-1519782539162-9e2d3b2a1a2b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
                  );
                  break;
              case 'European Wedding':
                  includedDecorations.push(
                      { name: 'White Floral Arch', description: 'Classic white floral arch for ceremony backdrop.', image: 'https://images.unsplash.com/photo-1543781295-bb04907a909b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'Elegant Table Settings', description: 'Refined table settings with white linens and subtle accents.', image: 'https://images.unsplash.com/photo-1517441589140-5e365b2635fe?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'Table for Wedding Cake', description: 'Decorated table dedicated for the wedding cake.', image: 'https://images.unsplash.com/photo-1519782539162-9e2d3b2a1a2b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
                  );
                  break;
              case 'Indian Wedding':
                  includedDecorations.push(
                      { name: 'Vibrant Mandap Setup', description: 'Colorful and elaborate mandap for traditional rituals.', image: 'https://images.unsplash.com/photo-1616853609804-d7d8e2b8b9c7?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'Traditional Oil Lamp', description: 'Ornately decorated traditional oil lamp.', image: 'https://images.unsplash.com/photo-1620894541999-9b43e86fcf7c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'Table for Wedding Cake', description: 'Decorated table dedicated for the wedding cake.', image: 'https://images.unsplash.com/photo-1519782539162-9e2d3b2a1a2b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
                  );
                  break;
              case 'Catholic Wedding':
                  includedDecorations.push(
                      { name: 'Church Altar Floral Arrangements', description: 'Elegant flower arrangements for the church altar and pews.', image: 'https://images.unsplash.com/photo-1559132145-128a1c910398?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'White Floral Arch', description: 'Classic white floral arch for reception entrance.', image: 'https://images.unsplash.com/photo-1543781295-bb04907a909b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'Table for Wedding Cake', description: 'Decorated table dedicated for the wedding cake.', image: 'https://images.unsplash.com/photo-1519782539162-9e2d3b2a1a2b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
                  );
                  break;
              default:
                  // Common compulsory decorations for all wedding types if not specified
                  includedDecorations.push(
                      { name: 'Table for Wedding Cake', description: 'Decorated table dedicated for the wedding cake.', image: 'https://images.unsplash.com/photo-1519782539162-9e2d3b2a1a2b?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' },
                      { name: 'Traditional Oil Lamp', description: 'Ornately decorated traditional oil lamp.', image: 'https://images.unsplash.com/photo-1620894541999-9b43e86fcf7c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' }
                  );
                  break;
          }
          return includedDecorations;
      },

      getAdditionalServiceById(id) {
          return this.additionalServicesData.freeCompulsory.find(s => s.id === id) ||
                 this.additionalServicesData.optionalFree.find(s => s.id === id) ||
                 this.additionalServicesData.paid.find(s => s.id === id);
      },

      // Helper function to get decorations for the selected wedding type
      getDecorationsForWeddingType(weddingType) {
          if (!weddingType || !this.additionalDecorationsData[weddingType]) {
              return { free: [], paid: [] };
          }
          return this.additionalDecorationsData[weddingType];
      },

      // Helper function to get all decorations (free + paid) for the selected wedding type
      getAllDecorationsForWeddingType(weddingType) {
          const decorations = this.getDecorationsForWeddingType(weddingType);
          return [...decorations.free, ...decorations.paid];
      },

      // Helper function to find a decoration by ID across all wedding types
      findDecorationById(decorId) {
          for (const weddingType in this.additionalDecorationsData) {
              const decorations = this.getAllDecorationsForWeddingType(weddingType);
              const found = decorations.find(d => d.id === decorId);
              if (found) return found;
          }
          return null;
      },

      // Catering Methods
      filteredCateringMenus() {
        const packageId = this.formData.package.id;
        if (!packageId) return [];

        const menuMap = {
            'package-basic': ['menu-01', 'menu-02', 'menu-03'],
            'package-golden': ['menu-01', 'menu-02', 'menu-03', 'wedding-package-04'],
            'package-infinity': ['menu-01', 'menu-02', 'menu-03', 'wedding-package-04', 'wedding-package-05']
        };
        const allowedMenuIds = menuMap[packageId] || [];
        return this.cateringMenusData.filter(menu => allowedMenuIds.includes(menu.id));
      },

      selectCateringMenu(menuId) {
        this.formData.customization.catering.selectedMenuId = menuId;
        this.cateringMenuError = ''; // Clear error on selection
        this.updateCustomizationCosts();
      },

      toggleCustomCateringItem(category, item) {
          if (!this.formData.customization.catering.custom[category]) {
              this.formData.customization.catering.custom[category] = [];
          }
          const index = this.formData.customization.catering.custom[category].findIndex(i => i.name === item.name);
          if (index > -1) {
              this.formData.customization.catering.custom[category].splice(index, 1);
          } else {
              this.formData.customization.catering.custom[category].push(item);
          }
          // Ensure reactivity for nested objects
          this.formData.customization.catering.custom = { ...this.formData.customization.catering.custom };
          this.updateCustomizationCosts();
      },

      isCustomCateringItemSelected(category, itemName) {
          return this.formData.customization.catering.custom[category]?.some(item => item.name === itemName) || false;
      },

      // Visit Date (Step 4) Methods
      getMinDateForBooking(daysOffset = 0) {
          const today = new Date();
          if (daysOffset > 0) {
              today.setDate(today.getDate() + daysOffset);
          }
          return formatDate(today);
      },

      getMinDateForCatholicDay2() {
          if (!this.formData.customization.catholicDay1Date) {
              return this.getMinDateForBooking(0);
          }
          const day1 = new Date(this.formData.customization.catholicDay1Date + 'T00:00:00');
          day1.setDate(day1.getDate() + 1); // Day 2 must be after Day 1
          return formatDate(day1);
      },

      updateVisitTimeOptions() {
        // In a real app, this would fetch available slots for formData.visitDate
        // For simulation, we'll just re-evaluate which are 'booked'
        this.formData.visitTime = null; // Reset selected time
      },

      isVisitTimeBooked(date, time) {
          if (!date || !time) return false;
          const formattedDate = date; // Already YYYY-MM-DD
          return this.visitBookings[formattedDate]?.includes(time) || false;
      },

      // Cost Calculation Getters
      get totalAdditionalDecorationsCost() {
          return this.formData.customization.decorations.additional.reduce((sum, decorId) => {
              const decor = this.findDecorationById(decorId);
              return sum + (decor ? decor.price : 0);
          }, 0);
      },

      get totalCustomCateringCost() {
          let total = 0;
          for (const category in this.formData.customization.catering.custom) {
              this.formData.customization.catering.custom[category].forEach(item => {
                  total += item.price || 0;
              });
          }
          return total;
      },

      get totalPaidAdditionalServicesCost() {
          return this.formData.customization.additionalServices.selected.reduce((sum, serviceId) => {
              const service = this.additionalServicesData.paid.find(s => s.id === serviceId);
              return sum + (service ? service.price : 0);
          }, 0);
      },

      get totalChairCoverCost() {
          // Chair covers are optional additional decoration. If selected, calculate based on guest count.
          // Check for any chair cover decoration ID (they now have wedding-type prefixes)
          const selectedChairCover = this.formData.customization.decorations.additional.find(decorId => 
              decorId.includes('chair-covers')
          );
          if (selectedChairCover) {
              const chairCoverDecor = this.findDecorationById(selectedChairCover);
              if (chairCoverDecor) {
                  return this.formData.customization.guestCount * chairCoverDecor.price;
              }
          }
          return 0;
      },

      // Grand Total Calculation (for Summary Step)
      get subtotalCost() {
          let sub = this.formData.package.price || 0;
          sub += this.totalAdditionalDecorationsCost;
          sub += this.totalCustomCateringCost;
          sub += this.totalPaidAdditionalServicesCost;
          sub += this.supplementaryChargesData.charges.venueCharge; // Base venue charge
          sub += this.supplementaryChargesData.charges.electricity;
          sub += this.supplementaryChargesData.charges.multimedia;
          sub += this.totalChairCoverCost; // Add calculated chair cover cost
          // Assuming hard liquor corkage is applied per bottle if selected as a custom item,
          // or if it's implicitly part of the calculation, need clarity.
          // For now, I'm not adding it as a separate bill item unless chosen explicitly from custom catering.
          return sub;
      },

      get serviceCharge() {
          return this.subtotalCost * this.supplementaryChargesData.charges.serviceChargePercentage;
      },

      get totalTaxes() {
          return this.subtotalCost * this.supplementaryChargesData.charges.taxesPercentage;
      },

      get grandTotalCost() {
          return this.subtotalCost + this.serviceCharge + this.totalTaxes;
      },

      // Update costs whenever relevant data changes (debounced for performance)
      updateCustomizationCosts: Alpine.debounce(function() {
          // This method primarily triggers re-calculation of computed properties.
          // In a more complex scenario, you might update a totalCost property in formData.
          console.log('Recalculating costs...');
          // Trigger reactivity by accessing computed properties or updating a dummy value
          const dummy = this.totalAdditionalDecorationsCost; // Accessing forces re-evaluation
          const dummy2 = this.totalCustomCateringCost;
          const dummy3 = this.totalPaidAdditionalServicesCost;
          const dummy4 = this.grandTotalCost; // Accessing grand total
      }, 200),

      get summaryTermsAndConditions() {
          return this.supplementaryChargesData.notes.summary.concat(this.supplementaryChargesData.notes.catering);
      },

      // Validation Methods
      clearErrors() {
          this.validationErrors = {};
          this.hallBookingError = '';
          this.guestCountError = '';
          this.weddingTypeError = '';
          this.weddingTypeTimeSlotError = '';
          this.catholicDay1DateError = '';
          this.catholicDay2DateError = '';
          this.cateringMenuError = '';
          this.visitPurposeError = '';
          this.visitDateError = '';
          this.visitTimeError = '';

          // Clear general step errors
          const errorElements = document.querySelectorAll('.step-container .error-message');
          errorElements.forEach(el => el.classList.add('hidden'));
      },

      validateField(fieldPath) {
          let isValid = true;
          let errorMessage = '';

          const [section, field] = fieldPath.split('.');
          let value = this.formData;
          if (section) value = value[section];
          if (field) value = value[field];

          // Basic validation logic (can be expanded)
          switch (fieldPath) {
              case 'hallBookingDate':
                  if (!this.formData.hallBookingDate) {
                      errorMessage = 'Please select a booking date.';
                      isValid = false;
                  } else if (!this.isFutureDate(new Date(this.formData.hallBookingDate + 'T00:00:00').getDate())) {
                      errorMessage = 'Booking date must be in the future.';
                      isValid = false;
                  }
                  this.hallBookingError = errorMessage;
                  break;
              case 'customization.guestCount':
                  if (this.formData.customization.guestCount < 10 || this.formData.customization.guestCount > 1000) {
                      errorMessage = 'Guest count must be between 10 and 1000.';
                      isValid = false;
                  }
                  this.guestCountError = errorMessage;
                  break;
              case 'customization.weddingType':
                  if (!this.formData.customization.weddingType) {
                      errorMessage = 'Please select a wedding type.';
                      isValid = false;
                  }
                  this.weddingTypeError = errorMessage;
                  break;
              case 'customization.weddingTypeTimeSlot':
                  if (['Kandyan Wedding', 'Low-Country Wedding', 'European Wedding', 'Indian Wedding'].includes(this.formData.customization.weddingType) && !this.formData.customization.weddingTypeTimeSlot) {
                      errorMessage = 'Please select a time slot for your wedding type.';
                      isValid = false;
                  }
                  this.weddingTypeTimeSlotError = errorMessage;
                  break;
              case 'customization.catholicDay1Date':
                  if (this.formData.customization.weddingType === 'Catholic Wedding' && !this.formData.customization.catholicDay1Date) {
                      errorMessage = 'Please select Day 1 date for Catholic Wedding.';
                      isValid = false;
                  }
                  this.catholicDay1DateError = errorMessage;
                  break;
              case 'customization.catholicDay2Date':
                  if (this.formData.customization.weddingType === 'Catholic Wedding' && !this.formData.customization.catholicDay2Date) {
                      errorMessage = 'Please select Day 2 date for Catholic Wedding.';
                      isValid = false;
                  } else if (this.formData.customization.weddingType === 'Catholic Wedding' && this.formData.customization.catholicDay1Date && new Date(this.formData.customization.catholicDay2Date) <= new Date(this.formData.customization.catholicDay1Date)) {
                      errorMessage = 'Day 2 date must be after Day 1 date.';
                      isValid = false;
                  }
                  this.catholicDay2DateError = errorMessage;
                  break;
              case 'customization.catering.selectedMenuId':
                  if (!this.formData.customization.catering.selectedMenuId) {
                      errorMessage = 'Please select a catering menu.';
                      isValid = false;
                  }
                  this.cateringMenuError = errorMessage;
                  break;
              case 'contact.name':
                  if (!value || value.length < 3) {
                      errorMessage = 'Full Name is required (min 3 characters).';
                      isValid = false;
                  }
                  break;
              case 'contact.email':
                  if (!value || !/\S+@\S+\.\S+/.test(value)) {
                      errorMessage = 'Please enter a valid email address.';
                      isValid = false;
                  }
                  break;
              case 'contact.phone':
                  if (!value || !/^[0-9]{10}$/.test(value)) {
                      errorMessage = 'Please enter a valid 10-digit phone number.';
                      isValid = false;
                  }
                  break;
              case 'contact.visitPurpose':
                  if (!value) {
                      errorMessage = 'Please select a visit purpose.';
                      isValid = false;
                  }
                  break;
              case 'contact.visitPurposeOther':
                  if (this.formData.contact.visitPurpose === 'Other' && !value) {
                      errorMessage = 'Please specify your visit purpose.';
                      isValid = false;
                  }
                  break;
              case 'visitDate':
                  if (!this.formData.visitDate) {
                      errorMessage = 'Please select a visit date.';
                      isValid = false;
                  } else if (!this.isFutureDate(new Date(this.formData.visitDate + 'T00:00:00').getDate())) {
                      errorMessage = 'Visit date must be in the future.';
                      isValid = false;
                  }
                  break;
              case 'visitTime':
                  if (!this.formData.visitTime) {
                      errorMessage = 'Please select a visit time.';
                      isValid = false;
                  } else if (this.isVisitTimeBooked(this.formData.visitDate, this.formData.visitTime)) {
                      errorMessage = 'Selected visit time is already booked.';
                      isValid = false;
                  }
                  break;
              case 'weddingDetails.groomName':
              case 'weddingDetails.brideName':
                  if (!value || value.length < 2) {
                      errorMessage = `${field.includes('groom') ? 'Groom\'s' : 'Bride\'s'} Name is required (min 2 characters).`;
                      isValid = false;
                  }
                  break;
              case 'weddingDetails.groomEmail':
              case 'weddingDetails.brideEmail':
                  if (value && !/\S+@\S+\.\S+/.test(value)) { // Optional, but if filled, must be valid
                      errorMessage = `Please enter a valid ${field.includes('groom') ? 'groom\'s' : 'bride\'s'} email address.`;
                      isValid = false;
                  }
                  break;
              case 'weddingDetails.groomPhone':
              case 'weddingDetails.bridePhone':
                  if (fieldPath === 'weddingDetails.groomPhone' && (!value || !/^[0-9]{10}$/.test(value))) {
                      errorMessage = 'Groom\'s Phone is required (10 digits).';
                      isValid = false;
                  } else if (fieldPath === 'weddingDetails.bridePhone' && value && !/^[0-9]{10}$/.test(value)) { // Bride's phone is optional
                      errorMessage = 'Please enter a valid 10-digit phone number for Bride.';
                      isValid = false;
                  }
                  break;
              case 'weddingDetails.weddingDate':
                  if (!value) {
                      errorMessage = 'Preferred Wedding Date is required.';
                      isValid = false;
                  } else {
                      const selectedDate = new Date(value + 'T00:00:00');
                      const minDate = new Date();
                      minDate.setDate(minDate.getDate() + 90); // At least 3 months from today
                      if (selectedDate < minDate) {
                          errorMessage = 'Wedding date must be at least 3 months from today.';
                          isValid = false;
                      }
                  }
                  break;
              case 'weddingDetails.ceremonyTime':
              case 'weddingDetails.receptionTime':
                  if (!value) {
                      errorMessage = `${field.includes('ceremony') ? 'Ceremony' : 'Reception'} Start Time is required.`;
                      isValid = false;
                  }
                  break;
              case 'weddingDetails.termsAgreed':
                  if (!value) {
                      errorMessage = 'You must agree to the Terms and Conditions.';
                      isValid = false;
                  }
                  break;
              case 'weddingDetails.privacyAgreed':
                  if (!value) {
                      errorMessage = 'You must agree to the Privacy Policy.';
                      isValid = false;
                  }
                  break;
          }

          // Update validationErrors object and class for the input element
          if (fieldPath.includes('.')) {
              let currentObj = this.validationErrors;
              const pathParts = fieldPath.split('.');
              for (let i = 0; i < pathParts.length - 1; i++) {
                  if (!currentObj[pathParts[i]]) {
                      currentObj[pathParts[i]] = {};
                  }
                  currentObj = currentObj[pathParts[i]];
              }
              currentObj[pathParts[pathParts.length - 1]] = errorMessage;
          } else {
              this.validationErrors[fieldPath] = errorMessage;
          }

          const inputElement = document.getElementById(fieldPath.replace(/\./g, '-')); // Adapt ID format
          if (inputElement) {
              if (isValid) {
                  inputElement.classList.remove('is-invalid');
              } else {
                  inputElement.classList.add('is-invalid');
              }
          }

          return isValid;
      },

      validateStep(step) {
        let isValid = true;
        this.clearErrors(); // Clear all errors first

        // Explicitly set validation errors for the current step's fields
        const setError = (fieldPath, message) => {
            let currentObj = this.validationErrors;
            const pathParts = fieldPath.split('.');
            for (let i = 0; i < pathParts.length - 1; i++) {
                if (!currentObj[pathParts[i]]) {
                    currentObj[pathParts[i]] = {};
                }
                currentObj = currentObj[pathParts[i]];
            }
            currentObj[pathParts[pathParts.length - 1]] = message;
            isValid = false; // Mark overall step as invalid
        };

        const showStepError = (message) => {
            const errorElement = document.getElementById(`error-step-${step}`);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        };


        if (step === 1) {
          if (!this.formData.hallId) {
            setError('hallId', 'Please select a wedding hall.');
            this.hallBookingError = 'Please select a wedding hall.';
            isValid = false;
          }
          if (!this.formData.hallBookingDate) {
            setError('hallBookingDate', 'Please select a booking date.');
            this.hallBookingError = 'Please select a booking date.'; // Overwrite if already set
            isValid = false;
          } else if (!this.isFutureDate(new Date(this.formData.hallBookingDate + 'T00:00:00').getDate())) {
              setError('hallBookingDate', 'Booking date must be in the future.');
              this.hallBookingError = 'Booking date must be in the future.';
              isValid = false;
          }
        } else if (step === 2) {
          if (!this.formData.package.id) {
            setError('package.id', 'Please select a wedding package.');
            isValid = false;
          }
        } else if (step === 3) {
            this.validateField('customization.guestCount');
            if (this.guestCountError) isValid = false;

            this.validateField('customization.weddingType');
            if (this.weddingTypeError) isValid = false;

            if (this.formData.customization.weddingType === 'Catholic Wedding') {
                this.validateField('customization.catholicDay1Date');
                if (this.catholicDay1DateError) isValid = false;
                this.validateField('customization.catholicDay2Date');
                if (this.catholicDay2DateError) isValid = false;
            } else if (['Kandyan Wedding', 'Low-Country Wedding', 'European Wedding', 'Indian Wedding'].includes(this.formData.customization.weddingType)) {
                this.validateField('customization.weddingTypeTimeSlot');
                if (this.weddingTypeTimeSlotError) isValid = false;
           
            }

            this.validateField('customization.catering.selectedMenuId');
            if (this.cateringMenuError) isValid = false;

            // If the user is on the customization summary tab, ensure they have completed previous tabs
            if (this.formData.customization.activeTab === 'customization-summary') {
                if (!this.formData.customization.weddingType) {
                    setError('customization.weddingType', 'Please select a wedding type in the "Wedding Type" tab.');
                    showStepError('Please complete all customization tabs.');
                    this.formData.customization.activeTab = 'wedding-type'; // Redirect user
                    isValid = false;
                } else if (!this.formData.customization.catering.selectedMenuId) {
                    setError('customization.catering.selectedMenuId', 'Please select a catering menu in the "Catering" tab.');
                    showStepError('Please complete all customization tabs.');
                    this.formData.customization.activeTab = 'catering'; // Redirect user
                    isValid = false;
                }
                // Add more checks for other tabs if they have required fields
            }
        } else if (step === 4) {
            this.validateField('contact.name');
            if (this.validationErrors['contact.name']) isValid = false;

            this.validateField('contact.email');
            if (this.validationErrors['contact.email']) isValid = false;

            this.validateField('contact.phone');
            if (this.validationErrors['contact.phone']) isValid = false;

            this.validateField('contact.visitPurpose');
            if (this.validationErrors['contact.visitPurpose']) isValid = false;

            if (this.formData.contact.visitPurpose === 'Other') {
                this.validateField('contact.visitPurposeOther');
                if (this.validationErrors['contact.visitPurposeOther']) isValid = false;
            }

            this.validateField('visitDate');
            if (this.validationErrors['visitDate']) isValid = false;

            this.validateField('visitTime');
            if (this.validationErrors['visitTime']) isValid = false;

            if (!isValid) {
                showStepError("Please fill in all required contact and visit details.");
            }
        } else if (step === 5) {
            this.validateField('weddingDetails.groomName');
            if (this.validationErrors['weddingDetails.groomName']) isValid = false;

            this.validateField('weddingDetails.brideName');
            if (this.validationErrors['weddingDetails.brideName']) isValid = false;


            this.validateField('weddingDetails.groomPhone');
            if (this.validationErrors['weddingDetails.groomPhone']) isValid = false;

            // Optional fields, validate if filled
            this.validateField('weddingDetails.groomEmail');
            if (this.validationErrors['weddingDetails.groomEmail']) isValid = false;
            this.validateField('weddingDetails.brideEmail');
            if (this.validationErrors['weddingDetails.brideEmail']) isValid = false;
            this.validateField('weddingDetails.bridePhone');
            if (this.validationErrors['weddingDetails.bridePhone']) isValid = false;

            this.validateField('weddingDetails.weddingDate');
            if (this.validationErrors['weddingDetails.weddingDate']) isValid = false;

            this.validateField('weddingDetails.ceremonyTime');
            if (this.validationErrors['weddingDetails.ceremonyTime']) isValid = false;

            this.validateField('weddingDetails.receptionTime');
            if (this.validationErrors['weddingDetails.receptionTime']) isValid = false;

            this.validateField('weddingDetails.termsAgreed');
            if (this.validationErrors['weddingDetails.termsAgreed']) isValid = false;

            this.validateField('weddingDetails.privacyAgreed');
            if (this.validationErrors['weddingDetails.privacyAgreed']) isValid = false;

            if (!isValid) {
                showStepError("Please complete all required wedding details and agree to the terms.");
            }
        }
        // No specific validation for Step 6 (summary), as it's the final review.

        return isValid;
      },

      debugInfo: null,
      
      testSubmission() {
        console.log('Test submission called');
        try {
          // Create a copy of the form
          const form = document.getElementById('booking-form');
          const testForm = form.cloneNode(true);
          
          // Change the action to the test route
          testForm.action = '/booking/test';
          testForm.method = 'POST';
          
          // Add a hidden field to indicate this is a test
          const testField = document.createElement('input');
          testField.type = 'hidden';
          testField.name = 'is_test';
          testField.value = '1';
          testForm.appendChild(testField);
          
          // Append to body, submit, then remove
          document.body.appendChild(testForm);
          
          // Log form data for debugging
          console.log('Test form data:', this.formData);
          
          // Submit the form
          testForm.submit();
          
          // Show alert
          alert('Test submission initiated. Check browser console and server logs for details.');
          
          // Remove the form after submission
          setTimeout(() => {
            document.body.removeChild(testForm);
          }, 1000);
        } catch (error) {
          console.error('Error in test submission:', error);
          alert('Error in test submission: ' + error.message);
        }
      },
      
      ajaxSubmission() {
        console.log('AJAX submission called');
        this.debugInfo = null;
        
        try {
          if (this.validateStep(6)) {
            console.log('Form validation passed, preparing AJAX submission');
            
            // Create a loading indicator
            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'fixed top-0 left-0 w-full bg-yellow-600 text-white text-center py-2 z-50';
            loadingIndicator.textContent = 'Submitting via AJAX...';
            document.body.appendChild(loadingIndicator);
            
            // Prepare form data
            const formData = new FormData(document.getElementById('booking-form'));
            
            // Log form data entries for debugging
            console.log('Form data entries:');
            for (let [key, value] of formData.entries()) {
              console.log(`${key}: ${value}`);
            }
            
            // Make the AJAX request
            fetch('{{ route("booking.submit") }}', {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
              }
            })
            .then(response => {
              console.log('Response status:', response.status);
              return response.text().then(text => {
                try {
                  return text ? JSON.parse(text) : {};
                } catch (e) {
                  console.error('Error parsing JSON response:', e);
                  return { html: text, parseError: e.message };
                }
              });
            })
            .then(data => {
              console.log('Response data:', data);
              this.debugInfo = data;
              
              if (data.success) {
                alert('Booking submitted successfully!');
                // Clear localStorage
                localStorage.removeItem('formData');
                localStorage.removeItem('currentStep');
                // Redirect to success page or show success message
                window.location.href = data.redirect || '/bookings/my';
              } else {
                alert('Booking submission failed. See debug panel for details.');
              }
            })
            .catch(error => {
              console.error('AJAX submission error:', error);
              this.debugInfo = { error: error.message };
              alert('Error submitting booking: ' + error.message);
            })
            .finally(() => {
              // Remove loading indicator
              if (document.body.contains(loadingIndicator)) {
                document.body.removeChild(loadingIndicator);
              }
            });
          } else {
            console.warn('Form validation failed');
            alert('Please correct errors before submitting.');
          }
        } catch (error) {
          console.error('Error in AJAX submission:', error);
          this.debugInfo = { error: error.message };
          alert('Error in AJAX submission: ' + error.message);
        }
      },
      
      submitBookingForm() {
        console.log('submitBookingForm called');
        
        // Add a global error handler to catch any submission errors
        window.onerror = function(message, source, lineno, colno, error) {
          console.error('Global error caught:', message, error);
          alert('An error occurred during form submission: ' + message);
          return true;
        };
        
        try {
          if (this.validateStep(6)) {
            console.log('Form validation passed, preparing to submit');
            
            // Log form data for debugging
            console.log('Form data being submitted:', this.formData);
            
            // Ensure all JSON fields are properly stringified
            const jsonFields = [
              'customization_decorations_additional',
              'customization_catering_custom',
              'customization_additional_services_selected'
            ];
            
            jsonFields.forEach(field => {
              const input = document.querySelector(`input[name="${field}"]`);
              if (input) {
                try {
                  // Ensure the value is valid JSON by parsing and re-stringifying
                  const value = input.value;
                  const parsed = JSON.parse(value);
                  input.value = JSON.stringify(parsed);
                  console.log(`Field ${field} processed:`, input.value);
                } catch (e) {
                  console.error(`Error processing JSON field ${field}:`, e);
                  // Set to empty array if invalid JSON
                  input.value = '[]';
                }
              }
            });
            
            // Clear localStorage so next login starts fresh
            localStorage.removeItem('formData');
            localStorage.removeItem('currentStep');
            
            // Add a submission indicator
            const form = document.getElementById('booking-form');
            const submitIndicator = document.createElement('div');
            submitIndicator.className = 'fixed top-0 left-0 w-full bg-blue-600 text-white text-center py-2 z-50';
            submitIndicator.textContent = 'Submitting your booking...';
            document.body.appendChild(submitIndicator);
            
            console.log('Submitting form to:', form.action);
            
            // Submit the form to backend
            form.submit();
            
            // Show a processing message
            setTimeout(() => {
              if (document.body.contains(submitIndicator)) {
                submitIndicator.textContent = 'Processing your booking... This may take a moment.';
              }
            }, 2000);
          } else {
            console.warn('Form validation failed');
            const errorElement = document.getElementById(`error-step-6`);
            if (errorElement) {
                errorElement.textContent = "Please review all details and ensure all previous steps are complete.";
                errorElement.classList.remove('hidden');
            }
            alert('Please correct errors before finalizing your booking.');
          }
        } catch (error) {
          console.error('Error in submitBookingForm:', error);
          alert('An error occurred while submitting the form: ' + error.message);
        }
      },

      printBill() {
        // Hide everything except the printable bill, then print
        const bill = document.getElementById('printable-bill');
        bill.classList.remove('hidden');
        window.print();
        setTimeout(() => bill.classList.add('hidden'), 1000);
      },

      openTermsModal() {
      alert("Terms and Conditions:\n" + this.summaryTermsAndConditions.join('\n'));
      // In a real application, this would open a proper modal with full text
      },
      
      // CRITICAL FIX: Add missing proceedWithoutConfirmation function
      proceedWithoutConfirmation() {
      // Allow user to proceed to Step 5 even without manager confirmation
      console.log('Proceeding without confirmation');
      this.nextStep();
      }
    };
  }

  // Initialize Alpine store globally for easier access
  document.addEventListener('alpine:init', () => {
    Alpine.store('booking', weddingBooking());
  });

  document.addEventListener('DOMContentLoaded', () => {
    if (Alpine.store('booking')) {
        Alpine.store('booking').updateProgress(Alpine.store('booking').currentStep);
    }
  });
// Reset error messages when switching steps
function resetErrorMessages() {
    document.querySelectorAll('.error-message').forEach(div => {
        div.textContent = '';
        div.classList.add('hidden');
    });
}
</script>
<script>
    // Debounce utility
    function debounce(func, wait) {
      let timeout;
      return function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, arguments), wait);
      };
    }
  
    // Scroll effect for navbar 
    // //when navibar scroll make white
    document.addEventListener('DOMContentLoaded', function () {
      const navbar = document.getElementById('navbar');
  
      const handleScroll = debounce(() => {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      }, 50);
  
      window.addEventListener('scroll', handleScroll);
    });
  </script>
<script>
// Clear localStorage if user changes (ensure fresh form for each user)
(function() {
    // You can set window.bookingUserId in your layout with the current user's ID
    var currentUserId = window.bookingUserId || null;
    var lastUserId = localStorage.getItem('lastBookingUserId');
    if (currentUserId && lastUserId && currentUserId !== lastUserId) {
        localStorage.removeItem('formData');
        localStorage.removeItem('currentStep');
    }
    if (currentUserId) {
        localStorage.setItem('lastBookingUserId', currentUserId);
    }
})();
</script>

<!-- Booking System Integration Script -->
<script src="{{ asset('js/booking-system-integration.js') }}"></script>

@endsection
