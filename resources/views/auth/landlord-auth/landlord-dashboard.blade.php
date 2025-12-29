@extends('layouts.landlord')

@section('title', 'Dashboard')

@section('content')

<!-- ================= HERO ================= -->
<section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-3xl overflow-hidden mb-8 shadow-2xl">
    <div class="absolute inset-0 bg-gradient-to-br from-red-600/20 via-transparent to-red-600/10"></div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-red-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-red-600/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 px-8 lg:px-12 py-12 lg:py-16">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-red-500/20 backdrop-blur-sm border border-red-500/30 rounded-full px-4 py-2 mb-4">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-white text-sm font-medium">Active Dashboard</span>
            </div>

            <h1 class="text-white text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">
                Welcome back, <span class="text-red-400">{{ Auth::user()->name }}</span>
            </h1>

            <p class="text-gray-300 text-base lg:text-lg leading-relaxed mb-8 max-w-2xl">
                Manage your rental portfolio, respond to bookings, and grow your property business — all in one place.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('landlord.listings') }}"
                   class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3.5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-red-500/50 hover:scale-105">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add New Listing</span>
                </a>

                <a href="#"
                   class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold px-6 py-3.5 rounded-xl hover:bg-white/20 transition-all duration-300">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>View All Bookings</span>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ================= STATS OVERVIEW ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Overview</h2>
        <div x-data="{ open: false, selected: 'This Month' }" class="relative w-56">
            <!-- Trigger -->
            <button
                @click="open = !open"
                class="w-full bg-white border border-gray-200 rounded-xl
                    px-6 py-2.5 text-sm font-medium text-gray-700
                    flex items-center justify-between
                    shadow-sm hover:shadow transition
                    focus:outline-none focus:ring-2 focus:ring-red-500">

                <span x-text="selected"></span>
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </button>

            <!-- Dropdown -->
            <div x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute z-50 mt-2 w-full
                        bg-white rounded-xl shadow-lg border border-gray-100
                        overflow-hidden">

                <button
                    @click="selected='This Month'; open=false"
                    class="w-full text-left px-6 py-3 text-sm
                        hover:bg-red-50 transition">
                    This Month
                </button>

                <button
                    @click="selected='Last Month'; open=false"
                    class="w-full text-left px-6 py-3 text-sm
                        hover:bg-red-50 transition">
                    Last Month
                </button>

                <button
                    @click="selected='This Year'; open=false"
                    class="w-full text-left px-6 py-3 text-sm
                        hover:bg-red-50 transition">
                    This Year
                </button>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Properties -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fa-solid fa-house text-xl"></i>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2.5 py-1 rounded-full">+12%</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Total Properties</p>
            <h3 class="text-3xl font-bold text-gray-800">3</h3>
        </div>

        <!-- Active Listings -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Live</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Active Listings</p>
            <h3 class="text-3xl font-bold text-gray-800">3</h3>
        </div>

        <!-- Pending Bookings -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gradient-to-br from-yellow-500 to-amber-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fa-solid fa-clock text-xl"></i>
                </div>
                <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-full">Action Required</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Pending Requests</p>
            <h3 class="text-3xl font-bold text-gray-800">2</h3>
        </div>

        <!-- Average Rating -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fa-solid fa-star text-xl"></i>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2.5 py-1 rounded-full">Excellent</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Average Rating</p>
            <h3 class="text-3xl font-bold text-gray-800">4.8 <span class="text-base text-gray-400 font-normal">/5.0</span></h3>
        </div>
    </div>
</div>


<!-- ================= MAIN CONTENT GRID ================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    
    <!-- Quick Actions -->
    <div class="lg:col-span-2">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Actions</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Manage Listings -->
            <a href="{{ route('landlord.listings') }}"
               class="group bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-2xl shadow-lg hover:shadow-xl p-8 transition-all duration-300 hover:scale-105 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-8 -translate-y-8"></div>
                
                <div class="relative z-10">
                    <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-house text-white text-2xl"></i>
                    </div>
                    
                    <h3 class="text-white font-bold text-xl mb-2">
                        Manage Listings
                    </h3>
                    
                    <p class="text-white/80 text-sm leading-relaxed">
                        Create, edit, or remove your rental properties with ease.
                    </p>
                </div>
            </a>

            <!-- Booking Requests -->
            <a href="#"
               class="group bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-2xl shadow-lg hover:shadow-xl p-8 transition-all duration-300 hover:scale-105 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-8 -translate-y-8"></div>
                
                <div class="relative z-10">
                    <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-file-lines text-white text-2xl"></i>
                    </div>
                    
                    <h3 class="text-white font-bold text-xl mb-2">
                        Booking Requests
                    </h3>
                    
                    <p class="text-white/80 text-sm leading-relaxed">
                        Review and respond to student inquiries and requests.
                    </p>
                    
                    @if(true) {{-- Replace with actual pending count check --}}
                    <span class="absolute top-4 right-4 bg-white text-yellow-600 text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">
                        2 New
                    </span>
                    @endif
                </div>
            </a>

            <!-- Earnings -->
            <a href="#"
               class="group bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-2xl shadow-lg hover:shadow-xl p-8 transition-all duration-300 hover:scale-105 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-8 -translate-y-8"></div>
                
                <div class="relative z-10">
                    <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-wallet text-white text-2xl"></i>
                    </div>
                    
                    <h3 class="text-white font-bold text-xl mb-2">
                        Earnings
                    </h3>
                    
                    <p class="text-white/80 text-sm leading-relaxed">
                        Track your income, payments, and financial analytics.
                    </p>
                </div>
            </a>

            <!-- Profile Settings -->
            <a href="#"
               class="group bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-2xl shadow-lg hover:shadow-xl p-8 transition-all duration-300 hover:scale-105 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-8 -translate-y-8"></div>
                
                <div class="relative z-10">
                    <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-user text-white text-2xl"></i>
                    </div>
                    
                    <h3 class="text-white font-bold text-xl mb-2">
                        Profile Settings
                    </h3>
                    
                    <p class="text-white/80 text-sm leading-relaxed">
                        Update account details and notification preferences.
                    </p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity Sidebar -->
    <div class="lg:col-span-1">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Recent Activity</h2>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="space-y-5">
                <!-- Activity Item -->
                <div class="flex gap-4">
                    <div class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-calendar-check text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">New booking request</p>
                        <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- Activity Item -->
                <div class="flex gap-4">
                    <div class="bg-green-100 text-green-600 w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-star text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">New review received</p>
                        <p class="text-xs text-gray-500 mt-1">5 hours ago</p>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- Activity Item -->
                <div class="flex gap-4">
                    <div class="bg-red-100 text-red-600 w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-house text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Listing published</p>
                        <p class="text-xs text-gray-500 mt-1">1 day ago</p>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- Activity Item -->
                <div class="flex gap-4">
                    <div class="bg-purple-100 text-purple-600 w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-dollar-sign text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Payment received</p>
                        <p class="text-xs text-gray-500 mt-1">2 days ago</p>
                    </div>
                </div>
            </div>

            <a href="#" class="block mt-6 text-center text-sm font-medium text-red-500 hover:text-red-600 transition">
                View All Activity →
            </a>
        </div>

        <!-- Tips Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 p-6 mt-6">
            <div class="flex items-start gap-3">
                <div class="bg-blue-500 text-white w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Quick Tip</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Properties with detailed descriptions and quality photos receive 3x more bookings!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection