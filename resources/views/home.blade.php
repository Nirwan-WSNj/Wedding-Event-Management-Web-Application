@extends('layouts.app')

@section('title', 'Wet Water Resort - Luxury Wedding Venue')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-screen overflow-hidden pt-20" aria-label="Wedding Hero Section">
        <div class="hero-slideshow absolute inset-0" role="region" aria-label="Wedding Slideshow">
            <div class="image-loader">
                <div class="spinner w-10 h-10 border-4 border-white/30 rounded-full border-t-white animate-spin"></div>
                <p class="text-white mt-2">Loading image...</p>
            </div>
            <div class="slideshow-controls">
                <button class="prev-slide bg-white/20 backdrop-blur-sm border-none rounded-full w-10 h-10 text-white cursor-pointer transition-all duration-300" aria-label="Previous slide">&#10094;</button>
                <button class="next-slide bg-white/20 backdrop-blur-sm border-none rounded-full w-10 h-10 text-white cursor-pointer transition-all duration-300" aria-label="Next slide">&#10095;</button>
            </div>
            <div class="slide lazy-image absolute inset-0 bg-cover bg-center will-change-transform opacity-0 transform translate-x-full" style="background-image: url('{{ asset('storage/halls/weddinghero1.jpg') }}')" preload></div>
            <div class="slide lazy-image absolute inset-0 bg-cover bg-center will-change-transform opacity-0 transform translate-x-full" style="background-image: url('{{ asset('storage/halls/weddinghero2.jpg') }}')" preload></div>
            <div class="slide lazy-image absolute inset-0 bg-cover bg-center will-change-transform opacity-0 transform translate-x-full" style="background-image: url('{{ asset('storage/halls/weddinghero3.jpg') }}')" preload></div>
            <div class="slide lazy-image absolute inset-0 bg-cover bg-center will-change-transform opacity-0 transform translate-x-full" style="background-image: url('{{ asset('storage/halls/weddinghero4.jpg') }}')" preload></div>
            <div class="slide lazy-image absolute inset-0 bg-cover bg-center will-change-transform opacity-0 transform translate-x-full" style="background-image: url('{{ asset('storage/halls/weddinghero5.jpg') }}')" preload></div>
            <div class="slide lazy-image absolute inset-0 bg-cover bg-center will-change-transform opacity-0 transform translate-x-full" style="background-image: url('{{ asset('storage/halls/weddinghero6.jpg') }}')" preload></div>
        </div>
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative w-full h-full flex items-center">
            <div class="container mx-auto px-6">
                <div class="max-w-3xl text-white space-y-8">
                    <h1 class="font-mrs-saint-delafield text-7xl leading-tight animate-fade-in" style="letter-spacing: 0.06em;">
                        Where Tradition Meets Luxury
                    </h1>
                    <h2 class="font-cinzel text-5xl font-bold mb-6 animate-fade-in-delay-2" style="letter-spacing: 0.04em;">
                        at Wet Water Resort
                    </h2>
                    <p class="text-xl font-lora leading-relaxed animate-fade-in-delay-4" style="letter-spacing: 0.02em;">
                        Experience the perfect fusion of traditional Sri Lankan elegance and modern luxury. From authentic Kandyan ceremonies to grand European celebrations, create your dream wedding in our stunning venues.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 animate-fade-in-delay-4">
                        <a href="{{ route('packages') }}" class="explore-button text-white px-8 py-3 rounded-button font-medium inline-flex items-center">
                            <span>Explore Wedding Packages</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                            <div class="gold-sparkle"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-float">
            <a href="{{ route('halls') }}" class="w-12 h-12 flex items-center justify-center text-white">
                <i class="ri-arrow-down-line ri-xl"></i>
            </a>
        </div>
        <div class="absolute bottom-10 right-10 flex gap-2">
            <button class="w-3 h-3 rounded-full bg-white/50 slide-dot active"></button>
            <button class="w-3 h-3 rounded-full bg-white/50 slide-dot"></button>
            <button class="w-3 h-3 rounded-full bg-white/50 slide-dot"></button>
            <button class="w-3 h-3 rounded-full bg-white/50 slide-dot"></button>
            <button class="w-3 h-3 rounded-full bg-white/50 slide-dot"></button>
        </div>
    </section>

   <!-- Modern Premium Venue Section Styles -->
    <style>
    #venues {
        background: linear-gradient(135deg, #f8f5f2 0%, #f3e9df 100%);
        position: relative;
    }
    .luxury-venue-card {
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px rgba(148,100,76,0.10), 0 1.5px 8px #e5c9a4;
        background: #f7f3ef;
        border: 1.5px solid #e5c9a4;
        overflow: hidden;
        transition: transform 0.35s cubic-bezier(.4,2,.6,1), box-shadow 0.35s, border-color 0.3s;
        opacity: 0;
        transform: translateY(40px) scale(0.98);
    }
    .luxury-venue-card.visible {
        opacity: 1;
        transform: translateY(0) scale(1);
        transition: opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1);
    }
    .luxury-venue-card:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 32px 80px rgba(148,100,76,0.16), 0 8px 25px rgba(148,100,76,0.10);
        border-color: #bfa16b;
        background: #f3e9df;
    }
    .luxury-venue-card img {
        filter: brightness(0.98) contrast(1.08) saturate(1.08);
        transition: transform 0.7s cubic-bezier(.4,0,.2,1), filter 0.4s;
        border-bottom: 2px solid #e5c9a4;
    }
    .luxury-venue-card:hover img {
        transform: scale(1.04) rotate(-0.5deg);
        filter: brightness(1.01) contrast(1.10) saturate(1.10);
    }
    .luxury-venue-card .p-4 h3 {
        color: #94644c;
        font-weight: 700;
        letter-spacing: 0.01em;
        margin-bottom: 0.5rem;
    }
    .luxury-venue-card .p-4 p {
        color: #5a3d2b;
        font-weight: 400;
        margin-bottom: 1.1rem;
        letter-spacing: 0.01em;
    }
    .luxury-venue-card .flex.flex-wrap.gap-2.mb-4 span {
        border-bottom: 2px solid #e5c9a4;
        background: #f3e9df;
        color: #94644c;
        font-weight: 600;
        margin-bottom: 2px;
        padding: 0.25rem 0.9rem;
        border-radius: 1rem;
    }
    .luxury-venue-card .absolute.top-4.right-4 {
        background: #f3e9df;
        color: #94644c;
        font-weight: 700;
        box-shadow: 0 2px 8px #e5c9a4;
        border: 1px solid #e5c9a4;
        letter-spacing: 0.01em;
    }
    .luxury-venue-card a {
        background: linear-gradient(90deg,#bfa16b 0%,#94644c 100%);
        color: #fff;
        font-weight: 600;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px #e5c9a4;
        padding: 10px 28px;
        margin-top: 1rem;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        border: none;
        letter-spacing: 0.01em;
        text-shadow: none;
    }
    .luxury-venue-card a:hover {
        background: linear-gradient(90deg,#94644c 0%,#bfa16b 100%);
        color: #fff8f3;
        box-shadow: 0 4px 16px #e5c9a4;
    }
    .luxury-venue-card .p-4 {
        background: #f7f3ef;
    }
    @media (max-width: 1024px) {
        .luxury-venue-card .p-4 h3 { font-size: 1.3rem; }
    }
    </style>
    <section id="venues" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-primary font-mrs-saint-delafield text-3xl mb-2 block">Discover</span>
                <h2 class="text-4xl font-playfair font-bold text-dark-color">Our Wedding Venues</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4 mb-8"></div>
                <p class="text-lg text-dark-color max-w-2xl mx-auto">Choose from our collection of waterfront venues, offering stunning backdrops for your special day.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Jubilee Ballroom -->
                <div class="luxury-venue-card rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative overflow-hidden h-64">
                        <img src="{{ asset('storage/halls/jublieeballroom.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Jubilee Ballroom">
                        <div class="absolute top-4 right-4 bg-primary text-white text-sm px-3 py-1 rounded-full">
                            From Rs.4,200
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h3 class="text-2xl font-bold mb-2 font-cormorant">Jubilee Ballroom</h3>
                        <p class="text-dark-color mb-4">Transform your day into a fairytale with this octagonal, pillarless ballroom, adorned with Victorian skylights and colonial charm.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Indoor</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">7,956 sq ft</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Up to 200 Guests</span>
                        </div>
                        <a href="{{ route('halls') }}" class="inline-block text-primary font-medium hover:text-secondary transition-colors">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Grand Ballroom -->
                <div class="luxury-venue-card rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative overflow-hidden h-64">
                        <img src="{{ asset('storage/halls/GrandBallroom.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Grand Ballroom">
                        <div class="absolute top-4 right-4 bg-primary text-white text-sm px-3 py-1 rounded-full">
                            From Rs.5,500
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h3 class="text-2xl font-bold mb-2 font-cormorant">Grand Ballroom</h3>
                        <p class="text-dark-color mb-4">Celebrate in unparalleled luxury with crystal chandeliers, a grand stage, and cutting-edge acoustics for a majestic wedding.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Indoor</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">10,000 sq ft</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Up to 500 Guests</span>
                        </div>
                        <a href="{{ route('halls') }}" class="inline-block text-primary font-medium hover:text-secondary transition-colors">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Garden Pavilion -->
                <div class="luxury-venue-card rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative overflow-hidden h-64">
                        <img src="{{ asset('storage/halls/GardenPavilion.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Garden Pavilion">
                        <div class="absolute top-4 right-4 bg-primary text-white text-sm px-3 py-1 rounded-full">
                            From Rs.3,500
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h3 class="text-2xl font-bold mb-2 font-cormorant">Garden Pavilion</h3>
                        <p class="text-dark-color mb-4">Embrace nature’s embrace in this romantic outdoor pavilion, surrounded by lush gardens and twinkling string lights.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Outdoor</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">7,500 sq ft</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Up to 300 Guests</span>
                        </div>
                        <a href="{{ route('halls') }}" class="inline-block text-primary font-medium hover:text-secondary transition-colors">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="{{ route('halls') }}" class="inline-flex items-center text-primary hover:text-secondary font-medium transition-colors border-b border-primary hover:border-secondary pb-1">
                    View All Wedding Venues <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Wedding Packages Section -->
    <section id="packages" class="py-24 bg-pink-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-primary font-mrs-saint-delafield text-3xl mb-2 block">Choose</span>
                <h2 class="text-4xl font-playfair font-bold text-dark-color">Our Wedding Packages</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4 mb-8"></div>
                <p class="text-lg text-dark-color max-w-2xl mx-auto">Customizable packages designed to suit your vision, style, and budget.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Basic Package -->
                <div class="package-card bg-white rounded-xl shadow-lg overflow-hidden" data-aos="zoom-in" data-aos-delay="100">
                    <div class="p-4 sm:p-6 bg-primary bg-opacity-10 relative overflow-hidden">
                        <div class="bg-primary text-white text-xs px-3 py-1 rounded-full absolute top-4 right-4">Most Affordable</div>
                        <h3 class="text-xl font-bold mb-2 font-cormorant">Basic Package</h3>
                        <div class="text-3xl font-bold mb-1">Rs.300,000</div>
                        <p class="text-sm text-dark-color">An ideal choice for a beautiful yet budget-conscious celebration</p>
                        <div class="gold-glow"></div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <ul class="space-y-4 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Venue rental for 6 hours</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Basic floral arrangements</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>3-course plated dinner</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Wedding coordinator</span>
                            </li>
                        </ul>
                        <a href="{{ route('packages') }}" class="select-button block text-center bg-primary text-white px-6 py-3 rounded-button font-medium transition-all shadow-md">
                            Get Details
                        </a>
                    </div>
                </div>
                <!-- Infinity Package-->
                <div class="package-card bg-white rounded-xl shadow-lg overflow-hidden" data-aos="zoom-in" data-aos-delay="200">
                    <div class="p-4 sm:p-6 bg-primary bg-opacity-10 relative overflow-hidden">
                        <div class="bg-primary text-white text-xs px-3 py-1 rounded-full absolute top-4 right-4">Most Popular</div>
                        <h3 class="text-xl font-bold mb-2 font-cormorant">Infinity Package</h3>
                        <div class="text-3xl font-bold mb-1">Rs. 450,000</div>
                        <p class="text-sm text-dark-color">Luxury wedding package with premium décor, gourmet dining, and live entertainment</p>
                        <div class="gold-glow"></div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <ul class="space-y-4 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Full-day event coverage with premium decorations</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Premium floral arrangements</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>5-course gourmet dinner</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Professional photography</span>
                            </li>
                        </ul>
                        <a href="{{ route('packages') }}" class="select-button block text-center bg-primary text-white px-6 py-3 rounded-button font-medium transition-all shadow-md">
                            Get Details
                        </a>
                    </div>
                </div>
                <!-- Royal Grandeur -->
                <div class="package-card bg-white rounded-xl shadow-lg overflow-hidden" data-aos="zoom-in" data-aos-delay="300">
                    <div class="p-4 sm:p-6 bg-primary bg-opacity-10 relative overflow-hidden">
                        <div class="bg-primary text-white text-xs px-3 py-1 rounded-full absolute top-4 right-4">Exclusive</div>
                        <h3 class="text-xl font-bold mb-2 font-cormorant">Golden Package</h3>
                        <div class="text-3xl font-bold mb-1">Rs. 600,000</div>
                        <p class="text-sm text-dark-color">The ultimate luxury wedding experience</p>
                        <div class="gold-glow"></div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <ul class="space-y-4 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>6-hour celebration with premium DJ music</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Poruwa & Ashtaka ceremonies with floral decor</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Unlimited bites, beverages & premium buffet</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Milk or champagne fountain & traditional dances</span>
                            </li>
                        </ul>
                        <a href="{{ route('packages') }}" class="select-button block text-center bg-primary text-white px-6 py-3 rounded-button font-medium transition-all shadow-md">
                            Get Details
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-16 bg-white rounded-xl shadow-lg overflow-hidden" data-aos="fade-up">
                <div class="p-8 md:p-12 flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-6 md:mb-0 md:mr-8">
                        <h3 class="text-2xl md:text-3xl font-playfair font-medium text-dark-color mb-3">Custom Wedding Package</h3>
                        <p class="text-dark-color">Looking for something unique? Let us create a bespoke wedding package tailored to your specific vision and requirements.</p>
                    </div>
                    <a href="{{ route('contactUs') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-medium text-white bg-primary hover:bg-opacity-90 rounded-button transition-all">
                        Request Custom Quote
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-primary font-mrs-saint-delafield text-3xl mb-2 block">Our Offerings</span>
                <h2 class="text-4xl font-playfair font-bold text-dark-color">Wedding Services</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4 mb-8"></div>
                <p class="text-lg text-dark-color max-w-2xl mx-auto">Comprehensive services to ensure every aspect of your special day is perfect.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-4 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-calendar-check-line ri-xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-playfair font-medium text-dark-color mb-3">Wedding Planning</h3>
                    <p class="text-dark-color">Our expert planners guide you through every step, from venue selection to day-of coordination.</p>
                </div>
                <div class="p-4 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-restaurant-line ri-xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-playfair font-medium text-dark-color mb-3">Catering & Cuisine</h3>
                    <p class="text-dark-color">Exquisite culinary experiences by award-winning chefs, with customizable menus.</p>
                </div>
                <div class="p-4 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-flower-line ri-xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-playfair font-medium text-dark-color mb-3">Floral Design</h3>
                    <p class="text-dark-color">Stunning floral arrangements and decorations that bring your vision to life.</p>
                </div>
                <div class="p-4 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-camera-line ri-xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-playfair font-medium text-dark-color mb-3">Photography & Video</h3>
                    <p class="text-dark-color">Capture every moment with professional photography and videography services.</p>
                </div>
                <div class="p-4 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-music-line ri-xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-playfair font-medium text-dark-color mb-3">Entertainment</h3>
                    <p class="text-dark-color">Live bands, DJs, and more to keep your guests dancing all night long.</p>
                </div>
                <div class="p-4 sm:p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="600">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mb-6">
                        <i class="ri-hotel-line ri-xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-playfair font-medium text-dark-color mb-3">Accommodation</h3>
                    <p class="text-dark-color">Luxury accommodations for the couple and special rates for guests.</p>
                </div>
            </div>
        </div>
    </section>
<!-- Gallery Section -->
<section id="gallery" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-primary font-mrs-saint-delafield text-3xl mb-2 block">Captured Moments</span>
            <h2 class="text-4xl font-playfair font-bold text-dark-color">Wedding Gallery</h2>
            <div class="w-20 h-1 bg-primary mx-auto mt-4 mb-8"></div>
            <p class="text-lg text-dark-color max-w-2xl mx-auto">A glimpse into the magical moments we've helped create.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/couple1.jpg') }}" alt="Wedding couple" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/couple3.jpg') }}" alt="Wedding reception" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/couple7.jfif') }}" alt="Wedding Dance" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/cermony1.jpg') }}" alt="Wedding Ceromony" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/couple2.jpg') }}" alt="Wedding Dance" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/dance1.jpg') }}" alt="Wedding Dance" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/decoration2.jpg') }}" alt="Wedding Dance" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
             <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/decoration1.jpg') }}" alt="Wedding Dance" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
             <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
            <img src="{{ asset('storage/halls/decoration3.jpeg') }}" alt="Wedding Dance" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
        </div>
    </div>
