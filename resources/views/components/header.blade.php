<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between py-4">
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                <img class="h-14 w-auto rounded-none" src="{{ asset('images/logo44.png') }}" alt="Wet Water Resort"> </a>
            
        </div>
        <div class="hidden sm:flex sm:items-center space-x-6">
            <a href="{{ route('home') }}" class="nav-link text-dark-color hover:text-primary font-semibold uppercase tracking-wider transition-colors">Home</a>
            <a href="{{ route('halls') }}" class="nav-link text-dark-color hover:text-primary font-semibold uppercase tracking-wider transition-colors">Wedding Halls</a>
            <a href="{{ route('packages') }}" class="nav-link text-dark-color hover:text-primary font-semibold uppercase tracking-wider transition-colors">Wedding Packages</a>
            <a href="{{ route('gallery') }}" class="nav-link text-dark-color hover:text-primary font-semibold uppercase tracking-wider transition-colors">Gallery</a>
            <a href="{{ route('about') }}" class="nav-link text-dark-color hover:text-primary font-semibold uppercase tracking-wider transition-colors">About</a>
            <a href="{{ route('contactUs') }}" class="nav-link text-dark-color hover:text-primary font-semibold uppercase tracking-wider transition-colors">Contact</a>
        </div>
        <div class="hidden sm:flex sm:items-center space-x-6">
            @auth
    <div class="relative">
        <button id="userMenuButton" class="flex items-center focus:outline-none">
            <span class="bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold uppercase shadow-md">
                {{ strtoupper(auth()->user()->first_name[0] ?? auth()->user()->name[0]) }}
            </span>
        </button>
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-dark-color hover:bg-primary hover:text-white">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-dark-color hover:bg-primary hover:text-white">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" id="signInBtn" class="border px-6 py-2 !rounded-button font-semibold uppercase tracking-wider transition-all">Sign In</a>
                <a href="#" class="bg-primary text-white px-6 py-3 rounded-button font-medium hover:bg-opacity-90 transition-all shadow-lg transform hover:scale-105" onclick="startBooking()">Book Now</a>
            @endauth
        </div>
        <div class="sm:hidden">
            <button type="button" class="nav-link text-dark-color hover:text-primary" id="mobileMenuButton">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </nav>
    <!-- Mobile Menu -->
    <div class="sm:hidden hidden bg-white shadow-lg" id="mobileMenu">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">Home</a>
            <a href="{{ route('halls') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">Wedding Halls</a>
            <a href="{{ route('packages') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">Wedding Packages</a>
            <a href="{{ route('gallery') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">Gallery</a>
            <a href="{{ route('about') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">About</a>
            <a href="{{ route('contactUs') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">Contact</a>
            @auth
                <a href="{{ route('profile.edit') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2">Sign In</a>
                <a href="#" class="block bg-primary text-white px-4 py-2 rounded-button font-medium hover:bg-opacity-90 transition-all" onclick="startBooking()">Book Now</a>
            @endauth
        </div>
    </div>
</header>