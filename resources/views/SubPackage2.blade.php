<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wedding Packages - Elegant Celebrations</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { primary: "#b8860b", secondary: "#f5f5f5" },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
          },
        },
      };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    />
    <style>
      :where([class^="ri-"])::before { content: "\f3c2"; }
      body {
      font-family: 'Inter', sans-serif;
      }
      input[type="number"]::-webkit-inner-spin-button,
      input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
      }
      input[type="number"] {
      -moz-appearance: textfield;
      }
      .calendar-day {
      width: 2rem;
      height: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      }
      .calendar-day.available {
      background-color: #f0f9ff;
      }
      .calendar-day.booked {
      background-color: #fee2e2;
      }
      .calendar-day.selected {
      background-color: #b8860b;
      color: white;
      }
      .calendar-day.empty {
      visibility: hidden;
      }
    </style>
  </head>
  <body class="bg-white">
    <div class="relative">
      <!-- Hero Section -->
      <div
        class="relative h-[400px] flex items-center"
        style="background-image: url('https://readdy.ai/api/search-image?query=luxury%20golden%20theme%20wedding%20venue%20with%20premium%20decorations%2C%20crystal%20chandeliers%2C%20elegant%20floral%20arrangements%2C%20premium%20wedding%20setup%20with%20golden%20accents%2C%20romantic%20luxurious%20atmosphere&width=1280&height=600&seq=1&orientation=landscape'); background-size: cover; background-position: center;"
      >
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="container mx-auto px-4 relative z-10">
          <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
              Golden Wedding Package
            </h1>
            <p class="text-xl text-white mb-6">
              Experience luxury and elegance with our premium wedding package
            </p>
            <div class="bg-white inline-block px-6 py-3 rounded-button">
              <span class="text-2xl font-bold text-gray-800">Rs. 450,000</span>
              <span class="text-sm text-gray-500 ml-2"
                >for up to 150 guests</span
              >
            </div>
          </div>
        </div>
      </div>
      <!-- Main Content -->
      <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Content (2/3) -->
          <div class="lg:col-span-2">
            <!-- Package Overview -->
            <div class="mb-12">
              <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Package Overview
              </h2>
              <p class="text-gray-600 mb-8">
                Our Basic Wedding Package is designed to provide you with
                everything you need for creating a beautiful celebration without
                compromising on quality. This package includes all essential
                elements for a memorable wedding day, making it perfect for
                couples who want a streamlined yet elegant wedding.
              </p>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                  class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 text-center"
                >
                  <div
                    class="w-12 h-12 mx-auto mb-4 flex items-center justify-center"
                  >
                    <i class="ri-heart-line ri-2x text-primary"></i>
                  </div>
                  <h3 class="font-semibold mb-2">Event Space</h3>
                  <p class="text-gray-500 text-sm">5 hrs for guests</p>
                </div>
                <div
                  class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 text-center"
                >
                  <div
                    class="w-12 h-12 mx-auto mb-4 flex items-center justify-center"
                  >
                    <i class="ri-time-line ri-2x text-primary"></i>
                  </div>
                  <h3 class="font-semibold mb-2">Hours of Service</h3>
                  <p class="text-gray-500 text-sm">6 hours</p>
                </div>
                <div
                  class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 text-center"
                >
                  <div
                    class="w-12 h-12 mx-auto mb-4 flex items-center justify-center"
                  >
                    <i class="ri-user-line ri-2x text-primary"></i>
                  </div>
                  <h3 class="font-semibold mb-2">Guest Capacity</h3>
                  <p class="text-gray-500 text-sm">Rs. 2,500 per person</p>
                </div>
              </div>
            </div>
            <!-- What's Included -->
            <div class="mb-12">
              <h2 class="text-2xl font-bold text-gray-800 mb-6">
                What's Included
              </h2>
              <div class="mb-8">
                <h3
                  class="text-lg font-semibold text-gray-800 mb-4 flex items-center"
                >
                  <i class="ri-flower-line mr-2 text-primary"></i> Ceremonies &
                  Decorations
                </h3>
                <ul class="space-y-3 pl-8">
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2"
                      >Poruwa ceremony with premium decorations</span
                    >
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2">Ashtaka ceremony</span>
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2">Elegant entrance decorations</span>
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2">Oil lamp with floral arrangements</span>
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2"
                      >Table decorations with centerpieces</span
                    >
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2">Luxury head table decorations</span>
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2">Setty back decorations</span>
                  </li>
                </ul>
              </div>
              <div class="mb-8">
                <h3
                  class="text-lg font-semibold text-gray-800 mb-4 flex items-center"
                >
                  <i class="ri-restaurant-line mr-2 text-primary"></i> Food &
                  Beverages
                </h3>
                <ul class="space-y-3 pl-8">
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2"
                      >Premium wedding buffet with extensive selection</span
                    >
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2"
                      >Unlimited bites (chicken, sausage, chickpea,
                      mixture)</span
                    >
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2"
                      >Unlimited beverages (Coca-Cola, Sprite, Shandy)</span
                    >
                  </li>
                </ul>
              </div>
              <div>
                <h3
                  class="text-lg font-semibold text-gray-800 mb-4 flex items-center"
                >
                  <i class="ri-music-line mr-2 text-primary"></i> Entertainment
                </h3>
                <ul class="space-y-3 pl-8">
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2"
                      >DJ with complete sound system for 4 hours</span
                    >
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2"
                      >Basic lighting effects for dance floor area</span
                    >
                  </li>
                  <li class="flex items-start">
                    <div
                      class="w-5 h-5 flex items-center justify-center mt-0.5"
                    >
                      <i class="ri-check-line text-primary"></i>
                    </div>
                    <span class="ml-2"
                      >Traditional drummers for entrance and announcements</span
                    >
                  </li>
                </ul>
              </div>
            </div>
            <!-- Gallery -->
            <div class="mb-12">
              <h2 class="text-2xl font-bold text-gray-800 mb-6">Gallery</h2>
              <p class="text-gray-600 mb-6">
                View sample setups and decorations from our Basic Package
                weddings
              </p>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="aspect-square overflow-hidden rounded-lg">
                  <img
                    src="https://readdy.ai/api/search-image?query=elegant%20wedding%20ceremony%20setup%20with%20white%20chairs%20and%20floral%20decorations%2C%20indoor%20wedding%20venue%20with%20soft%20lighting&width=400&height=400&seq=2&orientation=squarish"
                    alt="Wedding ceremony setup"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div class="aspect-square overflow-hidden rounded-lg">
                  <img
                    src="https://readdy.ai/api/search-image?query=wedding%20reception%20table%20setup%20with%20floral%20centerpieces%2C%20elegant%20table%20settings%20with%20white%20tablecloth%20and%20golden%20accents&width=400&height=400&seq=3&orientation=squarish"
                    alt="Reception table setup"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div class="aspect-square overflow-hidden rounded-lg">
                  <img
                    src="https://readdy.ai/api/search-image?query=wedding%20buffet%20setup%20with%20elegant%20food%20display%2C%20catering%20arrangement%20with%20decorative%20elements&width=400&height=400&seq=4&orientation=squarish"
                    alt="Wedding buffet"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div class="aspect-square overflow-hidden rounded-lg">
                  <img
                    src="https://readdy.ai/api/search-image?query=wedding%20cake%20table%20with%20three-tier%20white%20cake%20and%20floral%20decorations%2C%20elegant%20cake%20display&width=400&height=400&seq=5&orientation=squarish"
                    alt="Wedding cake"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div class="aspect-square overflow-hidden rounded-lg">
                  <img
                    src="https://readdy.ai/api/search-image?query=wedding%20dance%20floor%20with%20basic%20lighting%20effects%2C%20evening%20reception%20setup&width=400&height=400&seq=6&orientation=squarish"
                    alt="Dance floor"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div class="aspect-square overflow-hidden rounded-lg">
                  <img
                    src="https://readdy.ai/api/search-image?query=traditional%20wedding%20ceremony%20elements%2C%20oil%20lamp%20ceremony%20setup%20with%20decorations&width=400&height=400&seq=7&orientation=squarish"
                    alt="Traditional elements"
                    class="w-full h-full object-cover"
                  />
                </div>
              </div>
            </div>
            <!-- Testimonials -->
            <div class="mb-12">
              <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Testimonials
              </h2>
              <p class="text-gray-600 mb-8">
                Read what couples who chose our Basic Package have to say about
                their experience
              </p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                  class="bg-white p-6 rounded-lg shadow-sm border border-gray-100"
                >
                  <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                      <img
                        src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20south%20asian%20man%20in%20formal%20attire%2C%20neutral%20background%2C%20wedding%20guest&width=100&height=100&seq=8&orientation=squarish"
                        alt="Sarah D"
                        class="w-full h-full object-cover"
                      />
                    </div>
                    <div>
                      <h4 class="font-semibold">Nikhil & Priya Desa</h4>
                      <div class="flex text-yellow-400">
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <p class="text-gray-600 text-sm">
                    "We had a wonderful wedding experience with the Basic
                    Package. The decorations were beautiful and the food was
                    delicious. Everything was well organized, the staff was
                    friendly, and it made our big day so special. We would
                    definitely recommend this venue to other couples planning
                    their wedding."
                  </p>
                </div>
                <div
                  class="bg-white p-6 rounded-lg shadow-sm border border-gray-100"
                >
                  <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                      <img
                        src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20south%20asian%20woman%20in%20formal%20attire%2C%20neutral%20background%2C%20wedding%20guest&width=100&height=100&seq=9&orientation=squarish"
                        alt="James R"
                        class="w-full h-full object-cover"
                      />
                    </div>
                    <div>
                      <h4 class="font-semibold">Fiona & Raj Patel</h4>
                      <div class="flex text-yellow-400">
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <p class="text-gray-600 text-sm">
                    "The Basic Package was the perfect choice for our intimate
                    wedding. The venue was beautifully decorated, and the staff
                    was incredibly attentive to our needs. The food was
                    delicious, and our guests were impressed with the quality of
                    service. The DJ kept everyone dancing all night, and the
                    overall atmosphere was exactly what we wanted."
                  </p>
                </div>
              </div>
              <div class="mt-6 text-center">
                <button
                  class="bg-white border border-primary text-primary px-6 py-2 rounded-button font-medium hover:bg-primary hover:text-white transition-colors whitespace-nowrap"
                >
                  View More
                </button>
              </div>
            </div>
            <!-- Customize Package -->
            <div class="mb-12">
              <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Customize Your Package
              </h2>
              <p class="text-gray-600 mb-8">
                Enhance your Basic Package with these premium add-ons to create
                your perfect wedding day
              </p>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Decoration Upgrades
                  </h3>
                  <ul class="space-y-4">
                    <li>
                      <div class="flex items-center">
                        <input
                          type="checkbox"
                          id="premium-floral"
                          class="hidden"
                        />
                        <label
                          for="premium-floral"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Premium Floral Arrangements</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 25,000
                      </div>
                    </li>
                    <li>
                      <div class="flex items-center">
                        <input
                          type="checkbox"
                          id="luxury-chair"
                          class="hidden"
                        />
                        <label
                          for="luxury-chair"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Luxury Chair Covers & Sashes</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 15,000
                      </div>
                    </li>
                    <li>
                      <div class="flex items-center">
                        <input
                          type="checkbox"
                          id="premium-poruwa"
                          class="hidden"
                        />
                        <label
                          for="premium-poruwa"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Enhanced Poruwa Decoration</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 20,000
                      </div>
                    </li>
                  </ul>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Entertainment Add-ons
                  </h3>
                  <ul class="space-y-4">
                    <li>
                      <div class="flex items-center">
                        <input
                          type="checkbox"
                          id="extended-dj"
                          class="hidden"
                        />
                        <label
                          for="extended-dj"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Extended DJ Hours</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 10,000 (2 additional hours)
                      </div>
                    </li>
                    <li>
                      <div class="flex items-center">
                        <input type="checkbox" id="live-band" class="hidden" />
                        <label
                          for="live-band"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Live Band Performance</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 45,000
                      </div>
                    </li>
                    <li>
                      <div class="flex items-center">
                        <input
                          type="checkbox"
                          id="traditional-dancers"
                          class="hidden"
                        />
                        <label
                          for="traditional-dancers"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Traditional Dancers</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 30,000
                      </div>
                    </li>
                  </ul>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Food & Beverage Upgrades
                  </h3>
                  <ul class="space-y-4">
                    <li>
                      <div class="flex items-center">
                        <input
                          type="checkbox"
                          id="premium-buffet"
                          class="hidden"
                        />
                        <label
                          for="premium-buffet"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Premium Dessert Station</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 35,000 (includes chocolate fountain)
                      </div>
                    </li>
                    <li>
                      <div class="flex items-center">
                        <input
                          type="checkbox"
                          id="beverage-package"
                          class="hidden"
                        />
                        <label
                          for="beverage-package"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Mocktail Available</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 25,000
                      </div>
                    </li>
                    <li>
                      <div class="flex items-center">
                        <input
                          type="checkbox"
                          id="signature-buffet"
                          class="hidden"
                        />
                        <label
                          for="signature-buffet"
                          class="flex items-center cursor-pointer"
                        >
                          <div
                            class="w-5 h-5 border border-gray-300 rounded mr-3 flex items-center justify-center"
                          >
                            <div
                              class="w-3 h-3 bg-primary rounded hidden"
                            ></div>
                          </div>
                          <span>Signature Buffet Menu</span>
                        </label>
                      </div>
                      <div class="mt-1 pl-8 text-sm text-gray-500">
                        + Rs. 50,000
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- Estimated Total -->
            <div class="mb-12 bg-gray-50 p-6 rounded-lg">
              <h2 class="text-xl font-bold text-gray-800 mb-2">
                Estimated Total
              </h2>
              <p class="text-gray-600 mb-4">
                Final price will be calculated based on your selections and
                guest count
              </p>
              <div class="flex justify-between items-center">
                <span class="text-lg font-semibold">Total Package Price:</span>
                <span id="totalPrice" class="text-2xl font-bold text-primary"
                  >Rs. 450,000</span
                >
              </div>
              <div class="mt-4 space-y-2" id="selectedAddons"></div>
            </div>
            <!-- Book Your Wedding -->
            <div>
              <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Book Your Wedding
              </h2>
              <p class="text-gray-600 mb-8">
                Fill out this form below to request your preferred date and
                we'll get back to you within 24 hours
              </p>
              <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Preferred Wedding Date</label
                  >
                  <div class="relative">
                    <input
                      type="date"
                      class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    />
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Number of Guests</label
                  >
                  <input
                    type="number"
                    min="50"
                    max="200"
                    placeholder="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Special Requests (Optional)</label
                  >
                  <textarea
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                  ></textarea>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2"
                    >Contact Information</label
                  >
                  <input
                    type="text"
                    placeholder="Full Name"
                    class="w-full px-4 py-2 border border-gray-300 rounded mb-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                  />
                  <input
                    type="email"
                    placeholder="Email Address"
                    class="w-full px-4 py-2 border border-gray-300 rounded mb-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                  />
                  <input
                    type="tel"
                    placeholder="Phone Number"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                  />
                </div>
                <div class="md:col-span-2 flex justify-end">
                  <button
                    type="submit"
                    class="bg-primary text-white px-8 py-3 rounded-button font-medium hover:bg-opacity-90 transition-colors whitespace-nowrap"
                  >
                    Book Now
                  </button>
                </div>
              </form>
            </div>
          </div>
          <!-- Right Sidebar (1/3) -->
          <div class="lg:col-span-1">
            <!-- Package Summary -->
            <div
              class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mb-8 sticky top-4"
            >
              <h3 class="text-lg font-bold text-gray-800 mb-4">
                Package Summary
              </h3>
              <div class="mb-4">
                <div class="flex justify-between mb-2">
                  <span class="text-gray-600">Golden Package Fee:</span>
                  <span class="font-semibold">Rs. 450,000</span>
                </div>
                <div class="flex justify-between mb-2">
                  <span class="text-gray-600">Guest Capacity:</span>
                  <span>150 persons</span>
                </div>
                <div class="flex justify-between mb-2">
                  <span class="text-gray-600">Additional guests:</span>
                  <span>Rs. 3,000/person</span>
                </div>
                <div class="flex justify-between mb-2">
                  <span class="text-gray-600">Duration:</span>
                  <span>6 hours</span>
                </div>
              </div>
              <div class="pt-4 border-t border-gray-100">
                <button
                  class="bg-primary text-white w-full py-3 rounded-button font-medium hover:bg-opacity-90 transition-colors whitespace-nowrap"
                >
                  Book Now
                </button>
              </div>
              <div class="mt-6">
                <h4 class="font-semibold text-gray-800 mb-2">Need Help?</h4>
                <p class="text-gray-600 text-sm mb-3">
                  Contact our wedding specialists for assistance with your
                  booking
                </p>
                <div class="flex items-center mb-2">
                  <div class="w-5 h-5 flex items-center justify-center mr-2">
                    <i class="ri-phone-line text-primary"></i>
                  </div>
                  <span>+94 77 123 4567</span>
                </div>
                <div class="flex items-center mb-2">
                  <div class="w-5 h-5 flex items-center justify-center mr-2">
                    <i class="ri-mail-line text-primary"></i>
                  </div>
                  <span>weddings@example.com</span>
                </div>
                <div class="flex items-center">
                  <div class="w-5 h-5 flex items-center justify-center mr-2">
                    <i class="ri-whatsapp-line text-primary"></i>
                  </div>
                  <span>+94 77 123 4567</span>
                </div>
              </div>
            </div>
            <!-- Availability Calendar -->
            <div
              class="bg-white p-6 rounded-lg shadow-sm border border-gray-100"
            >
              <h3 class="text-lg font-bold text-gray-800 mb-4">
                Availability Calendar
              </h3>
              <p class="text-gray-600 text-sm mb-4">
                Check available dates for your wedding
              </p>
              <div class="mb-4">
                <div class="flex justify-between items-center mb-4">
                  <button class="text-gray-600 hover:text-primary">
                    <i class="ri-arrow-left-s-line"></i>
                  </button>
                  <h4 class="font-medium">May 2025</h4>
                  <button class="text-gray-600 hover:text-primary">
                    <i class="ri-arrow-right-s-line"></i>
                  </button>
                </div>
                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                  <div class="text-xs font-medium text-gray-500">Su</div>
                  <div class="text-xs font-medium text-gray-500">Mo</div>
                  <div class="text-xs font-medium text-gray-500">Tu</div>
                  <div class="text-xs font-medium text-gray-500">We</div>
                  <div class="text-xs font-medium text-gray-500">Th</div>
                  <div class="text-xs font-medium text-gray-500">Fr</div>
                  <div class="text-xs font-medium text-gray-500">Sa</div>
                </div>
                <div class="grid grid-cols-7 gap-1">
                  <div class="calendar-day empty"></div>
                  <div class="calendar-day empty"></div>
                  <div class="calendar-day empty"></div>
                  <div class="calendar-day empty"></div>
                  <div class="calendar-day available rounded-sm">1</div>
                  <div class="calendar-day available rounded-sm">2</div>
                  <div class="calendar-day available rounded-sm">3</div>
                  <div class="calendar-day available rounded-sm">4</div>
                  <div class="calendar-day booked rounded-sm">5</div>
                  <div class="calendar-day booked rounded-sm">6</div>
                  <div class="calendar-day available rounded-sm">7</div>
                  <div class="calendar-day available rounded-sm">8</div>
                  <div class="calendar-day available rounded-sm">9</div>
                  <div class="calendar-day available rounded-sm">10</div>
                  <div class="calendar-day available rounded-sm">11</div>
                  <div class="calendar-day booked rounded-sm">12</div>
                  <div class="calendar-day booked rounded-sm">13</div>
                  <div class="calendar-day available rounded-sm">14</div>
                  <div class="calendar-day available rounded-sm">15</div>
                  <div class="calendar-day available rounded-sm">16</div>
                  <div class="calendar-day available rounded-sm">17</div>
                  <div class="calendar-day available rounded-sm">18</div>
                  <div class="calendar-day booked rounded-sm">19</div>
                  <div class="calendar-day booked rounded-sm">20</div>
                  <div class="calendar-day available rounded-sm">21</div>
                  <div class="calendar-day available rounded-sm">22</div>
                  <div class="calendar-day available rounded-sm">23</div>
                  <div class="calendar-day available rounded-sm">24</div>
                  <div class="calendar-day available rounded-sm">25</div>
                  <div class="calendar-day booked rounded-sm">26</div>
                  <div class="calendar-day selected rounded-sm">27</div>
                  <div class="calendar-day available rounded-sm">28</div>
                  <div class="calendar-day available rounded-sm">29</div>
                  <div class="calendar-day available rounded-sm">30</div>
                  <div class="calendar-day available rounded-sm">31</div>
                </div>
              </div>
              <div class="flex justify-between text-sm">
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-[#f0f9ff] rounded-sm mr-1"></div>
                  <span class="text-gray-600">Available</span>
                </div>
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-[#fee2e2] rounded-sm mr-1"></div>
                  <span class="text-gray-600">Booked</span>
                </div>
                <div class="flex items-center">
                  <div class="w-3 h-3 bg-primary rounded-sm mr-1"></div>
                  <span class="text-gray-600">Selected</span>
                </div>
              </div>
              <div class="mt-6 pt-4 border-t border-gray-100">
                <button
                  class="bg-white border border-primary text-primary w-full py-2 rounded-button font-medium hover:bg-primary hover:text-white transition-colors whitespace-nowrap"
                >
                  Check Availability
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script id="checkbox-script">
      document.addEventListener("DOMContentLoaded", function () {
        const basePrice = 450000;
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const totalPriceElement = document.getElementById("totalPrice");
        const selectedAddonsElement = document.getElementById("selectedAddons");

        function updatePrice() {
          let total = basePrice;
          const selectedItems = [];

          checkboxes.forEach((checkbox) => {
            const label = document.querySelector(`label[for="${checkbox.id}"]`);
            const indicator = label.querySelector("div > div");
            const priceText = label
              .closest("li")
              .querySelector(".text-gray-500").textContent;
            const price = parseInt(
              priceText.match(/Rs\. ([\d,]+)/)[1].replace(",", ""),
            );

            if (checkbox.checked) {
              indicator.classList.remove("hidden");
              total += price;
              selectedItems.push({
                name: label.textContent.trim(),
                price: price,
              });
            } else {
              indicator.classList.add("hidden");
            }
          });

          totalPriceElement.textContent = `Rs. ${total.toLocaleString()}`;

          selectedAddonsElement.innerHTML = selectedItems
            .map(
              (item) => `
                  <div class="flex justify-between text-sm text-gray-600">
                      <span>${item.name}</span>
                      <span>+ Rs. ${item.price.toLocaleString()}</span>
                  </div>
              `,
            )
            .join("");
        }

        checkboxes.forEach((checkbox) => {
          checkbox.addEventListener("change", updatePrice);
        });
      });
    </script>
    <script id="calendar-script">
      document.addEventListener("DOMContentLoaded", function () {
        const calendarDays = document.querySelectorAll(".calendar-day:not(.empty)");
        calendarDays.forEach((day) => {
          day.addEventListener("click", function () {
            if (!this.classList.contains("booked")) {
              // Remove selected class from all days
              document
                .querySelectorAll(".calendar-day.selected")
                .forEach((selected) => {
                  selected.classList.remove("selected");
                });
              // Add selected class to clicked day
              this.classList.add("selected");
            }
          });
        });
      });
    </script>
  </body>
</html>
