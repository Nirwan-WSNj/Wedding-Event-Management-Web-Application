<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin & Manager Login Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full">
        <h1 class="text-3xl font-bold mb-6 text-center">Admin & Manager Login Test</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Admin Login -->
            <div class="border border-blue-200 rounded-lg p-6 bg-blue-50">
                <h2 class="text-xl font-semibold mb-4 text-blue-800">Admin Login</h2>
                
                <form method="POST" action="{{ route('login') }}" autocomplete="off">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input 
                            type="email" 
                            id="admin_email" 
                            name="email" 
                            value="admin@weddingmanagement.com"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            autocomplete="off"
                            readonly
                            onfocus="this.removeAttribute('readonly');"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input 
                            type="password" 
                            id="admin_password" 
                            name="password" 
                            placeholder="Enter: admin123"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            autocomplete="new-password"
                            readonly
                            onfocus="this.removeAttribute('readonly'); this.value='';"
                        >
                        <div class="text-xs text-gray-600 mt-1">
                            Type exactly: <code class="bg-gray-100 px-1 rounded">admin123</code>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Login as Admin
                    </button>
                </form>

                <div class="mt-4 text-sm text-blue-700">
                    <strong>Expected after login:</strong>
                    <ul class="list-disc list-inside mt-1">
                        <li>Redirect to Admin Dashboard</li>
                        <li>Profile menu shows "Dashboard" link</li>
                        <li>Profile menu shows "Profile" link</li>
                    </ul>
                </div>
            </div>

            <!-- Manager Login -->
            <div class="border border-green-200 rounded-lg p-6 bg-green-50">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Manager Login</h2>
                
                <form method="POST" action="{{ route('login') }}" autocomplete="off">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="manager_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input 
                            type="email" 
                            id="manager_email" 
                            name="email" 
                            value="manager@weddingmanagement.com"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            autocomplete="off"
                            readonly
                            onfocus="this.removeAttribute('readonly');"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="manager_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input 
                            type="password" 
                            id="manager_password" 
                            name="password" 
                            placeholder="Enter: manager123"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            autocomplete="new-password"
                            readonly
                            onfocus="this.removeAttribute('readonly'); this.value='';"
                        >
                        <div class="text-xs text-gray-600 mt-1">
                            Type exactly: <code class="bg-gray-100 px-1 rounded">manager123</code>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        Login as Manager
                    </button>
                </form>

                <div class="mt-4 text-sm text-green-700">
                    <strong>Expected after login:</strong>
                    <ul class="list-disc list-inside mt-1">
                        <li>Redirect to Manager Dashboard</li>
                        <li>Profile menu shows "Dashboard" link</li>
                        <li>Profile menu shows "Profile" link</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Customer Login for comparison -->
        <div class="mt-6 border border-purple-200 rounded-lg p-6 bg-purple-50">
            <h2 class="text-xl font-semibold mb-4 text-purple-800">Customer Login (for comparison)</h2>
            
            <form method="POST" action="{{ route('login') }}" autocomplete="off" class="flex gap-4">
                @csrf
                
                <div class="flex-1">
                    <input 
                        type="email" 
                        name="email" 
                        value="thanukadil789@gmail.com"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        autocomplete="off"
                        readonly
                        onfocus="this.removeAttribute('readonly');"
                    >
                </div>

                <div class="flex-1">
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Enter: password123"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        autocomplete="new-password"
                        readonly
                        onfocus="this.removeAttribute('readonly'); this.value='';"
                    >
                </div>

                <button 
                    type="submit" 
                    class="bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
                    Login as Customer
                </button>
            </form>

            <div class="mt-2 text-sm text-purple-700">
                <strong>Expected:</strong> Redirect to Home, Profile menu shows "Profile" and "My Bookings"
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">← Back to Home</a>
            <span class="mx-2">|</span>
            <a href="{{ route('auth.test') }}" class="text-green-600 hover:underline">Check Auth Status</a>
        </div>

        <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
            <h3 class="font-semibold text-yellow-800 mb-2">Testing Instructions:</h3>
            <ol class="text-sm text-yellow-700 space-y-1">
                <li>1. Choose a role to test (Admin, Manager, or Customer)</li>
                <li>2. Click on the password field and clear any autofilled text</li>
                <li>3. Type the exact password shown</li>
                <li>4. Click the login button</li>
                <li>5. Check if you're redirected to the correct dashboard</li>
                <li>6. Check if the header shows the correct profile menu options</li>
                <li>7. Click on profile picture to see dropdown menu</li>
            </ol>
        </div>
    </div>

    <script>
        // Prevent autofill and add validation
        document.addEventListener('DOMContentLoaded', function() {
            // Clear password fields on page load
            document.querySelectorAll('input[type="password"]').forEach(field => {
                field.value = '';
            });
            
            // Add validation for each form
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const passwordField = this.querySelector('input[type="password"]');
                    const expectedPasswords = ['admin123', 'manager123', 'password123'];
                    
                    if (!expectedPasswords.includes(passwordField.value)) {
                        e.preventDefault();
                        alert('Please enter the correct password as shown in the hint.');
                        passwordField.focus();
                    }
                });
            });
            
            // Handle password field focus
            document.querySelectorAll('input[type="password"]').forEach(field => {
                field.addEventListener('focus', function() {
                    this.value = '';
                    this.style.borderColor = '#f59e0b';
                });
                
                field.addEventListener('input', function() {
                    const expectedPasswords = ['admin123', 'manager123', 'password123'];
                    if (expectedPasswords.includes(this.value)) {
                        this.style.borderColor = '#10b981';
                    } else if (this.value.length > 0) {
                        this.style.borderColor = '#f59e0b';
                    }
                });
            });
        });
    </script>
</body>
</html>