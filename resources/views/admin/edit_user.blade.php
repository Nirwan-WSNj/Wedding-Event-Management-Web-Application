@extends('layouts.admin')

@section('title', 'Edit User - ' . $user->full_name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit User</h1>
            <p class="text-gray-600">Update user information and settings</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="ri-arrow-left-line"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">User Information</h2>
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select id="role" name="role" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror">
                                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Change Password (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" id="password" name="password" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                                <p class="text-sm text-gray-500 mt-1">Leave blank to keep current password</p>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('admin.users') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="ri-save-line mr-2"></i>
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- User Information Sidebar -->
        <div class="space-y-6">
            <!-- Current User Info -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Current Information</h2>
                <div class="space-y-3">
                    <div class="flex items-center">
                        @if($user->profile_photo_path)
                            <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-16 h-16 rounded-full object-cover mr-4">
                        @else
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                                <span class="text-white text-xl font-bold">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $user->full_name }}</h3>
                            <p class="text-sm text-gray-600">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">User Statistics</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Bookings</span>
                        <span class="font-semibold text-gray-900">{{ $user->bookings()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Confirmed Bookings</span>
                        <span class="font-semibold text-gray-900">{{ $user->bookings()->where('status', 'confirmed')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Spent</span>
                        <span class="font-semibold text-gray-900">Rs. {{ number_format($user->bookings()->where('status', 'confirmed')->sum('total_amount')) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Member Since</span>
                        <span class="font-semibold text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Details -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Account Details</h2>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">User ID</span>
                        <p class="font-medium">#{{ $user->id }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Email Verified</span>
                        <p class="font-medium">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <p class="font-medium">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Warning Notice -->
            @if($user->role === 'admin' && $user->id === auth()->id())
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="ri-alert-line text-yellow-600 text-xl mr-3 mt-1"></i>
                    <div>
                        <h3 class="text-yellow-800 font-semibold mb-2">Editing Your Own Account</h3>
                        <p class="text-yellow-700 text-sm">Be careful when changing your own role or password. Make sure you remember your new credentials.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection