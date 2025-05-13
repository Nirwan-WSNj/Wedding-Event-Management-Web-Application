<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Wedding Planner</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6'
                    },
                    borderRadius: {
                        button: '8px'
                    }
                }
            }
        };
    </script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        body {
            background-image: url('https://static.readdy.ai/image/ac1308ec00b397716f0c080c2e21fef7/5c4f36c15a4c402f90ae54610ff824e2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Inter', sans-serif;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            z-index: -1;
        }
        .form-container {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.98));
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15);
            border-radius: 1rem;
            padding: 2rem;
            max-width: 480px;
            width: 100%;
            margin: auto;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
<div class="form-container">
    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Welcome Back</h2>
    <p class="text-gray-600 mb-6">Sign in to your account to continue planning your perfect day</p>

    @if (session('status'))
        <div class="text-sm text-green-600 mb-4 font-medium">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="text-sm text-red-600 mb-4 font-medium">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <div class="relative">
                <i class="ri-mail-line absolute left-3 top-2.5 text-gray-400"></i>
                <input type="email" id="email" name="email" class="pl-10 w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-primary/20 focus:border-primary" placeholder="Enter your email" required>
                @error('email')
                    <div class="text-sm text-red-600 mt-1 font-medium">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <a href="#" id="forgot-password-link" class="text-sm text-primary hover:underline">Forgot password?</a>
            </div>
            <div class="relative">
                <i class="ri-lock-line absolute left-3 top-2.5 text-gray-400"></i>
                <input type="password" id="password" name="password" class="pl-10 w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-primary/20 focus:border-primary" placeholder="Enter your password" required>
                <i id="toggle-password" class="ri-eye-line absolute right-3 top-2.5 text-gray-400 cursor-pointer" onclick="togglePassword()"></i>
                @error('password')
                    <div class="text-sm text-red-600 mt-1 font-medium">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="flex items-center mb-5">
            <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
        </div>

        <button type="submit" class="w-full bg-primary text-white py-2 px-3 rounded-button font-medium hover:bg-primary/90 transition duration-300 shadow-lg shadow-primary/20">Sign In</button>
    </form>

    <div class="mt-5 text-center text-sm text-gray-600">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Register now</a>
    </div>
</div>

<!-- Forgot Password Modal -->
<div id="forgot-password-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Reset Password</h3>
            <button id="close-forgot-modal" class="text-gray-400 hover:text-gray-500">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <p class="text-gray-600 mb-6">Enter your email and we’ll send a password reset link.</p>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label for="reset-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="reset-email" name="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-primary/20 focus:border-primary" placeholder="Enter your email" required>
                @error('email')
                    <div class="text-sm text-red-600 mt-1 font-medium">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-button font-medium hover:bg-primary/90 transition">Send Reset Link</button>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const field = document.getElementById("password");
        field.type = field.type === "password" ? "text" : "password";
        document.getElementById("toggle-password").classList.toggle("ri-eye-line");
        document.getElementById("toggle-password").classList.toggle("ri-eye-off-line");
    }

    const forgotLink = document.getElementById("forgot-password-link");
    const modal = document.getElementById("forgot-password-modal");
    const closeBtn = document.getElementById("close-forgot-modal");

    forgotLink.addEventListener('click', (e) => {
        e.preventDefault();
        modal.classList.remove('hidden');
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === "Escape" && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });
</script>
</body>
</html>
