@extends('layouts.landlord')

@section('title', 'Dashboard')

@section('content')

    <!-- WELCOME -->
    <h1 class="text-xl font-semibold mb-6">Welcome, {{ Auth::user()->name ?? 'Landlord' }}</h1>

    <!-- TOP GRID: 3 Placeholder Cards -->
    <div class="grid grid-cols-3 gap-6 mb-10">

        <!-- Rental Properties -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="font-semibold text-gray-700 mb-4">Rental Properties</h3>

            <div class="w-full h-40 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                Chart Placeholder
            </div>
        </div>

        <!-- Pending Booking -->
        <div class="bg-yellow-300 rounded-xl shadow p-6 border border-yellow-400">
            <h3 class="font-semibold text-gray-800 mb-4">Pending Booking</h3>

            <div class="w-full h-40 bg-yellow-200 rounded-lg flex items-center justify-center text-gray-700">
                Number Placeholder
            </div>
        </div>

        <!-- Ratings -->
        <div class="bg-green-500 text-white rounded-xl shadow p-6">
            <h3 class="font-semibold mb-4">Ratings</h3>

            <div class="w-full h-40 bg-green-600 rounded-lg flex items-center justify-center text-green-200">
                Ratings Chart Placeholder
            </div>
        </div>

    </div>


    <!-- FUNCTIONS SECTION -->
    <h2 class="text-lg font-semibold mb-4">Functions</h2>

    <div class="grid grid-cols-6 gap-6">

        <!-- Manage Listings -->
        <div class="bg-red-500 h-80 hover:bg-red-600 text-white rounded-xl shadow p-8 flex flex-col items-center justify-center transition cursor-pointer">
            <i class="fa-solid fa-house text-4xl mb-4"></i>
            <span class="text-lg font-semibold text-center"><a href="{{ route('landlord.listings') }}">Manage Rental Listings</a></span>
        </div>

        <!-- Manage Booking Requests -->
        <div class="bg-red-500 h-80 hover:bg-red-600 text-white rounded-xl shadow p-8 flex flex-col items-center justify-center transition cursor-pointer">
            <i class="fa-solid fa-file-lines text-4xl mb-4"></i>
            <span class="text-lg font-semibold text-center">Manage Booking Request</span>
        </div>

    </div>

@endsection
