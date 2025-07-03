<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Login Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-2xl font-bold mb-6 text-center">Simple Login Test</h1>
        
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

        <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="thanukadil789@gmail.com"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    autocomplete="off"
                    readonly
                    onfocus="this.removeAttribute('readonly');"
                >
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter: password123"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    autocomplete="new-password"
                    readonly
                    onfocus="this.removeAttribute('readonly'); this.value='';"
                >
                <div class="text-sm text-gray-600 mt-1">
                    Type exactly: <code class="bg-gray-100 px-1 rounded">password123</code>
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                Login
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">← Back to Home</a>
        </div>

        <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
            <h3 class="font-semibold text-yellow-800 mb-2">Instructions:</h3>
            <ol class="text-sm text-yellow-700 space-y-1">
                <li>1. Click on the password field</li>
                <li>2. Clear any autofilled text</li>
                <li>3. Type exactly: <strong>password123</strong></li>
                <li>4. Click Login</li>
            </ol>
        </div>
    </div>

    <script>
        // Prevent autofill
        document.addEventListener('DOMContentLoaded', function() {
            // Clear password field on page load
            document.getElementById('password').value = '';
            
            // Add event listener to password field
            document.getElementById('password').addEventListener('focus', function() {
                this.value = '';
                this.placeholder = 'Type: password123';
            });
            
            // Prevent form submission if password is wrong
            document.querySelector('form').addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                if (password !== 'password123') {
                    e.preventDefault();
                    alert('Please enter exactly: password123');
                    document.getElementById('password').focus();
                }
            });
        });
    </script>
</body>
</html>