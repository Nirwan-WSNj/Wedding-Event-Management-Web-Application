@extends('layouts.app')

@section('title', 'Contact Us')


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Contact Wet Water Resort</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<style>
:where([class^="ri-"])::before { content: "\f3c2"; }
input[type="date"]::-webkit-calendar-picker-indicator {
opacity: 0.6;
cursor: pointer;
}
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
-webkit-appearance: none;
margin: 0;
}
input[type="number"] {
-moz-appearance: textfield;
}

.shadow-soft {
    box-shadow: 0 20px 50px rgba(60, 47, 47, 0.15);
}

.card-hover {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform: translateY(0);
    box-shadow: 0 10px 30px rgba(87, 41, 6, 0.1);
}

.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 60px rgba(87, 41, 6, 0.18), 0 8px 20px rgba(87, 41, 6, 0.12);
}

.romantic-glow {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,252,248,0.95) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.input-focus {
    transition: all 0.3s ease;
    position: relative;
}

.input-focus:focus {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(87, 41, 6, 0.15);
}

.btn-romantic {
    background: linear-gradient(135deg, #572906 0%, #8b4513 100%);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-romantic::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-romantic:hover::before {
    left: 100%;
}

.btn-romantic:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(87, 41, 6, 0.3);
}

.floating-animation {
    animation: floating 3s ease-in-out infinite;
}

@keyframes floating {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.fade-in {
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.icon-bounce {
    transition: transform 0.3s ease;
}

.icon-bounce:hover {
    transform: scale(1.2) rotate(5deg);
}

#navbar {
    transition: all 0.3s ease;
}

.navbar-default {
    background-color: rgba(31, 31, 31, 0.6);
    color: white;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}

.navbar-scrolled {
    background-color: white;
    color: black;
    text-shadow: none;
}
</style>
<script>
tailwind.config={
theme:{
extend:{
colors:{
primary:'#572906',
secondary:'#1f0c01'
},
borderRadius:{
'none':'0px',
'sm':'4px',
DEFAULT:'8px',
'md':'12px',
'lg':'16px',
'xl':'20px',
'2xl':'24px',
'3xl':'32px',
'full':'9999px',
'button':'8px'
}
}

}
</script>
</head>
<body class="bg-gradient-to-br from-[#fdf7f2] via-[#f7efe8] to-[#e9dbcc] min-h-screen font-body text-gray-800">

  <!-- Placeholder for Header -->
  <div class="h-24"></div>
  @section('content')
  <div class="max-w-6xl mx-auto px-4 py-8 fade-in">

    <!-- Server-side Flash Messages -->
    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 fade-in">
        <div class="flex items-center">
          <i class="ri-check-circle-line mr-2"></i>
          <span>{{ session('success') }}</span>
        </div>
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 fade-in">
        <div class="flex items-center">
          <i class="ri-error-warning-line mr-2"></i>
          <span>{{ session('error') }}</span>
        </div>
      </div>
    @endif

    <h1 class="text-4xl text-center text-gray-900 font-[Playfair Display] floating-animation">
      Contact Wet Water Resort
    </h1>
    <div class="flex items-center justify-center gap-2 mt-2 mb-2">
      <div class="flex items-center gap-1">
        <i class="ri-star-fill text-yellow-400 icon-bounce"></i>
        <span class="text-gray-700">4.01</span>
      </div>
      <span class="text-gray-500">(859 Google reviews)</span>
      <span class="text-gray-700">•</span>
      <span class="text-gray-700">3-star hotel</span>
    </div>
    <p class="text-center text-gray-600 mt-2 mb-10">Experience comfort and convenience at our modern resort in Gampaha</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Contact Form -->
      <div class="romantic-glow p-8 rounded-2xl card-hover">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Send Us a Message</h2>
        <p class="text-gray-600 text-sm mb-6">Fill out the form below and our wedding team will get back to you within 24 hours.</p>
        <form id="contactForm" action="{{ route('contact.submit') }}" method="POST">
          @csrf
          
          <!-- Success/Error Messages -->
          <div id="form-messages" class="mb-4 hidden">
            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 hidden">
              <div class="flex items-center">
                <i class="ri-check-circle-line mr-2"></i>
                <span id="success-text"></span>
              </div>
            </div>
            <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden">
              <div class="flex items-center">
                <i class="ri-error-warning-line mr-2"></i>
                <span id="error-text"></span>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
              <input type="text" id="firstName" name="firstName" value="{{ old('firstName') }}" placeholder="Enter your first name" class="input-focus w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906] @error('firstName') border-red-500 @enderror" required>
              @error('firstName')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
              <input type="text" id="lastName" name="lastName" value="{{ old('lastName') }}" placeholder="Enter your last name" class="input-focus w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906] @error('lastName') border-red-500 @enderror" required>
              @error('lastName')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" class="input-focus w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906] @error('email') border-red-500 @enderror" required>
            @error('email')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" class="input-focus w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906] @error('phone') border-red-500 @enderror" required>
            @error('phone')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="eventType" class="block text-sm font-medium text-gray-700 mb-1">Event Type *</label>
            <div class="relative">
              <select id="eventType" name="eventType" class="input-focus w-full px-3 py-2 border border-gray-300 rounded appearance-none focus:outline-none focus:ring-1 focus:ring-[#572906] pr-8 @error('eventType') border-red-500 @enderror" required>
                <option value="" disabled {{ old('eventType') ? '' : 'selected' }}>Select event type</option>
                <option value="wedding" {{ old('eventType') == 'wedding' ? 'selected' : '' }}>Wedding</option>
                <option value="engagement" {{ old('eventType') == 'engagement' ? 'selected' : '' }}>Engagement Party</option>
                <option value="rehearsal" {{ old('eventType') == 'rehearsal' ? 'selected' : '' }}>Rehearsal Dinner</option>
                <option value="reception" {{ old('eventType') == 'reception' ? 'selected' : '' }}>Reception Only</option>
                <option value="other" {{ old('eventType') == 'other' ? 'selected' : '' }}>Other</option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                <i class="ri-arrow-down-s-line text-gray-400"></i>
              </div>
            </div>
            @error('eventType')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Preferred Date</label>
            <input type="date" id="date" name="date" value="{{ old('date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="input-focus w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906] @error('date') border-red-500 @enderror">
            @error('date')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="guestCount" class="block text-sm font-medium text-gray-700 mb-1">Estimated Guest Count</label>
            <input type="number" id="guestCount" name="guestCount" value="{{ old('guestCount') }}" placeholder="Enter estimated number of guests" min="1" max="1000" class="input-focus w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906] @error('guestCount') border-red-500 @enderror">
            @error('guestCount')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-6">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Your Message *</label>
            <textarea id="message" name="message" rows="4" placeholder="Tell us about your vision for your special day" class="input-focus w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#572906] @error('message') border-red-500 @enderror" required>{{ old('message') }}</textarea>
            @error('message')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <button type="submit" id="submitBtn" class="btn-romantic w-full text-white py-2 px-4 !rounded-button font-medium relative overflow-hidden whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed">
            <span id="submitText">Send Message</span>
            <span id="submitLoader" class="hidden">
              <i class="ri-loader-4-line animate-spin mr-2"></i>
              Sending...
            </span>
          </button>
          
          <p class="text-xs text-gray-500 mt-2 text-center">
            * Required fields. We'll respond within 24 hours.
          </p>
        </form>
      </div>

      <!-- Contact Information -->
      <div class="space-y-6">
        <div class="romantic-glow p-6 rounded-2xl card-hover">
          <h2 class="text-xl font-semibold text-gray-800 mb-2">Contact Information</h2>
          <p class="text-gray-600 text-sm mb-6">Reach out to us directly using the information below</p>
          <div class="space-y-5">
            <div class="flex items-start">
              <div class="w-10 h-10 flex items-center justify-center bg-brown-50 rounded-full mr-3 icon-bounce">
                <i class="ri-phone-line text-primary"></i>
              </div>
              <div>
                <h3 class="font-medium text-gray-800">Phone</h3>
                <p class="text-primary">0332 226 886</p>
                <p class="text-sm text-gray-500 mt-1">24/7 Front Desk Available</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="w-10 h-10 flex items-center justify-center bg-brown-50 rounded-full mr-3 icon-bounce">
                <i class="ri-mail-line text-primary"></i>
              </div>
              <div>
                <h3 class="font-medium text-gray-800">Email</h3>
                <p class="text-primary">info@wetwaterresort.com</p>
                <p class="text-sm text-gray-500 mt-1">We'll respond within 24 hours</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="w-10 h-10 flex items-center justify-center bg-brown-50 rounded-full mr-3 icon-bounce">
                <i class="ri-map-pin-line text-primary"></i>
              </div>
              <div>
                <h3 class="font-medium text-gray-800">Location</h3>
                <p class="text-gray-700">No- 136/D, "Lumbini Uyana"</p>
                <p class="text-gray-700">Ja Ela-Ekala-Gampaha-Yakkala Hwy, Gampaha</p>
                <p class="text-sm text-gray-500 mt-1">Conveniently located near Gampaha city center</p>
              </div>
            </div>
            <div class="flex items-start">
              <div class="w-10 h-10 flex items-center justify-center bg-brown-50 rounded-full mr-3 icon-bounce">
                <i class="ri-time-line text-primary text-xl"></i>
              </div>
              <div>
                <h3 class="font-medium text-gray-800">Time</h3>
                <p class="text-gray-700">Mon-Fri: 9am-6pm, Sat: 10am-4pm</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="romantic-glow p-6 rounded card-hover">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Our Resort</h2>
          <div class="rounded overflow-hidden h-64 mb-4">
            <img src="https://readdy.ai/api/search-image?query=Modern%20tropical%20resort%20with%20swimming%20pool%20surrounded%20by%20lush%20gardens%2C%20contemporary%20architecture%20with%20clean%20lines%2C%20comfortable%20loungers%20and%20umbrellas%2C%20peaceful%20atmosphere%20with%20mountain%20views%20in%20the%20background%2C%20perfect%20for%20relaxation&width=800&height=400&seq=12345&orientation=landscape" alt="Wet Water Resort" class="w-full h-full object-cover object-top">
          </div>
          <button class="w-full bg-gray-100 text-gray-800 py-2 px-4 !rounded-button font-medium hover:bg-gray-200 transition duration-200 flex items-center justify-center gap-2 whitespace-nowrap card-hover">
            <i class="ri-map-2-line"></i>
            <span>Get Directions</span>
          </button>
        </div>
      </div>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoader = document.getElementById('submitLoader');
    const formMessages = document.getElementById('form-messages');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    const successText = document.getElementById('success-text');
    const errorText = document.getElementById('error-text');

    // Show/hide messages
    function showMessage(type, message) {
        formMessages.classList.remove('hidden');
        
        if (type === 'success') {
            successMessage.classList.remove('hidden');
            errorMessage.classList.add('hidden');
            successText.textContent = message;
        } else {
            errorMessage.classList.remove('hidden');
            successMessage.classList.add('hidden');
            errorText.textContent = message;
        }

        // Scroll to message
        formMessages.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideMessages() {
        formMessages.classList.add('hidden');
        successMessage.classList.add('hidden');
        errorMessage.classList.add('hidden');
    }

    // Set loading state
    function setLoading(loading) {
        if (loading) {
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoader.classList.remove('hidden');
        } else {
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoader.classList.add('hidden');
        }
    }

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        hideMessages();
        setLoading(true);

        // Get form data
        const formData = new FormData(form);

        // Submit via AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
            }
        })
        .then(response => response.json())
        .then(data => {
            setLoading(false);
            
            if (data.success) {
                showMessage('success', data.message);
                form.reset();
                
                // Show floating notification
                showFloatingNotification('Message sent successfully!', 'success');
                
                // Optional: Track the submission
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'contact_form_submit', {
                        'event_category': 'engagement',
                        'event_label': 'contact_form'
                    });
                }
            } else {
                showMessage('error', data.message || 'There was an error sending your message. Please try again.');
                
                // Handle validation errors
                if (data.errors) {
                    let errorList = Object.values(data.errors).flat().join(', ');
                    showMessage('error', 'Please fix the following errors: ' + errorList);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            setLoading(false);
            showMessage('error', 'There was an error sending your message. Please try again or call us directly.');
        });
    });

    // Floating notification function
    function showFloatingNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white shadow-lg transform transition-all duration-300 translate-x-full ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="ri-${type === 'success' ? 'check' : 'error'}-warning-line mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Animate out and remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }

    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500') && this.value.trim()) {
                this.classList.remove('border-red-500');
            }
        });
    });

    // Email validation
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('border-red-500');
        }
    });

    // Phone number formatting (basic)
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        // Remove non-numeric characters except + and spaces
        this.value = this.value.replace(/[^\d\s\+\-\(\)]/g, '');
    });

    // Guest count validation
    const guestCountInput = document.getElementById('guestCount');
    guestCountInput.addEventListener('input', function() {
        if (this.value && (this.value < 1 || this.value > 1000)) {
            this.classList.add('border-red-500');
        } else {
            this.classList.remove('border-red-500');
        }
    });

    // Date validation
    const dateInput = document.getElementById('date');
    dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        if (selectedDate < tomorrow) {
            this.classList.add('border-red-500');
            showMessage('error', 'Please select a date that is at least one day in the future.');
        } else {
            this.classList.remove('border-red-500');
            hideMessages();
        }
    });

    // Auto-hide server-side error messages after 10 seconds
    @if(session('error') || $errors->any())
        setTimeout(() => {
            const errorAlerts = document.querySelectorAll('.bg-red-100');
            errorAlerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            });
        }, 10000);
    @endif

    // Auto-hide success messages after 8 seconds
    @if(session('success'))
        setTimeout(() => {
            const successAlerts = document.querySelectorAll('.bg-green-100');
            successAlerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            });
        }, 8000);
    @endif
});
</script>
<script>
    // Debounce utility
    function debounce(func, wait) {
      let timeout;
      return function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, arguments), wait);
      };
    }
  
    // Scroll effect for navbar
    document.addEventListener('DOMContentLoaded', function () {
      const navbar = document.getElementById('navbar');
  
      const handleScroll = debounce(() => {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      }, 50);
  
      window.addEventListener('scroll', handleScroll);
    });
  </script>
</body>
</html>
@endsection