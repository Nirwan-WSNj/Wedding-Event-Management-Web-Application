<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 bg-black/5 backdrop-blur-[2px]" id="navbar">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between py-4">
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                <img id="mainLogo" class="h-16 w-auto rounded-none object-contain p-0 ml-2 transition-all duration-300 backdrop-filter backdrop-blur-sm hover:backdrop-blur-md animate-float animate-water-ripple animate-gold-glow group-hover:scale-105 transform-gpu gold-water-effect" src="{{ asset('images/logo45-removebg-preview.png') }}" alt="Wet Water Resort">
            </a>
        </div>
        <div class="hidden sm:flex sm:items-center space-x-6">
            <a href="{{ route('home') }}" class="relative px-3 py-2 text-sm font-semibold uppercase tracking-wider transition-all duration-300 text-white before:absolute before:bottom-0 before:left-0 before:h-0.5 before:w-full before:origin-left before:scale-x-0 before:bg-white before:transition-transform hover:before:scale-x-100 group overflow-hidden [.scrolled_&]:text-[#94644c] [.scrolled_&]:before:bg-[#94644c] hover:text-white [.scrolled_&]:hover:text-[#94644c]">Home</a>
            <a href="{{ route('halls') }}" class="relative px-3 py-2 text-sm font-semibold uppercase tracking-wider transition-all duration-300 text-white before:absolute before:bottom-0 before:left-0 before:h-0.5 before:w-full before:origin-left before:scale-x-0 before:bg-white before:transition-transform hover:before:scale-x-100 group overflow-hidden [.scrolled_&]:text-[#94644c] [.scrolled_&]:before:bg-[#94644c] hover:text-white [.scrolled_&]:hover:text-[#94644c]">Wedding Halls</a>
            <a href="{{ route('packages') }}" class="relative px-3 py-2 text-sm font-semibold uppercase tracking-wider transition-all duration-300 text-white before:absolute before:bottom-0 before:left-0 before:h-0.5 before:w-full before:origin-left before:scale-x-0 before:bg-white before:transition-transform hover:before:scale-x-100 group overflow-hidden [.scrolled_&]:text-[#94644c] [.scrolled_&]:before:bg-[#94644c] hover:text-white [.scrolled_&]:hover:text-[#94644c]">Wedding Packages</a>
            <a href="{{ route('gallery') }}" class="relative px-3 py-2 text-sm font-semibold uppercase tracking-wider transition-all duration-300 text-white before:absolute before:bottom-0 before:left-0 before:h-0.5 before:w-full before:origin-left before:scale-x-0 before:bg-white before:transition-transform hover:before:scale-x-100 group overflow-hidden [.scrolled_&]:text-[#94644c] [.scrolled_&]:before:bg-[#94644c] hover:text-white [.scrolled_&]:hover:text-[#94644c]">Gallery</a>
            <a href="{{ route('about') }}" class="relative px-3 py-2 text-sm font-semibold uppercase tracking-wider transition-all duration-300 text-white before:absolute before:bottom-0 before:left-0 before:h-0.5 before:w-full before:origin-left before:scale-x-0 before:bg-white before:transition-transform hover:before:scale-x-100 group overflow-hidden [.scrolled_&]:text-[#94644c] [.scrolled_&]:before:bg-[#94644c] hover:text-white [.scrolled_&]:hover:text-[#94644c]">About</a>
            <a href="{{ route('contactUs') }}" class="relative px-3 py-2 text-sm font-semibold uppercase tracking-wider transition-all duration-300 text-white before:absolute before:bottom-0 before:left-0 before:h-0.5 before:w-full before:origin-left before:scale-x-0 before:bg-white before:transition-transform hover:before:scale-x-100 group overflow-hidden [.scrolled_&]:text-[#94644c] [.scrolled_&]:before:bg-[#94644c] hover:text-white [.scrolled_&]:hover:text-[#94644c]">Contact</a>
        </div>
        <div class="hidden sm:flex sm:items-center space-x-4">
            @auth
                <div class="flex items-center space-x-4">
                    <a href="{{ route('booking') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg font-semibold text-sm uppercase tracking-wider hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg transform hover:scale-105 duration-200">
                        Book Now
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button id="userMenuButton" @click="open = !open" class="flex items-center focus:outline-none" aria-label="User menu">
                            <span class="bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold uppercase shadow-md overflow-hidden">
                                <img src="{{ auth()->user()->profile_photo_url }}?{{ time() }}" alt="Profile" class="w-10 h-10 rounded-full object-cover" onerror="this.onerror=null;this.src='{{ asset('images/logo45.png') }}';">
                            </span>
                        </button>
                        <div id="userMenu"
                             x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="open = false"
                             class="absolute right-0 top-12 w-56 bg-white/95 backdrop-blur-md border border-gray-200 rounded-xl shadow-2xl py-2 z-50 origin-top-right">
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-5 py-3 text-sm font-medium text-gray-800 hover:bg-primary hover:text-white transition rounded-md">
                                    <i class="fas fa-user w-4"></i> Profile
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 py-3 text-sm font-medium text-gray-800 hover:bg-primary hover:text-white transition rounded-md">
                                    <i class="fas fa-tachometer-alt w-4"></i> Dashboard
                                </a>
                            @elseif (auth()->user()->isManager())
                                <a href="{{ route('manager.profile') }}" class="flex items-center gap-3 px-5 py-3 text-sm font-medium text-gray-800 hover:bg-primary hover:text-white transition rounded-md">
                                    <i class="fas fa-user w-4"></i> Profile
                                </a>
                                <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-3 px-5 py-3 text-sm font-medium text-gray-800 hover:bg-primary hover:text-white transition rounded-md">
                                    <i class="fas fa-tachometer-alt w-4"></i> Dashboard
                                </a>
                            @else
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-5 py-3 text-sm font-medium text-gray-800 hover:bg-primary hover:text-white transition rounded-md">
                                    <i class="fas fa-user w-4"></i> Profile
                                </a>
                                <a href="{{ route('bookings.my') }}" class="flex items-center gap-3 px-5 py-3 text-sm font-medium text-gray-800 hover:bg-primary hover:text-white transition rounded-md">
                                    <i class="fas fa-calendar-check w-4"></i> My Bookings
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full text-left px-5 py-3 text-sm font-medium text-red-600 hover:bg-red-600 hover:text-white transition rounded-md">
                                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center space-x-4">
                    <a href="#" onclick="startBooking()" class="bg-primary text-white px-6 py-2.5 rounded-lg font-semibold text-sm uppercase tracking-wider hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg transform hover:scale-105 duration-200">
                        Book Now
                    </a>
                    <a href="{{ route('login') }}" id="signInBtn" class="border border-gray-300 px-6 py-2.5 rounded-lg font-semibold text-sm uppercase text-gray-700 tracking-wider transition hover:bg-gray-100">
                        Sign In
                    </a>
                </div>
            @endauth
        </div>
        <div class="sm:hidden">
            <button type="button" class="nav-link text-dark-color hover:text-primary" id="mobileMenuButton" aria-label="Toggle mobile menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </nav>
    <!-- Mobile Menu -->
    <div class="sm:hidden bg-white shadow-lg hidden" id="mobileMenu">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Home</a>
            <a href="{{ route('halls') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Wedding Halls</a>
            <a href="{{ route('packages') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Wedding Packages</a>
            <a href="{{ route('gallery') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Gallery</a>
            <a href="{{ route('about') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">About</a>
            <a href="{{ route('contactUs') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Contact</a>
            @auth
                <a href="{{ route('booking') }}" class="block bg-primary text-white px-4 py-2 rounded-lg font-semibold uppercase tracking-wider text-sm">Book Now</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.profile') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Profile</a>
                    <a href="{{ route('admin.dashboard') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Dashboard</a>
                @elseif (auth()->user()->isManager())
                    <a href="{{ route('manager.profile') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Profile</a>
                    <a href="{{ route('manager.dashboard') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Dashboard</a>
                @else
                    <a href="{{ route('profile.edit') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Profile</a>
                    <a href="{{ route('bookings.my') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">My Bookings</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left text-red-600 hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Logout</button>
                </form>
            @else
                <a href="#" onclick="startBooking()" class="block bg-primary text-white px-4 py-2 rounded-lg font-semibold uppercase tracking-wider text-sm">Book Now</a>
                <a href="{{ route('login') }}" class="block text-dark-color hover:text-primary font-semibold uppercase tracking-wider py-2 text-sm">Sign In</a>
            @endauth
        </div>
    </div>
