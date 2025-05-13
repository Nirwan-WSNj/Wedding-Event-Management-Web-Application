@extends('layouts.app')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Basic Wedding Package</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>tailwind.config={theme:{extend:{colors:{primary:'#D4AF37',secondary:'#8D6A9F'},borderRadius:{'none':'0px','sm':'4px',DEFAULT:'8px','md':'12px','lg':'16px','xl':'20px','2xl':'24px','3xl':'32px','full':'9999px','button':'8px'}}}}</script>
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
    background-image: url("https://readdy.ai/api/search-image?query=subtle%252520elegant%252520wedding%252520background%252520pattern%252520with%252520delicate%252520floral%252520motifs%252520in%252520very%252520light%252520beige%252520and%252520cream%252520colors%252520on%252520white%252520background%252520minimalist%252520design%252520high%252520end%252520luxury%252520feel%252520seamless%252520pattern&width=1920&height=1080&seq=wedding-bg-pattern&orientation=landscape");
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
    background-color: #D4AF37;
    border-radius: 50%;
}
.gallery-image {
    transition: all 0.3s ease;
}
.gallery-image:hover {
    transform: scale(1.03);
}
.custom-checkbox {
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
.custom-checkbox:checked {
    background-color: #D4AF37;
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
.category-title {
    color: #8D6A9F;
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
    background-color: #D4AF37;
}
input:checked + .slider:before {
    transform: translateX(1.75rem);
}
.date-cell {
    cursor: pointer;
    transition: all 0.2s ease;
}
.date-cell:hover:not(.unavailable) {
    background-color: #f9f3d9;
    transform: scale(1.05);
}
.date-cell.unavailable {
    background-color: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    text-decoration: line-through;
}
.date-cell.selected {
    background-color: #D4AF37;
    color: white;
}
.date-cell.peak-season {
    border: 2px solid #D4AF37;
}
.testimonial-card {
    transition: all 0.3s ease;
}
.testimonial-card:hover {
    transform: translateY(-5px);
}







</style>
</head>
@section('content')
<body>
    

<div class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="{{ route('packages') }}" class="flex items-center text-gray-700 hover:text-primary transition-colors">
                    <div class="w-8 h-8 flex items-center justify-center mr-2">
                        <i class="ri-arrow-left-line ri-lg"></i>
                    </div>
                    <span>Back to Packages</span>
                </a>
            </div>
            
            <div class="font-['Playfair_Display'] text-2xl font-bold text-gray-800">Basic Wedding Package</div>
            <div class="flex items-center">
                <a href="#booking" class="bg-primary hover:bg-amber-600 text-white py-2 px-4 !rounded-button font-medium transition-colors whitespace-nowrap">Book Now</a>
            </div>
        </div>
    </div>
</div>

<div class="hero-section relative h-[500px] bg-cover bg-center" style="background-image: url('https://readdy.ai/api/search-image?query=elegant%2520simple%2520wedding%2520setup%2520with%2520basic%2520decorations%252C%2520white%2520and%2520cream%2520color%2520scheme%252C%2520simple%2520floral%2520arrangements%252C%2520clean%2520and%2520minimalist%2520design%252C%2520natural%2520lighting%252C%2520professional%2520photography%2520with%2520soft%2520bokeh%2520effect%2520and%2520romantic%2520atmosphere&width=1920&height=800&seq=basic-wedding-hero&orientation=landscape')">
    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
    <div class="relative h-full flex items-center justify-start px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl ml-0 sm:ml-8 lg:ml-16">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4 font-['Playfair_Display']">Basic Wedding Package</h1>
            <p class="text-xl text-white/90 mb-6">An elegant and affordable option for your special day</p>
            <div class="bg-white/90 backdrop-blur-sm py-3 px-6 rounded-lg inline-block">
                <span class="text-3xl font-bold text-gray-900">Rs. 300,000</span>
                <span class="text-gray-600 ml-2">for up to 100 guests</span>
            </div>
        
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
                    <p class="text-gray-700 mb-8">Our Basic Wedding Package offers an elegant and affordable solution for couples seeking a beautiful celebration without compromising on quality. This package includes essential services and decorations to create a memorable wedding day experience for you and your guests.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="w-12 h-12 flex items-center justify-center mx-auto mb-3 text-primary">
                                <i class="ri-user-line ri-2x"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Guest Capacity</h3>
                            <p class="text-gray-600">Up to 100 guests</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="w-12 h-12 flex items-center justify-center mx-auto mb-3 text-primary">
                                <i class="ri-time-line ri-2x"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Duration</h3>
                            <p class="text-gray-600">6 hours of venue use</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <div class="w-12 h-12 flex items-center justify-center mx-auto mb-3 text-primary">
                                <i class="ri-user-add-line ri-2x"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Additional Guests</h3>
                            <p class="text-gray-600">Rs. 2,500 per person</p>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">What's Included</h3>
                    
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 flex items-center justify-center text-primary bg-amber-50 rounded-full mr-3">
                                <i class="ri-flower-line ri-lg"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800">Ceremonies & Decorations</h4>
                        </div>
                        <ul class="feature-list space-y-3 text-gray-700 ml-4">
                            <li>Traditional Poruwa ceremony setup with basic floral decorations</li>
                            <li>Oil lamp ceremony with standard decorations</li>
                            <li>Basic table decorations with white linens and simple centerpieces</li>
                            <li>Head table decoration with floral arrangements</li>
                            <li>Standard chair covers for all guest seating</li>
                            <li>Welcome board with couple's names</li>
                        </ul>
                    </div>
                    
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 flex items-center justify-center text-primary bg-amber-50 rounded-full mr-3">
                                <i class="ri-restaurant-line ri-lg"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800">Food & Beverages</h4>
                        </div>
                        <ul class="feature-list space-y-3 text-gray-700 ml-4">
                            <li>Standard wedding buffet with 10 items (3 starters, 4 main dishes, 3 desserts)</li>
                            <li>Selection of soft drinks (Coca-Cola, Sprite, Soda)</li>
                            <li>Welcome drinks for all guests upon arrival</li>
                            <li>Basic cake table setup</li>
                            <li>Service staff for food and beverage service</li>
                        </ul>
                    </div>
                    
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 flex items-center justify-center text-primary bg-amber-50 rounded-full mr-3">
                                <i class="ri-music-line ri-lg"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800">Entertainment</h4>
                        </div>
                        <ul class="feature-list space-y-3 text-gray-700 ml-4">
                            <li>DJ entertainment for 4 hours with standard sound system</li>
                            <li>Basic lighting setup for dance floor area</li>
                            <li>Microphone for speeches and announcements</li>
                        </ul>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-8 mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Gallery</h2>
                    <p class="text-gray-700 mb-8">View sample setups and decorations from our Basic Package weddings.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="gallery-image overflow-hidden rounded-lg h-48">
                            <img src="https://readdy.ai/api/search-image?query=elegant%2520poruwa%2520ceremony%2520setup%2520with%2520simple%2520floral%2520decorations%252C%2520white%2520and%2520cream%2520color%2520scheme%252C%2520traditional%2520Sri%2520Lankan%2520wedding%2520setup%252C%2520professional%2520photography%2520with%2520natural%2520lighting&width=400&height=300&seq=poruwa-setup&orientation=landscape" alt="Poruwa Ceremony Setup" class="w-full h-full object-cover">
                        </div>
                        <div class="gallery-image overflow-hidden rounded-lg h-48">
                            <img src="https://readdy.ai/api/search-image?query=wedding%2520head%2520table%2520with%2520simple%2520elegant%2520decorations%252C%2520white%2520floral%2520arrangements%252C%2520soft%2520lighting%252C%2520minimalist%2520design%252C%2520professional%2520photography&width=400&height=300&seq=head-table&orientation=landscape" alt="Head Table Decoration" class="w-full h-full object-cover">
                        </div>
                        <div class="gallery-image overflow-hidden rounded-lg h-48">
                            <img src="https://readdy.ai/api/search-image?query=traditional%2520oil%2520lamp%2520ceremony%2520setup%2520with%2520simple%2520decorations%252C%2520elegant%2520design%252C%2520white%2520and%2520gold%2520color%2520scheme%252C%2520professional%2520photography%2520with%2520soft%2520lighting&width=400&height=300&seq=oil-lamp&orientation=landscape" alt="Oil Lamp Ceremony" class="w-full h-full object-cover">
                        </div>
                        <div class="gallery-image overflow-hidden rounded-lg h-48">
                            <img src="https://readdy.ai/api/search-image?query=wedding%2520guest%2520tables%2520with%2520simple%2520centerpieces%252C%2520white%2520linens%252C%2520elegant%2520table%2520settings%252C%2520minimalist%2520design%252C%2520professional%2520photography%2520with%2520natural%2520lighting&width=400&height=300&seq=guest-tables&orientation=landscape" alt="Guest Table Decorations" class="w-full h-full object-cover">
                        </div>
                        <div class="gallery-image overflow-hidden rounded-lg h-48">
                            <img src="https://readdy.ai/api/search-image?query=wedding%2520buffet%2520setup%2520with%2520elegant%2520presentation%252C%2520variety%2520of%2520dishes%252C%2520professional%2520catering%2520display%252C%2520simple%2520decorations%252C%2520professional%2520photography&width=400&height=300&seq=buffet-setup&orientation=landscape" alt="Buffet Setup" class="w-full h-full object-cover">
                        </div>
                        <div class="gallery-image overflow-hidden rounded-lg h-48">
                            <img src="https://readdy.ai/api/search-image?query=wedding%2520DJ%2520setup%2520with%2520basic%2520lighting%252C%2520dance%2520floor%2520area%252C%2520simple%2520elegant%2520design%252C%2520professional%2520photography%2520with%2520atmospheric%2520lighting&width=400&height=300&seq=dj-setup&orientation=landscape" alt="DJ Setup" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-lg p-8 mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Testimonials</h2>
                    <p class="text-gray-700 mb-8">Read what couples who chose our Basic Package have to say about their experience.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="testimonial-card bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                    <img src="https://readdy.ai/api/search-image?query=professional%2520portrait%2520of%2520young%2520Asian%2520couple%252C%2520happy%2520smiling%252C%2520well-dressed%252C%2520natural%2520lighting%252C%2520neutral%2520background&width=100&height=100&seq=couple1&orientation=squarish" alt="Sarah & David" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Sarah & David Chen</h4>
                                    <p class="text-gray-500 text-sm">Wedding Date: March 12, 2025</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 mb-3">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                            </div>
                            <p class="text-gray-700">"We couldn't be happier with our decision to go with the Basic Package. Everything was beautifully arranged, and the staff was incredibly attentive. The decorations were simple yet elegant, exactly what we wanted. The food was delicious and our guests had a wonderful time!"</p>
                        </div>
                        
                        <div class="testimonial-card bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                    <img src="https://readdy.ai/api/search-image?query=professional%2520portrait%2520of%2520young%2520Indian%2520couple%252C%2520happy%2520smiling%252C%2520well-dressed%252C%2520natural%2520lighting%252C%2520neutral%2520background&width=100&height=100&seq=couple2&orientation=squarish" alt="Priya & Raj" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Priya & Raj Patel</h4>
                                    <p class="text-gray-500 text-sm">Wedding Date: January 8, 2025</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 mb-3">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-half-fill"></i>
                            </div>
                            <p class="text-gray-700">"The Basic Package was perfect for our intimate wedding. The Poruwa ceremony setup was beautiful, and the DJ kept everyone dancing all night. We appreciated how the team handled everything so professionally. Great value for the price!"</p>
                        </div>
                        
                        <div class="testimonial-card bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                    <img src="https://readdy.ai/api/search-image?query=professional%2520portrait%2520of%2520young%2520Caucasian%2520couple%252C%2520happy%2520smiling%252C%2520well-dressed%252C%2520natural%2520lighting%252C%2520neutral%2520background&width=100&height=100&seq=couple3&orientation=squarish" alt="Emma & Michael" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Emma & Michael Johnson</h4>
                                    <p class="text-gray-500 text-sm">Wedding Date: November 20, 2024</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 mb-3">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-line"></i>
                            </div>
                            <p class="text-gray-700">"We had a wonderful experience with the Basic Package. The decorations were lovely and the food was excellent. Our only suggestion would be to extend the DJ hours, as 4 hours went by so quickly! Otherwise, everything was perfect for our special day."</p>
                        </div>
                        
                        <div class="testimonial-card bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                    <img src="https://readdy.ai/api/search-image?query=professional%2520portrait%2520of%2520young%2520Sri%2520Lankan%2520couple%252C%2520happy%2520smiling%252C%2520well-dressed%252C%2520natural%2520lighting%252C%2520neutral%2520background&width=100&height=100&seq=couple4&orientation=squarish" alt="Amara & Nisal" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Amara & Nisal Perera</h4>
                                    <p class="text-gray-500 text-sm">Wedding Date: February 15, 2025</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 mb-3">
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                                <i class="ri-star-fill"></i>
                            </div>
                            <p class="text-gray-700">"The Basic Package exceeded our expectations! The team was so attentive to detail and made sure everything ran smoothly. The Poruwa ceremony was beautiful, and the head table decorations were stunning. We received so many compliments from our guests about the food and overall atmosphere."</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Package Summary</h3>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                            <span class="text-gray-700">Base Price:</span>
                            <span class="font-semibold">Rs. 300,000</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                            <span class="text-gray-700">Guest Capacity:</span>
                            <span class="font-semibold">100 guests</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                            <span class="text-gray-700">Additional Guests:</span>
                            <span class="font-semibold">Rs. 2,500/person</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                            <span class="text-gray-700">Duration:</span>
                            <span class="font-semibold">6 hours</span>
                        </div>
                       
                        <a href="{{ route('packages') }}" class="block w-full bg-white border border-gray-300 hover:border-gray-400 text-gray-700 text-center py-3 px-4 !rounded-button font-medium transition-colors whitespace-nowrap mt-3">
                            Compare All Packages
                        </a>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Availability Calendar</h3>
                        <p class="text-gray-600 text-sm mb-4">Check available dates for your wedding.</p>
                        
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <button id="prev-month" class="text-gray-600 hover:text-primary">
                                    <div class="w-8 h-8 flex items-center justify-center">
                                        <i class="ri-arrow-left-s-line ri-lg"></i>
                                    </div>
                                </button>
                                <h4 id="current-month" class="text-lg font-semibold text-gray-800">April 2025</h4>
                                <button id="next-month" class="text-gray-600 hover:text-primary">
                                    <div class="w-8 h-8 flex items-center justify-center">
                                        <i class="ri-arrow-right-s-line ri-lg"></i>
                                    </div>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-7 gap-1 text-center text-xs text-gray-500 mb-2">
                                <div>Sun</div>
                                <div>Mon</div>
                                <div>Tue</div>
                                <div>Wed</div>
                                <div>Thu</div>
                                <div>Fri</div>
                                <div>Sat</div>
                            </div>
                            
                            <div id="calendar-days" class="grid grid-cols-7 gap-1">
                                <!-- Calendar days will be inserted here by JavaScript -->
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-3 text-xs mt-4">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-white border border-gray-300 rounded-sm mr-1"></div>
                                <span>Available</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-200 rounded-sm mr-1"></div>
                                <span>Unavailable</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 border-2 border-primary bg-white rounded-sm mr-1"></div>
                                <span>Peak Season</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-primary rounded-sm mr-1"></div>
                                <span>Selected</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Need Help?</h3>
                        <p class="text-gray-600 mb-4">Have questions about this package? Contact our wedding specialists.</p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 flex items-center justify-center text-primary bg-amber-50 rounded-full mr-3">
                                    <i class="ri-phone-line ri-lg"></i>
                                </div>
                                <span class="text-gray-700">+94 77 123 4567</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 flex items-center justify-center text-primary bg-amber-50 rounded-full mr-3">
                                    <i class="ri-mail-line ri-lg"></i>
                                </div>
                                <span class="text-gray-700">weddings@venueexample.com</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 flex items-center justify-center text-primary bg-amber-50 rounded-full mr-3">
                                    <i class="ri-whatsapp-line ri-lg"></i>
                                </div>
                                <span class="text-gray-700">WhatsApp: +94 77 123 4567</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="customize" class="bg-white rounded-lg shadow-lg p-8 mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Customize Your Package</h2>
            <p class="text-gray-700 mb-8">Enhance your Basic Package with these premium add-ons to create your perfect wedding day.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Decoration Upgrades</h3>
                    <div class="space-y-4">
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Premium Floral Arrangements</span>
                                <p class="text-gray-600 text-sm ml-2">Upgrade to premium flowers and more elaborate arrangements</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 35,000</span>
                            </div>
                        </label>
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Luxury Chair Covers & Sashes</span>
                                <p class="text-gray-600 text-sm ml-2">Premium fabric chair covers with decorative sashes</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 15,000</span>
                            </div>
                        </label>
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Enhanced Poruwa Decoration</span>
                                <p class="text-gray-600 text-sm ml-2">Upgrade to a more elaborate Poruwa setup</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 25,000</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Entertainment Add-ons</h3>
                    <div class="space-y-4">
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Extended DJ Hours</span>
                                <p class="text-gray-600 text-sm ml-2">Additional 2 hours of DJ entertainment</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 15,000</span>
                            </div>
                        </label>
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Photography Package</span>
                                <p class="text-gray-600 text-sm ml-2">Professional photographer for 6 hours</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 30,000</span>
                            </div>
                        </label>
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Traditional Dancers</span>
                                <p class="text-gray-600 text-sm ml-2">Kandyan dancers for welcome ceremony</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 20,000</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Food & Beverage Upgrades</h3>
                    <div class="space-y-4">
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Premium Dessert Station</span>
                                <p class="text-gray-600 text-sm ml-2">Additional dessert varieties and chocolate fountain</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 20,000</span>
                            </div>
                        </label>
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Welcome Cocktails</span>
                                <p class="text-gray-600 text-sm ml-2">Non-alcoholic signature cocktails for guests</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 15,000</span>
                            </div>
                        </label>
                        <label class="flex items-start">
                            <input type="checkbox" class="custom-checkbox mt-1">
                            <div>
                                <span class="ml-2 font-medium text-gray-800">Upgraded Buffet Menu</span>
                                <p class="text-gray-600 text-sm ml-2">Additional premium dishes and menu options</p>
                                <span class="text-primary font-medium ml-2">+ Rs. 25,000</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 p-6 bg-gray-50 rounded-lg">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">Estimated Total</h3>
                        <p class="text-gray-600">Base package + selected add-ons</p>
                    </div>
                    <div class="text-right mt-4 md:mt-0">
                        <div class="text-3xl font-bold text-gray-900" id="total-price">Rs. 300,000</div>
                        <p class="text-gray-600">For up to 100 guests</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="booking" class="bg-white rounded-lg shadow-lg p-8 mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Book Your Wedding</h2>
            <p class="text-gray-700 mb-8">Fill out the form below to request your preferred date and we'll get back to you within 24 hours.</p>
            
            <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Your Information</h3>
                </div>
                
                <div>
                    <label for="first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                    <input type="text" id="first-name" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label for="last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                    <input type="text" id="last-name" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" id="phone" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div class="md:col-span-2 pt-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Wedding Details</h3>
                </div>
                
                <div>
                    <label for="wedding-date" class="block text-sm font-medium text-gray-700 mb-1">Preferred Wedding Date</label>
                    <input type="date" id="wedding-date" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label for="alternative-date" class="block text-sm font-medium text-gray-700 mb-1">Alternative Date (Optional)</label>
                    <input type="date" id="alternative-date" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label for="guest-count" class="block text-sm font-medium text-gray-700 mb-1">Estimated Guest Count</label>
                    <input type="number" id="guest-count" min="1" max="200" value="100" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Interested in Add-ons?</label>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="addons" value="yes" class="w-4 h-4 text-primary">
                            <span class="ml-2">Yes</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="addons" value="no" class="w-4 h-4 text-primary">
                            <span class="ml-2">No</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="addons" value="maybe" checked class="w-4 h-4 text-primary">
                            <span class="ml-2">Not sure yet</span>
                        </label>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <label for="special-requests" class="block text-sm font-medium text-gray-700 mb-1">Special Requests or Questions</label>
                    <textarea id="special-requests" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary"></textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="inline-flex items-start">
                        <input type="checkbox" class="custom-checkbox mt-1">
                        <span class="ml-2 text-gray-700">I agree to receive communications about my wedding booking and related services.</span>
                    </label>
                </div>
                
                <div class="md:col-span-2 text-center">
                    <button type="submit" class="bg-primary hover:bg-amber-600 text-white py-3 px-8 !rounded-button font-medium transition-colors whitespace-nowrap shadow-md">Submit Booking Request</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calendar functionality
    const calendarDays = document.getElementById('calendar-days');
    const currentMonthElement = document.getElementById('current-month');
    const prevMonthButton = document.getElementById('prev-month');
    const nextMonthButton = document.getElementById('next-month');
    
    let currentDate = new Date(2025, 3, 21); // April 21, 2025 (0-indexed month)
    let selectedDate = null;
    
    // Randomly generate unavailable dates
    const unavailableDates = [];
    for (let i = 0; i < 10; i++) {
        const randomDay = Math.floor(Math.random() * 30) + 1;
        unavailableDates.push(new Date(2025, 3, randomDay));
    }
    
    // Define peak season dates (e.g., weekends and holidays)
    const peakSeasonDates = [];
    for (let i = 0; i < 30; i++) {
        const date = new Date(2025, 3, i + 1);
        if (date.getDay() === 0 || date.getDay() === 6) { // Weekends
            peakSeasonDates.push(date);
        }
    }
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Update month display
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        currentMonthElement.textContent = `${monthNames[month]} ${year}`;
        
        // Clear previous calendar
        calendarDays.innerHTML = '';
        
        // Get first day of month and total days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Add empty cells for days before the first day of month
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.classList.add('h-8', 'w-8');
            calendarDays.appendChild(emptyCell);
        }
        
        // Add cells for each day of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateCell = document.createElement('div');
            dateCell.classList.add('date-cell', 'h-8', 'w-8', 'flex', 'items-center', 'justify-center', 'text-sm', 'rounded-full', 'transition-all');
            dateCell.textContent = day;
            
            // Check if date is unavailable
            const isUnavailable = unavailableDates.some(unavailableDate => 
                unavailableDate.getDate() === day && 
                unavailableDate.getMonth() === month && 
                unavailableDate.getFullYear() === year
            );
            
            // Check if date is in peak season
            const isPeakSeason = peakSeasonDates.some(peakDate => 
                peakDate.getDate() === day && 
                peakDate.getMonth() === month && 
                peakDate.getFullYear() === year
            );
            
            // Check if date is selected
            const isSelected = selectedDate && 
                selectedDate.getDate() === day && 
                selectedDate.getMonth() === month && 
                selectedDate.getFullYear() === year;
            
            if (isUnavailable) {
                dateCell.classList.add('unavailable');
            } else if (isSelected) {
                dateCell.classList.add('selected');
            } else if (isPeakSeason) {
                dateCell.classList.add('peak-season');
            } else {
                dateCell.classList.add('bg-white', 'hover:bg-gray-100');
            }
            
            // Add click event for available dates
            if (!isUnavailable) {
                dateCell.addEventListener('click', function() {
                    // Remove selected class from previously selected date
                    const previouslySelected = document.querySelector('.date-cell.selected');
                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected');
                        if (previouslySelected.classList.contains('peak-season')) {
                            previouslySelected.classList.add('peak-season');
                        } else {
                            previouslySelected.classList.add('bg-white', 'hover:bg-gray-100');
                        }
                    }
                    
                    // Add selected class to clicked date
                    dateCell.classList.remove('bg-white', 'hover:bg-gray-100', 'peak-season');
                    dateCell.classList.add('selected');
                    
                    // Update selected date
                    selectedDate = new Date(year, month, day);
                    
                    // Update wedding date input
                    const weddingDateInput = document.getElementById('wedding-date');
                    if (weddingDateInput) {
                        const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        weddingDateInput.value = formattedDate;
                    }
                });
            }
            
            calendarDays.appendChild(dateCell);
        }
    }
    
    // Initialize calendar
    renderCalendar();
    
    // Add event listeners for month navigation
    prevMonthButton.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    nextMonthButton.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    // Customization add-ons price calculation
    const checkboxes = document.querySelectorAll('.custom-checkbox');
    const totalPriceElement = document.getElementById('total-price');
    let basePrice = 300000;
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            calculateTotal();
            
            // Add animation for checkbox change
            if (this.checked) {
                this.parentElement.classList.add('text-primary');
            } else {
                this.parentElement.classList.remove('text-primary');
            }
        });
    });
    
    function calculateTotal() {
        let total = basePrice;
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const priceText = checkbox.nextElementSibling.querySelector('.text-primary').textContent;
                const price = parseInt(priceText.replace(/[^0-9]/g, ''));
                total += price;
            }
        });
        
        totalPriceElement.textContent = `Rs. ${total.toLocaleString()}`;
    }
    
    // Guest count input
    const guestCountInput = document.getElementById('guest-count');
    if (guestCountInput) {
        guestCountInput.addEventListener('input', function() {
            const guestCount = parseInt(this.value);
            if (guestCount > 100) {
                const additionalGuests = guestCount - 100;
                const additionalCost = additionalGuests * 2500;
                basePrice = 300000 + additionalCost;
            } else {
                basePrice = 300000;
            }
            calculateTotal();
        });
    }
    
    // Form submission
    const bookingForm = document.querySelector('form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your booking request! Our team will contact you within 24 hours to confirm your reservation.');
        });
    }
});
</script>




</body>
</html>
@endsection