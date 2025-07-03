@extends('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Infinity Wedding Package</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>tailwind.config={theme:{extend:{colors:{primary:'#8A2BE2',secondary:'#D4AF37'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
body {
    font-family: 'Poppins', sans-serif;
    background-color: #fefefe;
    background-image: url("https://readdy.ai/api/search-image?query=subtle%252520elegant%252520wedding%252520background%252520pattern%252520with%252520delicate%252520floral%252520motifs%252520in%252520very%252520light%252520purple%252520and%252520gold%252520colors%252520on%252520white%252520background%252520minimalist%252520design%252520high%252520end%252520luxury%252520feel%252520seamless%252520pattern&width=1920&height=1080&seq=infinity-bg-pattern&orientation=landscape");
    background-repeat: repeat;
    background-size: 600px;
    background-attachment: fixed;
}
h1, h2, h3, h4 {
    font-family: 'Playfair Display', serif;
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
    background-color: #8A2BE2;
    border-radius: 50%;
}
.gallery-image {
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    overflow: hidden;
}
.gallery-image img {
    transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.gallery-image:hover img {
    transform: scale(1.08);
}
.gallery-image::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(138, 43, 226, 0) 0%, rgba(138, 43, 226, 0.2) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.gallery-image:hover::after {
    opacity: 1;
}
.custom-checkbox {
    appearance: none;
    -webkit-appearance: none;
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid #8A2BE2;
    border-radius: 4px;
    margin-right: 0.5rem;
    position: relative;
    cursor: pointer;
    transition: all 0.2s ease;
}
.custom-checkbox:checked {
    background-color: #8A2BE2;
}
.custom-checkbox:checked::after {
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
.custom-checkbox:focus {
    outline: 2px solid rgba(138, 43, 226, 0.5);
    outline-offset: 2px;
}
.category-title {
    color: #D4AF37;
    font-weight: 600;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
    display: block;
}
.custom-switch {
    position: relative;
    display: inline-block;
    width: 3.5rem;
    height: 1.75rem;
}
.custom-switch input {
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
    background-color: #e2e8f0;
    transition: .4s;
    border-radius: 34px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 1.25rem;
    width: 1.25rem;
    left: 0.25rem;
    bottom: 0.25rem;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
input:checked + .slider {
    background-color: #8A2BE2;
}
input:checked + .slider:before {
    transform: translateX(1.75rem);
}
.date-cell {
    cursor: pointer;
    transition: all 0.3s ease;
}
.date-cell:hover:not(.unavailable) {
    background-color: #f0e6ff;
    transform: scale(1.05);
}
.date-cell.unavailable {
    background-color: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    text-decoration: line-through;
}
.date-cell.selected {
    background-color: #8A2BE2;
    color: white;
}
.date-cell.peak-season {
    border: 2px solid #D4AF37;
}
.testimonial-card {
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.testimonial-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
.btn-primary {
    background-color: #8A2BE2;
    color: white;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    background-color: #7B25C9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(138, 43, 226, 0.25);
}
.btn-primary:active {
    transform: translateY(0);
}
.btn-secondary {
    background-color: white;
    color: #8A2BE2;
    border: 2px solid #8A2BE2;
    transition: all 0.3s ease;
}
.btn-secondary:hover {
    background-color: #f9f5ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(138, 43, 226, 0.15);
}
.btn-secondary:active {
    transform: translateY(0);
}
.infinity-badge {
    position: relative;
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #8A2BE2, #D4AF37);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(138, 43, 226, 0.25);
}
.infinity-badge::before {
    content: "∞";
    margin-right: 0.5rem;
    font-size: 1.25rem;
}
.feature-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}
.feature-card:hover {
    border-color: #8A2BE2;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
.feature-icon {
    transition: all 0.3s ease;
    background-color: rgba(138, 43, 226, 0.1);
    color: #8A2BE2;
}
.feature-card:hover .feature-icon {
    background-color: #8A2BE2;
    color: white;
}
.scroll-reveal {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease;
}
.scroll-reveal.revealed {
    opacity: 1;
    transform: translateY(0);
}
</style>
</head>
@section('content')
<body>
    
<div class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="{{ route('packages') }}" class="flex items-center text-gray-700 hover:text-primary transition-colors" aria-label="Back to Packages">
                    <div class="w-8 h-8 flex items-center justify-center mr-2">
                        <i class="ri-arrow-left-line ri-lg" aria-hidden="true"></i>
                    </div>
                    <span>Back to Packages</span>
                </a>
            </div>
            
            <div class="font-['Playfair_Display'] text-2xl font-bold text-gray-800">Infinity Wedding Package</div>
            <div class="flex items-center">
                <a href="#booking" class="btn-primary py-2 px-4 !rounded-button font-medium transition-colors whitespace-nowrap">Book Now</a>
            </div>
        </div>
    </div>
</div>

<div class="hero-section relative h-[600px] bg-cover bg-center" style="background-image: url('https://readdy.ai/api/search-image?query=ultra%20luxury%20wedding%20setup%20with%20crystal%20chandeliers%2C%20premium%20floral%20installations%2C%20elegant%20drapery%2C%20sophisticated%20lighting%20design%2C%20exclusive%20high-end%20decorations%20with%20purple%20and%20gold%20color%20scheme%2C%20professional%20photography%20with%20dramatic%20lighting&width=1920&height=1000&seq=infinity-wedding-hero&orientation=landscape')">
    <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-purple-900/40"></div>
    <div class="relative h-full flex items-center justify-start px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl ml-0 sm:ml-8 lg:ml-16">
            <div class="infinity-badge mb-6">Infinity Package</div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4 font-['Playfair_Display']">Infinity Wedding Package</h1>
            <p class="text-xl text-white/90 mb-8">Experience the epitome of luxury and elegance for your once-in-a-lifetime celebration</p>
            <div class="bg-white/90 backdrop-blur-sm py-4 px-8 rounded-lg inline-block">
                <span class="text-3xl font-bold text-gray-900">Rs. 600,000</span>
                <span class="text-gray-600 ml-2">for up to 200 guests</span>
            </div>
        </div>
    </div>
</div>

<div class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8 mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Package Overview</h2>
                    <p class="text-gray-700 mb-8">Our Infinity Wedding Package represents the pinnacle of luxury and sophistication. Designed for couples who desire an extraordinary celebration, this premium package offers an unparalleled experience with exclusive amenities, bespoke decorations, and personalized service that will create memories to last a lifetime.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="feature-card bg-white p-6 rounded-lg text-center">
                            <div class="feature-icon w-14 h-14 flex items-center justify-center mx-auto mb-4 rounded-full">
                                <i class="ri-vip-crown-line ri-2x" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Premium Experience</h3>
                            <p class="text-gray-600">Exclusive luxury service</p>
                        </div>
                        <div class="feature-card bg-white p-6 rounded-lg text-center">
                            <div class="feature-icon w-14 h-14 flex items-center justify-center mx-auto mb-4 rounded-full">
                                <i class="ri-user-star-line ri-2x" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Guest Capacity</h3>
                            <p class="text-gray-600">Up to 200 guests</p>
                        </div>
                        <div class="feature-card bg-white p-6 rounded-lg text-center">
                            <div class="feature-icon w-14 h-14 flex items-center justify-center mx-auto mb-4 rounded-full">
                                <i class="ri-time-line ri-2x" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Duration</h3>
                            <p class="text-gray-600">8 hours of venue use</p>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">What's Included</h3>
                    
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 flex items-center justify-center text-primary bg-purple-50 rounded-full mr-3">
                                <i class="ri-flower-line ri-lg" aria-hidden="true"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800">Ceremonies & Decorations</h4>
                        </div>
                        <ul class="feature-list space-y-3 text-gray-700 ml-4">
                            <li>Premium Poruwa ceremony with designer floral arrangements and custom backdrop</li>
                            <li>Ashtaka ceremony with traditional elements and premium decorations</li>
                            <li>Luxury entrance with floral arch and LED pathway lighting</li>
                            <li>Designer oil lamp ceremony with premium decorations and lighting effects</li>
                            <li>Elegant table settings with premium linens, crystal glassware, and gold-accented dinnerware</li>
                            <li>VIP head table with cascading floral arrangements and custom lighting</li>
                            <li>Premium setty back decorations with designer elements</li>
                            <li>Custom monogram projection and ambient lighting throughout venue</li>
                            <li>Luxury chair covers with designer sashes and embellishments</li>
                        </ul>
                    </div>
                    
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 flex items-center justify-center text-primary bg-purple-50 rounded-full mr-3">
                                <i class="ri-restaurant-line ri-lg" aria-hidden="true"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800">Food & Beverages</h4>
                        </div>
                        <ul class="feature-list space-y-3 text-gray-700 ml-4">
                            <li>Premium wedding buffet with chef's signature dishes (20+ items including international cuisine)</li>
                            <li>Unlimited premium bites (chicken, sausage, chickpea, boiled vegetables, seafood options)</li>
                            <li>Unlimited beverages (Coca-Cola, Sprite, Soda, Shandy, fresh fruit juices)</li>
                            <li>Luxury dessert station with chocolate fountain and premium sweets</li>
                            <li>Custom wedding cake (3-tier) with personalized design</li>
                            <li>Champagne toast for bride, groom, and head table</li>
                            <li>Premium coffee and tea station with barista service</li>
                            <li>Late night snack station with gourmet options</li>
                        </ul>
                    </div>
                    
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 flex items-center justify-center text-primary bg-purple-50 rounded-full mr-3">
                                <i class="ri-music-line ri-lg" aria-hidden="true"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800">Entertainment & Extras</h4>
                        </div>
                        <ul class="feature-list space-y-3 text-gray-700 ml-4">
                            <li>Professional DJ entertainment for full event with premium sound system</li>
                            <li>Advanced lighting effects for dance floor with programmable LED systems</li>
                            <li>Welcome dance performance with traditional dancers</li>
                            <li>Jayamangala Gatha with traditional performers</li>
                            <li>Milk/champagne fountain with custom lighting effects</li>
                            <li>Professional photography package (8 hours, 2 photographers)</li>
                            <li>Videography with drone coverage and same-day highlight reel</li>
                            <li>Photo booth with custom backdrop and props</li>
                            <li>Fireworks display (subject to venue approval)</li>
                        </ul>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 flex items-center justify-center text-primary bg-purple-50 rounded-full mr-3">
                                <i class="ri-vip-diamond-line ri-lg" aria-hidden="true"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800">VIP Services</h4>
                        </div>
                        <ul class="feature-list space-y-3 text-gray-700 ml-4">
                            <li>Dedicated wedding coordinator for entire event</li>
                            <li>Bridal suite for preparation and relaxation</li>
                            <li>Complimentary honeymoon suite for wedding night</li>
                            <li>VIP transportation for bride and groom (luxury vehicle)</li>
                            <li>Personal attendants for bride and groom throughout event</li>
                            <li>Express check-in for overnight guests</li>
                            <li>Complimentary spa treatment for bride and groom</li>
                        </ul>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-8 mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Gallery</h2>
                    <p class="text-gray-700 mb-8">Experience the luxury and elegance of our Infinity Package weddings through these stunning images.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="gallery-image relative overflow-hidden rounded-lg h-60">
                            <img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20ceremony%20setup%20with%20premium%20decorations%2C%20crystal%20chandeliers%2C%20elegant%20floral%20arrangements%2C%20purple%20and%20gold%20color%20scheme%2C%20professional%20photography%20with%20dramatic%20lighting&width=600&height=400&seq=infinity-ceremony&orientation=landscape" alt="Luxury Ceremony Setup" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="gallery-image relative overflow-hidden rounded-lg h-60">
                            <img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20head%20table%20with%20cascading%20floral%20arrangements%2C%20crystal%20glassware%2C%20gold%20accents%2C%20purple%20lighting%2C%20professional%20photography&width=600&height=400&seq=infinity-head-table&orientation=landscape" alt="VIP Head Table" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="gallery-image relative overflow-hidden rounded-lg h-60">
                            <img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20entrance%20with%20floral%20arch%2C%20LED%20pathway%20lighting%2C%20purple%20and%20gold%20color%20scheme%2C%20professional%20photography%20with%20dramatic%20lighting&width=600&height=400&seq=infinity-entrance&orientation=landscape" alt="Luxury Entrance" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="gallery-image relative overflow-hidden rounded-lg h-60">
                            <img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20dessert%20station%20with%20chocolate%20fountain%2C%20premium%20sweets%2C%20elegant%20display%2C%20purple%20and%20gold%20color%20scheme%2C%20professional%20photography&width=600&height=400&seq=infinity-dessert&orientation=landscape" alt="Dessert Station" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="gallery-image relative overflow-hidden rounded-lg h-60">
                            <img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20dance%20floor%20with%20advanced%20lighting%20effects%2C%20LED%20systems%2C%20purple%20and%20gold%20color%20scheme%2C%20professional%20photography%20with%20dramatic%20lighting&width=600&height=400&seq=infinity-dance-floor&orientation=landscape" alt="Dance Floor" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="gallery-image relative overflow-hidden rounded-lg h-60">
                            <img src="https://readdy.ai/api/search-image?query=luxury%20wedding%20table%20settings%20with%20crystal%20glassware%2C%20gold-accented%20dinnerware%2C%20premium%20linens%2C%20elegant%20centerpieces%2C%20purple%20and%20gold%20color%20scheme%2C%20professional%20photography&width=600&height=400&seq=infinity-table-settings&orientation=landscape" alt="Table Settings" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    </div>
                </div>