</section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-pink-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-primary font-mrs-saint-delafield text-3xl mb-2 block">What Our Clients Say</span>
                <h2 class="text-4xl font-playfair font-bold text-dark-color">Testimonials</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4 mb-8"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center text-primary mb-4">
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                    </div>
                    <p class="text-dark-color mb-4">"Our wedding day was absolutely perfect. The team went above and beyond to make our vision come to life. Every detail was handled with care and professionalism."</p>
                    <div class="flex items-center">
                        <img src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20a%20smiling%20bride%20with%20elegant%20wedding%20makeup%20and%20hairstyle%2C%20natural%20lighting%2C%20neutral%20background%2C%20professional%20photography&width=80&height=80&seq=5&orientation=squarish" alt="Emily & James" class="w-12 h-12 rounded-full object-cover">
                        <div class="ml-4">
                            <h4 class="font-medium text-dark-color">Emily & James</h4>
                            <p class="text-sm text-gray-500">Married on June 15, 2023</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center text-primary mb-4">
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                    </div>
                    <p class="text-dark-color mb-4">"The resort's ambiance and personalized services made our wedding unforgettable. A dream come true!"</p>
                    <div class="flex items-center">
                        <img src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20a%20happy%20couple%2C%20mixed%20ethnicity%2C%20formal%20attire%2C%20natural%20lighting%2C%20neutral%20background%2C%20professional%20photography&width=80&height=80&seq=6&orientation=squarish" alt="Sophia & Michael" class="w-12 h-12 rounded-full object-cover">
                        <div class="ml-4">
                            <h4 class="font-medium text-dark-color">Sophia & Michael</h4>
                            <p class="text-sm text-gray-500">Married on March 22, 2023</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center text-primary mb-4">
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                        <i class="ri-star-fill"></i>
                    </div>
                    <p class="text-dark-color mb-4">"We had a destination wedding and were worried about planning from afar, but the team made everything so easy. Our beachfront ceremony was magical."</p>
                    <div class="flex items-center">
                        <img src="https://readdy.ai/api/search-image?query=professional%20headshot%20of%20a%20stylish%20couple%20in%20wedding%20attire%2C%20smiling%20at%20camera%2C%20natural%20lighting%2C%20neutral%20background%2C%20professional%20photography&width=80&height=80&seq=7&orientation=squarish" alt="Olivia & Daniel" class="w-12 h-12 rounded-full object-cover">
                        <div class="ml-4">
                            <h4 class="font-medium text-dark-color">Olivia & Daniel</h4>
                            <p class="text-sm text-gray-500">Married on November 5, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="#" class="inline-flex items-center text-primary hover:text-secondary font-medium transition-colors border-b border-primary hover:border-secondary pb-1">
                    Read More Reviews <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slide-dot');
        let slideInterval = null;
        let touchStartX = 0;
        let touchEndX = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.style.transition = 'opacity 1.2s cubic-bezier(0.4,0,0.2,1), transform 1.2s cubic-bezier(0.4,0,0.2,1)';
                    slide.style.transform = 'translateX(0)';
                    slide.style.opacity = '1';
                    slide.classList.add('loaded');
                } else {
                    slide.style.transition = 'opacity 1.2s cubic-bezier(0.4,0,0.2,1), transform 1.2s cubic-bezier(0.4,0,0.2,1)';
                    slide.style.transform = 'translateX(30px)';
                    slide.style.opacity = '0';
                    slide.classList.remove('loaded');
                }
            });
            dots.forEach(dot => dot.classList.remove('active'));
            dots[index].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function previousSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        function startSlideshow() {
            if (!slideInterval) {
                slideInterval = setInterval(nextSlide, 6000);
            }
        }

        function pauseSlideshow() {
            clearInterval(slideInterval);
            slideInterval = null;
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                previousSlide();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
            }
        });

        // Touch events
        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeLength = touchEndX - touchStartX;
            if (Math.abs(swipeLength) > swipeThreshold) {
                if (swipeLength > 0) {
                    previousSlide();
                } else {
                    nextSlide();
                }
            }
        }

        // Image loading
        slides.forEach(slide => {
            const img = new Image();
            img.src = slide.style.backgroundImage.replace(/url\(['"](.+)['"]\)/, '$1');
            img.onload = () => {
                slide.classList.add('loaded');
            };
            img.onerror = () => {
                // Optionally handle error
            };
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                pauseSlideshow();
                currentSlide = index;
                showSlide(currentSlide);
                setTimeout(startSlideshow, 6000);
            });
        });

        // Smooth fade-in on load
        document.addEventListener('DOMContentLoaded', () => {
            showSlide(currentSlide);
            setTimeout(startSlideshow, 1000);
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


       

@endsection