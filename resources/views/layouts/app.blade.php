<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wet Water Resort')</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/lio90.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/lio90.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/lio90.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Mrs+Saint+Delafield&family=Cinzel:wght@400;700&family=Lora:wght@400;500&family=Playfair+Display:wght@400;500;600;700&family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Animate.css and AOS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8B4513',
                        secondary: '#2c1810'
                    },
                    borderRadius: {
                        'button': '8px'
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        /* Your existing styles remain unchanged */
        :root {
            --primary-color: #d8b4a0;
            --secondary-color: #4a6670;
            --accent-color: #d0a98f;
            --dark-color: #2d3748;
            --light-color: #f8f5f2;
            --brown-color: #4a2c1a;
            --gold-color: #d4af37;
        }
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-cormorant { font-family: 'Cormorant Garamond', serif; }
        .font-mrs-saint-delafield { font-family: 'Mrs Saint Delafield', cursive; }
        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-lora { font-family: 'Lora', serif; }
        .font-pacifico { font-family: 'Pacifico', cursive; }
        header {
            background: transparent;
            transition: all 0.3s ease;
        }
        header.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        header nav a {
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: color 0.3s ease;
        }
        header.scrolled .nav-link { color: #1f2937; }
        header.scrolled .nav-link:hover { color: var(--tw-color-primary); }
        header:not(.scrolled) .nav-link { color: white; }
        header:not(.scrolled) .nav-link:hover { color: var(--tw-color-primary); }
        header.scrolled #signInBtn { color: #1f2937; border-color: rgba(31, 41, 55, 0.3); }
        header.scrolled #signInBtn:hover { background-color: #1f2937; color: white; }
        header:not(.scrolled) #signInBtn { color: white; border-color: rgba(255, 255, 255, 0.3); }
        header:not(.scrolled) #signInBtn:hover { background-color: rgba(255, 255, 255, 0.9); color: #2c1810; }
        .hero-slideshow .slide {
            transition: opacity 1s ease;
        }
        .slide-dot.active {
            background-color: white;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .animate-fade-in-delay-2 { animation: fadeIn 0.8s ease-out 0.2s forwards; }
        .animate-fade-in-delay-4 { animation: fadeIn 0.8s ease-out 0.4s forwards; }
        .animate-float { animation: float 2s ease-in-out infinite; }
        .explore-button {
            position: relative;
            background-color: var(--brown-color);
            color: white;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .explore-button:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            border: 1px solid var(--gold-color);
        }
        .explore-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.5), transparent);
            transition: left 0.5s ease;
        }
        .explore-button:hover::before {
            left: 100%;
        }
        .explore-button .gold-sparkle {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.3) 0%, transparent 70%);
            opacity: 0;
            pointer-events: none;
        }
        .explore-button:hover .gold-sparkle {
            opacity: 1;
            animation: sparkle 1.5s infinite;
        }
        @keyframes sparkle {
            0% { transform: scale(1); opacity: 0.4; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 0.4; }
        }
        .package-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .package-card:hover, .package-card.touched {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }
        .package-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(74, 44, 26, 0.3));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }
        .package-card:hover::before, .package-card.touched::before {
            opacity: 1;
        }
        .package-card .gold-glow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.4) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
        }
        .package-card:hover .gold-glow, .package-card.touched .gold-glow {
            opacity: 1;
            animation: sparkle 1.5s infinite;
        }
        .select-button {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .select-button:hover {
            transform: scale(1.05);
            background-color: rgba(139, 69, 19, 0.9);
        }
        .venue-card img {
            transition: transform 0.5s ease;
        }
        .venue-card:hover img, .venue-card.touched img {
            transform: scale(1.1);
        }
        .venue-card {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .venue-card.hidden {
            opacity: 0;
            transform: scale(0.95);
            display: none;
        }
        footer {
            background-color: var(--dark-color);
            color: var(--light-color);
            padding: 4rem 1rem 2rem;
            background-image: linear-gradient(rgba(44, 24, 16, 0.85), rgba(44, 24, 16, 0.85)), url('https://readdy.ai/api/search-image?query=delicate%20rose%20petals%20pattern%2C%20soft%20focus%2C%20romantic%20texture%2C%20gentle%20pink%20and%20white%20tones%2C%20ethereal%20and%20dreamy%2C%20professional%20photography&width=400&height=400&seq=9&orientation=squarish');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
        }
        footer .social-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.15);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        footer .social-icons a:hover {
            background-color: var(--primary-color);
            transform: scale(1.1);
        }
        @media (max-width: 768px) {
            header nav a {
                font-size: 0.875rem;
            }
        }
        @if (Route::currentRouteName() === 'contactUs')
            header {
                background-color: rgba(31, 31, 31, 0.7) !important;
                color: white !important;
            }
            header a {
                color: white !important;
                text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
            }
            header.scrolled {
                background-color: white !important;
                color: black !important;
            }
            header.scrolled a {
                color: black !important;
                text-shadow: none !important;
            }
        @endif
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('components.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('components.footer')

    <!-- AOS Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 1000 });
    </script>
</body>
</html>