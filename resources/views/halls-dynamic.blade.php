@extends('layouts.app')

@section('title', 'halls')

@push('styles')

<style>

.bg-bgrose {
    background-image: url('/images/Bgrose.jpg');
    background-size: cover;
    background-position: center;
}


/* Font Classes */
.font-playfair { font-family: 'Playfair Display', serif; }
.font-cormorant { font-family: 'Cormorant Garamond', serif; }
.font-mrs-saint-delafield { font-family: 'Mrs Saint Delafield', cursive; }
.font-lora { font-family: 'Lora', serif; }
.font-pacifico { font-family: 'Pacifico', cursive; }



  input:focus, button:focus, select:focus {
    outline: none;
  }
  input[type="number"]::-webkit-inner-spin-button,
  input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  .hero-gradient {
  background: linear-gradient(to bottom, rgba(0, 0, 0, 0.808), rgba(0, 0, 0, 0.966));
}
  .venue-card {
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  .venue-card.hidden {
    opacity: 0;
    transform: scale(0.95);
    display: none;
  }
  select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: none !important;
    transition: background-color 0.2s ease;
  }
  select:hover {
    background-color: var(--gold-color);
  }
  .no-results {
    transition: opacity 0.3s ease;
  }
</style>
  @endpush

@section('content')
<section class="bg-bgrose bg-transparent backdrop-blur-md">
     
 
<body class="bg-white">


<!-- Page Content -->
<main>
<!-- Hero Section -->
<section class="relative h-[500px] overflow-hidden pt-20">
    <div id="bg-slideshow" class="absolute inset-0">
        <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" 
             style="background-image: url('https://wedluxe.com/wp-content/uploads/2024/12/a-fairy-tale-wedding-five-years-in-the-making-at-casa-loma2-1536x1024.jpg');">
          <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
      
        <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
             style="background-image: url('https://images.squarespace-cdn.com/content/v1/60a499a21e471f054b4fcf0e/8874148e-efeb-40e9-b6b1-9d27b6c7e79b/vjry-6120.jpg?format=2500w');">
          <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
      
        <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
             style="background-image: url('https://www.thegoldencrownhotel.com/images/site-specific/golden_crown/meetings-and-events.jpg');">
          <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
      </div>
      
  
<div class="absolute inset-0 hero-gradient"></div>
<div class="container mx-auto px-4 h-full flex flex-col justify-center relative z-10">
    <h1 class="text-white text-6xl md:text-8xl font-mrs-saint-delafield mb-6 leading-tight tracking-wide drop-shadow-[0_2px_6px_rgba(0,0,0,0.6)]">
        Our Wedding Venues
    </h1>
    
<p class="text-white text-xl font-lora max-w-2xl" style="letter-spacing: 0.02em;">Discover enchanting venues crafted to transform your wedding day into a timeless celebration of love and elegance.</p>
</div>
</section>
<!-- Filters Section -->
<section class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
      <div class="flex flex-wrap items-center justify-between gap-4">
        
        <!-- Dropdown Filters -->
        <div class="flex flex-wrap items-center gap-4">
          
          <!-- Guest Capacity Filter -->
          <div class="relative">
            <select id="capacityFilter" aria-label="Filter by guest capacity" class="appearance-none bg-white border border-gray-300 text-gray-700 py-2 pl-4 pr-8 rounded-button focus:border-primary">
              <option value="all">All Capacities</option>
              <option value="0-100">Up to 100 Guests</option>
              <option value="101-200">100–200 Guests</option>
              <option value="201-400">200–400 Guests</option>
              <option value="401+">400+ Guests</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <i class="ri-arrow-down-s-line"></i>
            </div>
          </div>
  
          <!-- Venue Type Filter -->
          <div class="relative">
            <select id="venueTypeFilter" aria-label="Filter by venue type" class="appearance-none bg-white border border-gray-300 text-gray-700 py-2 pl-4 pr-8 rounded-button focus:border-primary">
              <option value="all">All Venues</option>
              <option value="indoor">Indoor</option>
              <option value="outdoor">Outdoor</option>
              <option value="semi-outdoor">Semi-outdoor</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <i class="ri-arrow-down-s-line"></i>
            </div>
          </div>
  
          <!-- Price Filter -->
          <div class="relative">
            <select id="priceFilter" aria-label="Filter by price range" class="appearance-none bg-white border border-gray-300 text-gray-700 py-2 pl-4 pr-8 rounded-button focus:border-primary">
              <option value="all">All Prices</option>
              <option value="1000-2000">Rs.1,000 – Rs.2,000</option>
              <option value="2001-3000">Rs.2,001 – Rs.3,000</option>
              <option value="3001-4000">Rs.3,001 – Rs.4,000</option>
              <option value="4001-5000">Rs.4,001 – Rs.5,000</option>
              <option value="above-5000">Above Rs.5,000</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <i class="ri-arrow-down-s-line"></i>
            </div>
          </div>
  
        </div>
  
        <!-- Search Input -->
        <div class="relative w-full md:w-auto">
          <input type="text" placeholder="Search venues..." aria-label="Search wedding venues" class="bg-white border border-gray-300 text-gray-700 py-2 pl-10 pr-4 rounded-button w-full md:w-64 focus:border-primary">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
            <i class="ri-search-line"></i>
          </div>
        </div>
  
      </div>
    </div>
  </section>
  
