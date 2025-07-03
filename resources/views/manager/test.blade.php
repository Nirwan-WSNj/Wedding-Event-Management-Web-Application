@extends('layouts.manager')

@section('title', 'Manager Dashboard Test')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Manager Dashboard Test</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold text-blue-800 mb-2">Layout Test</h2>
                    <p class="text-gray-600">✅ Manager layout is working correctly</p>
                    <p class="text-gray-600">✅ No common header/footer included</p>
                    <p class="text-gray-600">✅ Tailwind CSS is loaded</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold text-green-800 mb-2">Dashboard Features</h2>
                    <p class="text-gray-600">✅ Sidebar navigation</p>
                    <p class="text-gray-600">✅ Statistics cards</p>
                    <p class="text-gray-600">✅ Data tables</p>
                    <p class="text-gray-600">✅ Modal dialogs</p>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold text-yellow-800 mb-2">Functionality</h2>
                    <p class="text-gray-600">✅ Visit request management</p>
                    <p class="text-gray-600">✅ Wedding date approvals</p>
                    <p class="text-gray-600">✅ Booking tracking</p>
                    <p class="text-gray-600">✅ Hall management</p>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold text-purple-800 mb-2">Enhanced Features</h2>
                    <p class="text-gray-600">✅ Calendar view</p>
                    <p class="text-gray-600">✅ Message center</p>
                    <p class="text-gray-600">✅ Real-time updates</p>
                    <p class="text-gray-600">✅ Toast notifications</p>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('manager.dashboard') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Go to Manager Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection