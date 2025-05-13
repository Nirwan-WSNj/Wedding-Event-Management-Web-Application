@extends('layouts.app')

@section('title', 'Wet Water Resort - Luxury Wedding Venue')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-screen overflow-hidden pt-20">
        <div class="hero-slideshow absolute inset-0">
            <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: url('https://static.readdy.ai/image/1b97f4e5b196ed86fdcfc88d43e6e863/e4befd73cdc33d8d4a54286869abb8f9.png')"></div>
            <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" style="background-image: url('https://readdy.ai/api/search-image?query=luxury%20european%20wedding%20ceremony%20in%20grand%20ballroom%2C%20crystal%20chandeliers%2C%20white%20and%20gold%20decor%2C%20elegant%20floral%20arrangements%20with%20roses%20and%20orchids%2C%20dramatic%20lighting%2C%20romantic%20atmosphere%2C%20professional%20photography%20style&width=1920&height=1080&seq=13&orientation=landscape')"></div>
            <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" style="background-image: url('https://readdy.ai/api/search-image?query=romantic%20evening%20wedding%20reception%20in%20luxury%20hotel%20garden%2C%20fairy%20lights%20canopy%2C%20white%20roses%20and%20hydrangeas%2C%20elegant%20table%20settings%20with%20gold%20details%2C%20soft%20ambient%20lighting%2C%20dreamy%20atmosphere%2C%20professional%20photography%20style&width=1920&height=1080&seq=14&orientation=landscape')"></div>
            <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" style="background-image: url('https://readdy.ai/api/search-image?query=dreamy%20romantic%20wedding%20venue%20at%20sunset%2C%20soft%20pink%20and%20white%20roses%20everywhere%2C%20fairy%20lights%2C%20flowing%20silk%20drapes%2C%20cherry%20blossoms%2C%20ethereal%20atmosphere%2C%20magical%20garden%20setting%2C%20romantic%20lighting%2C%20professional%20photography%20style&width=1920&height=1080&seq=1&orientation=landscape')"></div>
            <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0" style="background-image: url('https://readdy.ai/api/search-image?query=luxury%20asian%20hotel%20grand%20ballroom%20wedding%20setup%2C%20crystal%20chandeliers%2C%20elegant%20table%20settings%2C%20marble%20floors%2C%20golden%20accents%2C%20warm%20lighting%2C%20opulent%20oriental%20decor%20elements%2C%20professional%20photography%20style&width=1920&height=1080&seq=10&orientation=landscape')"></div>
            <div class="slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: url('https://onefabday.com/wp-content/uploads/2023/12/peach-fuzz-wedding-reception.jpg?resize=1536,2048')"></div>
        
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

    <!-- Wedding Venues Section -->
    <section id="venues" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-primary font-mrs-saint-delafield text-3xl mb-2 block">Discover</span>
                <h2 class="text-4xl font-playfair font-bold text-dark-color">Our Wedding Venues</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4 mb-8"></div>
                <p class="text-lg text-dark-color max-w-2xl mx-auto">Choose from our collection of waterfront venues, offering stunning backdrops for your special day.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Grand Ballroom -->
                <div class="venue-card rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://readdy.ai/api/search-image?query=elegant%20ballroom%20wedding%20venue%20with%20crystal%20chandeliers%2C%20high%20ceilings%2C%20marble%20floors%2C%20luxurious%20decor%2C%20soft%20lighting%2C%20round%20tables%20with%20white%20linens%20and%20centerpieces%2C%20professional%20photography&width=600&height=400&seq=2&orientation=landscape" onerror="this.src='https://via.placeholder.com/600x400?text=Venue+Image'" class="w-full h-full object-cover" alt="Grand Ballroom">
                        <div class="absolute top-4 right-4 bg-primary text-white text-sm px-3 py-1 rounded-full">
                            From ₹1,599/hr
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h3 class="text-2xl font-bold mb-2 font-cormorant">Grand Ballroom</h3>
                        <p class="text-dark-color mb-4">An opulent indoor venue with crystal chandeliers and marble floors, perfect for grand celebrations of up to 300 guests.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Up to 300 guests</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Ocean View</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Dance Floor</span>
                        </div>
                        <a href="{{ route('halls') }}" class="inline-block text-primary font-medium hover:text-secondary transition-colors">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Beachfront Pavilion -->
                <div class="venue-card rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://readdy.ai/api/search-image?query=beachfront%20wedding%20venue%20at%20sunset%2C%20white%20canopy%2C%20flower%20petals%20on%20sand%2C%20ocean%20view%2C%20tropical%20setting%2C%20wooden%20chairs%20with%20white%20fabric%2C%20palm%20trees%2C%20professional%20photography&width=600&height=400&seq=4&orientation=landscape" onerror="this.src='https://via.placeholder.com/600x400?text=Venue+Image'" class="w-full h-full object-cover" alt="Beachfront Pavilion">
                        <div class="absolute top-4 right-4 bg-primary text-white text-sm px-3 py-1 rounded-full">
                            From ₹1,299/hr
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h3 class="text-2xl font-bold mb-2 font-cormorant">Beachfront Pavilion</h3>
                        <p class="text-dark-color mb-4">An enchanting seaside venue with panoramic ocean views, perfect for sunset ceremonies and receptions.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Up to 200 guests</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Sunset Views</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Private Area</span>
                        </div>
                        <a href="{{ route('halls') }}" class="inline-block text-primary font-medium hover:text-secondary transition-colors">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Garden Terrace -->
                <div class="venue-card rounded-xl overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://readdy.ai/api/search-image?query=outdoor%20garden%20wedding%20venue%20with%20lush%20greenery%2C%20floral%20archway%2C%20white%20chairs%20arranged%20in%20rows%2C%20stone%20pathway%2C%20fountain%2C%20string%20lights%20overhead%2C%20sunset%20lighting%2C%20professional%20photography&width=600&height=400&seq=3&orientation=landscape" onerror="this.src='https://via.placeholder.com/600x400?text=Venue+Image'" class="w-full h-full object-cover" alt="Garden Terrace">
                        <div class="absolute top-4 right-4 bg-primary text-white text-sm px-3 py-1 rounded-full">
                            From ₹999/hr
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h3 class="text-2xl font-bold mb-2 font-cormorant">Garden Terrace</h3>
                        <p class="text-dark-color mb-4">A picturesque outdoor setting surrounded by lush gardens and water features, ideal for romantic ceremonies.</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Up to 150 guests</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Outdoor Setting</span>
                            <span class="bg-gray-100 text-dark-color text-xs px-2 py-1 rounded">Garden Views</span>
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
                        <h3 class="text-xl font-bold mb-2 font-cormorant">Golden Package </h3>
                        <div class="text-3xl font-bold mb-1">Rs. 450,000  </div>
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
                <img src="https://source.unsplash.com/800x600/?wedding,bride" alt="Wedding 1" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
                <img src="https://source.unsplash.com/800x600/?wedding,reception" alt="Wedding 2" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
                <img src="https://source.unsplash.com/800x600/?wedding,ceremony" alt="Wedding 3" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
                <img src="https://source.unsplash.com/800x600/?wedding,dance" alt="Wedding 4" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
                <img src="https://source.unsplash.com/800x600/?wedding,couple" alt="Wedding 5" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
            </div>
            <div class="overflow-hidden rounded-xl shadow hover:shadow-lg transition duration-300">
                <img src="https://source.unsplash.com/800x600/?wedding,decor" alt="Wedding 6" class="w-full h-64 object-cover hover:scale-105 transition duration-300">
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
                            <p class="text-sm text-gray-500">Married on June 15, 2024</p>
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
                            <p class="text-sm text-gray-500">Married on March 22, 2025</p>
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

        function showSlide(index) {
            slides.forEach(slide => slide.style.opacity = '0');
            dots.forEach(dot => dot.classList.remove('active'));
            slides[index].style.opacity = '1';
            dots[index].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function startSlideshow() {
            if (!slideInterval) {
                slideInterval = setInterval(nextSlide, 5000);
            }
        }

        function pauseSlideshow() {
            clearInterval(slideInterval);
            slideInterval = null;
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                pauseSlideshow();
                currentSlide = index;
                showSlide(currentSlide);
                setTimeout(startSlideshow, 5000);
            });
        });

        startSlideshow();
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