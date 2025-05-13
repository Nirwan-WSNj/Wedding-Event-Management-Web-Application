<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Wedding Planner</title>
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
  <h2 class="text-2xl font-semibold text-gray-800 mb-2">Create an Account</h2>
  <p class="text-gray-600 mb-6">Begin your journey to a perfect wedding day</p>

  @if (session('status'))
    <div class="text-sm text-green-600 mb-4 font-medium">{{ session('status') }}</div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="grid grid-cols-2 gap-4 mb-4">
      <div>
        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
        <input type="text" id="first_name" name="first_name" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-primary/20 focus:border-primary" placeholder="First name" required>
        @error('first_name')
          <div class="text-sm text-red-600 mt-1 font-medium">{{ $message }}</div>
        @enderror
      </div>
      <div>
        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
        <input type="text" id="last_name" name="last_name" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-primary/20 focus:border-primary" placeholder="Last name" required>
        @error('last_name')
          <div class="text-sm text-red-600 mt-1 font-medium">{{ $message }}</div>
        @enderror
      </div>
    </div>

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
      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
      <div class="relative">
        <i class="ri-lock-line absolute left-3 top-2.5 text-gray-400"></i>
        <input type="password" id="password" name="password" class="pl-10 w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-primary/20 focus:border-primary" placeholder="Enter your password" required>
        <i id="toggle-password" class="ri-eye-line absolute right-3 top-2.5 text-gray-400 cursor-pointer" onclick="togglePassword('password', this)"></i>
        @error('password')
          <div class="text-sm text-red-600 mt-1 font-medium">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="mb-4">
      <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
      <div class="relative">
        <i class="ri-lock-line absolute left-3 top-2.5 text-gray-400"></i>
        <input type="password" id="password_confirmation" name="password_confirmation" class="pl-10 w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-primary/20 focus:border-primary" placeholder="Confirm your password" required>
        <i id="toggle-confirm" class="ri-eye-line absolute right-3 top-2.5 text-gray-400 cursor-pointer" onclick="togglePassword('password_confirmation', this)"></i>
      </div>
    </div>

    <div class="flex items-center mb-5">
      <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" required>
      <label for="terms" class="ml-2 text-sm text-gray-700">I accept the <a href="/terms" class="text-primary hover:underline">Terms</a> and <a href="/privacy" class="text-primary hover:underline">Privacy Policy</a></label>
      @error('terms')
        <div class="text-sm text-red-600 mt-1 font-medium">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-button font-medium hover:bg-primary/90 transition">Sign Up</button>
  </form>

  <div class="mt-5 text-center text-sm text-gray-600">
    Already have an account?
    <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Login now</a>
  </div>
</div>

<script>
  function togglePassword(id, icon) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
    icon.classList.toggle("ri-eye-line");
    icon.classList.toggle("ri-eye-off-line");
  }
</script>
</body>
</html>
