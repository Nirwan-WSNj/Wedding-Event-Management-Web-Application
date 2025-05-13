<footer class="bg-secondary bg-opacity-95 text-white">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between space-y-8 md:space-y-0 md:space-x-8 mb-12">
            <!-- Resort Info -->
            <div class="flex-1">
                <a href="{{ route('home') }}" class="text-3xl font-pacifico text-white mb-6 inline-block">Wet Water Resort</a>
                <p class="text-gray-200 mb-6">Creating unforgettable waterfront wedding experiences at Sri Lanka's premier resort destination.</p>
                <div class="flex space-x-4 social-icons">
                    <a href="https://web.facebook.com/wwrgampaha/?_rdc=1&_rdr#" class="hover:bg-primary transition-colors">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="https://www.instagram.com/wet_water_resort/" class="hover:bg-primary transition-colors">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="#" class="hover:bg-primary transition-colors">
                        <i class="ri-pinterest-line"></i>
                    </a>
                    <a href="#" class="hover:bg-primary transition-colors">
                        <i class="ri-youtube-line"></i>
                    </a>
                    
                <a href="https://lk.linkedin.com/in/jayantha-cooray-wet-water-resort-gampaha-147b8472" class="hover:bg-primary transition-colors">
                    <i class="ri-linkedin-fill"></i>
                </a>

                </div>
                
            </div>
            <!-- Quick Links -->
            <div class="flex-1">
                <h3 class="text-lg font-playfair font-medium mb-6">Quick Links</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-gray-200 hover:text-primary transition-colors">Home</a></li>
                    <li><a href="{{ route('halls') }}" class="text-gray-200 hover:text-primary transition-colors">Wedding Halls</a></li>
                    <li><a href="{{ route('packages') }}" class="text-gray-200 hover:text-primary transition-colors">Wedding Packages</a></li>
                    <li><a href="{{ route('gallery') }}" class="text-gray-200 hover:text-primary transition-colors">Gallery</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-200 hover:text-primary transition-colors">About</a></li>
                    <li><a href="{{ route('contactUs') }}" class="text-gray-200 hover:text-primary transition-colors">Contact Us</a></li>
                </ul>
            </div>
            <!-- Contact Information -->
            <div class="flex-1">
                <h3 class="text-lg font-playfair font-medium mb-6">Contact Information</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <span class="w-5 h-5 mt-0.5 flex-shrink-0 flex items-center justify-center">
                            <i class="ri-map-pin-line"></i>
                        </span>
                        <span class="ml-3 text-gray-200">No- 136/D, "Lumbini Uyana", Ja Ela-Ekala-Gampaha-Yakkala Hwy, Gampaha, Sri Lanka</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-5 h-5 mt-0.5 flex-shrink-0 flex items-center justify-center">
                            <i class="ri-phone-line"></i>
                        </span>
                        <span class="ml-3 text-gray-200">0332 226 886</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-5 h-5 mt-0.5 flex-shrink-0 flex items-center justify-center">
                            <i class="ri-mail-line"></i>
                        </span>
                        <span class="ml-3 text-gray-200">weddings@wetwaterresort.com</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-5 h-5 mt-0.5 flex-shrink-0 flex items-center justify-center">
                            <i class="ri-time-line"></i>
                        </span>
                        <span class="ml-3 text-gray-200">Mon-Fri: 9am-6pm, Sat: 10am-4pm</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left pt-6 border-t border-gray-700">
            <p class="text-gray-300 text-sm mb-4 md:mb-0">© {{ date('Y') }} Wet Water Resort Wedding Management. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-300 hover:text-primary text-sm">Privacy Policy</a>
                <a href="#" class="text-gray-300 hover:text-primary text-sm">Terms of Service</a>
                <a href="#" class="text-gray-300 hover:text-primary text-sm">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>