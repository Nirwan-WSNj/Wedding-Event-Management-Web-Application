@extends('layouts.app')

@section('title', 'Gallery')

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wedding Gallery</title>
  <script src="https://cdn.tailwindcss.com/3.4.16"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#8B5CF6',
            secondary: '#F59E0B',
            glass: 'rgba(255, 255, 255, 0.1)'
          },
          backdropBlur: {
            xs: '2px',
          },
          borderRadius: {
            'none': '0px',
            'sm': '4px', 
            DEFAULT: '8px',
            'md': '12px',
            'lg': '16px',
            'xl': '20px',
            '2xl': '24px',
            '3xl': '32px',
            'full': '9999px',
            'button': '8px'
          },
          animation: {
            'fade-in': 'fadeIn 0.5s ease-out',
            'slide-up': 'slideUp 0.5s ease-out',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            slideUp: {
              '0%': { transform: 'translateY(20px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' },
            }
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
  <style>
    :where([class^="ri-"])::before { content: "\f3c2"; }
    html {
      scroll-behavior: smooth;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fafafa;
      overflow-x: hidden;
    }
    .gallery-title {
      font-family: 'Playfair Display', serif;
    }
    
    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .hover-zoom {
      transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-zoom:hover {
      transform: scale(1.03);
    }

.gallery-item {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  break-inside: avoid;
  position: relative;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}
.gallery-item:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 25px 50px rgba(0,0,0,0.15);
  z-index: 10;
  border: 1px solid rgba(255, 255, 255, 0.2);
}
.gallery-item img {
  @apply transition-transform duration-500;
}
.gallery-item:hover img {
  @apply scale-110;
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

  /* Hero section blur image */
.hero-blur-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: 1;
}
.hero-blur-bg img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: none;
  transform: none;
  pointer-events: none;
  user-select: none;
}
  </style>
</head>

<body>
@section('content')


<section class="relative h-[600px] mb-16 overflow-hidden">
  <div class="absolute inset-0 hero-blur-bg">
    <img src="{{ asset('storage/halls/gallery_hero.jpg') }}" alt="Wedding Venue" class="w-full h-full object-cover object-center" style="filter: none; transform: none; pointer-events: none; user-select: none;">
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_transparent_0%,_rgba(0,0,0,0.4)_100%)]"></div>
  </div>
  <div class="relative container mx-auto px-4 h-full flex items-center z-10">
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
        <input id="gallery-search" type="search" placeholder="Search photos..." class="w-full py-3 pl-10 pr-4 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:outline-none bg-white/80 text-gray-700 shadow-sm" />
        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-400">
          <i class="ri-search-line"></i>
        </div>
      </div>
      <div class="flex items-center gap-2"></div>
    </div>
    <div class="flex justify-center mb-8">
      <nav class="flex flex-wrap justify-center gap-4">
        <button class="category-btn active py-3 px-8 text-primary font-medium bg-primary/5 hover:bg-primary/10 rounded-full transition-all shadow-lg shadow-primary/10 border border-primary/20" data-category="all">All Photos</button>
        <button class="category-btn py-3 px-8 text-gray-600 hover:text-primary glass-card hover:border-primary/30 rounded-full transition-all" data-category="venue">Venues</button>
        <button class="category-btn py-3 px-8 text-gray-600 hover:text-primary glass-card hover:border-primary/30 rounded-full transition-all" data-category="decoration">Decorations</button>
        <button class="category-btn py-3 px-8 text-gray-600 hover:text-primary glass-card hover:border-primary/30 rounded-full transition-all" data-category="catering">Catering</button>
        <button class="category-btn py-3 px-8 text-gray-600 hover:text-primary glass-card hover:border-primary/30 rounded-full transition-all" data-category="couple">Happy Couples</button>
        <button class="category-btn py-3 px-8 text-gray-600 hover:text-primary glass-card hover:border-primary/30 rounded-full transition-all" data-category="ceremony">Ceremony</button>
        <button class="category-btn py-3 px-8 text-gray-600 hover:text-primary glass-card hover:border-primary/30 rounded-full transition-all" data-category="reception">Reception</button>
      </nav>
    </div>
  </div>
  <div id="gallery-grid" class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4"></div>
  <div class="mt-12 text-center">
    <button id="load-more-btn" class="px-6 py-2 bg-white border border-gray-200 rounded-button text-gray-700 hover:bg-gray-50 transition-colors !rounded-button whitespace-nowrap">Load More</button>
  </div>
