@extends('layouts.app')

@section('title', 'Contact Us')


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Wet Water Resort</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
input[type="date"]::-webkit-calendar-picker-indicator {
opacity: 0.6;
cursor: pointer;
}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
-webkit-appearance: none;
margin: 0;
}
input[type="number"] {
-moz-appearance: textfield;
}

.shadow-soft {
    box-shadow: 0 20px 50px rgba(60, 47, 47, 0.15);}


  #navbar {
    transition: all 0.3s ease;
  }

  .navbar-default {
    background-color: rgba(31, 31, 31, 0.6); /* Transparent dark gray */
    color: white;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
  }

  .navbar-scrolled {
    background-color: white;
    color: black;
    text-shadow: none;
  }





</style>
<script>
tailwind.config={
theme:{
extend:{
colors:{
primary:'#572906'       ,
secondary:'#1f0c01'
},
borderRadius:{
'none':'0px',
'sm':'4px',
DEFAULT:'8px',
'md':'12px',
'lg':'16px',
'xl':'20px',
'2xl':'24px',
'3xl':'32px',
'full':'9999px',
'button':'8px'
}
}

}
</script>
</head>
<body class="bg-gradient-to-br from-[#fdf7f2] via-[#f7efe8] to-[#e9dbcc] min-h-screen font-body text-gray-800">

  <!-- Placeholder for <div class="h-24 bg-gradient-to-b from-[#e0c9b5] to-transparent rounded-b-2xl shadow-inner blur-sm opacity-95"></div>
    Header -->
  <div class="h-24"></div>
  @section('content')
  <div class="max-w-6xl mx-auto px-4 py-8 fade-in">



    <h1 class="text-4xl text-center text-gray-900 font-[Playfair Display]">
  Contact Wet Water Resort
</h1>
<div class="flex items-center justify-center gap-2 mt-2 mb-2">
<div class="flex items-center gap-1">
<i class="ri-star-fill text-yellow-400"></i>
<span class="text-gray-700">4.01</span>
</div>
<span class="text-gray-500">(859 Google reviews)</span>
<span class="text-gray-700">•</span>
<span class="text-gray-700">3-star hotel</span>
</div>
<p class="text-center text-gray-600 mt-2 mb-10">Experience comfort and convenience at our modern resort in Gampaha</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Contact Form -->
<div class="bg-white p-8 rounded-2xl shadow-soft transition hover:scale-[1.01] hover:shadow-lg duration-300">
  <h2 class="text-xl font-semibold text-gray-800 mb-2">Send Us a Message</h2>
  <p class="text-gray-600 text-sm mb-6">Fill out the form below and our wedding team will get back to you within 24 hours.</p>
  <form>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
      <div>
        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
        <input type="text" id="firstName" placeholder="Enter your first name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906]">
      </div>
      <div>
        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
        <input type="text" id="lastName" placeholder="Enter your last name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906]">
      </div>
    </div>

    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
      <input type="email" id="email" placeholder="Enter your email address" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906]">
    </div>

    <div class="mb-4">
      <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
      <input type="tel" id="phone" placeholder="Enter your phone number" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906]">
    </div>

    <div class="mb-4">
      <label for="eventType" class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
      <div class="relative">
        <select id="eventType" class="w-full px-3 py-2 border border-gray-300 rounded appearance-none focus:outline-none focus:ring-1 focus:ring-[#572906] pr-8">
          <option value="" disabled selected>Select event type</option>
          <option value="wedding">Wedding</option>
          <option value="engagement">Engagement Party</option>
          <option value="rehearsal">Rehearsal Dinner</option>
          <option value="reception">Reception Only</option>
          <option value="other">Other</option>
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"></div>
      </div>
    </div>

    <div class="mb-4">
      <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Preferred Date</label>
      <input type="date" id="date" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906]">
    </div>

    <div class="mb-4">
      <label for="guestCount" class="block text-sm font-medium text-gray-700 mb-1">Estimated Guest Count</label>
      <input type="number" id="guestCount" placeholder="Enter estimated number of guests" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906]">
    </div>

    <div class="mb-6">
      <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Your Message</label>
      <textarea id="message" rows="4" placeholder="Tell us about your vision for your special day" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906]"></textarea>
    </div>

    <button type="submit" class="w-full bg-primary text-white py-2 px-4 !rounded-button font-medium hover:bg-blue-700 transition duration-200 whitespace-nowrap">Send Message</button>
  </form>
