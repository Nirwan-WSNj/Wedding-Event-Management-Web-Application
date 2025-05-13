@extends('layouts.app')

@section('title', 'About Us')


    


<html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Wet Water Resort</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
const observerOptions = {
threshold: 0.1
};
const observer = new IntersectionObserver((entries) => {
entries.forEach(entry => {
if (entry.isIntersecting) {
entry.target.style.visibility = 'visible';
entry.target.style.opacity = '1';
entry.target.style.transform = 'translateY(0)';
}
});
}, observerOptions);
document.querySelectorAll('.animate-fadeInUp').forEach(el => {
el.style.visibility = 'hidden';
el.style.opacity = '0';
el.style.transform = 'translateY(20px)';
el.style.transition = 'all 0.6s ease-out';
observer.observe(el);
});
});
</script>
<script>tailwind.config={theme:{extend:{colors:{primary:'#0891b2',secondary:'#155e75'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
body {
font-family: 'Poppins', sans-serif;
}
.heading {
font-family: 'Playfair Display', serif;
}
.feature-card {
transition: all 0.3s ease-in-out;
box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}
.feature-card:hover {
transform: translateY(-5px);
box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
}
.service-card {
transition: all 0.3s ease-in-out;
box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}
.service-card:hover {
transform: translateY(-3px);
box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
}
.testimonial-card {
transition: all 0.3s ease-in-out;
box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}
.testimonial-card:hover {
transform: scale(1.02);
box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
}
input:focus {
outline: none;
}
@keyframes fadeInUp {
from {
opacity: 0;
transform: translateY(20px);
}
to {
opacity: 1;
transform: translateY(0);
}
}
.animate-fadeInUp {
animation: fadeInUp 0.6s ease-out forwards;
}
</style>
</head>

@section('content')

<body class="bg-white text-gray-800">


<!-- Hero Section -->
<section class="relative h-[500px] w-full overflow-hidden">
<div style="background-image: url('https://static.readdy.ai/image/5408d492ca493d214bdee9469a91d7cb/8f13d23650074caa3c99497a56ca2189.png');" class="absolute inset-0 bg-cover bg-center w-full h-full"></div>
<div class="absolute inset-0 bg-black bg-opacity-40"></div>
<div class="relative h-full flex flex-col items-center justify-center text-white px-4 text-center z-10">
<h1 class="heading text-5xl md:text-6xl font-bold mb-4">About Us</h1>
<p class="text-xl md:text-2xl max-w-3xl">A Peaceful Wedding Getaway in Gampaha</p>
</div>
</section>
<!-- Introduction Section -->
<section class="py-16 px-4">
<div class="max-w-6xl mx-auto">
<div class="bg-white rounded-lg p-8 shadow-lg">
<h2 class="heading text-3xl md:text-4xl font-semibold text-center mb-8 text-primary">Wet Water Resort</h2>
<p class="text-lg text-center max-w-4xl mx-auto leading-relaxed">
Located in the lush greenery of Gampaha, Sri Lanka, Wet Water Resort is just 25 km from Bandaranaike International Airport and 30 minutes from Colombo. It's a serene blend of nature and comfort, perfect for weddings and special events.
</p>
</div>
</div>
</section>
<!-- Feature Grid Layout -->
<section class="py-16 px-4 bg-gray-50">
<div class="max-w-6xl mx-auto">
<h2 class="heading text-3xl font-semibold text-center mb-12">Our Resort Features</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<!-- Nature & Comfort -->
<div class="feature-card bg-white p-8 rounded-lg shadow-md transition-all duration-300">
<div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-6">
<i class="ri-plant-line text-primary text-2xl"></i>
</div>
<h3 class="heading text-2xl font-semibold mb-4">Nature & Comfort</h3>
<p class="text-gray-600">
Surrounded by paddy fields, lakes, and tropical gardens, the resort offers a peaceful, romantic atmosphere with gentle breezes and scenic views.
</p>
</div>
<!-- Stay with Us -->
<div class="feature-card bg-white p-8 rounded-lg shadow-md transition-all duration-300">
<div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-6">
<i class="ri-hotel-bed-line text-primary text-2xl"></i>
</div>
<h3 class="heading text-2xl font-semibold mb-4">Stay with Us</h3>
<p class="text-gray-600">
Enjoy elegant rooms with A/C, flat-screen TVs, minibars, balconies, and modern bathrooms—ideal for bridal parties and guests.
</p>
</div>
<!-- Food & Fun -->
<div class="feature-card bg-white p-8 rounded-lg shadow-md transition-all duration-300">
<div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-6">
<i class="ri-restaurant-line text-primary text-2xl"></i>
</div>
<h3 class="heading text-2xl font-semibold mb-4">Food & Fun</h3>
<p class="text-gray-600">
Dine on Sri Lankan, Indian, and Chinese cuisine at our restaurant or enjoy a drink at the King Fisher Bar. Relax by the outdoor pool or explore the grounds on foot or bike.
</p>
</div>
<!-- Services & Amenities -->
<div class="feature-card bg-white p-8 rounded-lg shadow-md transition-all duration-300">
<div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-6">
<i class="ri-service-line text-primary text-2xl"></i>
</div>
<h3 class="heading text-2xl font-semibold mb-4">Services & Amenities</h3>
<p class="text-gray-600">
From our landscaped outdoor pool to expert wedding planning services, we offer comprehensive amenities to make your special day perfect.
</p>
</div>
</div>
</div>
</section>
<!-- Services List Section -->
<section class="py-16 px-4">
<div class="max-w-6xl mx-auto">
<h2 class="heading text-3xl font-semibold text-center mb-12">Our Services</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="bg-white p-8 rounded-lg service-card animate-fadeInUp">
<div class="flex items-start mb-6">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-water-flash-line text-primary"></i>
</div>
<div>
<h3 class="heading text-xl font-semibold mb-2">Pool</h3>
<p class="text-gray-600">Beautifully landscaped outdoor pool surrounded by tropical gardens.</p>
</div>
</div>
<div class="flex items-start mb-6">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-heart-line text-primary"></i>
</div>
<div>
<h3 class="heading text-xl font-semibold mb-2">Event Support</h3>
<p class="text-gray-600">Expert team dedicated to making your wedding or function flawless.</p>
</div>
</div>
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-flight-land-line text-primary"></i>
</div>
<div>
<h3 class="heading text-xl font-semibold mb-2">Airport Shuttle</h3>
<p class="text-gray-600">Convenient transportation available upon request.</p>
</div>
</div>
</div>
<div class="bg-white p-8 rounded-lg service-card animate-fadeInUp">
<div class="flex items-start mb-6">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-bike-line text-primary"></i>
</div>
<div>
<h3 class="heading text-xl font-semibold mb-2">Activities</h3>
<p class="text-gray-600">Bike tours, walking paths, and entertainment options for all guests.</p>
</div>
</div>
<div class="flex items-start mb-6">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-parent-line text-primary"></i>
</div>
<div>
<h3 class="heading text-xl font-semibold mb-2">Family Friendly</h3>
<p class="text-gray-600">Kids' play area and club to keep the little ones entertained.</p>
</div>
</div>
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-map-pin-line text-primary"></i>
</div>
<div>
<h3 class="heading text-xl font-semibold mb-2">Easy Access</h3>
<p class="text-gray-600">Peaceful yet well-connected—near major highways, Colombo, and the airport.</p>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Wedding Showcase -->
<section class="py-16 px-4 bg-gray-50">
<div class="max-w-6xl mx-auto">
<h2 class="heading text-3xl font-semibold text-center mb-12">Perfect for Your Special Day</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="overflow-hidden rounded-lg service-card animate-fadeInUp">
<img src="https://readdy.ai/api/search-image?query=Elegant%20outdoor%20wedding%20ceremony%20setup%20at%20a%20luxury%20resort%20with%20floral%20decorations%2C%20white%20chairs%2C%20and%20a%20beautiful%20arch.%20Tropical%20garden%20setting%20with%20palm%20trees%20and%20water%20features.%20Professional%20photography%20with%20soft%20natural%20lighting.&width=600&height=400&seq=2&orientation=landscape" alt="Wedding Ceremony" class="w-full h-64 object-cover object-top">
<div class="p-6 bg-white">
<h3 class="heading text-xl font-semibold mb-2">Ceremonies</h3>
<p class="text-gray-600">Beautiful outdoor and indoor spaces for your wedding ceremony.</p>
</div>
</div>
<div class="overflow-hidden rounded-lg service-card animate-fadeInUp">
<img src="https://readdy.ai/api/search-image?query=Luxurious%20wedding%20reception%20hall%20at%20a%20resort%20with%20elegant%20table%20settings%2C%20floral%20centerpieces%2C%20and%20ambient%20lighting.%20Tables%20arranged%20for%20a%20formal%20dinner%20with%20white%20linens%20and%20gold%20accents.%20Professional%20photography%20with%20warm%20lighting.&width=600&height=400&seq=3&orientation=landscape" alt="Wedding Reception" class="w-full h-64 object-cover object-top">
<div class="p-6 bg-white">
<h3 class="heading text-xl font-semibold mb-2">Receptions</h3>
<p class="text-gray-600">Elegant spaces for your reception with customizable setups.</p>
</div>
</div>
<div class="overflow-hidden rounded-lg service-card animate-fadeInUp">
<img src="https://readdy.ai/api/search-image?query=Luxury%20resort%20accommodation%20with%20king-sized%20bed%2C%20elegant%20decor%2C%20and%20romantic%20atmosphere.%20Honeymoon%20suite%20with%20modern%20amenities%2C%20large%20windows%20with%20scenic%20views%2C%20and%20tasteful%20floral%20arrangements.%20Professional%20photography%20with%20soft%20lighting%20highlighting%20the%20rooms%20features.&width=600&height=400&seq=4&orientation=landscape" alt="Accommodations" class="w-full h-64 object-cover object-top">
<div class="p-6 bg-white">
<h3 class="heading text-xl font-semibold mb-2">Accommodations</h3>
<p class="text-gray-600">Luxurious rooms for the wedding party and guests.</p>
</div>
</div>
</div>
</div>
</section>
<!-- Location & Map Section -->
<section class="py-16 px-4">
<div class="max-w-6xl mx-auto">
<h2 class="heading text-3xl font-semibold text-center mb-12">Our Location</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
<div>
<div class="bg-white p-8 rounded-lg service-card animate-fadeInUp">
<h3 class="heading text-2xl font-semibold mb-4">Easy Access</h3>
<p class="text-gray-600 mb-6">
Peaceful yet well-connected—near major highways, Colombo, and the airport. Perfect for local and destination weddings.
</p>
<div class="space-y-4">
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-map-pin-line text-primary"></i>
</div>
<div>
<h4 class="font-semibold">Address</h4>
<p class="text-gray-600">No. 136/D, Lumbini Uyana, Ja-Ela Rd, Ambanwita, Gampaha</p>
</div>
</div>
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-phone-line text-primary"></i>
</div>
<div>
<h4 class="font-semibold">Phone</h4>
<p class="text-gray-600">+94 33 222 6886</p>
<p class="text-gray-600">Mobile: +94 70 342 9910 / 9919 / 9916</p>
</div>
</div>
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-mail-line text-primary"></i>
</div>
<div>
<h4 class="font-semibold">Email</h4>
<p class="text-gray-600">info@wetwaterresort.com</p>
</div>
</div>
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mr-4">
<i class="ri-global-line text-primary"></i>
</div>
<div>
<h4 class="font-semibold">Website</h4>
<p class="text-gray-600">www.wetwaterresort.com</p>
</div>
</div>
</div>
</div>
</div>
<div class="h-[400px] rounded-lg overflow-hidden shadow-lg">
<iframe
src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.8876523493837!2d79.99183007485764!3d7.141499915287307!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2ee76a4d0f447%3A0x4c6f1c13d9d4007a!2sWet%20Water%20Resort!5e0!3m2!1sen!2sus!4v1682334567890!5m2!1sen!2sus&markers=color:red%7C7.141499915287307,79.99183007485764"
width="100%"
height="100%"
style="border:0;"
allowfullscreen=""
loading="lazy"
referrerpolicy="no-referrer-when-downgrade"
class="w-full h-full">
</iframe>
</div>
</div>
</div>
</section>
<!-- Contact Form -->
<section class="py-16 px-4 bg-gray-50">
<div class="max-w-3xl mx-auto">
<h2 class="heading text-3xl font-semibold text-center mb-12">Get in Touch</h2>
<div class="bg-white p-8 rounded-lg service-card animate-fadeInUp">
<form>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
<div>
<label for="name" class="block text-gray-700 mb-2">Name</label>
<input type="text" id="name" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-primary" placeholder="Your name">
</div>
<div>
<label for="email" class="block text-gray-700 mb-2">Email</label>
<input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-primary" placeholder="Your email">
</div>
</div>
<div class="mb-6">
<label for="subject" class="block text-gray-700 mb-2">Subject</label>
<input type="text" id="subject" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-primary" placeholder="Subject">
</div>
<div class="mb-6">
<label for="message" class="block text-gray-700 mb-2">Message</label>
<textarea id="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-primary" placeholder="Your message"></textarea>
</div>
<button type="submit" class="bg-primary text-white px-6 py-3 rounded-button hover:bg-opacity-90 transition-all duration-300 whitespace-nowrap">Send Message</button>
</form>
</div>
</div>
</section>
<!-- Testimonials -->
<section class="py-16 px-4">
<div class="max-w-6xl mx-auto">
<h2 class="heading text-3xl font-semibold text-center mb-12">What Our Couples Say</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="bg-white p-8 rounded-lg service-card animate-fadeInUp">
<div class="flex mb-4">
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
</div>
<p class="text-gray-600 mb-6">"Our wedding at Wet Water Resort was absolutely magical. The staff went above and beyond to make our day perfect. The natural setting provided the most beautiful backdrop for our ceremony."</p>
<div class="flex items-center">
<div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
<i class="ri-user-line text-gray-500"></i>
</div>
<div>
<h4 class="font-semibold">Amara & Dinesh Perera</h4>
<p class="text-gray-500 text-sm">Wedding: April 2025</p>
</div>
</div>
</div>
<div class="bg-white p-8 rounded-lg service-card animate-fadeInUp">
<div class="flex mb-4">
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
</div>
<p class="text-gray-600 mb-6">"The resort's natural beauty combined with their attention to detail made our wedding day unforgettable. Our guests were impressed with the accommodations and the delicious food."</p>
<div class="flex items-center">
<div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
<i class="ri-user-line text-gray-500"></i>
</div>
<div>
<h4 class="font-semibold">Nisha & Rohan Gunawardena</h4>
<p class="text-gray-500 text-sm">Wedding: February 2025</p>
</div>
</div>
</div>
<div class="bg-white p-8 rounded-lg service-card animate-fadeInUp">
<div class="flex mb-4">
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
<i class="ri-star-fill text-yellow-400"></i>
</div>
<p class="text-gray-600 mb-6">"We had guests flying in from around the world, and the resort's proximity to the airport was perfect. The event team coordinated everything flawlessly, and the setting was breathtaking."</p>
<div class="flex items-center">
<div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
<i class="ri-user-line text-gray-500"></i>
</div>
<div>
<h4 class="font-semibold">Priya & Michael Fernando</h4>
<p class="text-gray-500 text-sm">Wedding: March 2025</p>
</div>
</div>
</div>
</div>
</div>
</section>


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