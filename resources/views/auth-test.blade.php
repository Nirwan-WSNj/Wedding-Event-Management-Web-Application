<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Authentication State Test</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Authentication Status -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4 text-blue-800">Authentication Status</h2>
                
                @auth
                    <div class="text-green-600 font-semibold mb-2">✅ User is AUTHENTICATED</div>
                    <div class="space-y-2 text-sm">
                        <div><strong>User ID:</strong> {{ auth()->user()->id }}</div>
                        <div><strong>Name:</strong> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
                        <div><strong>Email:</strong> {{ auth()->user()->email }}</div>
                        <div><strong>Role:</strong> {{ auth()->user()->role }}</div>
                        <div><strong>Phone:</strong> {{ auth()->user()->phone ?? 'N/A' }}</div>
                    </div>
                @else
                    <div class="text-red-600 font-semibold">❌ User is NOT authenticated</div>
                @endauth
            </div>

            <!-- Header Logic Test -->
            <div class="bg-green-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Header Logic Test</h2>
                
                @auth
                    <div class="space-y-2 text-sm">
                        <div class="text-green-600">✅ Should show "Book Now" button</div>
                        <div class="text-green-600">✅ Should show user profile menu</div>
                        <div class="text-green-600">✅ Should show logout option</div>
                        <div class="text-red-600">❌ Should NOT show "Sign In" button</div>
                        
                        @if(auth()->user()->isAdmin())
                            <div class="text-blue-600">👑 Admin dashboard link should be visible</div>
                        @elseif(auth()->user()->isManager())
                            <div class="text-purple-600">🏢 Manager dashboard link should be visible</div>
                        @else
                            <div class="text-orange-600">👤 Customer profile/bookings links should be visible</div>
                        @endif
                    </div>
                @else
                    <div class="space-y-2 text-sm">
                        <div class="text-red-600">❌ Should show "Sign In" button</div>
                        <div class="text-red-600">❌ Should show "Book Now" (but prompt for login)</div>
                        <div class="text-green-600">✅ Should NOT show user profile menu</div>
                        <div class="text-green-600">✅ Should NOT show user-specific links</div>
                    </div>
                @endauth
            </div>

            <!-- Session Information -->
            <div class="bg-yellow-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4 text-yellow-800">Session Information</h2>
                <div class="space-y-2 text-sm">
                    <div><strong>Session ID:</strong> {{ session()->getId() }}</div>
                    <div><strong>CSRF Token:</strong> {{ csrf_token() }}</div>
                    <div><strong>Session Driver:</strong> {{ config('session.driver') }}</div>
                    <div><strong>Session Lifetime:</strong> {{ config('session.lifetime') }} minutes</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-purple-50 p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4 text-purple-800">Quick Actions</h2>
                <div class="space-y-3">
                    @auth
                        <a href="{{ route('home') }}" class="block bg-blue-500 text-white px-4 py-2 rounded text-center hover:bg-blue-600">Go to Home</a>
                        <a href="{{ route('booking') }}" class="block bg-green-500 text-white px-4 py-2 rounded text-center hover:bg-green-600">Go to Booking</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline-block w-full">
                            @csrf
                            <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block bg-blue-500 text-white px-4 py-2 rounded text-center hover:bg-blue-600">Go to Login</a>
                        <a href="{{ route('register') }}" class="block bg-green-500 text-white px-4 py-2 rounded text-center hover:bg-green-600">Go to Register</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Test Login Form (if not authenticated) -->
        @guest
        <div class="mt-8 bg-gray-50 p-6 rounded-lg">
            <h2 class="text-xl font-semibold mb-4">Quick Login Test</h2>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="thanukadil789@gmail.com" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" value="Password123!" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Login
                </button>
            </form>
        </div>
        @endguest

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-red-800 font-semibold mb-2">Errors:</h3>
                <ul class="list-disc list-inside text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Messages -->
        @if (session('success'))
            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="text-green-800 font-semibold">{{ session('success') }}</div>
            </div>
        @endif
    </div>

    <script>
        // Auto-refresh every 5 seconds to see real-time auth state
        setTimeout(() => {
            window.location.reload();
        }, 10000);
    </script>
</body>
</html>