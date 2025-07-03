<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Upload Debug</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6">Profile Upload Debug Test</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Errors:</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <strong>Success:</strong> {{ session('status') }}
            </div>
        @endif

        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Current User Info</h2>
            <div class="bg-gray-50 p-4 rounded">
                <p><strong>Name:</strong> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Role:</strong> {{ auth()->user()->role }}</p>
                <p><strong>Current Photo Path:</strong> {{ auth()->user()->profile_photo_path ?? 'None' }}</p>
                <p><strong>Photo URL:</strong> {{ auth()->user()->profile_photo_url }}</p>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Current Profile Picture</h2>
            <img src="{{ auth()->user()->profile_photo_url }}" alt="Current Profile" class="w-32 h-32 rounded-full border-4 border-blue-500 object-cover">
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6" id="debug-form">
            @csrf
            @method('patch')
            
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ auth()->user()->first_name }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ auth()->user()->last_name }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Max size: 2MB. Formats: JPG, PNG, GIF</p>
            </div>

            <div id="preview-container" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                <img id="preview-image" src="" alt="Preview" class="w-32 h-32 rounded-full border-4 border-green-500 object-cover">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Profile
            </button>
        </form>

        <div class="mt-8">
            <h2 class="text-lg font-semibold mb-2">Debug Information</h2>
            <div class="bg-gray-50 p-4 rounded text-sm">
                <p><strong>Form Action:</strong> {{ route('profile.update') }}</p>
                <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
                <p><strong>Storage URL:</strong> {{ asset('storage/profile-photos/test.jpg') }}</p>
                <p><strong>Upload Max Size:</strong> {{ ini_get('upload_max_filesize') }}</p>
                <p><strong>Post Max Size:</strong> {{ ini_get('post_max_size') }}</p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">← Back to Regular Profile</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('debug-form');
            const fileInput = document.getElementById('profile_photo');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');

            // File preview
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);
                    
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        this.value = '';
                        previewContainer.classList.add('hidden');
                        return;
                    }
                    
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Please select a valid image file');
                        this.value = '';
                        previewContainer.classList.add('hidden');
                        return;
                    }
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                }
            });

            // Form submission debug
            form.addEventListener('submit', function(e) {
                console.log('Form submitting...');
                
                const formData = new FormData(this);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        console.log(key + ':', value.name, '(' + value.size + ' bytes)');
                    } else {
                        console.log(key + ':', value);
                    }
                }
                
                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Saving...';
            });
        });
    </script>
</body>
</html>