</header>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.animate-float {
    animation: float 3s ease-in-out infinite;
}
@keyframes water-ripple {
    0% {
        filter: blur(0px) drop-shadow(0 0 0px #d4af37);
        box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.15);
    }
    50% {
        filter: blur(1.5px) drop-shadow(0 0 16px #d4af37);
        box-shadow: 0 0 40px 20px rgba(212, 175, 55, 0.25);
    }
    100% {
        filter: blur(0px) drop-shadow(0 0 0px #d4af37);
        box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.15);
    }
}
.animate-water-ripple {
    animation: water-ripple 4s ease-in-out infinite;
}
@keyframes gold-glow {
    0%, 100% { filter: brightness(100%) drop-shadow(0 0 0px #d4af37); }
    50% { filter: brightness(120%) drop-shadow(0 0 16px #d4af37); }
}
.animate-gold-glow {
    animation: gold-glow 3.5s ease-in-out infinite;
}
.gold-water-effect {
    position: relative;
    z-index: 1;
}
.gold-water-effect::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 0.5rem;
    pointer-events: none;
    background: radial-gradient(circle at 60% 40%, rgba(212,175,55,0.18) 0%, rgba(212,175,55,0.08) 60%, transparent 100%);
    z-index: 2;
    mix-blend-mode: lighten;
    filter: blur(6px);
    opacity: 0.7;
    transition: opacity 0.4s;
}
.gold-water-effect:hover::after {
    opacity: 1;
}

/* High DPI optimizations */
@media (min-resolution: 192dpi) {
    .transform-gpu {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const logo = document.getElementById('mainLogo');
    const navbar = document.getElementById('navbar');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Logo swap and scroll behavior
    function handleScroll() {
        if (window.scrollY > 0) {
            navbar.classList.add('bg-white', 'shadow-md', 'scrolled');
            navbar.classList.remove('backdrop-blur-sm', 'bg-black/5');
            logo.src = "{{ asset('images/logo45-removebg-black.png') }}";
        } else {
            navbar.classList.remove('bg-white', 'shadow-md', 'scrolled');
            navbar.classList.add('backdrop-blur-[2px]', 'bg-black/5');
            logo.src = "{{ asset('images/logo45-removebg-preview.png') }}";
        }
    }
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check
});
</script>