<!-- Wedding Halls Grid -->
<section class="py-16">
<div class="container mx-auto px-4">
<div class="text-center mb-16">
<span class="text-primary font-mrs-saint-delafield text-3xl mb-2 block">Discover</span>
<h2 class="text-4xl font-playfair font-bold text-dark-color" style="letter-spacing: 0.04em;">Our Wedding Venues</h2>
<div class="w-20 h-1 bg-primary mx-auto mt-4 mb-8"></div>
</div>

@php
    $halls = \App\Models\Hall::where('is_active', true)->orderBy('price', 'asc')->get();
@endphp

<div id="venueGrid" class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div id="noResults" class="hidden col-span-full text-center text-gray-600 py-8" role="alert">
<p class="text-lg font-lora">No venues match your criteria. Try adjusting your filters to find the perfect venue.</p>
</div>

@forelse($halls as $hall)
<div class="bg-white rounded-lg shadow-lg overflow-hidden venue-card" data-price="{{ $hall->price }}" data-capacity="{{ $hall->capacity }}" data-venue-type="indoor">
<div class="relative h-64 overflow-hidden">
<img src="{{ $hall->image ? asset('storage/halls/' . $hall->image) : asset('storage/halls/default-hall.jpg') }}" alt="{{ $hall->name }}" class="w-full h-full object-cover object-top transition-transform hover:scale-105 duration-500">
<div class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-sm">
Up to {{ $hall->capacity }} Guests
</div>
</div>
<div class="p-6">
<h3 class="text-2xl font-cormorant font-bold text-gray-800 mb-2" style="letter-spacing: 0.04em;">{{ $hall->name }}</h3>
<p class="text-gray-600 mb-4 font-lora">{{ $hall->description ?: 'Experience the perfect wedding venue with elegant ambiance and exceptional service for your special day.' }}</p>
<div class="flex items-center gap-4 mb-6">
<div class="flex items-center gap-1 text-gray-700">
<div class="w-6 h-6 flex items-center justify-center">
<i class="ri-building-line"></i>
</div>
<span>Indoor</span>
</div>
<div class="flex items-center gap-1 text-gray-700">
<div class="w-6 h-6 flex items-center justify-center">
<i class="ri-group-line"></i>
</div>
<span>{{ $hall->capacity }} guests</span>
</div>
<div class="flex items-center gap-1 text-gray-700">
<div class="w-6 h-6 flex items-center justify-center">
<i class="ri-money-dollar-circle-line"></i>
</div>
<span>Rs.{{ number_format($hall->price) }}</span>
</div>
</div>
<div class="flex justify-between items-center">
<span class="text-gray-800 font-medium">From Rs.{{ number_format($hall->price) }}</span>
<a href="{{ route('booking') }}" class="bg-primary text-white px-4 py-2 rounded-button whitespace-nowrap hover:bg-opacity-90 transition-colors font-playfair font-medium">Book Now</a>
</div>
</div>
</div>
@empty
<div class="col-span-full text-center text-gray-600 py-8">
<p class="text-lg font-lora">No wedding halls available at the moment. Please check back later.</p>
</div>
@endforelse

