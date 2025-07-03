<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile Upload Debug</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6">Profile Upload Debug Test</h1>
        
        <!-- Current User Info -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h2 class="font-semibold mb-2">Current User Info:</h2>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
            <p><strong>Name:</strong> {{ auth()->user()->full_name }}</p>
            <p><strong>Current Profile Photo Path:</strong> {{ auth()->user()->profile_photo_path ?? 'None' }}</p>
            <p><strong>Profile Photo URL:</strong> {{ auth()->user()->profile_photo_url }}</p>
            <p><strong>Avatar URL:</strong> {{ auth()->user()->avatar_url }}</p>
        </div>

        <!-- Current Profile Photo Display -->
        <div class="mb-6">
            <h2 class="font-semibold mb-2">Current Profile Photo:</h2>
            <img src="{{ auth()->user()->profile_photo_url }}" alt="Current Profile" class="w-32 h-32 rounded-full border-4 border-blue-500 object-cover">
        </div>

        <!-- Upload Form -->
        <form method="POST" action="{{ route('debug.profile.upload') }}" enctype="multipart/form-data" id="debug-form">
            @csrf
            
            <div class="mb-4">
                <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                    Select New Profile Photo:
                </label>
                <input type="file" 
                       id="profile_photo" 
                       name="profile_photo" 
                       accept="image/*" 
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- Hidden fields to maintain other user data -->
            <input type="hidden" name="first_name" value="{{ auth()->user()->first_name }}">
            <input type="hidden" name="last_name" value="{{ auth()->user()->last_name }}">
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Upload Profile Photo
            </button>
        </form>

        <!-- Error Display -->
        @if ($errors->any())
            <div class="mt-6 p-4 bg-red-50 rounded-lg">
                <h3 class="font-semibold text-red-800 mb-2">Errors:</h3>
                <ul class="list-disc list-inside text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('status') === 'profile-updated')
            <div class="mt-6 p-4 bg-green-50 rounded-lg">
                <p class="text-green-800 font-semibold">Profile updated successfully!</p>
            </div>
        @endif

        <!-- Debug Console -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold mb-2">Debug Console:</h3>
            <div id="debug-console" class="text-sm text-gray-600 font-mono"></div>
        </div>
    </div>

    <script>
        const debugConsole = document.getElementById('debug-console');
        
        function log(message) {
            const timestamp = new Date().toLocaleTimeString();
            debugConsole.innerHTML += `[${timestamp}] ${message}<br>`;
            console.log(message);
        }

        document.addEventListener('DOMContentLoaded', function() {
            log('Page loaded successfully');
            log('CSRF Token: ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            const form = document.getElementById('debug-form');
            const fileInput = document.getElementById('profile_photo');
            
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    log(`File selected: ${file.name}`);
                    log(`File size: ${file.size} bytes`);
                    log(`File type: ${file.type}`);
                    
                    // Validate file
                    if (file.size > 2 * 1024 * 1024) {
                        log('ERROR: File too large (>2MB)');
                        alert('File size must be less than 2MB');
                        this.value = '';
                        return;
                    }
                    
                    if (!file.type.startsWith('image/')) {
                        log('ERROR: Invalid file type');
                        alert('Please select a valid image file');
                        this.value = '';
                        return;
                    }
                    
                    log('File validation passed');
                } else {
                    log('No file selected');
                }
            });
            
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                log('Form submission started');
                const fileInput = document.getElementById('profile_photo');
                const file = fileInput.files[0];
                
                if (!file) {
                    log('ERROR: No file selected for upload');
                    alert('Please select a file to upload');
                    return;
                }
                
                log(`Submitting form with file: ${file.name}`);
                log('Form data being sent...');
                
                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = 'Uploading...';
                
                // Create FormData
                const formData = new FormData(this);
                log('FormData created with keys: ' + Array.from(formData.keys()).join(', '));
                
                // Send AJAX request
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    log(`Response status: ${response.status} ${response.statusText}`);
                    return response.json();
                })
                .then(data => {
                    log('Response received: ' + JSON.stringify(data, null, 2));
                    
                    if (data.success) {
                        log('✅ Upload successful!');
                        alert('Upload successful! Check the debug console for details.');
                    } else {
                        log('❌ Upload failed: ' + data.message);
                        alert('Upload failed: ' + data.message);
                    }
                })
                .catch(error => {
                    log('❌ Network error: ' + error.message);
                    alert('Network error: ' + error.message);
                })
                .finally(() => {
                    // Reset button
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Upload Profile Photo';
                });
            });
        });
    </script>
</body>
</html>