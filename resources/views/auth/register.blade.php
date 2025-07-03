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
                        primary: '#d97398',
                        secondary: '#f8e8ed',
                        accent: '#8b5a6b'
                    },
                    borderRadius: {
                        button: '25px'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delay': 'float 6s ease-in-out infinite 2s',
                        'petal-fall': 'petalFall 15s linear infinite',
                        'shimmer': 'shimmer 3s ease-in-out infinite',
                        'fade-in': 'fadeIn 1s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out'
                    }
                }
            }
        };
    </script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            background-image: url('https://static.readdy.ai/image/ac1308ec00b397716f0c080c2e21fef7/5c4f36c15a4c402f90ae54610ff824e2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Cinematic overlay with gradient */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(
                135deg, 
                rgba(217, 115, 152, 0.15) 0%,
                rgba(248, 232, 237, 0.08) 25%,
                rgba(139, 90, 107, 0.12) 50%,
                rgba(217, 115, 152, 0.18) 75%,
                rgba(248, 232, 237, 0.05) 100%
            );
            z-index: 0;
            animation: shimmer 8s ease-in-out infinite;
        }

        /* Floating particles/petals */
        .petal {
            position: absolute;
            width: 8px;
            height: 8px;
            background: linear-gradient(45deg, rgba(217, 115, 152, 0.6), rgba(248, 232, 237, 0.8));
            border-radius: 50% 0 50% 0;
            animation: petal-fall 15s linear infinite;
        }

        .petal:nth-child(odd) {
            animation-delay: -5s;
            background: linear-gradient(45deg, rgba(248, 232, 237, 0.7), rgba(217, 115, 152, 0.5));
        }

        .petal:nth-child(3n) {
            animation-delay: -10s;
            width: 6px;
            height: 6px;
        }

        /* Glassmorphism container */
        .form-container {
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.25) 0%,
                rgba(255, 255, 255, 0.15) 50%,
                rgba(255, 255, 255, 0.05) 100%
            );
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 25px 50px -15px rgba(217, 115, 152, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            max-width: 580px;
            width: 100%;
            margin: auto;
            position: relative;
            z-index: 10;
            animation: fade-in 1s ease-out, slide-up 0.8s ease-out;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        }

        .form-container::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(217, 115, 152, 0.03), transparent 60deg);
            animation: shimmer 6s linear infinite;
            pointer-events: none;
        }

        /* Floating decorative elements */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(217, 115, 152, 0.1), rgba(248, 232, 237, 0.15));
            backdrop-filter: blur(10px);
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 15%;
            left: 8%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 70%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 20%;
            left: 15%;
            animation-delay: 4s;
        }

        .floating-element:nth-child(4) {
            width: 50px;
            height: 50px;
            top: 40%;
            right: 25%;
            animation-delay: 1s;
        }

        /* Enhanced form styling */
        .form-title {
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, #d97398, #7a4e5e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 10px rgba(153, 72, 102, 0.3);
        }

        .form-subtitle {
            color: rgba(138, 85, 103, 0.8);
            font-weight: 300;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-field {
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(217, 115, 152, 0.2);
            border-radius: 16px;
            padding: 14px 20px 14px 50px;
            width: 100%;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(217, 115, 152, 0.1);
        }

        .input-field.no-icon {
            padding-left: 20px;
        }

        .input-field:focus {
            outline: none;
            border-color: rgba(217, 115, 152, 0.5);
            box-shadow: 
                0 0 0 3px rgba(217, 115, 152, 0.1),
                0 8px 25px rgba(217, 115, 152, 0.2);
            transform: translateY(-1px);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(217, 115, 152, 0.6);
            font-size: 18px;
            z-index: 2;
        }

        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(217, 115, 152, 0.6);
            cursor: pointer;
            font-size: 18px;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .toggle-password:hover {
            color: rgba(217, 115, 152, 0.8);
        }

        .submit-btn {
            background: linear-gradient(135deg, #d97398 0%, #8b5a6b 100%);
            border: none;
            border-radius: 25px;
            padding: 16px 32px;
            width: 100%;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 8px 25px rgba(217, 115, 152, 0.3),
                0 4px 15px rgba(139, 90, 107, 0.2);
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s ease;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 35px rgba(217, 115, 152, 0.4),
                0 8px 25px rgba(139, 90, 107, 0.3);
        }

        .checkbox-custom {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(217, 115, 152, 0.3);
            border-radius: 6px;
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7));
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .checkbox-custom:checked {
            background: linear-gradient(135deg, #d97398, #8b5a6b);
            border-color: #d97398;
        }

        .checkbox-custom:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .link-style {
            color: #d97398;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .link-style::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 50%;
            background: linear-gradient(90deg, #d97398, #8b5a6b);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .link-style:hover::after {
            width: 100%;
        }

        .link-style:hover {
            color: #8b5a6b;
        }

        /* Status messages */
        .status-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #166534;
            padding: 12px 16px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .status-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        /* Grid styling for name fields */
        .name-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Label styling */
        .input-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: rgba(75, 85, 99, 0.9);
            margin-bottom: 8px;
        }

        /* Terms checkbox container */
        .terms-container {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 2rem;
        }

        .terms-text {
            font-size: 14px;
            color: rgba(75, 85, 99, 0.8);
            line-height: 1.5;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(-10px) rotate(240deg); }
        }

        @keyframes petalFall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        @keyframes shimmer {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Responsive design */
        @media (max-width: 640px) {
            .form-container {
                margin: 20px;
                padding: 2rem 1.5rem;
                max-width: none;
            }
            
            .floating-element {
                display: none;
            }

            .name-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .input-group {
                margin-bottom: 1.25rem;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 relative py-8">
    <!-- Floating petals -->
    <div class="petal" style="left: 10%; animation-delay: 0s;"></div>
    <div class="petal" style="left: 20%; animation-delay: -2s;"></div>
    <div class="petal" style="left: 30%; animation-delay: -4s;"></div>
    <div class="petal" style="left: 40%; animation-delay: -6s;"></div>
    <div class="petal" style="left: 50%; animation-delay: -8s;"></div>
    <div class="petal" style="left: 60%; animation-delay: -10s;"></div>
    <div class="petal" style="left: 70%; animation-delay: -12s;"></div>
    <div class="petal" style="left: 80%; animation-delay: -14s;"></div>
    <div class="petal" style="left: 90%; animation-delay: -16s;"></div>

    <!-- Floating decorative elements -->
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>

    <div class="form-container">
        <div class="text-center mb-8">
            <h2 class="form-title text-4xl font-bold mb-3">Create Your Account</h2>
            <p class="form-subtitle text-base">Begin your journey to the perfect wedding day</p>
        </div>

        {{-- Show all error messages at the top --}}
        @if ($errors->any())
            <div class="status-error text-sm mb-6 font-medium">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @if (session('status'))
            <div class="status-success text-sm mb-6 font-medium">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf

            <div class="name-grid">
                <div class="input-group">
                    <label for="first_name" class="input-label">First Name</label>
                    <div class="relative">
                        <i class="ri-user-line input-icon"></i>
                        <input type="text" id="first_name" name="first_name" class="input-field" placeholder="Enter first name" required value="{{ old('first_name') }}">
                        @error('first_name')
                            <div class="status-error text-sm mt-2 font-medium">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="input-group">
                    <label for="last_name" class="input-label">Last Name</label>
                    <div class="relative">
                        <i class="ri-user-line input-icon"></i>
                        <input type="text" id="last_name" name="last_name" class="input-field" placeholder="Enter last name" required value="{{ old('last_name') }}">
                        @error('last_name')
                            <div class="status-error text-sm mt-2 font-medium">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="input-group">
                <label for="email" class="input-label">Email Address</label>
                <div class="relative">
                    <i class="ri-mail-line input-icon"></i>
                    <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email address" required value="{{ old('email') }}">
                    @error('email')
                        <div class="status-error text-sm mt-2 font-medium">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="input-group">
                <label for="phone" class="input-label">Phone Number</label>
                <div class="relative">
                    <i class="ri-phone-line input-icon"></i>
                    <input type="text" id="phone" name="phone" class="input-field" placeholder="Enter your phone number" required value="{{ old('phone') }}">
                    @error('phone')
                        <div class="status-error text-sm mt-2 font-medium">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="input-group">
                <label for="password" class="input-label">Password</label>
                <div class="relative">
                    <i class="ri-lock-line input-icon"></i>
                    <input type="password" id="password" name="password" class="input-field" placeholder="Create a secure password" required>
                    <i id="toggle-password" class="ri-eye-line toggle-password" onclick="togglePassword('password', this)"></i>
                    @error('password')
                        <div class="status-error text-sm mt-2 font-medium">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="input-group">
                <label for="password_confirmation" class="input-label">Confirm Password</label>
                <div class="relative">
                    <i class="ri-lock-line input-icon"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="input-field" placeholder="Confirm your password" required>
                    <i id="toggle-confirm" class="ri-eye-line toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
                </div>
            </div>

            <div class="terms-container">
                <input type="checkbox" id="terms" name="terms" class="checkbox-custom" required {{ old('terms') ? 'checked' : '' }}>
                <label for="terms" class="terms-text cursor-pointer">
                    I accept the <a href="/terms" class="link-style">Terms of Service</a> and <a href="/privacy" class="link-style">Privacy Policy</a>
                </label>
                @error('terms')
                    <div class="status-error text-sm mt-2 font-medium">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="link-style ml-1">Sign in here</a>
        </div>
    </div>

    <script>
        function togglePassword(id, icon) {
            const field = document.getElementById(id);
            field.type = field.type === "password" ? "text" : "password";
            icon.classList.toggle("ri-eye-line");
            icon.classList.toggle("ri-eye-off-line");
        }

        // Add interactive sparkle effects on form interactions
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Enhanced form validation feedback
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.style.borderColor = 'rgba(34, 197, 94, 0.3)';
                } else {
                    this.style.borderColor = 'rgba(217, 115, 152, 0.2)';
                }
            });
        });

        // Prevent loading state if validation fails
        document.getElementById('register-form').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.submit-btn');
            setTimeout(() => {
                if (document.querySelector('.status-error')) {
                    submitBtn.innerHTML = 'Create Account';
                    submitBtn.disabled = false;
                }
            }, 100);
        });
    </script>
</body>
</html>