</div>
</section>
<!-- Why Choose Our Venues -->
<section class="py-16 bg-gray-50">
<div class="container mx-auto px-4">
<h2 class="text-4xl font-playfair font-bold text-center mb-12" style="letter-spacing: 0.04em;">Why Choose Our Venues</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="bg-white p-6 rounded-lg shadow-md text-center">
<div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
<div class="w-8 h-8 flex items-center justify-center text-primary">
<i class="ri-star-line ri-xl"></i>
</div>
</div>
<h3 class="text-xl font-cormorant font-bold mb-3" style="letter-spacing: 0.04em;">Unmatched Elegance</h3>
<p class="text-gray-600 font-lora">Our venues exude luxury and sophistication, ensuring your wedding is a masterpiece of style and comfort.</p>
</div>
<div class="bg-white p-6 rounded-lg shadow-md text-center">
<div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
<div class="w-8 h-8 flex items-center justify-center text-primary">
<i class="ri-team-line ri-xl"></i>
</div>
</div>
<h3 class="text-xl font-cormorant font-bold mb-3" style="letter-spacing: 0.04em;">Expert Planners</h3>
<p class="text-gray-600 font-lora">Our dedicated wedding coordinators craft every detail, turning your vision into a flawless celebration.</p>
</div>
<div class="bg-white p-6 rounded-lg shadow-md text-center">
<div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
<div class="w-8 h-8 flex items-center justify-center text-primary">
<i class="ri-settings-line ri-xl"></i>
</div>
</div>
<h3 class="text-xl font-cormorant font-bold mb-3" style="letter-spacing: 0.04em;">Tailored Experiences</h3>
<p class="text-gray-600 font-lora">Personalize your wedding with flexible packages designed to reflect your unique love story and budget.</p>
</div>
</div>
</div>
</section>
<!-- Testimonials -->
<section class="py-16">
<div class="container mx-auto px-4">
<h2 class="text-4xl font-playfair font-bold text-center mb-12" style="letter-spacing: 0.04em;">What Our Couples Say</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="bg-white p-6 rounded-lg shadow-md">
<div class="flex items-center gap-2 text-yellow-400 mb-4">
<i class="ri-star-fill"></i>
<i class="ri-star-fill"></i>
<i class="ri-star-fill"></i>
<i class="ri-star-fill"></i>
<i class="ri-star-fill"></i>
</div>
<p class="text-gray-600 mb-6 font-lora">"The Grand Ballroom was a dream come true. The team's attention to detail made our wedding unforgettable, and the venue was breathtaking."</p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20a%20smiling%20woman%20with%20elegant%20hairstyle%2C%20neutral%20background%2C%20business%20portrait%20style&width=100&height=100&seq=6&orientation=squarish" alt="Emily Johnson" class="w-full h-full object-cover">
</div>
<div>
<h4 class="font-medium font-cormorant" style="letter-spacing: 0.04em;">Emily & Michael Johnson</h4>
<p class="text-gray-500 text-sm">Married on April 12, 2025</p>
</div>
</div>
</div>
<div class="bg-white p-6 rounded-lg shadow-md">
<div class="flex items-center gap-2 text-yellow-400 mb-4">
<i class="ri-star-fill"></i>
<i class="ri-star-fill"></i>
<i class="ri-star-fill"></i>
<i class="ri-star-fill"></i>
<i class="ri-star-fill"></i>
</div>
<p class="text-gray-600 mb-6 font-lora">"The Beachfront Terrace captured our hearts with its sunset views. The Wet Water Resort team made our intimate wedding absolutely perfect."</p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20a%20smiling%20man%20with%20neat%20hairstyle%2C%20neutral%20background%2C%20business%20portrait%20style&width=100&height=100&seq=7&orientation=squarish" alt="David Williams" class="w-full h-full object-cover">
</div>
<div>
<h4 class="font-medium font-cormorant" style="letter-spacing: 0.04em;">Sarah & David Williams</h4>
<p class="text-gray-500 text-sm">Married on February 28, 2025</p>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- CTA Section -->
<section class="py-16 bg-primary bg-opacity-10">
<div class="container mx-auto px-4 text-center">
<h2 class="text-4xl font-playfair font-bold mb-6" style="letter-spacing: 0.04em;">Begin Your Forever Here</h2>
<p class="text-gray-600 max-w-2xl mx-auto mb-8 font-lora">Let our wedding specialists guide you to the perfect venue. Schedule a tour today and start crafting your dream celebration.</p>
<div class="flex flex-wrap justify-center gap-4">
<a href="booking.html" class="bg-primary text-white px-6 py-3 rounded-button whitespace-nowrap font-medium hover:bg-opacity-90 transition-colors font-playfair">Book a Tour</a>
<a href="contact.html" class="border border-primary text-primary px-6 py-3 rounded-button whitespace-nowrap font-medium hover:bg-opacity-90 hover:text-white transition-colors font-playfair">Contact Us</a>
</div>
</div>
</section>
</main>


<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
// AOS Initialization
try {
  AOS.init({ duration: 1000 });
} catch (e) {
  console.error('AOS initialization failed:', e);
}
// Mobile Menu Toggle
const menuButton = document.getElementById('mobileMenuButton');
const mobileMenu = document.getElementById('mobileMenu');
if (menuButton && mobileMenu) {
  menuButton.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.add('hidden');
    });
  });
  document.addEventListener('click', (e) => {
    if (!mobileMenu.contains(e.target) && !menuButton.contains(e.target)) {
      mobileMenu.classList.add('hidden');
    }
  });
}