</div>

<!-- Contact Information -->
<div class="space-y-6">
<div class="bg-white p-6 rounded-2xl shadow-soft">
<h2 class="text-xl font-semibold text-gray-800 mb-2">Contact Information</h2>
<p class="text-gray-600 text-sm mb-6">Reach out to us directly using the information below</p>
<div class="space-y-5">
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-brown-50 rounded-full mr-3">
<i class="ri-phone-line text-primary"></i>
</div>
<div>
<h3 class="font-medium text-gray-800">Phone</h3>
<p class="text-primary">0332 226 886</p>
<p class="text-sm text-gray-500 mt-1">24/7 Front Desk Available</p>
</div>
</div>
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-brown-50 rounded-full mr-3">
<i class="ri-mail-line text-primary"></i>
</div>
<div>
<h3 class="font-medium text-gray-800">Email</h3>
<p class="text-primary">info@wetwaterresort.com</p>
<p class="text-sm text-gray-500 mt-1">We'll respond within 24 hours</p>
</div>
</div>
<div class="flex items-start">
<div class="w-10 h-10 flex items-center justify-center bg-brown-50 rounded-full mr-3">
<i class="ri-map-pin-line text-primary"></i>
</div>
<div>
<h3 class="font-medium text-gray-800">Location</h3>
<p class="text-gray-700">No- 136/D, "Lumbini Uyana"</p>
<p class="text-gray-700">Ja Ela-Ekala-Gampaha-Yakkala Hwy, Gampaha</p>
<p class="text-sm text-gray-500 mt-1">Conveniently located near Gampaha city center</p>
</div>
</div>
<div class="flex items-start">
    <div class="w-10 h-10 flex items-center justify-center bg-brown-50 rounded-full mr-3">
      <i class="ri-time-line text-primary text-xl"></i>
    </div>
    <div>
      <h3 class="font-medium text-gray-800"> Time</h3>
      <p class="text-gray-700">Mon-Fri: 9am-6pm, Sat: 10am-4pm</p>
    </div>
  </div>
</div>
</div>
<div class="bg-white p-6 rounded shadow-lg">

<h2 class="text-xl font-semibold text-gray-800 mb-4">Our Resort</h2>
<div class="rounded overflow-hidden h-64 mb-4">
<img src="https://readdy.ai/api/search-image?query=Modern%20tropical%20resort%20with%20swimming%20pool%20surrounded%20by%20lush%20gardens%2C%20contemporary%20architecture%20with%20clean%20lines%2C%20comfortable%20loungers%20and%20umbrellas%2C%20peaceful%20atmosphere%20with%20mountain%20views%20in%20the%20background%2C%20perfect%20for%20relaxation&width=800&height=400&seq=12345&orientation=landscape" alt="Wet Water Resort" class="w-full h-full object-cover object-top">
</div>
<button class="w-full bg-gray-100 text-gray-800 py-2 px-4 !rounded-button font-medium hover:bg-gray-200 transition duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
<i class="ri-map-2-line"></i>
<span>Get Directions</span>
</button>
</div>
</div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
const form = document.querySelector('form');
form.addEventListener('submit', function(e) {
e.preventDefault();
const messageDiv = document.createElement('div');
messageDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded';
messageDiv.innerHTML = 'Thank you for your message! Our team will contact you shortly.';
document.body.appendChild(messageDiv);
setTimeout(() => messageDiv.remove(), 3000);
form.reset();
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
</section>
@endsection