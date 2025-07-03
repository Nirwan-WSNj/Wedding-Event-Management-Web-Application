<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Images Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .image-test { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .profile-image { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #007cba; }
        .info { background: #f0f8ff; padding: 15px; margin: 10px 0; border-left: 4px solid #007cba; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profile Images Test</h1>
        
        <div class="info">
            <strong>Current User:</strong> {{ auth()->user()->email }}<br>
            <strong>Profile Photo Path:</strong> {{ auth()->user()->profile_photo_path ?? 'None' }}<br>
            <strong>Profile Photo URL:</strong> {{ auth()->user()->profile_photo_url }}<br>
            <strong>Avatar URL:</strong> {{ auth()->user()->avatar_url }}
        </div>

        <div class="image-test">
            <h3>1. Profile Photo URL Method</h3>
            <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile Photo URL" class="profile-image">
            <p>URL: {{ auth()->user()->profile_photo_url }}</p>
        </div>

        <div class="image-test">
            <h3>2. Avatar URL Method</h3>
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar URL" class="profile-image">
            <p>URL: {{ auth()->user()->avatar_url }}</p>
        </div>

        <div class="image-test">
            <h3>3. Direct Asset URL</h3>
            @if(auth()->user()->profile_photo_path)
                <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Direct Asset" class="profile-image">
                <p>URL: {{ asset('storage/' . auth()->user()->profile_photo_path) }}</p>
            @else
                <p>No profile photo path available</p>
            @endif
        </div>

        <div class="image-test">
            <h3>4. With Cache Busting</h3>
            <img src="{{ auth()->user()->profile_photo_url }}?v={{ time() }}" alt="Cache Busted" class="profile-image">
            <p>URL: {{ auth()->user()->profile_photo_url }}?v={{ time() }}</p>
        </div>

        <div class="image-test">
            <h3>5. Test Direct File Access</h3>
            @if(auth()->user()->profile_photo_path)
                <a href="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" target="_blank">
                    Click to open image in new tab
                </a>
            @else
                <p>No profile photo to test</p>
            @endif
        </div>

        <div class="image-test">
            <h3>6. JavaScript Console Test</h3>
            <button onclick="testImageLoad()">Test Image Loading</button>
            <div id="test-result"></div>
        </div>
    </div>

    <script>
        function testImageLoad() {
            const url = '{{ auth()->user()->profile_photo_url }}';
            const img = new Image();
            const resultDiv = document.getElementById('test-result');
            
            resultDiv.innerHTML = 'Testing image load...';
            
            img.onload = function() {
                resultDiv.innerHTML = `✅ Image loaded successfully!<br>
                                     Dimensions: ${this.naturalWidth}x${this.naturalHeight}<br>
                                     URL: ${url}`;
            };
            
            img.onerror = function() {
                resultDiv.innerHTML = `❌ Failed to load image<br>
                                     URL: ${url}`;
            };
            
            img.src = url;
        }

        // Auto-test on page load
        window.addEventListener('load', function() {
            console.log('Profile photo URL:', '{{ auth()->user()->profile_photo_url }}');
            console.log('Avatar URL:', '{{ auth()->user()->avatar_url }}');
            
            // Test all images on the page
            const images = document.querySelectorAll('.profile-image');
            images.forEach((img, index) => {
                img.addEventListener('load', function() {
                    console.log(`Image ${index + 1} loaded successfully:`, this.src);
                });
                
                img.addEventListener('error', function() {
                    console.error(`Image ${index + 1} failed to load:`, this.src);
                });
            });
        });
    </script>
</body>
</html>