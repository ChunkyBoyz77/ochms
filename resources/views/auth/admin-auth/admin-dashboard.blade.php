@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <!-- WELCOME -->
    <h1 class="text-xl font-semibold mb-6">
        Welcome {{ Auth::user()->name ?? 'Admin' }},
    </h1>

    <!-- TOP METRICS -->
    <div class="grid grid-cols-3 gap-6 mb-10">

        <!-- Rental Properties Registered -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h3 class="font-semibold text-gray-700 mb-4">
                Rental Properties Registered
            </h3>

            <div class="w-full h-40 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                Pie Chart Placeholder
            </div>
        </div>

        <!-- OCS Housed -->
        <div class="bg-yellow-400 rounded-xl shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4">
                OCS Housed
            </h3>

            <div class="flex items-center justify-center h-40">
                <span class="text-5xl font-bold text-gray-900">
                    1435
                </span>
            </div>
        </div>

        <!-- Ratings -->
        <div class="bg-green-500 rounded-xl shadow p-6 text-white">
            <h3 class="font-semibold mb-4">
                Ratings
            </h3>

            <div class="h-40 flex flex-col justify-between">
                <div class="text-2xl font-semibold">
                    Average: 4.53 ⭐
                </div>

                <div class="w-full h-20 bg-green-600 rounded-lg flex items-center justify-center text-green-200">
                    Rating Trend Placeholder
                </div>
            </div>
        </div>

    </div>

    <!-- FUNCTIONS -->
    <h2 class="text-lg font-semibold mb-4">Functions</h2>

    <div class="grid grid-cols-6 gap-6">

        <!-- Landlord Screening -->
        <a href="{{ route('admin.verifications.index') }}"
           class="bg-purple-500 hover:bg-purple-700 text-white
                  rounded-xl shadow p-8 h-80
                  flex flex-col items-center justify-center
                  transition">

            <i class="fa-solid fa-user-check text-4xl mb-4"></i>
            <span class="text-lg font-semibold text-center">
                Landlord Screening
            </span>
        </a>

        <!-- Manage UMPSA Resources -->
        <a href="#"
           class="bg-purple-500 hover:bg-purple-700 text-white
                  rounded-xl shadow p-8 h-80
                  flex flex-col items-center justify-center
                  transition">

            <i class="fa-solid fa-building-columns text-4xl mb-4"></i>
            <span class="text-lg font-semibold text-center">
                Manage UMPSA Resources
            </span>
        </a>

    </div>

@endsection
