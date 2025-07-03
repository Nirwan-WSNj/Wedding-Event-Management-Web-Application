@extends('layouts.app')

@section('title', 'Wedding Packages')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wedding Packages</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>tailwind.config={theme:{extend:{colors:{primary:'#D4AF37',secondary:'#8D6A9F'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
body {
  position: relative;
  font-family: 'Poppins', sans-serif;
  background-color: #fefefe;
  z-index: 0;
}

/* Add background image + opacity */
body::before {
  content: "";
  position: fixed;
  inset: 0;
  background-image: url("https://readdy.ai/api/search-image?query=subtle%2520elegant%2520wedding%2520background%2520pattern%2520with%2520delicate%2520floral%2520motifs%2520in%2520very%2520light%2520beige%2520and%2520cream%2520colors%2520on%2520white%2520background%2520minimalist%2520design%2520high%2520end%2520luxury%2520feel%2520seamless%2520pattern&width=1920&height=1080&seq=wedding-bg-pattern&orientation=landscape");
  background-repeat: repeat;
  background-size: 600px;
  background-attachment: fixed;
  opacity: 0.5; /* 👈 change this to adjust opacity */
  z-index: -1;
  pointer-events: none;
}
h1, h2, h3, h4 {
font-family: 'Playfair Display', serif;
}
.package-card {
transition: all 0.3s ease;
}
.package-card:hover {
transform: translateY(-8px);
}
.feature-list li {
position: relative;
padding-left: 1.75rem;
}
.feature-list li::before {
content: "";
position: absolute;
left: 0;
top: 0.5rem;
width: 0.5rem;
height: 0.5rem;
background-color: #D4AF37;
border-radius: 50%;
}
input[type="checkbox"] {
appearance: none;
-webkit-appearance: none;
width: 1.25rem;
height: 1.25rem;
border: 2px solid #D4AF37;
border-radius: 4px;
margin-right: 0.5rem;
position: relative;
cursor: pointer;
}
input[type="checkbox"]:checked {
background-color: #D4AF37;
}
input[type="checkbox"]:checked::after {
content: "";
position: absolute;
left: 0.3rem;
top: 0.1rem;
width: 0.5rem;
height: 0.75rem;
border: solid white;
border-width: 0 2px 2px 0;
transform: rotate(45deg);
}
.category-title {
color: #8D6A9F;
font-weight: 600;
margin-top: 1rem;
margin-bottom: 0.5rem;
display: block;
}
</style>
</head>
<body>

@section('content')

<section class="relative h-[500px] overflow-hidden pt-20">
    <div id="bg-slideshow" class="absolute inset-0">
        <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" 
             style="background-image: url('https://onefabday.com/wp-content/uploads/2023/12/pink-summer-cherry-blossom-wedding-inspiration-14-scaled-1130x848_2x.jpg?w=1200');">
          <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
      
        <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
             style="background-image: url('https://images.unsplash.com/photo-1511795409834-ef04bbd61622?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8d2VkZGluZyUyMGNhdGVyaW5nfGVufDB8fDB8fHww');">
          <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
      
        <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0"
     style="background-image: url('{{ asset('images/wedding-brown-bg.jpg') }}');">
  <div class="absolute inset-0 bg-black bg-opacity-40"></div>
</div>

      <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" 
             style="background-image: url('https://images.pexels.com/photos/1114425/pexels-photo-1114425.jpeg');">
          <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        </div>
      </div>
      
<div class="absolute inset-0 hero-gradient"></div>
<div class="container mx-auto px-4 h-full flex flex-col justify-center relative z-10">
    <h1 class="text-white text-8xl md:text-8xl font-mrs-saint-delafield mb-6 leading-tight tracking-wide drop-shadow-[0_2px_6px_rgba(0,0,0,0.6)]">
         Wedding Packages
    </h1>
    
<p class="text-white text-xl font-lora max-w-2xl" style="letter-spacing: 0.02em;"> Create unforgettable memories with our carefully curated wedding packages designed to make your special day truly magical.</p>
</div>
</section>

<div class="py-16 px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto">
<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Elegant Wedding Packages</h2>
<p class="text-lg text-gray-600 max-w-3xl mx-auto">Choose from our carefully designed packages to create your dream wedding celebration.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10">

@forelse($packages as $index => $package)
    @php
        // Define package-specific styling (use actual uploaded images)
        $packageStyles = [
            'Basic Package' => [
                'border' => 'border border-gray-100',
                'gradient' => 'bg-gradient-to-r from-gray-50 to-gray-100',
                'border_bottom' => 'border-gray-200',
                'line_color' => 'bg-gray-300',
                'button_color' => 'bg-gray-700 hover:bg-gray-800',
                'route' => 'package.view'
            ],
            'Golden Package' => [
                'border' => 'border border-gray-100',
                'gradient' => 'bg-gradient-to-r from-amber-50 to-amber-100',
                'border_bottom' => 'border-amber-200',
                'line_color' => 'bg-primary',
                'button_color' => 'bg-primary hover:bg-amber-600',
                'route' => 'package.view2'
            ],
            'Infinity Package' => [
                'border' => 'border-2 border-primary relative',
                'gradient' => 'bg-gradient-to-r from-amber-50 to-amber-100',
                'border_bottom' => 'border-amber-200',
                'line_color' => 'bg-primary',
                'button_color' => 'bg-primary hover:bg-amber-600',
                'route' => 'packages.infinity'
            ]
        ];
        
        $style = $packageStyles[$package->name] ?? $packageStyles['Basic Package'];
        
        // Use actual uploaded image or fallback to default
        $packageImage = $package->image 
            ? asset('storage/packages/' . $package->image) 
            : asset('images/default-package.jpg');
        
        // Use actual guest capacity data from database
        $guestData = [
            'guests' => $package->min_guests . '-' . $package->max_guests . ' guests',
            'additional' => 'Additional guest: Rs. ' . number_format($package->additional_guest_price) . '/person'
        ];
    @endphp

<div class="package-card bg-white rounded-lg shadow-lg overflow-hidden {{ $style['border'] }}">
    @if($package->highlight)
    <div class="absolute top-0 right-0 bg-primary text-white px-4 py-1 text-sm font-semibold">POPULAR</div>
    @endif
    
    <div class="h-48 overflow-hidden">
        <img src="{{ $packageImage }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
    </div>
    
    <div class="{{ $style['gradient'] }} px-6 py-8 border-b {{ $style['border_bottom'] }}">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $package->name }}</h2>
        <div class="w-16 h-1 {{ $style['line_color'] }} mb-4"></div>
        <div class="text-3xl font-bold text-gray-900 mb-2">Rs. {{ number_format($package->price, 0) }}</div>
        <p class="text-gray-600">{{ $guestData['guests'] }}</p>
        <p class="text-sm text-gray-500">{{ $guestData['additional'] }}</p>
    </div>
    
    <div class="px-6 py-6">
        @if($package->features && is_array($package->features) && count($package->features) > 0)
        <ul class="feature-list space-y-4 text-gray-700">
            @php
                $ceremonyFeatures = [];
                $foodFeatures = [];
                $entertainmentFeatures = [];
                
                foreach($package->features as $feature) {
                    if (str_contains(strtolower($feature), 'poruwa') || 
                        str_contains(strtolower($feature), 'decoration') || 
                        str_contains(strtolower($feature), 'ceremony') ||
                        str_contains(strtolower($feature), 'entrance') ||
                        str_contains(strtolower($feature), 'table') ||
                        str_contains(strtolower($feature), 'oil lamp') ||
                        str_contains(strtolower($feature), 'signage') ||
                        str_contains(strtolower($feature), 'altar') ||
                        str_contains(strtolower($feature), 'setty')) {
                        $ceremonyFeatures[] = $feature;
                    } elseif (str_contains(strtolower($feature), 'menu') || 
                             str_contains(strtolower($feature), 'drink') || 
                             str_contains(strtolower($feature), 'bite') ||
                             str_contains(strtolower($feature), 'beverage') ||
                             str_contains(strtolower($feature), 'buffet') ||
                             str_contains(strtolower($feature), 'food')) {
                        $foodFeatures[] = $feature;
                    } else {
                        $entertainmentFeatures[] = $feature;
                    }
                }
            @endphp
            
            @if(count($ceremonyFeatures) > 0)
            <span class="category-title">Ceremonies & Decorations</span>
            @foreach($ceremonyFeatures as $feature)
            <li>{{ $feature }}</li>
            @endforeach
            @endif
            
            @if(count($foodFeatures) > 0)
            <span class="category-title">Food & Beverages</span>
            @foreach($foodFeatures as $feature)
            <li>{{ $feature }}</li>
            @endforeach
            @endif
            
            @if(count($entertainmentFeatures) > 0)
            <span class="category-title">Entertainment & Extras</span>
            @foreach($entertainmentFeatures as $feature)
            <li>{{ $feature }}</li>
            @endforeach
            @endif
        </ul>
        @else
        <div class="text-gray-600">
            <p>{{ $package->description }}</p>
        </div>
        @endif
        
        <a href="{{ route($style['route']) }}" class="mt-8 block w-full">
            <button class="w-full {{ $style['button_color'] }} text-white py-3 px-6 !rounded-button font-medium transition-colors whitespace-nowrap">
                View Package
            </button>
        </a>
    </div>
</div>

@empty
<!-- Fallback content when no packages are available -->
<div class="col-span-full text-center py-12">
    <div class="text-gray-500">
        <i class="ri-gift-line text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold mb-2">No Packages Available</h3>
        <p>We're currently updating our wedding packages. Please check back soon!</p>
    </div>
</div>
@endforelse

</div>

<div class="mt-20 bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
<h2 class="text-2xl font-bold text-gray-800 mb-6">Customize Your Package</h2>
<p class="text-gray-600 mb-8">Enhance your wedding experience with these premium add-ons</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<h3 class="text-lg font-semibold text-gray-800 mb-4">Decoration Upgrades</h3>
<div class="space-y-3">
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Premium floral arrangements (+Rs. 35,000)</span>
</label>
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Luxury chair covers and sashes (+Rs. 15,000)</span>
</label>
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Designer backdrop with lighting (+Rs. 25,000)</span>
</label>
</div>
</div>
<div>
<h3 class="text-lg font-semibold text-gray-800 mb-4">Entertainment Add-ons</h3>
<div class="space-y-3">
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Live band performance (3 hours) (+Rs. 45,000)</span>
</label>
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Professional photography package (+Rs. 30,000)</span>
</label>
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Videography with drone shots (+Rs. 40,000)</span>
</label>
</div>
</div>
<div>
<h3 class="text-lg font-semibold text-gray-800 mb-4">Food & Beverage Upgrades</h3>
<div class="space-y-3">
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Premium dessert station (+Rs. 20,000)</span>
</label>
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Champagne toast for all guests (+Rs. 25,000)</span>
</label>
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Late night snack station (+Rs. 15,000)</span>
</label>
</div>
</div>
<div>
<h3 class="text-lg font-semibold text-gray-800 mb-4">Special Extras</h3>
<div class="space-y-3">
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Bridal suite for wedding night (+Rs. 25,000)</span>
</label>
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Fireworks display (+Rs. 40,000)</span>
</label>
<label class="flex items-start">
<input type="checkbox" class="mt-1">
<span class="ml-2">Wedding day coordinator (+Rs. 30,000)</span>
</label>
</div>
</div>
</div>
<div class="mt-8 text-center">
<button class="bg-primary hover:bg-amber-600 text-white py-3 px-8 !rounded-button font-medium transition-colors whitespace-nowrap">Request Custom Quote</button>
</div>
</div>
</div>
</div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
const checkboxes = document.querySelectorAll('input[type="checkbox"]');
checkboxes.forEach(checkbox => {
checkbox.addEventListener('change', function() {
// Add animation for checkbox change
if (this.checked) {
this.parentElement.classList.add('text-primary');
} else {
this.parentElement.classList.remove('text-primary');
}
});
});
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

<!-- Booking System Integration Script -->
<script src="{{ asset('js/booking-system-integration.js') }}"></script>

</body>
</html>
@endsection