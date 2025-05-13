
@extends('layouts.app')

@section('title', 'Wedding Packages')
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wedding Booking - Wet Water Resort</title>
    
    <style>
      .step-container {
        display: none;
      }
      .step-container.active {
        display: block;
      }
      .progress-step {
        width: 16.66%;
        position: relative;
      }
      .progress-step::after {
        content: "";
        position: absolute;
        top: 50%;
        right: -50%;
        width: 100%;
        height: 2px;
        background-color: #e5e7eb;
        z-index: 0;
      }
      .progress-step:last-child::after {
        display: none;
      }
      .progress-step.active .step-circle {
        background-color: #3b82f6;
        color: white;
      }
      .progress-step.completed .step-circle {
        background-color: #10b981;
        color: white;
      }
      .progress-step.completed::after {
        background-color: #10b981;
      }
      .progress-step.active::after {
        background-color: #e5e7eb;
      }
    </style>
  </head>

  @section('content')

  <body class="bg-gray-50">
    <main class="container mx-auto py-8 px-4">
      <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold text-center mb-2">Wedding Booking</h1>
        <p class="text-gray-600 text-center mb-8">
          Follow the steps below to book your dream wedding at Wet Water Resort
        </p>

        <!-- Progress Bar -->
        <div class="flex justify-between mb-12">
          <div class="progress-step active">
            <div
              class="step-circle w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center mx-auto mb-2"
            >
              1
            </div>
            <div class="text-xs text-center">Hall</div>
          </div>
          <div class="progress-step">
            <div
              class="step-circle w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center mx-auto mb-2"
            >
              2
            </div>
            <div class="text-xs text-center">Package</div>
          </div>
          <div class="progress-step">
            <div
              class="step-circle w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center mx-auto mb-2"
            >
              3
            </div>
            <div class="text-xs text-center">Customize</div>
          </div>
          <div class="progress-step">
            <div
              class="step-circle w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center mx-auto mb-2"
            >
              4
            </div>
            <div class="text-xs text-center">Visit</div>
          </div>
          <div class="progress-step">
            <div
              class="step-circle w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center mx-auto mb-2"
            >
              5
            </div>
            <div class="text-xs text-center">Date</div>
          </div>
          <div class="progress-step">
            <div
              class="step-circle w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center mx-auto mb-2"
            >
              6
            </div>
            <div class="text-xs text-center">Summary</div>
          </div>
        </div>

        <!-- Step 1: Hall Selection -->
        <form id="booking-form" action="javascript:void(0);" method="post">
          <div id="step-1" class="step-container active">
            <h2 class="text-2xl font-bold mb-6">Choose Your Wedding Hall</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div
                class="border rounded-lg overflow-hidden hover:shadow-lg transition-all cursor-pointer"
              >
                <input
                  type="radio"
                  name="hall"
                  id="hall-1"
                  value="royal-ballroom"
                  class="sr-only hall-radio"
                />
                <label for="hall-1" class="block cursor-pointer">
                  <div class="relative">
                    <img
                      src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=800&q=80"
                      alt="Royal Ballroom"
                      class="w-full h-48 object-cover"
                    />
                    <div
                      class="absolute top-2 right-2 w-6 h-6 rounded-full border-2 border-white hall-check"
                    ></div>
                  </div>
                  <div class="p-4">
                    <h3 class="font-bold text-lg">Royal Ballroom</h3>
                    <p class="text-gray-600 text-sm mb-2">
                      Our largest and most luxurious venue, perfect for grand
                      celebrations
                    </p>
                    <p class="text-blue-600 font-medium">Up to 300 guests</p>
                    <ul class="mt-2 text-sm space-y-1">
                      <li>• Crystal chandeliers and marble floors</li>
                      <li>• State-of-the-art lighting and sound</li>
                      <li>• Private entrance and reception area</li>
                    </ul>
                  </div>
                </label>
              </div>

              <div
                class="border rounded-lg overflow-hidden hover:shadow-lg transition-all cursor-pointer"
              >
                <input
                  type="radio"
                  name="hall"
                  id="hall-2"
                  value="garden-pavilion"
                  class="sr-only hall-radio"
                />
                <label for="hall-2" class="block cursor-pointer">
                  <div class="relative">
                    <img
                      src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=800&q=80"
                      alt="Garden Pavilion"
                      class="w-full h-48 object-cover"
                    />
                    <div
                      class="absolute top-2 right-2 w-6 h-6 rounded-full border-2 border-white hall-check"
                    ></div>
                  </div>
                  <div class="p-4">
                    <h3 class="font-bold text-lg">Garden Pavilion</h3>
                    <p class="text-gray-600 text-sm mb-2">
                      A stunning outdoor venue surrounded by lush tropical
                      gardens
                    </p>
                    <p class="text-blue-600 font-medium">Up to 150 guests</p>
                    <ul class="mt-2 text-sm space-y-1">
                      <li>• Open-air pavilion with optional enclosure</li>
                      <li>• Panoramic garden views</li>
                      <li>• Natural stone pathways</li>
                    </ul>
                  </div>
                </label>
              </div>
            </div>

            <div class="mt-8 flex justify-end">
              <button
                type="button"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                onclick="nextStep(1)"
              >
                Next
              </button>
            </div>
          </div>

          <!-- Step 2: Package Selection -->
          <div id="step-2" class="step-container">
            <h2 class="text-2xl font-bold mb-6">Select Your Wedding Package</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div
                class="border rounded-lg overflow-hidden hover:shadow-lg transition-all cursor-pointer bg-gray-50"
              >
                <input
                  type="radio"
                  name="package"
                  id="package-1"
                  value="silver"
                  class="sr-only package-radio"
                />
                <label for="package-1" class="block cursor-pointer">
                  <div class="p-4 text-center">
                    <h3 class="font-bold text-xl mb-1">Silver</h3>
                    <p class="text-gray-600 text-sm mb-2">
                      Perfect for intimate gatherings
                    </p>
                    <p class="text-2xl font-bold text-blue-600 mb-4">
                      ₹1,99,999
                    </p>
                    <ul class="text-left text-sm space-y-2">
                      <li>✓ Up to 50 guests</li>
                      <li>✓ Basic decoration setup</li>
                      <li>✓ 4-hour venue rental</li>
                      <li>✓ Standard catering menu</li>
                      <li>✓ Wedding coordinator</li>
                    </ul>
                  </div>
                </label>
              </div>

              <div
                class="border-2 border-blue-500 rounded-lg overflow-hidden hover:shadow-lg transition-all cursor-pointer bg-blue-50"
              >
                <input
                  type="radio"
                  name="package"
                  id="package-2"
                  value="gold"
                  class="sr-only package-radio"
                />
                <label for="package-2" class="block cursor-pointer">
                  <div
                    class="bg-blue-600 text-white text-center py-1 text-sm font-medium"
                  >
                    Most Popular
                  </div>
                  <div class="p-4 text-center">
                    <h3 class="font-bold text-xl mb-1">Gold</h3>
                    <p class="text-gray-600 text-sm mb-2">
                      Our most popular package
                    </p>
                    <p class="text-2xl font-bold text-blue-600 mb-4">
                      ₹3,99,999
                    </p>
                    <ul class="text-left text-sm space-y-2">
                      <li>✓ Up to 100 guests</li>
                      <li>✓ Premium decoration setup</li>
                      <li>✓ 6-hour venue rental</li>
                      <li>✓ Premium catering menu</li>
                      <li>✓ Wedding coordinator</li>
                      <li>✓ Photography service</li>
                      <li>✓ DJ and sound system</li>
                    </ul>
                  </div>
                </label>
              </div>

              <div
                class="border rounded-lg overflow-hidden hover:shadow-lg transition-all cursor-pointer bg-gray-50"
              >
                <input
                  type="radio"
                  name="package"
                  id="package-3"
                  value="platinum"
                  class="sr-only package-radio"
                />
                <label for="package-3" class="block cursor-pointer">
                  <div class="p-4 text-center">
                    <h3 class="font-bold text-xl mb-1">Platinum</h3>
                    <p class="text-gray-600 text-sm mb-2">
                      The ultimate wedding experience
                    </p>
                    <p class="text-2xl font-bold text-blue-600 mb-4">
                      ₹6,99,999
                    </p>
                    <ul class="text-left text-sm space-y-2">
                      <li>✓ Up to 200 guests</li>
                      <li>✓ Luxury decoration setup</li>
                      <li>✓ Full-day venue rental</li>
                      <li>✓ Gourmet catering menu</li>
                      <li>✓ Dedicated wedding planner</li>
                      <li>✓ Photography & videography</li>
                      <li>✓ Live band & entertainment</li>
                      <li>✓ Complimentary honeymoon suite</li>
                    </ul>
                  </div>
                </label>
              </div>
            </div>

            <div class="mt-8 flex justify-between">
              <button
                type="button"
                class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                onclick="prevStep(2)"
              >
                Back
              </button>
              <button
                type="button"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                onclick="nextStep(2)"
              >
                Next
              </button>
            </div>
          </div>

          <!-- Step 3: Package Customization -->
          <div id="step-3" class="step-container">
            <h2 class="text-2xl font-bold mb-6">Customize Your Package</h2>

            <div class="space-y-8">
              <div>
                <h3 class="text-lg font-semibold mb-3">Guest Count</h3>
                <div class="flex items-center">
                  <label for="guest-count" class="mr-4"
                    >Number of Guests:</label
                  >
                  <input
                    type="number"
                    id="guest-count"
                    name="guest_count"
                    min="10"
                    max="300"
                    value="50"
                    class="border rounded-md px-3 py-2 w-24"
                  />
                </div>
              </div>

              <div>
                <h3 class="text-lg font-semibold mb-3">Decoration Options</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="border rounded-lg p-4 hover:shadow-md">
                    <div class="flex items-start">
                      <input
                        type="checkbox"
                        id="decor-1"
                        name="decorations[]"
                        value="minimal-floral"
                        class="mt-1"
                      />
                      <label for="decor-1" class="ml-2">
                        <div class="font-medium">Minimal Floral</div>
                        <div class="text-sm text-gray-600">₹15,000</div>
                        <div class="text-sm text-gray-500 mt-1">
                          Simple, elegant floral arrangements for a minimalist
                          look
                        </div>
                      </label>
                    </div>
                  </div>
                  <div class="border rounded-lg p-4 hover:shadow-md">
                    <div class="flex items-start">
                      <input
                        type="checkbox"
                        id="decor-2"
                        name="decorations[]"
                        value="traditional-setup"
                        class="mt-1"
                      />
                      <label for="decor-2" class="ml-2">
                        <div class="font-medium">Traditional Setup</div>
                        <div class="text-sm text-gray-600">₹25,000</div>
                        <div class="text-sm text-gray-500 mt-1">
                          Classic decorations with traditional elements and
                          colors
                        </div>
                      </label>
                    </div>
                  </div>
                  <div class="border rounded-lg p-4 hover:shadow-md">
                    <div class="flex items-start">
                      <input
                        type="checkbox"
                        id="decor-3"
                        name="decorations[]"
                        value="luxury-decor"
                        class="mt-1"
                      />
                      <label for="decor-3" class="ml-2">
                        <div class="font-medium">Luxury Decor</div>
                        <div class="text-sm text-gray-600">₹45,000</div>
                        <div class="text-sm text-gray-500 mt-1">
                          Premium decorations with high-end materials and design
                        </div>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div>
                <h3 class="text-lg font-semibold mb-3">Catering Options</h3>
                <div class="space-y-3">
                  <div class="flex items-center">
                    <input
                      type="radio"
                      id="catering-1"
                      name="catering"
                      value="vegetarian"
                      class="mr-2"
                    />
                    <label for="catering-1">
                      <span class="font-medium">Vegetarian Menu</span>
                      <span class="text-sm text-gray-600 ml-2"
                        >₹1,200/plate</span
                      >
                    </label>
                  </div>
                  <div class="flex items-center">
                    <input
                      type="radio"
                      id="catering-2"
                      name="catering"
                      value="mixed"
                      class="mr-2"
                    />
                    <label for="catering-2">
                      <span class="font-medium">Mixed Menu</span>
                      <span class="text-sm text-gray-600 ml-2"
                        >₹1,500/plate</span
                      >
                    </label>
                  </div>
                  <div class="flex items-center">
                    <input
                      type="radio"
                      id="catering-3"
                      name="catering"
                      value="premium"
                      class="mr-2"
                    />
                    <label for="catering-3">
                      <span class="font-medium">Premium Menu</span>
                      <span class="text-sm text-gray-600 ml-2"
                        >₹1,800/plate</span
                      >
                    </label>
                  </div>
                </div>
              </div>

              <div>
                <h3 class="text-lg font-semibold mb-3">
                  Entertainment Add-ons
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="border rounded-lg p-4 hover:shadow-md">
                    <div class="flex items-start">
                      <input
                        type="checkbox"
                        id="ent-1"
                        name="entertainment[]"
                        value="dj"
                        class="mt-1"
                      />
                      <label for="ent-1" class="ml-2">
                        <div class="font-medium">Professional DJ</div>
                        <div class="text-sm text-gray-600">₹25,000</div>
                        <div class="text-sm text-gray-500 mt-1">
                          6-hour DJ service with premium sound system
                        </div>
                      </label>
                    </div>
                  </div>
                  <div class="border rounded-lg p-4 hover:shadow-md">
                    <div class="flex items-start">
                      <input
                        type="checkbox"
                        id="ent-2"
                        name="entertainment[]"
                        value="live-band"
                        class="mt-1"
                      />
                      <label for="ent-2" class="ml-2">
                        <div class="font-medium">Live Band</div>
                        <div class="text-sm text-gray-600">₹45,000</div>
                        <div class="text-sm text-gray-500 mt-1">
                          4-hour live music performance
                        </div>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-8 flex justify-between">
              <button
                type="button"
                class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                onclick="prevStep(3)"
              >
                Back
              </button>
              <button
                type="button"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                onclick="nextStep(3)"
              >
                Next
              </button>
            </div>
          </div>

          <!-- Step 4: Visit Scheduling -->
          <div id="step-4" class="step-container">
            <h2 class="text-2xl font-bold mb-6">Schedule a Visit</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Select Date & Time</h3>

                <div class="mb-4">
                  <label for="visit-date" class="block mb-2"
                    >Preferred Visit Date:</label
                  >
                  <input
                    type="date"
                    id="visit-date"
                    name="visit_date"
                    class="border rounded-md px-3 py-2 w-full"
                    min="2023-07-01"
                  />
                </div>

                <div>
                  <label for="visit-time" class="block mb-2"
                    >Preferred Time:</label
                  >
                  <select
                    id="visit-time"
                    name="visit_time"
                    class="border rounded-md px-3 py-2 w-full"
                  >
                    <option value="">Select a time slot</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                  </select>
                </div>
              </div>

              <div class="border rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Your Information</h3>

                <div class="space-y-4">
                  <div>
                    <label for="name" class="block mb-1">Full Name:</label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      class="border rounded-md px-3 py-2 w-full"
                      required
                    />
                  </div>

                  <div>
                    <label for="email" class="block mb-1">Email Address:</label>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      class="border rounded-md px-3 py-2 w-full"
                      required
                    />
                  </div>

                  <div>
                    <label for="phone" class="block mb-1">Phone Number:</label>
                    <input
                      type="tel"
                      id="phone"
                      name="phone"
                      class="border rounded-md px-3 py-2 w-full"
                      required
                    />
                  </div>

                  <div>
                    <label for="notes" class="block mb-1"
                      >Special Requests:</label
                    >
                    <textarea
                      id="notes"
                      name="notes"
                      rows="3"
                      class="border rounded-md px-3 py-2 w-full"
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-8 flex justify-between">
              <button
                type="button"
                class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                onclick="prevStep(4)"
              >
                Back
              </button>
              <button
                type="button"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                onclick="nextStep(4)"
              >
                Next
              </button>
            </div>
          </div>

          <!-- Step 5: Wedding Date Selection -->
          <div id="step-5" class="step-container">
            <h2 class="text-2xl font-bold mb-6">Select Your Wedding Date</h2>

            <div class="border rounded-lg p-6">
              <div class="mb-6">
                <label
                  for="wedding-date"
                  class="block text-lg font-semibold mb-2"
                  >Wedding Date:</label
                >
                <p class="text-sm text-gray-600 mb-2">
                  Please select a date at least 2 months in advance
                </p>
                <input
                  type="date"
                  id="wedding-date"
                  name="wedding_date"
                  class="border rounded-md px-3 py-2 w-full"
                  min="2023-09-01"
                />
              </div>

              <div>
                <label
                  for="time-preference"
                  class="block text-lg font-semibold mb-2"
                  >Time Preference:</label
                >
                <select
                  id="time-preference"
                  name="time_preference"
                  class="border rounded-md px-3 py-2 w-full"
                >
                  <option value="">Select time preference</option>
                  <option value="morning">Morning (10:00 AM - 12:00 PM)</option>
                  <option value="afternoon">
                    Afternoon (1:00 PM - 4:00 PM)
                  </option>
                  <option value="evening">Evening (5:00 PM - 9:00 PM)</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
              <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-semibold text-yellow-800 mb-2">
                  Important Notes
                </h3>
                <ul class="text-sm text-yellow-700 space-y-1">
                  <li>
                    • Wedding dates must be booked at least 2 months in advance
                  </li>
                  <li>• A 50% deposit is required to secure your date</li>
                  <li>
                    • Date changes are subject to availability and may incur
                    fees
                  </li>
                  <li>
                    • Final guest count must be confirmed 2 weeks before the
                    event
                  </li>
                </ul>
              </div>

              <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-800 mb-2">Benefits</h3>
                <ul class="text-sm text-green-700 space-y-1">
                  <li>
                    • Exclusive use of the venue for your selected time slot
                  </li>
                  <li>• Dedicated wedding coordinator on your special day</li>
                  <li>• Complimentary menu tasting for the couple</li>
                  <li>• Special room rates for wedding guests</li>
                </ul>
              </div>
            </div>

            <div class="mt-8 flex justify-between">
              <button
                type="button"
                class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                onclick="prevStep(5)"
              >
                Back
              </button>
              <button
                type="button"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                onclick="nextStep(5)"
              >
                Next
              </button>
            </div>
          </div>

          <!-- Step 6: Booking Summary -->
          <div id="step-6" class="step-container">
            <h2 class="text-2xl font-bold mb-6">Booking Summary</h2>

            <div class="border rounded-lg p-6 mb-6">
              <h3 class="text-lg font-semibold mb-4">Your Wedding Details</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                  <p class="font-medium">Selected Venue:</p>
                  <p id="summary-hall" class="text-gray-600">Royal Ballroom</p>
                </div>

                <div>
                  <p class="font-medium">Selected Package:</p>
                  <p id="summary-package" class="text-gray-600">Gold Package</p>
                </div>

                <div>
                  <p class="font-medium">Wedding Date:</p>
                  <p id="summary-date" class="text-gray-600">
                    October 15, 2023
                  </p>
                </div>

                <div>
                  <p class="font-medium">Time Preference:</p>
                  <p id="summary-time" class="text-gray-600">
                    Evening (5:00 PM - 9:00 PM)
                  </p>
                </div>

                <div>
                  <p class="font-medium">Guest Count:</p>
                  <p id="summary-guests" class="text-gray-600">100 guests</p>
                </div>

                <div>
                  <p class="font-medium">Visit Appointment:</p>
                  <p id="summary-visit" class="text-gray-600">
                    July 10, 2023 at 2:00 PM
                  </p>
                </div>
              </div>
            </div>

            <div class="border rounded-lg p-6 mb-6">
              <h3 class="text-lg font-semibold mb-4">Customizations</h3>

              <div class="space-y-4">
                <div>
                  <p class="font-medium">Decoration Options:</p>
                  <ul
                    id="summary-decorations"
                    class="text-gray-600 list-disc pl-5"
                  >
                    <li>Traditional Setup (₹25,000)</li>
                  </ul>
                </div>

                <div>
                  <p class="font-medium">Catering Option:</p>
                  <p id="summary-catering" class="text-gray-600">
                    Mixed Menu (₹1,500/plate)
                  </p>
                </div>

                <div>
                  <p class="font-medium">Entertainment Add-ons:</p>
                  <ul
                    id="summary-entertainment"
                    class="text-gray-600 list-disc pl-5"
                  >
                    <li>Professional DJ (₹25,000)</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="border rounded-lg p-6">
              <h3 class="text-lg font-semibold mb-4">Payment Summary</h3>

              <div class="space-y-2">
                <div class="flex justify-between">
                  <span>Base Package:</span>
                  <span id="summary-base-price">₹3,99,999</span>
                </div>

                <div class="flex justify-between">
                  <span>Decorations:</span>
                  <span id="summary-decor-price">₹25,000</span>
                </div>

                <div class="flex justify-between">
                  <span>Catering (100 guests):</span>
                  <span id="summary-catering-price">₹1,50,000</span>
                </div>

                <div class="flex justify-between">
                  <span>Entertainment:</span>
                  <span id="summary-entertainment-price">₹25,000</span>
                </div>

                <div class="flex justify-between pt-2 border-t font-bold">
                  <span>Total:</span>
                  <span id="summary-total">₹5,99,999</span>
                </div>

                <div class="flex justify-between text-sm text-gray-600">
                  <span>Required Deposit (50%):</span>
                  <span id="summary-deposit">₹3,00,000</span>
                </div>
              </div>
            </div>

            <div class="mt-8 flex justify-between">
              <button
                type="button"
                class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                onclick="prevStep(6)"
              >
                Back
              </button>
              <button
                type="button"
                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                onclick="showBookingConfirmation()"
              >
                Complete Booking
              </button>
            </div>
          </div>

          <!-- Booking Confirmation (Hidden initially) -->
          <div id="booking-confirmation" class="hidden">
            <div class="text-center py-8">
              <div
                class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-8 w-8 text-green-600"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Booking Confirmed!
              </h2>
              <p class="text-gray-600 mb-6">
                Thank you for choosing Wet Water Resort for your special day.
              </p>

              <div
                class="max-w-md mx-auto bg-gray-50 rounded-lg p-6 mb-6 text-left"
              >
                <h3 class="font-semibold mb-3">Booking Reference</h3>
                <p class="text-gray-700">
                  <span class="font-medium">Booking ID:</span>
                  <span id="booking-id">WW23456789</span>
                </p>
                <p class="text-gray-700">
                  <span class="font-medium">Date:</span>
                  <span id="confirmation-date"></span>
                </p>
              </div>

              <p class="text-sm text-gray-600 mb-6">
                A confirmation email has been sent to your email address with
                all the details.
              </p>

              <div class="flex justify-center space-x-4">
                <button
                  type="button"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  onclick="window.print()"
                >
                  Print Details
                </button>
                <button
                  type="button"
                  class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                  onclick="resetForm()"
                >
                  Book Another
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </main>

    <script>
      // JavaScript for multi-step form functionality
      function nextStep(currentStep) {
        // Validate current step
        if (!validateStep(currentStep)) {
          return false;
        }

        // Hide current step
        document
          .getElementById("step-" + currentStep)
          .classList.remove("active");

        // Show next step
        document
          .getElementById("step-" + (currentStep + 1))
          .classList.add("active");

        // Update progress bar
        updateProgress(currentStep + 1);

        // If moving to summary step, update summary
        if (currentStep + 1 === 6) {
          updateSummary();
        }

        // Scroll to top
        window.scrollTo(0, 0);

        return true;
      }

      function prevStep(currentStep) {
        // Hide current step
        document
          .getElementById("step-" + currentStep)
          .classList.remove("active");

        // Show previous step
        document
          .getElementById("step-" + (currentStep - 1))
          .classList.add("active");

        // Update progress bar
        updateProgress(currentStep - 1);

        // Scroll to top
        window.scrollTo(0, 0);

        return true;
      }

      function updateProgress(step) {
        // Get all progress steps
        const progressSteps = document.querySelectorAll(".progress-step");

        // Reset all steps
        progressSteps.forEach((stepEl, index) => {
          stepEl.classList.remove("active", "completed");

          if (index + 1 < step) {
            stepEl.classList.add("completed");
          } else if (index + 1 === step) {
            stepEl.classList.add("active");
          }
        });
      }

      function validateStep(step) {
        // Add validation logic for each step
        switch (step) {
          case 1: // Hall selection
            const hallSelected = document.querySelector(
              'input[name="hall"]:checked',
            );
            if (!hallSelected) {
              alert("Please select a wedding hall");
              return false;
            }
            return true;

          case 2: // Package selection
            const packageSelected = document.querySelector(
              'input[name="package"]:checked',
            );
            if (!packageSelected) {
              alert("Please select a wedding package");
              return false;
            }
            return true;

          case 3: // Customization
            // Basic validation - could be more complex in a real application
            const guestCount = document.getElementById("guest-count").value;
            if (!guestCount || guestCount < 10) {
              alert("Please enter a valid guest count (minimum 10)");
              return false;
            }
            return true;

          case 4: // Visit scheduling
            const visitDate = document.getElementById("visit-date").value;
            const visitTime = document.getElementById("visit-time").value;
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            const phone = document.getElementById("phone").value;

            if (!visitDate || !visitTime || !name || !email || !phone) {
              alert("Please fill in all required fields");
              return false;
            }
            return true;

          case 5: // Wedding date
            const weddingDate = document.getElementById("wedding-date").value;
            const timePreference =
              document.getElementById("time-preference").value;

            if (!weddingDate || !timePreference) {
              alert("Please select a wedding date and time preference");
              return false;
            }
            return true;

          default:
            return true;
        }
      }

      function updateSummary() {
        // Get selected hall
        const hallEl = document.querySelector('input[name="hall"]:checked');
        const hallLabel = document.querySelector(
          'label[for="' + hallEl.id + '"] h3',
        ).textContent;
        document.getElementById("summary-hall").textContent = hallLabel;

        // Get selected package
        const packageEl = document.querySelector(
          'input[name="package"]:checked',
        );
        const packageLabel = document.querySelector(
          'label[for="' + packageEl.id + '"] h3',
        ).textContent;
        document.getElementById("summary-package").textContent =
          packageLabel + " Package";

        // Get wedding date and time
        const weddingDate = new Date(
          document.getElementById("wedding-date").value,
        );
        const timePreference = document.getElementById("time-preference");
        const timeLabel =
          timePreference.options[timePreference.selectedIndex].text;

        document.getElementById("summary-date").textContent =
          weddingDate.toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
          });
        document.getElementById("summary-time").textContent = timeLabel;

        // Get guest count
        const guestCount = document.getElementById("guest-count").value;
        document.getElementById("summary-guests").textContent =
          guestCount + " guests";

        // Get visit appointment
        const visitDate = new Date(document.getElementById("visit-date").value);
        const visitTime = document.getElementById("visit-time").value;
        document.getElementById("summary-visit").textContent =
          visitDate.toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
          }) +
          " at " +
          visitTime;

        // Update decorations, catering, and entertainment
        // This is simplified - in a real app you would calculate actual prices
        updateCustomizationSummary();

        // Update pricing
        updatePricingSummary();
      }

      function updateCustomizationSummary() {
        // Decorations
        const decorations = document.querySelectorAll(
          'input[name="decorations[]"]:checked',
        );
        const decorList = document.getElementById("summary-decorations");
        decorList.innerHTML = "";

        if (decorations.length === 0) {
          decorList.innerHTML = "<li>No additional decorations selected</li>";
        } else {
          decorations.forEach((decor) => {
            const label = document.querySelector(
              'label[for="' + decor.id + '"] div.font-medium',
            ).textContent;
            const price = document.querySelector(
              'label[for="' + decor.id + '"] div.text-sm.text-gray-600',
            ).textContent;
            decorList.innerHTML += "<li>" + label + " (" + price + ")</li>";
          });
        }

        // Catering
        const catering = document.querySelector(
          'input[name="catering"]:checked',
        );
        if (catering) {
          const cateringLabel = document.querySelector(
            'label[for="' + catering.id + '"] span.font-medium',
          ).textContent;
          const cateringPrice = document.querySelector(
            'label[for="' + catering.id + '"] span.text-sm.text-gray-600',
          ).textContent;
          document.getElementById("summary-catering").textContent =
            cateringLabel + " (" + cateringPrice + ")";
        } else {
          document.getElementById("summary-catering").textContent =
            "No catering option selected";
        }

        // Entertainment
        const entertainment = document.querySelectorAll(
          'input[name="entertainment[]"]:checked',
        );
        const entList = document.getElementById("summary-entertainment");
        entList.innerHTML = "";

        if (entertainment.length === 0) {
          entList.innerHTML = "<li>No entertainment add-ons selected</li>";
        } else {
          entertainment.forEach((ent) => {
            const label = document.querySelector(
              'label[for="' + ent.id + '"] div.font-medium',
            ).textContent;
            const price = document.querySelector(
              'label[for="' + ent.id + '"] div.text-sm.text-gray-600',
            ).textContent;
            entList.innerHTML += "<li>" + label + " (" + price + ")</li>";
          });
        }
      }

      function updatePricingSummary() {
        // This is a simplified pricing calculation
        // In a real app, you would have more complex logic and server-side validation

        // Base package price
        let basePrice = 0;
        const packageEl = document.querySelector(
          'input[name="package"]:checked',
        );
        if (packageEl) {
          if (packageEl.value === "silver") basePrice = 199999;
          else if (packageEl.value === "gold") basePrice = 399999;
          else if (packageEl.value === "platinum") basePrice = 699999;
        }
        document.getElementById("summary-base-price").textContent =
          "₹" + basePrice.toLocaleString("en-IN");

        // Decoration price
        let decorPrice = 0;
        const decorations = document.querySelectorAll(
          'input[name="decorations[]"]:checked',
        );
        decorations.forEach((decor) => {
          if (decor.value === "minimal-floral") decorPrice += 15000;
          else if (decor.value === "traditional-setup") decorPrice += 25000;
          else if (decor.value === "luxury-decor") decorPrice += 45000;
        });
        document.getElementById("summary-decor-price").textContent =
          "₹" + decorPrice.toLocaleString("en-IN");

        // Catering price
        let cateringPrice = 0;
        const catering = document.querySelector(
          'input[name="catering"]:checked',
        );
        const guestCount =
          parseInt(document.getElementById("guest-count").value) || 0;

        if (catering) {
          if (catering.value === "vegetarian")
            cateringPrice = 1200 * guestCount;
          else if (catering.value === "mixed")
            cateringPrice = 1500 * guestCount;
          else if (catering.value === "premium")
            cateringPrice = 1800 * guestCount;
        }
        document.getElementById("summary-catering-price").textContent =
          "₹" + cateringPrice.toLocaleString("en-IN");

        // Entertainment price
        let entPrice = 0;
        const entertainment = document.querySelectorAll(
          'input[name="entertainment[]"]:checked',
        );
        entertainment.forEach((ent) => {
          if (ent.value === "dj") entPrice += 25000;
          else if (ent.value === "live-band") entPrice += 45000;
        });
        document.getElementById("summary-entertainment-price").textContent =
          "₹" + entPrice.toLocaleString("en-IN");

        // Total price
        const totalPrice = basePrice + decorPrice + cateringPrice + entPrice;
        document.getElementById("summary-total").textContent =
          "₹" + totalPrice.toLocaleString("en-IN");

        // Deposit
        const deposit = Math.round(totalPrice * 0.5);
        document.getElementById("summary-deposit").textContent =
          "₹" + deposit.toLocaleString("en-IN");
      }

      // Show booking confirmation
      function showBookingConfirmation() {
        // Hide the form
        document.getElementById("step-6").classList.remove("active");

        // Show confirmation
        const confirmationDiv = document.getElementById("booking-confirmation");
        confirmationDiv.classList.remove("hidden");

        // Set booking ID (in a real app, this would come from the server)
        document.getElementById("booking-id").textContent =
          "WW" + Math.floor(Math.random() * 10000000);

        // Set confirmation date
        const today = new Date();
        document.getElementById("confirmation-date").textContent =
          today.toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
          });

        // Scroll to top
        window.scrollTo(0, 0);
      }

      // Reset form to start over
      function resetForm() {
        // Hide confirmation
        document.getElementById("booking-confirmation").classList.add("hidden");

        // Reset form fields
        document.getElementById("booking-form").reset();

        // Show first step
        document.getElementById("step-1").classList.add("active");

        // Reset progress bar
        updateProgress(1);

        // Reset hall selection visuals
        document.querySelectorAll(".hall-check").forEach((check) => {
          check.style.backgroundColor = "transparent";
        });

        // Reset package selection visuals
        document.querySelectorAll('input[name="package"]').forEach((pkg) => {
          const label = document.querySelector('label[for="' + pkg.id + '"]');
          label.parentElement.classList.remove("border-blue-500", "bg-blue-50");
          label.parentElement.classList.add("border");
        });

        // Scroll to top
        window.scrollTo(0, 0);
      }

      // Add event listeners for hall selection
      document.querySelectorAll(".hall-radio").forEach((radio) => {
        radio.addEventListener("change", function () {
          // Reset all hall cards
          document.querySelectorAll(".hall-check").forEach((check) => {
            check.style.backgroundColor = "transparent";
          });

          // Highlight selected hall
          if (this.checked) {
            const checkmark = document.querySelector(
              'label[for="' + this.id + '"] .hall-check',
            );
            checkmark.style.backgroundColor = "#3b82f6";
          }
        });
      });

      // Add event listeners for package selection
      document.querySelectorAll(".package-radio").forEach((radio) => {
        radio.addEventListener("change", function () {
          // Reset all package cards
          document.querySelectorAll('input[name="package"]').forEach((pkg) => {
            const label = document.querySelector('label[for="' + pkg.id + '"]');
            label.parentElement.classList.remove(
              "border-blue-500",
              "bg-blue-50",
            );
            label.parentElement.classList.add("border");
          });

          // Highlight selected package
          if (this.checked) {
            const label = document.querySelector(
              'label[for="' + this.id + '"]',
            );
            label.parentElement.classList.add("border-blue-500", "bg-blue-50");
          }
        });
      });
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
  </body>
</html>
@endsection
