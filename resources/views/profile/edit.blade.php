@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<section class="py-24 bg-gradient-to-b from-[#fef8f5] to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl" x-data="{ tab: 'info' }">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-5xl font-playfair font-bold text-[#333]">Your Profile</h2>
            <p class="text-gray-600 mt-2">Manage your account information and settings.</p>
        </div>

        <!-- Tabs -->
        <div class="flex justify-center space-x-4 mb-10">
            <button @click="tab = 'info'"
                :class="tab === 'info' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'"
                class="px-6 py-2 rounded-button font-medium transition">Profile Info</button>
            <button @click="tab = 'password'"
                :class="tab === 'password' ? 'bg-secondary text-white' : 'bg-gray-200 text-gray-700'"
                class="px-6 py-2 rounded-button font-medium transition">Change Password</button>
            <button @click="tab = 'delete'"
                :class="tab === 'delete' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'"
                class="px-6 py-2 rounded-button font-medium transition">Delete Account</button>
        </div>

        <!-- Profile Info Tab -->
        <div x-show="tab === 'info'" x-transition>
            <div class="bg-white border-l-4 border-primary shadow-md rounded-xl p-8">
                <h3 class="text-2xl font-semibold text-primary mb-4">👤 Update Profile Information</h3>
                <div class="space-y-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Change Password Tab -->
        <div x-show="tab === 'password'" x-transition>
            <div class="bg-white border-l-4 border-secondary shadow-md rounded-xl p-8 mt-10">
                <h3 class="text-2xl font-semibold text-secondary mb-4">🔒 Change Password</h3>
                <div class="space-y-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete Account Tab -->
        <div x-show="tab === 'delete'" x-transition>
            <div class="bg-red-50 border-l-4 border-red-500 shadow-md rounded-xl p-8 mt-10">
                <h3 class="text-2xl font-semibold text-red-600 mb-4">⚠️ Danger Zone</h3>
                <p class="text-sm text-red-500 mb-4">Proceed only if you really want to delete your account.</p>
                <div class="space-y-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
