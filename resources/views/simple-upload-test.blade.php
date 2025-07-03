<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Upload Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        button:hover { background: #005a87; }
        .info { background: #f0f8ff; padding: 15px; margin-bottom: 20px; border-left: 4px solid #007cba; }
        .error { background: #ffe6e6; padding: 15px; margin-bottom: 20px; border-left: 4px solid #ff0000; }
        .success { background: #e6ffe6; padding: 15px; margin-bottom: 20px; border-left: 4px solid #00aa00; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Simple Profile Upload Test</h1>
        
        <div class="info">
            <strong>Current User:</strong> {{ auth()->user()->email }}<br>
            <strong>Current Profile Photo:</strong> {{ auth()->user()->profile_photo_path ?? 'None' }}<br>
            <strong>CSRF Token:</strong> {{ csrf_token() }}
        </div>

        @if ($errors->any())
            <div class="error">
                <strong>Errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="success">
                <strong>Success:</strong> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="error">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('debug.profile.upload') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="profile_photo">Select Profile Photo:</label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*" required>
            </div>
            
            <div class="form-group">
                <button type="submit">Upload Photo</button>
            </div>
        </form>

        <hr>
        
        <h3>Debug Information</h3>
        <p><strong>PHP Upload Settings:</strong></p>
        <ul>
            <li>upload_max_filesize: {{ ini_get('upload_max_filesize') }}</li>
            <li>post_max_size: {{ ini_get('post_max_size') }}</li>
            <li>max_file_uploads: {{ ini_get('max_file_uploads') }}</li>
            <li>file_uploads: {{ ini_get('file_uploads') ? 'Enabled' : 'Disabled' }}</li>
        </ul>
        
        <p><strong>Storage Directory:</strong></p>
        <ul>
            <li>Profile photos dir exists: {{ is_dir(storage_path('app/public/profile-photos')) ? 'Yes' : 'No' }}</li>
            <li>Profile photos dir writable: {{ is_writable(storage_path('app/public/profile-photos')) ? 'Yes' : 'No' }}</li>
        </ul>
    </div>

    <script>
        // Simple form submission logging
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form is being submitted...');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            console.log('Form enctype:', this.enctype);
            
            const fileInput = document.getElementById('profile_photo');
            const file = fileInput.files[0];
            
            if (file) {
                console.log('File selected:', file.name);
                console.log('File size:', file.size, 'bytes');
                console.log('File type:', file.type);
            } else {
                console.log('No file selected');
                alert('Please select a file first!');
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = 'Uploading...';
        });
    </script>
</body>
</html>