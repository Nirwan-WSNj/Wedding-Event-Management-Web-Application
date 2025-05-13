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
<!-- Basic Package -->
<div class="package-card bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
<div class="h-48 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=elegant%20simple%20wedding%20setup%20with%20basic%20decorations%2C%20white%20and%20cream%20color%20scheme%2C%20simple%20floral%20arrangements%2C%20clean%20and%20minimalist%20design%2C%20natural%20lighting&width=600&height=400&seq=basic-wedding&orientation=landscape" alt="Basic Package" class="w-full h-full object-cover">
</div>
<div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-8 border-b border-gray-200">
<h2 class="text-2xl font-bold text-gray-800 mb-2">Basic Package</h2>
<div class="w-16 h-1 bg-gray-300 mb-4"></div>
<div class="text-3xl font-bold text-gray-900 mb-2">Rs. 300,000</div>
<p class="text-gray-600">Up to 100 guests</p>
<p class="text-sm text-gray-500">Additional guest: Rs. 2,500/person</p>
</div>
<div class="px-6 py-6">
<ul class="feature-list space-y-4 text-gray-700">
<span class="category-title">Ceremonies & Decorations</span>
<li>Poruwa decoration setup</li>
<li>Traditional oil lamp ceremony</li>
<li>Basic table decorations</li>
<li>Head table decoration</li>
<span class="category-title">Food & Beverages</span>
<li>Standard wedding buffet</li>
<li>Basic selection of soft drinks</li>
<span class="category-title">Entertainment</span>
<li>DJ entertainment (4 hours)</li>
</ul>
<a href="{{ route('package.view') }}" class="mt-8 block w-full">
  <button class="w-full bg-gray-700 hover:bg-gray-800 text-white py-3 px-6 !rounded-button font-medium transition-colors whitespace-nowrap">
    View Package
  </button>
</a>
</div>
</div>
<!-- Golden Package -->
<div class="package-card bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
<div class="h-48 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=luxurious%20wedding%20venue%20setup%20with%20golden%20accents%2C%20premium%20floral%20arrangements%2C%20elegant%20lighting%2C%20sophisticated%20table%20settings%2C%20high-end%20decorative%20elements&width=600&height=400&seq=golden-wedding&orientation=landscape" alt="Golden Package" class="w-full h-full object-cover">
</div>
<div class="bg-gradient-to-r from-amber-50 to-amber-100 px-6 py-8 border-b border-amber-200">
<h2 class="text-2xl font-bold text-gray-800 mb-2">Golden Package</h2>
<div class="w-16 h-1 bg-primary mb-4"></div>
<div class="text-3xl font-bold text-gray-900 mb-2">Rs. 450,000</div>
<p class="text-gray-600">Up to 150 guests</p>
<p class="text-sm text-gray-500">Additional guest: Rs. 3,000/person</p>
</div>
<div class="px-6 py-6">
<ul class="feature-list space-y-4 text-gray-700">
<span class="category-title">Ceremonies & Decorations</span>
<li>Poruwa ceremony with premium decorations</li>
<li>Ashtaka ceremony</li>
<li>Elegant entrance decorations</li>
<li>Oil lamp with floral arrangements</li>
<li>Table decorations with centerpieces</li>
<li>Luxury head table decorations</li>
<li>Setty back decorations</li>
<span class="category-title">Food & Beverages</span>
<li>Unlimited bites (chicken, sausage, chickpea, mixture)</li>
<li>Unlimited beverages (Coca-Cola, Sprite, Shandy)</li>
<li>Premium wedding buffet</li>
<span class="category-title">Entertainment & Extras</span>
<li>DJ music (6 hours)</li>
<li>Milk/champagne fountain</li>
<li>Jayamangala Gatha performance</li>
</ul>
<a href="https://readdy.ai/home/94c556db-14d7-4c73-a45e-c7bf8e211065/d15b7295-22c8-44ce-80a6-1600bb40b7f9" data-readdy="true" class="mt-8 block w-full">
  <button class="w-full bg-primary hover:bg-amber-600 text-white py-3 px-6 !rounded-button font-medium transition-colors whitespace-nowrap">View Package</button>
</a>
</div>
</div>
<!-- Infinity Package -->
<div class="package-card bg-white rounded-lg shadow-lg overflow-hidden border-2 border-primary relative">
<div class="absolute top-0 right-0 bg-primary text-white px-4 py-1 text-sm font-semibold">POPULAR</div>
<div class="h-48 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=ultra%20luxury%20wedding%20setup%20with%20crystal%20chandeliers%2C%20premium%20floral%20installations%2C%20elegant%20drapery%2C%20sophisticated%20lighting%20design%2C%20exclusive%20high-end%20decorations%20with%20gold%20and%20white%20color%20scheme&width=600&height=400&seq=infinity-wedding&orientation=landscape" alt="Infinity Package" class="w-full h-full object-cover">
</div>
<div class="bg-gradient-to-r from-amber-50 to-amber-100 px-6 py-8 border-b border-amber-200">
<h2 class="text-2xl font-bold text-gray-800 mb-2">Infinity Package</h2>
<div class="w-16 h-1 bg-primary mb-4"></div>
<div class="text-3xl font-bold text-gray-900 mb-2">Rs. 450,000</div>
<p class="text-gray-600">Up to 150 guests</p>
<p class="text-sm text-gray-500">150+ guests: Rs. 4,000/person</p>
<p class="text-sm text-gray-500">200+ guests: Rs. 4,100/person</p>
</div>
<div class="px-6 py-6">
<ul class="feature-list space-y-4 text-gray-700">
<span class="category-title">Ceremonies & Decorations</span>
<li>Premium Poruwa setup with designer decorations</li>
<li>Ashtaka ceremony with traditional elements</li>
<li>Luxury entrance decorations with floral arrangements</li>
<li>Designer oil lamp with premium decorations</li>
<li>Elegant table decorations with custom centerpieces</li>
<li>VIP head table decorations</li>
<li>Premium setty back decorations</li>
<span class="category-title">Food & Beverages</span>
<li>Unlimited premium bites (chicken, sausage, chickpea, boiled vegetables)</li>
<li>Unlimited beverages (Coca-Cola, Sprite, Soda, Shandy)</li>
<li>Luxury wedding buffet with chef's specialties</li>
<span class="category-title">Entertainment & Extras</span>
<li>Professional DJ music (full event)</li>
<li>Milk/champagne fountain with lighting effects</li>
<li>Welcome dance performance</li>
<li>Jayamangala Gatha with traditional dancers</li>
</ul>
<a href="https://readdy.ai/home/94c556db-14d7-4c73-a45e-c7bf8e211065/d15b7295-22c8-44ce-80a6-1600bb40b7f9" data-readdy="true" class="mt-8 block w-full">
  <button class="w-full bg-primary hover:bg-amber-600 text-white py-3 px-6 !rounded-button font-medium transition-colors whitespace-nowrap">View Package</button>
</a>
</div>
</div>
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
    let currentSlide = 0;
    const slides = document.querySelectorAll('#bg-slideshow .slide');
  
    setInterval(() => {
      slides[currentSlide].classList.add('opacity-0');
      currentSlide = (currentSlide + 1) % slides.length;
      slides[currentSlide].classList.remove('opacity-0');
    }, 4000); // change slide every 4 seconds
  </script>
  

</body>
</html>
</section>
@endsection