@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-playfair font-bold text-dark-color mb-6">Dashboard</h2>
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-600">Welcome, {{ Auth::user()->first_name }}! This is your dashboard.</p>
                <div class="mt-6">
                    <a href="{{ route('profile.edit') }}" class="inline-block bg-primary text-white px-4 py-2 rounded-button hover:bg-primary/90">Edit Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline-block ml-4">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-button hover:bg-red-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection