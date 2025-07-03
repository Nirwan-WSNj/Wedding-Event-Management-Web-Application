@extends('layouts.app')

@section('title', 'Profile')

@push('styles')
<style>
    .profile-card {
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .profile-card:hover {
        transform: translateY(-5px);
    }
    .avatar-upload:hover .upload-overlay {
        opacity: 1;
    }
    .tab-button {
        transition: all 0.3s ease;
    }
    .tab-button:hover {
        transform: translateY(-2px);
    }
    .main-profile-image {
        transition: all 0.3s ease;
    }
    .main-profile-image:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .profile-badge {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .8;
        }
    }
</style>
@endpush

@section('content')
<section class="min-h-screen py-24 bg-gradient-to-br from-[#fef8f5] via-white to-[#f5f8fe]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl" x-data="{ 
        tab: 'info'
    }">
        <!-- Header -->
        <div class="text-center mb-12">
            <!-- Profile Image Display Area -->
            <div class="mb-8 animate-fade-in">
                <div class="relative inline-block">
                    <img id="main-profile-image" 
                         src="{{ auth()->user()->profile_photo_url }}?v={{ time() }}" 
                         alt="Profile Picture" 
                         class="main-profile-image w-32 h-32 rounded-full border-4 border-primary shadow-xl object-cover mx-auto"
                         onerror="this.src='{{ asset('storage/halls/default-avatar.png') }}'; console.log('Failed to load profile image:', this.src);">
                    <div class="absolute -bottom-2 -right-2 bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg profile-badge">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-xl font-semibold text-gray-800">{{ auth()->user()->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-primary/10 text-primary text-xs font-medium rounded-full">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
            
            <h2 class="text-5xl font-playfair font-bold text-[#333] animate-fade-in">
                @if (auth()->user()->isAdmin())
                    Admin Profile
                @elseif (auth()->user()->isManager())
                    Manager Profile
                @else
                    Your Profile
                @endif
            </h2>
            <p class="text-gray-600 mt-2 animate-fade-in-up">
                @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                    Manage your account and dashboard settings.
                @else
                    Manage your account information and settings.
                @endif
            </p>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap justify-center gap-4 mb-10">
            <button @click="tab = 'info'"
                :class="tab === 'info' ? 'bg-primary text-white shadow-lg scale-105' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                class="px-6 py-2 rounded-full font-medium transition-all duration-300 tab-button">
                <i class="fas fa-user mr-2"></i>Profile Info
            </button>
            <button @click="tab = 'password'"
                :class="tab === 'password' ? 'bg-secondary text-white shadow-lg scale-105' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                class="px-6 py-2 rounded-full font-medium transition-all duration-300 tab-button">
                <i class="fas fa-key mr-2"></i>Password
            </button>
            <button @click="tab = 'delete'"
                :class="tab === 'delete' ? 'bg-red-600 text-white shadow-lg scale-105' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                class="px-6 py-2 rounded-full font-medium transition-all duration-300 tab-button">
                <i class="fas fa-trash-alt mr-2"></i>Delete
            </button>
        </div>

        <!-- Profile Info Tab -->
        <div x-show="tab === 'info'" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4">
            <div class="profile-card bg-white/80 border-l-4 border-primary shadow-xl rounded-xl p-8">
                <h3 class="text-2xl font-semibold text-primary mb-6 flex items-center">
                    <i class="fas fa-user-circle mr-3"></i>Update Profile Information
                </h3>
                
                                <div class="space-y-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Change Password Tab -->
        <div x-show="tab === 'password'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4">
            <div class="profile-card bg-white/80 border-l-4 border-secondary shadow-xl rounded-xl p-8">
                <h3 class="text-2xl font-semibold text-secondary mb-6 flex items-center">
                    <i class="fas fa-key mr-3"></i>Change Password
                </h3>
                <div class="space-y-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete Account Tab -->
        <div x-show="tab === 'delete'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4">
            <div class="profile-card bg-red-50/80 border-l-4 border-red-500 shadow-xl rounded-xl p-8">
                <h3 class="text-2xl font-semibold text-red-600 mb-6 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-3"></i>Danger Zone
                </h3>
                <p class="text-sm text-red-500 mb-6 bg-red-100 p-4 rounded-lg">
                    <i class="fas fa-info-circle mr-2"></i>
                    Warning: This action cannot be undone. Please be certain.
                </p>
                <div class="space-y-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle CSRF token refresh
    function refreshCSRFToken() {
        fetch('/csrf-token', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.csrf_token) {
                // Update all CSRF tokens on the page
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.csrf_token;
                });
                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', data.csrf_token);
            }
        })
        .catch(error => {
            console.log('CSRF token refresh failed:', error);
        });
    }

    // Refresh CSRF token every 10 minutes
    setInterval(refreshCSRFToken, 10 * 60 * 1000);

    // Handle form submission with better error handling
    const profileForm = document.getElementById('profile-update-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            // Refresh CSRF token before submission
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const tokenInput = document.getElementById('csrf-token');
                if (tokenInput) {
                    tokenInput.value = csrfToken;
                }
            }
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            }
        });
    }

    // Handle profile photo preview and upload
    const profilePhotoInput = document.getElementById('profile_photo');
    const profilePreview = document.getElementById('profile-preview');
    const mainProfileImage = document.getElementById('main-profile-image');
    const uploadStatus = document.getElementById('upload-status');
    
    if (profilePhotoInput && profilePreview) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Show upload status
                if (uploadStatus) {
                    uploadStatus.innerHTML = '<span class="text-blue-600 text-sm">📁 File selected: ' + file.name + '</span>';
                }
                
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    if (uploadStatus) {
                        uploadStatus.innerHTML = '<span class="text-red-600 text-sm">❌ File size must be less than 2MB</span>';
                    }
                    this.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    if (uploadStatus) {
                        uploadStatus.innerHTML = '<span class="text-red-600 text-sm">❌ Please select a valid image file</span>';
                    }
                    this.value = '';
                    return;
                }
                
                // Update preview immediately (both form preview and main header image)
                const reader = new FileReader();
                reader.onload = function(e) {
                    const newImageSrc = e.target.result;
                    
                    // Update form preview
                    profilePreview.src = newImageSrc;
                    
                    // Update main header profile image
                    if (mainProfileImage) {
                        mainProfileImage.src = newImageSrc;
                        // Add a subtle animation to indicate change
                        mainProfileImage.style.transform = 'scale(1.05)';
                        setTimeout(() => {
                            mainProfileImage.style.transform = 'scale(1)';
                        }, 300);
                    }
                    
                    if (uploadStatus) {
                        uploadStatus.innerHTML = '<span class="text-green-600 text-sm">✅ Image preview updated. Click Save to upload.</span>';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                if (uploadStatus) {
                    uploadStatus.innerHTML = '';
                }
            }
        });
    }
    
    // Handle form submission with better feedback
    const profileForm = document.getElementById('profile-update-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('profile_photo');
            const submitButton = this.querySelector('button[type="submit"]');
            
            if (fileInput && fileInput.files.length > 0) {
                // Show uploading status
                if (uploadStatus) {
                    uploadStatus.innerHTML = '<span class="text-blue-600 text-sm">⏳ Uploading image...</span>';
                }
                
                // Disable submit button to prevent double submission
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '⏳ Saving...';
                }
            }
        });
    }
    
    // Function to refresh profile images after successful upload
    function refreshProfileImages() {
        const timestamp = new Date().getTime();
        const currentUser = '{{ auth()->user()->id }}';
        const baseUrl = '{{ auth()->user()->profile_photo_url }}';
        const newUrl = baseUrl + '?v=' + timestamp;
        
        // Update main header image
        if (mainProfileImage) {
            mainProfileImage.src = newUrl;
        }
        
        // Update form preview image
        if (profilePreview) {
            profilePreview.src = newUrl;
        }
        
        console.log('Profile images refreshed with timestamp:', timestamp);
    }
    
    // Check for successful upload status and refresh images
    const urlParams = new URLSearchParams(window.location.search);
    const status = '{{ session("status") }}';
    
    if (status === 'profile-photo-updated') {
        // Refresh images after successful upload
        setTimeout(refreshProfileImages, 500);
        
        // Show success animation on main profile image
        if (mainProfileImage) {
            mainProfileImage.style.border = '4px solid #10B981';
            setTimeout(() => {
                mainProfileImage.style.border = '4px solid var(--primary-color, #007cba)';
            }, 2000);
        }
    }
});
</script>
@endpush
@endsection