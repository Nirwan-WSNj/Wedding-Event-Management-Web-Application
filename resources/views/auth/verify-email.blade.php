<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Email Verification - Wedding Planner</title>
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
  <div class="form-container text-center">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Email Verification</h2>
    <p class="text-gray-600 mb-6">
      Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
      If you didn't receive the email, we will gladly send you another.
    </p>

    @if (session('status') == 'verification-link-sent')
      <div class="text-sm text-green-600 font-medium mb-4">
        A new verification link has been sent to the email address you provided during registration.
      </div>
    @endif

    @if (session('warning'))
      <div class="text-sm text-yellow-600 font-medium mb-4">
        {{ session('warning') }}
      </div>
    @endif

    <div class="flex items-center justify-between gap-4 mt-6">
      <form method="POST" action="{{ route('verification.send') }}" class="w-full">
        @csrf
        <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-button font-medium hover:bg-primary/90 transition">Resend Verification Email</button>
      </form>

      <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
        <button type="submit" class="w-full text-sm underline text-gray-600 hover:text-gray-900 transition">Log Out</button>
      </form>
    </div>
  </div>
</body>
</html>
