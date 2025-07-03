<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{ __('Profile Information') }}</h2>
        <p class="mt-1 text-sm text-gray-600">{{ __("Update your account's profile information, email address, and profile picture.") }}</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6" id="profile-update-form">
        @csrf
        @method('patch')
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf-token">

        <div class="flex items-center gap-6">
            <div class="relative group">
                <img id="profile-preview" src="{{ $user->profile_photo_url }}?v={{ time() }}" alt="Profile Picture" class="w-20 h-20 rounded-full border-4 border-primary shadow-lg object-cover transition-all duration-300 group-hover:border-secondary" onerror="this.src='{{ asset('storage/halls/default-avatar.png') }}'; console.log('Failed to load profile preview:', this.src);">
                <label for="profile_photo" class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                    <i class="fas fa-camera text-white text-2xl"></i>
                </label>
                <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="hidden">
            </div>
            <div>
                <span class="block text-sm text-gray-500">Click the photo to change your profile picture</span>
                <div id="upload-status" class="mt-2"></div>
                @error('profile_photo')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus autocomplete="given-name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 font-semibold">✅ Profile updated successfully!</p>
            @elseif (session('status') === 'profile-photo-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 font-semibold">✅ Profile photo updated successfully!</p>
            @endif
        </div>
    </form>
</section>