</main>
<!-- Lightbox -->
<div id="lightbox" class="lightbox backdrop-blur-md">
<div class="absolute top-4 right-4 z-10">
<button id="close-lightbox" class="w-12 h-12 flex items-center justify-center glass-card hover:bg-white/20 rounded-full text-white transition-all duration-300 hover:scale-110">
<i class="ri-close-line ri-xl"></i>
</button>
</div>
<div class="absolute top-1/2 left-4 z-10">
<button id="prev-image" class="w-12 h-12 glass-card hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 hover:scale-110">
<i class="ri-arrow-left-s-line text-xl"></i>
</button>
</div>
<div class="absolute top-1/2 right-4 z-10">
<button id="next-image" class="w-12 h-12 glass-card hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 hover:scale-110">
<i class="ri-arrow-right-s-line text-xl"></i>
</button>
</div>
<div class="flex items-center justify-center w-full h-full">
<div class="lightbox-content flex flex-col items-center animate-fade-in">
<img id="lightbox-img" src="" alt="" class="lightbox-img mb-4 rounded-lg shadow-2xl max-w-[90%] max-h-[80vh] object-contain">
<div class="glass-card p-6 rounded-xl text-white max-w-2xl">
<h3 id="lightbox-title" class="text-2xl font-semibold mb-2"></h3>
<p id="lightbox-description" class="text-white/90"></p>
</div>
</div>
</div>
</div>
<script>
// All gallery images (from existing HTML, now in JS array)
const galleryImages = [
  // Existing remote images
  { category: 'venue', image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20ballroom%20with%20round%20tables%2C%20elegant%20chandeliers%2C%20golden%20decorations%2C%20warm%20lighting%2C%20romantic%20atmosphere%2C%20high-end%20venue%20setup%20for%20wedding%20reception%2C%20professional%20photography&width=600&height=400&seq=1&orientation=landscape', title: 'Grand Ballroom', description: 'Evening Reception' },
  { category: 'ceremony', image: 'https://readdy.ai/api/search-image?query=elegant%20outdoor%20wedding%20ceremony%20setup%20with%20white%20chairs%2C%20floral%20arch%2C%20garden%20setting%2C%20natural%20lighting%2C%20romantic%20atmosphere%2C%20professional%20photography%2C%20high-end%20venue&width=600&height=450&seq=2&orientation=portrait', title: 'Garden Terrace', description: 'Evening Ceremony' },
  { category: 'detail', image: 'https://readdy.ai/api/search-image?query=elegant%20wedding%20table%20setting%20with%20fine%20china%2C%20crystal%20glasses%2C%20silver%20cutlery%2C%20floral%20centerpieces%2C%20white%20tablecloth%2C%20luxury%20catering%20setup%2C%20professional%20photography&width=600&height=350&seq=3&orientation=landscape', title: 'Dining Excellence', description: 'Luxury Catering' },
  { category: 'couple', image: 'https://readdy.ai/api/search-image?query=happy%20newlywed%20couple%20embracing%20in%20golden%20sunset%20light%2C%20romantic%20moment%2C%20wedding%20dress%20flowing%20in%20the%20wind%2C%20professional%20wedding%20photography%2C%20emotional%20candid%20shot%2C%20beautiful%20natural%20backdrop&width=600&height=800&seq=4&orientation=portrait', title: 'Sunset Terrace', description: 'Couple Portraits' },
  { category: 'catering', image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20cake%20display%2C%20multi-tier%20white%20cake%20with%20floral%20decorations%2C%20elegant%20cake%20table%20setup%2C%20soft%20lighting%2C%20professional%20photography%2C%20high-end%20wedding%20reception%20detail&width=600&height=400&seq=5&orientation=landscape', title: 'Crystal Lounge', description: 'Cake Display' },
  { category: 'decoration', image: 'https://readdy.ai/api/search-image?query=elegant%20wedding%20reception%20hall%20with%20hanging%20floral%20installations%2C%20fairy%20lights%2C%20romantic%20atmosphere%2C%20luxury%20venue%20decoration%2C%20professional%20photography%2C%20dreamy%20wedding%20setup&width=600&height=450&seq=6&orientation=portrait', title: 'Enchanted Hall', description: 'Floral Decorations' },
  { category: 'catering', image: 'https://readdy.ai/api/search-image?query=elegant%20wedding%20buffet%20display%20with%20gourmet%20food%2C%20chef%20carving%20station%2C%20luxury%20catering%20setup%2C%20fine%20dining%20presentation%2C%20professional%20photography%2C%20high-end%20wedding%20reception&width=600&height=350&seq=7&orientation=landscape', title: 'Royal Kitchen', description: 'Gourmet Buffet' },
  { category: 'ceremony', image: 'https://readdy.ai/api/search-image?query=elegant%20wedding%20ceremony%20by%20the%20water%2C%20lakeside%20wedding%20setup%2C%20white%20chairs%2C%20floral%20arch%2C%20natural%20lighting%2C%20romantic%20atmosphere%2C%20professional%20photography%2C%20high-end%20venue&width=600&height=400&seq=8&orientation=landscape', title: 'Lakeside Pavilion', description: 'Waterfront Ceremony' },
  { category: 'reception', image: 'https://readdy.ai/api/search-image?query=happy%20wedding%20guests%20dancing%20at%20reception%2C%20luxury%20ballroom%20with%20beautiful%20lighting%2C%20elegant%20decorations%2C%20professional%20photography%2C%20joyful%20atmosphere%2C%20high-end%20wedding%20party&width=600&height=450&seq=9&orientation=portrait', title: 'Celebration Hall', description: 'Dance Reception' },
  { category: 'detail', image: 'https://readdy.ai/api/search-image?query=elegant%20wedding%20rings%20on%20decorative%20cushion%2C%20luxury%20jewelry%20photography%2C%20soft%20lighting%2C%20close-up%20detail%20shot%2C%20professional%20wedding%20photography&width=600&height=400&seq=10&orientation=landscape', title: 'Wedding Rings', description: 'Precious Details' },
  { category: 'decoration', image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20flower%20arrangements%2C%20elegant%20centerpieces%20with%20roses%20and%20orchids%2C%20sophisticated%20floral%20design%2C%20soft%20lighting%2C%20professional%20wedding%20photography&width=600&height=450&seq=11&orientation=portrait', title: 'Floral Paradise', description: 'Elegant Arrangements' },
  { category: 'reception', image: 'https://readdy.ai/api/search-image?query=elegant%20wedding%20reception%20setup%20with%20crystal%20chandeliers%2C%20luxury%20table%20settings%2C%20romantic%20evening%20lighting%2C%20professional%20wedding%20photography&width=600&height=350&seq=12&orientation=landscape', title: 'Evening Reception', description: 'Magical Atmosphere' },
  // Additional images from previous code
  { category: 'venue', image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20venue%20with%20grand%20staircase%2C%20marble%20floors%2C%20crystal%20chandeliers%2C%20elegant%20architecture%2C%20professional%20photography&width=600&height=400&seq=13&orientation=landscape', title: 'Grand Entrance', description: 'Majestic Staircase' },
  { category: 'decoration', image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20decoration%20with%20hanging%20crystal%20installations%2C%20premium%20floral%20arrangements%2C%20soft%20lighting%2C%20romantic%20atmosphere%2C%20professional%20photography&width=600&height=450&seq=14&orientation=portrait', title: 'Crystal Dreams', description: 'Elegant Installations' },
  { category: 'catering', image: 'https://readdy.ai/api/search-image?query=luxury%20wedding%20dessert%20table%2C%20gourmet%20pastries%2C%20chocolate%20fountain%2C%20elegant%20presentation%2C%20professional%20food%20photography&width=600&height=400&seq=15&orientation=landscape', title: 'Sweet Delights', description: 'Dessert Station' },
  { category: 'couple', image: 'https://readdy.ai/api/search-image?query=elegant%20bride%20and%20groom%20portrait%20in%20luxury%20venue%2C%20romantic%20moment%2C%20professional%20wedding%20photography%2C%20emotional%20candid%20shot&width=600&height=450&seq=16&orientation=portrait', title: 'Forever Love', description: 'Romantic Moments' },
  // Local image assets from project
  { category: 'venue', image: '/images/GrandBallroom.jpg', title: 'Grand Ballroom', description: 'Spacious and elegant venue.' },
  { category: 'venue', image: '/images/GardenPavilion.jpg', title: 'Garden Pavilion', description: 'Beautiful garden setting.' },
  { category: 'venue', image: '/images/jublieeballroom.jpg', title: 'Jubilee Ballroom', description: 'Classic ballroom for celebrations.' },
  { category: 'venue', image: '/images/RoyalHeritage.jpg', title: 'Royal Heritage Hall', description: 'Heritage style wedding hall.' },
  { category: 'venue', image: '/images/Riverside Garden.jpg', title: 'Riverside Garden', description: 'Riverside outdoor venue.' },
  { category: 'venue', image: '/images/weddinghero1.jpg', title: 'Wedding Hero 1', description: 'Venue highlight.' },
  { category: 'venue', image: '/images/weddinghero2.jpg', title: 'Wedding Hero 2', description: 'Venue highlight.' },
  { category: 'venue', image: '/images/weddinghero3.jpg', title: 'Wedding Hero 3', description: 'Venue highlight.' },
  { category: 'venue', image: '/images/weddinghero4.jpg', title: 'Wedding Hero 4', description: 'Venue highlight.' },
  { category: 'venue', image: '/images/weddinghero5.jpg', title: 'Wedding Hero 5', description: 'Venue highlight.' },
  { category: 'venue', image: '/images/weddinghero6.jpg', title: 'Wedding Hero 6', description: 'Venue highlight.' },
  { category: 'decoration', image: '/images/decoration1.jpg', title: 'Decoration 1', description: 'Wedding decoration.' },
  { category: 'decoration', image: '/images/decoration2.jpg', title: 'Decoration 2', description: 'Wedding decoration.' },
  { category: 'decoration', image: '/images/decoration3.jpeg', title: 'Decoration 3', description: 'Wedding decoration.' },
  { category: 'decoration', image: '/images/rose pettle.png', title: 'Rose Petals', description: 'Petal decor.' },
  { category: 'decoration', image: '/images/Bgrose.jpg', title: 'Bouquet of Roses', description: 'Floral arrangement.' },
  { category: 'ceremony', image: '/images/cermony1.jpg', title: 'Wedding Ceremony', description: 'Ceremony moment.' },
  { category: 'ceremony', image: '/images/catholic_type.jpg', title: 'Catholic Wedding', description: 'Catholic style ceremony.' },
  { category: 'ceremony', image: '/images/Indian_type.jpg', title: 'Indian Wedding', description: 'Indian style ceremony.' },
  { category: 'ceremony', image: '/images/eurpian_type.jpg', title: 'European Wedding', description: 'European style ceremony.' },
  { category: 'ceremony', image: '/images/kandiayn_type.jpg', title: 'Kandyan Wedding', description: 'Kandyan style ceremony.' },
  { category: 'ceremony', image: '/images/kandyan_SetBack.jpg', title: 'Traditional Setee-back', description: 'Elegant settee-back for the couple.' },
  { category: 'ceremony', image: '/images/kandy_Poruwa.jpg', title: 'Traditional Poruwa Decor', description: 'Kandyan Poruwa for wedding.' },
  { category: 'ceremony', image: '/images/kandy_oilLamp.jpg', title: 'Traditional Oil Lamp', description: 'Ornately decorated oil lamp.' },
  { category: 'ceremony', image: '/images/kandy_headtab.jpg', title: 'Head Table Decor', description: 'Decorated head table.' },
  { category: 'ceremony', image: '/images/kandy_goldstand.jpg', title: 'Gold Stand', description: 'Traditional gold stand.' },
  { category: 'ceremony', image: '/images/low_country.jpg', title: 'Low-Country Wedding', description: 'Southern Sri Lankan traditions.' },
  { category: 'catering', image: '/images/Milk-Fountain.jpg', title: 'Milk Fountain', description: 'Symbolic milk fountain.' },
  { category: 'couple', image: '/images/couple1.jpg', title: 'Wedding Couple 1', description: 'Happy couple.' },
  { category: 'couple', image: '/images/couple2.jpg', title: 'Wedding Couple 2', description: 'Happy couple.' },
  { category: 'couple', image: '/images/couple3.jpg', title: 'Wedding Couple 3', description: 'Happy couple.' },
  { category: 'couple', image: '/images/couple5.jpg', title: 'Wedding Couple 5', description: 'Happy couple.' },
  { category: 'reception', image: '/images/dance1.jpg', title: 'Wedding Dance', description: 'Reception dance.' }
];
const IMAGES_PER_LOAD = 8;
let currentCategory = 'all';
let displayedCount = 0;
let filteredImages = galleryImages;
function renderGallery(reset = false) {
  const grid = document.getElementById('gallery-grid');
  if (reset) {
    grid.innerHTML = '';
    displayedCount = 0;
  }
  const toShow = filteredImages.slice(0, displayedCount + IMAGES_PER_LOAD);
  grid.innerHTML = '';
  toShow.forEach((item, idx) => {
    const div = document.createElement('div');
    div.className = 'gallery-item group rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-xl transition-all duration-300';
    div.setAttribute('data-category', item.category);
    div.innerHTML = `
      <div class=\"relative h-72 overflow-hidden\">
        <img src=\"${item.image}\" alt=\"${item.title}\" class=\"w-full h-full object-cover object-top\">
        <div class=\"absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300\"></div>
        <div class=\"absolute top-3 right-3 image-actions flex gap-2\">
          <button class=\"heart-btn w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600 hover:text-red-500\"><i class=\"ri-heart-line\"></i></button>
          <button class=\"w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full text-gray-600\"><i class=\"ri-fullscreen-line\"></i></button>
        </div>
        <div class=\"absolute bottom-0 left-0 right-0 p-4 text-white\">
          <h3 class=\"font-semibold\">${item.title}</h3>
          <p class=\"text-sm text-white/90\">${item.description}</p>
        </div>
      </div>
    `;
    grid.appendChild(div);
  });
  displayedCount = toShow.length;
  document.getElementById('load-more-btn').style.display = (displayedCount >= filteredImages.length) ? 'none' : '';
  attachGalleryEvents();
}
function filterByCategory(category) {
  currentCategory = category;
  filteredImages = (category === 'all') ? galleryImages : galleryImages.filter(img => img.category === category);
  renderGallery(true);
}
function searchGallery(term) {
  const search = term.trim().toLowerCase();
  filteredImages = galleryImages.filter(img =>
    (currentCategory === 'all' || img.category === currentCategory) &&
    (img.title.toLowerCase().includes(search) || img.description.toLowerCase().includes(search))
  );
  renderGallery(true);
}
function attachGalleryEvents() {
  document.querySelectorAll('.heart-btn').forEach(btn => {
    btn.onclick = function(e) {
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
    };
  });
  document.querySelectorAll('.gallery-item').forEach((item, idx) => {
    const fullscreenBtn = item.querySelector('.ri-fullscreen-line').parentElement;
    fullscreenBtn.onclick = function(e) {
      e.stopPropagation();
      openLightbox(idx);
    };
    item.onclick = function() {
      openLightbox(idx);
    };
  });
}
const categoryButtons = document.querySelectorAll('.category-btn');
categoryButtons.forEach(btn => {
  btn.onclick = function() {
    categoryButtons.forEach(b => b.classList.remove('active', 'text-primary'));
    categoryButtons.forEach(b => b.classList.add('text-gray-600'));
    this.classList.add('active', 'text-primary');
    this.classList.remove('text-gray-600');
    filterByCategory(this.getAttribute('data-category'));
  };
});
document.getElementById('load-more-btn').onclick = function() {
  renderGallery();
};
document.getElementById('gallery-search').addEventListener('input', function() {
  searchGallery(this.value);
});
// Lightbox logic
let currentIndex = 0;
function openLightbox(idx) {
  currentIndex = idx;
  const item = filteredImages[idx];
  document.getElementById('lightbox-img').src = item.image;
  document.getElementById('lightbox-img').alt = item.title;
  document.getElementById('lightbox-title').textContent = item.title;
  document.getElementById('lightbox-description').textContent = item.description;
  document.getElementById('lightbox').classList.add('active');
  document.body.style.overflow = 'hidden';
}
document.getElementById('close-lightbox').onclick = function() {
  document.getElementById('lightbox').classList.remove('active');
  document.body.style.overflow = '';
};
document.getElementById('prev-image').onclick = function() {
  currentIndex = (currentIndex - 1 + filteredImages.length) % filteredImages.length;
  openLightbox(currentIndex);
};
document.getElementById('next-image').onclick = function() {
  currentIndex = (currentIndex + 1) % filteredImages.length;
  openLightbox(currentIndex);
};
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && document.getElementById('lightbox').classList.contains('active')) {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
  }
});
document.getElementById('lightbox').onclick = function(e) {
  if (e.target === this) {
    this.classList.remove('active');
    document.body.style.overflow = '';
  }
};
// Initial render
renderGallery(true);
</script>
</body>
</html>
</section>
@endsection