// Placeholder Links
document.querySelectorAll('a[href="#gallery"], a[href="#about"]').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    alert('This section is coming soon!');
  });
});

// Booking Placeholder
document.querySelectorAll('a[href="#booking"]').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    alert('Booking system coming soon!');
  });
});



// Newsletter Form
const newsletterButton = document.querySelector('footer button');
if (newsletterButton) {
  newsletterButton.addEventListener('click', () => {
    const emailInput = document.querySelector('footer input[type="email"]');
    const email = emailInput.value.trim();
    if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      alert('Thank you for subscribing to our newsletter!');
      emailInput.value = '';
    } else {
      alert('Please enter a valid email address.');
    }
  });
}

// Venue Filter and Modal
document.addEventListener('DOMContentLoaded', function() {
  const venueModalContainer = document.createElement('div');
  venueModalContainer.className = 'fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center';
  venueModalContainer.innerHTML = `
    <div class="bg-white rounded-lg w-full max-w-2xl mx-4 relative">
      <button class="absolute right-4 top-4 text-gray-500 hover:text-gray-700" aria-label="Close venue details">
        <i class="ri-close-line ri-lg"></i>
      </button>
      <div class="p-6">
        <h2 class="text-2xl font-playfair font-bold mb-6" id="modal-title"></h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6" id="modal-content"></div>
      </div>
    </div>
  `;
  document.body.appendChild(venueModalContainer);

  venueModalContainer.querySelector('button').addEventListener('click', () => {
    venueModalContainer.classList.add('hidden');
  });

  venueModalContainer.addEventListener('click', (e) => {
    if (e.target === venueModalContainer) {
      venueModalContainer.classList.add('hidden');
    }
  });

  const capacityFilter = document.getElementById('capacityFilter');
  const venueTypeFilter = document.getElementById('venueTypeFilter');
  const priceFilter = document.getElementById('priceFilter');
  const venueCards = document.querySelectorAll('#venueGrid .venue-card');
  const noResults = document.getElementById('noResults');

  function applyFilters() {
    let visibleCount = 0;
    const selectedCapacity = capacityFilter.value;
    const selectedVenueType = venueTypeFilter.value;
    const selectedPrice = priceFilter.value;

    venueCards.forEach(card => {
      const capacity = parseInt(card.getAttribute('data-capacity'));
      const venueType = card.getAttribute('data-venue-type');
      const price = parseInt(card.getAttribute('data-price'));

      let capacityMatch = false;
      if (selectedCapacity === 'all') {
        capacityMatch = true;
      } else if (selectedCapacity === '0-100' && capacity <= 100) {
        capacityMatch = true;
      } else if (selectedCapacity === '101-200' && capacity >= 101 && capacity <= 200) {
        capacityMatch = true;
      } else if (selectedCapacity === '201-400' && capacity >= 201 && capacity <= 400) {
        capacityMatch = true;
      } else if (selectedCapacity === '401+' && capacity > 400) {
        capacityMatch = true;
      }

      const venueTypeMatch = selectedVenueType === 'all' || venueType === selectedVenueType;

      let priceMatch = false;
      if (selectedPrice === 'all') {
        priceMatch = true;
      } else if (selectedPrice === '1000-2000' && price >= 1000 && price <= 2000) {
        priceMatch = true;
      } else if (selectedPrice === '2001-3000' && price >= 2001 && price <= 3000) {
        priceMatch = true;
      } else if (selectedPrice === '3001-4000' && price >= 3001 && price <= 4000) {
        priceMatch = true;
      } else if (selectedPrice === '4001-5000' && price >= 4001 && price <= 5000) {
        priceMatch = true;
      } else if (selectedPrice === 'above-5000' && price > 5000) {
        priceMatch = true;
      }

      if (capacityMatch && venueTypeMatch && priceMatch) {
        card.classList.remove('hidden');
        visibleCount++;
      } else {
        card.classList.add('hidden');
      }
    });

    noResults.classList.toggle('hidden', visibleCount > 0);
  }

  [capacityFilter, venueTypeFilter, priceFilter].forEach(filter => {
    filter.addEventListener('change', applyFilters);
  });

  applyFilters();
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
    let currentSlide = 0;
    const slides = document.querySelectorAll('#bg-slideshow .slide');
  
    setInterval(() => {
      slides[currentSlide].classList.add('opacity-0');
      currentSlide = (currentSlide + 1) % slides.length;
      slides[currentSlide].classList.remove('opacity-0');
    }, 4000); // change slide every 4 seconds
  </script>
  



</section>

@endsection