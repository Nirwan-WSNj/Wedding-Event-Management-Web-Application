@extends('layouts.app')

@section('title', 'Gallery')


    


<html>
<html lang="en">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wedding Gallery</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>tailwind.config={theme:{extend:{colors:{primary:'#8B5CF6',secondary:'#F59E0B'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
body {
font-family: 'Poppins', sans-serif;
background-color: #fafafa;
}
.gallery-title {
font-family: 'Playfair Display', serif;
}
.gallery-item {
transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
overflow: hidden;
break-inside: avoid;
position: relative;
}
.gallery-item:hover {
transform: translateY(-4px);
box-shadow: 0 20px 40px rgba(0,0,0,0.1);
z-index: 10;
}
.gallery-item:hover img {
transform: scale(1.05);
}
.gallery-item img {
transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.gallery-item:hover .image-overlay {
opacity: 1;
}
.gallery-item:hover .image-actions {
opacity: 1;
}
.image-overlay {
background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);
opacity: 0.8;
transition: opacity 0.3s ease;
}
.image-actions {
opacity: 0;
transition: opacity 0.3s ease;
}
.category-btn {
position: relative;
transition: all 0.3s ease;
}
.category-btn.active::after {
content: '';
position: absolute;
bottom: -4px;
left: 50%;
transform: translateX(-50%);
width: 20px;
height: 2px;
background-color: #8B5CF6;
border-radius: 2px;
}
.heart-btn.active {
color: #ef4444;
}
input[type="search"]::-webkit-search-decoration,
input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-results-button,
input[type="search"]::-webkit-search-results-decoration {
display: none;
}
/* Custom switch */
.switch {
position: relative;
display: inline-block;
width: 40px;
height: 20px;
}
.switch input {
opacity: 0;
width: 0;
height: 0;
}
.slider {
position: absolute;
cursor: pointer;
top: 0;
left: 0;
right: 0;
bottom: 0;
background-color: #e5e7eb;
transition: .4s;
border-radius: 34px;
}
.slider:before {
position: absolute;
content: "";
height: 16px;
width: 16px;
left: 2px;
bottom: 2px;
background-color: white;
transition: .4s;
border-radius: 50%;
}
input:checked + .slider {
background-color: #8B5CF6;
}
input:checked + .slider:before {
transform: translateX(20px);
}
/* Lightbox styles */
.lightbox {
display: none;
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.9);
z-index: 1000;
opacity: 0;
transition: opacity 0.3s ease;
}
.lightbox.active {
display: flex;
opacity: 1;
}
.lightbox-img {
max-width: 90%;
max-height: 90%;
object-fit: contain;
}
.lightbox-content {
animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
from { opacity: 0; }
to { opacity: 1; }
}
/* Loading animation */
.loading-item {
animation: pulse 1.5s infinite;
}
@keyframes pulse {
0% { opacity: 0.6; }
50% { opacity: 0.3; }
100% { opacity: 0.6; }
}
/* Masonry grid */
.masonry-grid {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
grid-auto-rows: 10px;
grid-gap: 20px;
}
</style>
</head>
<body>

@section('content')

<section class="relative h-[500px] mb-16 overflow-hidden">
<div class="absolute inset-0">
<img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20venue%20with%20elegant%20decorations%2C%20grand%20ballroom%20with%20chandeliers%20and%20floral%20arrangements%2C%20romantic%20atmosphere%2C%20soft%20lighting%2C%20high-end%20wedding%20setup%2C%20professional%20photography%2C%20dreamy%20and%20romantic%20scene&width=1920&height=1000&seq=hero&orientation=landscape" alt="Wedding Venue" class="w-full h-full object-cover">
<div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent"></div>
</div>
<div class="relative container mx-auto px-4 h-full flex items-center">
<div class="max-w-2xl text-white">
<h1 class="gallery-title text-5xl font-bold mb-4">Capture Your Perfect Moments</h1>
<p class="text-xl text-white/90 mb-8">Explore our curated collection of stunning wedding photography at Enchanted Waters Resort. Every moment, every smile, every detail captured perfectly.</p>

</div>
</div>
</section>
<main class="container mx-auto px-4 pb-8 max-w-7xl">
<div class="text-center mb-10">
<h1 class="gallery-title text-4xl font-bold mb-2">Wedding Gallery</h1>
<p class="text-gray-600 mb-8">Browse through our collection of beautiful wedding moments at Enchanted Waters Resort</p>
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
<div class="relative w-full md:w-96">

<div class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-400">
<i class="ri-search-line"></i>
</div>
</div>
<div class="flex items-center gap-2">


</div>
</div>
<div class="flex justify-center mb-8">
<nav class="flex flex-wrap justify-center gap-6">
<button class="category-btn active py-2 px-6 text-primary font-medium hover:bg-primary/5 rounded-full transition-all" data-category="all">All Photos</button>
<button class="category-btn py-2 px-6 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-full transition-all" data-category="venue">Venues</button>
<button class="category-btn py-2 px-6 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-full transition-all" data-category="decoration">Decorations</button>
<button class="category-btn py-2 px-6 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-full transition-all" data-category="catering">Catering</button>
<button class="category-btn py-2 px-6 text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-full transition-all" data-category="couple">Happy Couples</button>
</nav>
</div>
</div>
<div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
<!-- Gallery Item 1 -->
<div class="gallery-item group rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-xl" data-category="venue">
<div class="relative h-72 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20ballroom%20with%20round%20tables%2C%20elegant%20chandeliers%2C%20golden%20decorations%2C%20warm%20lighting%2C%20romantic%20atmosphere%2C%20high-end%20venue%20setup%20for%20wedding%20reception%2C%20professional%20photography&width=600&height=400&seq=1&orientation=landscape" alt="Grand Ballroom" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Grand Ballroom</h3>
<p class="text-sm text-white/90">Evening Reception</p>
</div>
</div>
</div>
<!-- Gallery Item 2 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="ceremony">
<div class="relative h-80 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=elegant%20outdoor%20wedding%20ceremony%20setup%20with%20white%20chairs%2C%20floral%20arch%2C%20garden%20setting%2C%20natural%20lighting%2C%20romantic%20atmosphere%2C%20professional%20photography%2C%20high-end%20venue&width=600&height=450&seq=2&orientation=portrait" alt="Garden Terrace" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Garden Terrace</h3>
<p class="text-sm text-white/90">Evening Ceremony</p>
</div>
</div>
</div>
<!-- Gallery Item 3 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="detail">
<div class="relative h-64 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=elegant%20wedding%20table%20setting%20with%20fine%20china%2C%20crystal%20glasses%2C%20silver%20cutlery%2C%20floral%20centerpieces%2C%20white%20tablecloth%2C%20luxury%20catering%20setup%2C%20professional%20photography&width=600&height=350&seq=3&orientation=landscape" alt="Table Setting" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Dining Excellence</h3>
<p class="text-sm text-white/90">Luxury Catering</p>
</div>
</div>
</div>
<!-- Gallery Item 4 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="couple">
<div class="relative h-96 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=happy%20newlywed%20couple%20embracing%20in%20golden%20sunset%20light%2C%20romantic%20moment%2C%20wedding%20dress%20flowing%20in%20the%20wind%2C%20professional%20wedding%20photography%2C%20emotional%20candid%20shot%2C%20beautiful%20natural%20backdrop&width=600&height=800&seq=4&orientation=portrait" alt="Happy Couple" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn active w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-red-500">
<i class="ri-heart-fill"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Sunset Terrace</h3>
<p class="text-sm text-white/90">Couple Portraits</p>
</div>
</div>
</div>
<!-- Gallery Item 5 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="catering">
<div class="relative h-72 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20cake%20display%2C%20multi-tier%20white%20cake%20with%20floral%20decorations%2C%20elegant%20cake%20table%20setup%2C%20soft%20lighting%2C%20professional%20photography%2C%20high-end%20wedding%20reception%20detail&width=600&height=400&seq=5&orientation=landscape" alt="Wedding Cake" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Crystal Lounge</h3>
<p class="text-sm text-white/90">Cake Display</p>
</div>
</div>
</div>
<!-- Gallery Item 6 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="decoration">
<div class="relative h-80 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=elegant%20wedding%20reception%20hall%20with%20hanging%20floral%20installations%2C%20fairy%20lights%2C%20romantic%20atmosphere%2C%20luxury%20venue%20decoration%2C%20professional%20photography%2C%20dreamy%20wedding%20setup&width=600&height=450&seq=6&orientation=portrait" alt="Reception Hall" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Enchanted Hall</h3>
<p class="text-sm text-white/90">Floral Decorations</p>
</div>
</div>
</div>
<!-- Gallery Item 7 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="catering">
<div class="relative h-64 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=elegant%20wedding%20buffet%20display%20with%20gourmet%20food%2C%20chef%20carving%20station%2C%20luxury%20catering%20setup%2C%20fine%20dining%20presentation%2C%20professional%20photography%2C%20high-end%20wedding%20reception&width=600&height=350&seq=7&orientation=landscape" alt="Catering Display" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Royal Kitchen</h3>
<p class="text-sm text-white/90">Gourmet Buffet</p>
</div>
</div>
</div>
<!-- Gallery Item 8 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="ceremony">
<div class="relative h-72 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=elegant%20wedding%20ceremony%20by%20the%20water%2C%20lakeside%20wedding%20setup%2C%20white%20chairs%2C%20floral%20arch%2C%20natural%20lighting%2C%20romantic%20atmosphere%2C%20professional%20photography%2C%20high-end%20venue&width=600&height=400&seq=8&orientation=landscape" alt="Lakeside Ceremony" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Lakeside Pavilion</h3>
<p class="text-sm text-white/90">Waterfront Ceremony</p>
</div>
</div>
</div>
<!-- Gallery Item 9 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="reception">
<!-- Gallery Item 10 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="detail">
<div class="relative h-72 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=elegant%20wedding%20rings%20on%20decorative%20cushion%2C%20luxury%20jewelry%20photography%2C%20soft%20lighting%2C%20close-up%20detail%20shot%2C%20professional%20wedding%20photography&width=600&height=400&seq=10&orientation=landscape" alt="Wedding Rings" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Wedding Rings</h3>
<p class="text-sm text-white/90">Precious Details</p>
</div>
</div>
</div>
<!-- Gallery Item 11 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="decoration">
<div class="relative h-80 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20flower%20arrangements%2C%20elegant%20centerpieces%20with%20roses%20and%20orchids%2C%20sophisticated%20floral%20design%2C%20soft%20lighting%2C%20professional%20wedding%20photography&width=600&height=450&seq=11&orientation=portrait" alt="Floral Arrangements" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Floral Paradise</h3>
<p class="text-sm text-white/90">Elegant Arrangements</p>
</div>
</div>
</div>
<!-- Gallery Item 12 -->
<div class="gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300" data-category="reception">
<div class="relative h-64 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=elegant%20wedding%20reception%20setup%20with%20crystal%20chandeliers%2C%20luxury%20table%20settings%2C%20romantic%20evening%20lighting%2C%20professional%20wedding%20photography&width=600&height=350&seq=12&orientation=landscape" alt="Reception Setup" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Evening Reception</h3>
<p class="text-sm text-white/90">Magical Atmosphere</p>
</div>
</div>
</div>
<div class="relative h-80 overflow-hidden">
<img src="https://readdy.ai/api/search-image?query=happy%20wedding%20guests%20dancing%20at%20reception%2C%20luxury%20ballroom%20with%20beautiful%20lighting%2C%20elegant%20decorations%2C%20professional%20photography%2C%20joyful%20atmosphere%2C%20high-end%20wedding%20party&width=600&height=450&seq=9&orientation=portrait" alt="Dance Floor" class="w-full h-full object-cover object-top">
<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute top-3 right-3 image-actions flex gap-2">
<button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
<i class="ri-heart-line"></i>
</button>
<button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
<i class="ri-fullscreen-line"></i>
</button>
</div>
<div class="absolute bottom-0 left-0 right-0 p-4 text-white">
<h3 class="font-semibold">Celebration Hall</h3>
<p class="text-sm text-white/90">Dance Reception</p>
</div>
</div>
</div>
</div>
<div class="mt-12 text-center">
<button class="px-6 py-2 bg-white border border-gray-200 rounded-button text-gray-700 hover:bg-gray-50 transition-colors !rounded-button whitespace-nowrap">Load More</button>
</div>
</main>
<!-- Lightbox -->
<div id="lightbox" class="lightbox">
<div class="absolute top-4 right-4 z-10">
<button id="close-lightbox" class="w-10 h-10 flex items-center justify-center bg-black/50 backdrop-blur-sm rounded-full text-white hover:bg-black/70">
<i class="ri-close-line ri-lg"></i>
</button>
</div>
<div class="absolute top-1/2 left-4 z-10">
<button id="prev-image" class="w-12 h-12 flex items-center justify-center bg-black/50 backdrop-blur-sm rounded-full text-white hover:bg-black/70">
<i class="ri-arrow-left-s-line ri-lg"></i>
</button>
</div>
<div class="absolute top-1/2 right-4 z-10">
<button id="next-image" class="w-12 h-12 flex items-center justify-center bg-black/50 backdrop-blur-sm rounded-full text-white hover:bg-black/70">
<i class="ri-arrow-right-s-line ri-lg"></i>
</button>
</div>
<div class="flex items-center justify-center w-full h-full">
<div class="lightbox-content flex flex-col items-center">
<img id="lightbox-img" src="" alt="" class="lightbox-img mb-4">
<div class="bg-white/10 backdrop-blur-md p-4 rounded-lg text-white max-w-2xl">
<h3 id="lightbox-title" class="text-xl font-semibold mb-1"></h3>
<p id="lightbox-description" class="text-white/90"></p>
</div>
</div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
// Category buttons and filtering
const categoryButtons = document.querySelectorAll('.category-btn');
const galleryItems = document.querySelectorAll('.gallery-item');
function filterGallery(category) {
  galleryItems.forEach(item => {
    const itemCategory = item.getAttribute('data-category');
    if (category === 'all' || itemCategory === category) {
      item.style.display = '';
      item.classList.remove('scale-0', 'opacity-0');
      item.classList.add('scale-100', 'opacity-100');
    } else {
      item.classList.add('scale-0', 'opacity-0');
      setTimeout(() => {
        item.style.display = 'none';
      }, 300);
    }
  });
}
// Add more gallery items dynamically
const additionalItems = [
  {
    category: 'venue',
    image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20venue%20with%20grand%20staircase%2C%20marble%20floors%2C%20crystal%20chandeliers%2C%20elegant%20architecture%2C%20professional%20photography&width=600&height=400&seq=13&orientation=landscape',
    title: 'Grand Entrance',
    description: 'Majestic Staircase'
  },
  {
    category: 'decoration',
    image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20decoration%20with%20hanging%20crystal%20installations%2C%20premium%20floral%20arrangements%2C%20soft%20lighting%2C%20romantic%20atmosphere%2C%20professional%20photography&width=600&height=450&seq=14&orientation=portrait',
    title: 'Crystal Dreams',
    description: 'Elegant Installations'
  },
  {
    category: 'catering',
    image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20dessert%20table%2C%20gourmet%20pastries%2C%20chocolate%20fountain%2C%20elegant%20presentation%2C%20professional%20food%20photography&width=600&height=400&seq=15&orientation=landscape',
    title: 'Sweet Delights',
    description: 'Dessert Station'
  },
  {
    category: 'couple',
    image: 'https://readdy.ai/api/search-image?query=elegant%20bride%20and%20groom%20portrait%20in%20luxury%20venue%2C%20romantic%20moment%2C%20professional%20wedding%20photography%2C%20emotional%20candid%20shot&width=600&height=450&seq=16&orientation=portrait',
    title: 'Forever Love',
    description: 'Romantic Moments'
  }
];
const galleryContainer = document.querySelector('.columns-2');
additionalItems.forEach(item => {
  const galleryItem = document.createElement('div');
  galleryItem.className = 'gallery-item rounded-lg overflow-hidden bg-white transition-all duration-300';
  galleryItem.setAttribute('data-category', item.category);
  
  galleryItem.innerHTML = `
    <div class="relative h-72 overflow-hidden">
      <img src="${item.image}" alt="${item.title}" class="w-full h-full object-cover object-top">
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      <div class="absolute top-3 right-3 image-actions flex gap-2">
        <button class="heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500">
          <i class="ri-heart-line"></i>
        </button>
        <button class="w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600">
          <i class="ri-fullscreen-line"></i>
        </button>
      </div>
      <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
        <h3 class="font-semibold">${item.title}</h3>
        <p class="text-sm text-white/90">${item.description}</p>
      </div>
    </div>
  `;
  
  galleryContainer.appendChild(galleryItem);
});
categoryButtons.forEach(button => {
button.addEventListener('click', function() {
categoryButtons.forEach(btn => btn.classList.remove('active', 'text-primary'));
categoryButtons.forEach(btn => btn.classList.add('text-gray-600'));
this.classList.add('active', 'text-primary');
this.classList.remove('text-gray-600');
const category = this.getAttribute('data-category');
filterGallery(category);
});
});
// Specific handler for venue button
venueButton.addEventListener('click', function() {
categoryButtons.forEach(btn => btn.classList.remove('active', 'text-primary'));
categoryButtons.forEach(btn => btn.classList.add('text-gray-600'));
this.classList.add('active', 'text-primary');
this.classList.remove('text-gray-600');
filterGallery('venue');
});
// Search functionality
const searchInput = document.querySelector('input[type="search"]');
searchInput.addEventListener('input', function() {
const searchTerm = this.value.toLowerCase();
galleryItems.forEach(item => {
const title = item.querySelector('h3').textContent.toLowerCase();
const description = item.querySelector('p').textContent.toLowerCase();
if (title.includes(searchTerm) || description.includes(searchTerm)) {
item.style.display = '';
} else {
item.style.display = 'none';
}
});
});
// Favorites filter
const favoritesToggle = document.querySelector('.switch input');
favoritesToggle.addEventListener('change', function() {
if (this.checked) {
galleryItems.forEach(item => {
const heartBtn = item.querySelector('.heart-btn');
if (!heartBtn.classList.contains('active')) {
item.style.display = 'none';
}
});
} else {
galleryItems.forEach(item => {
item.style.display = '';
});
}
});
// Heart buttons
const heartButtons = document.querySelectorAll('.heart-btn');
heartButtons.forEach(button => {
button.addEventListener('click', function(e) {
e.stopPropagation();
this.classList.toggle('active');
if (this.classList.contains('active')) {
this.innerHTML = '<i class="ri-heart-fill"></i>';
this.classList.add('text-red-500');
this.classList.remove('text-gray-600');
} else {
this.innerHTML = '<i class="ri-heart-line"></i>';
this.classList.remove('text-red-500');
this.classList.add('text-gray-600');
}
});
});
// Lightbox functionality
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');
const lightboxTitle = document.getElementById('lightbox-title');
const lightboxDescription = document.getElementById('lightbox-description');
const closeLightbox = document.getElementById('close-lightbox');
// Gallery items
const galleryItems = document.querySelectorAll('.gallery-item');
let currentIndex = 0;
galleryItems.forEach((item, index) => {
const fullscreenBtn = item.querySelector('.ri-fullscreen-line').parentElement;
fullscreenBtn.addEventListener('click', function(e) {
e.stopPropagation();
openLightbox(index);
});
item.addEventListener('click', function() {
openLightbox(index);
});
});
function openLightbox(index) {
currentIndex = index;
const item = galleryItems[index];
const img = item.querySelector('img');
const title = item.querySelector('h3').textContent;
const description = item.querySelector('p').textContent;
lightboxImg.src = img.src;
lightboxImg.alt = img.alt;
lightboxTitle.textContent = title;
lightboxDescription.textContent = description;
lightbox.classList.add('active');
document.body.style.overflow = 'hidden';
}
closeLightbox.addEventListener('click', function() {
lightbox.classList.remove('active');
document.body.style.overflow = '';
});
// Navigation in lightbox
const prevButton = document.getElementById('prev-image');
const nextButton = document.getElementById('next-image');
prevButton.addEventListener('click', function() {
currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
openLightbox(currentIndex);
});
nextButton.addEventListener('click', function() {
currentIndex = (currentIndex + 1) % galleryItems.length;
openLightbox(currentIndex);
});
// Close lightbox with escape key
document.addEventListener('keydown', function(e) {
if (e.key === 'Escape' && lightbox.classList.contains('active')) {
lightbox.classList.remove('active');
document.body.style.overflow = '';
}
});
// Click outside to close
lightbox.addEventListener('click', function(e) {
if (e.target === lightbox) {
lightbox.classList.remove('active');
document.body.style.overflow = '';
}
});
});
</script>
</body>
</html>
@endsection