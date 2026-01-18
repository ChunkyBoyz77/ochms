@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<!-- ================= HERO ================= -->
<section class="relative bg-gradient-to-br from-indigo-900 via-purple-800 to-indigo-900 rounded-3xl overflow-hidden mb-8 shadow-2xl">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 via-transparent to-indigo-600/10"></div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 px-8 lg:px-12 py-12 lg:py-16">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-purple-500/20 backdrop-blur-sm border border-purple-500/30 rounded-full px-4 py-2 mb-4">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-white text-sm font-medium">Admin Dashboard</span>
            </div>

            <h1 class="text-white text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">
                Welcome back, <span class="text-purple-300">{{ Auth::user()->name }}</span>
            </h1>

            <p class="text-gray-300 text-base lg:text-lg leading-relaxed mb-8 max-w-2xl">
                Monitor platform activity, manage verifications, and oversee UMPSA's off-campus housing ecosystem.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.verifications.index') }}"
                   class="inline-flex items-center gap-2 bg-purple-500 hover:bg-purple-600 text-white font-semibold px-6 py-3.5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-purple-500/50 hover:scale-105">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Verify Landlords</span>
                </a>

                <a href="{{ route('admin.resources.index') }}"
                   class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-semibold px-6 py-3.5 rounded-xl hover:bg-white/20 transition-all duration-300">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Manage Resources</span>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ================= STATS OVERVIEW ================= -->
<div class="mb-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Platform Overview</h2>
        <span class="text-sm text-gray-500">Last updated: {{ now()->format('M d, Y') }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Landlords -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">Landlords</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Total Landlords</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalLandlords ?? 0 }}</h3>
        </div>

        <!-- Total Students -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fa-solid fa-user-graduate text-xl"></i>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Students</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Registered Students</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalStudents ?? 0 }}</h3>
        </div>

        <!-- Total Properties -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fa-solid fa-house text-xl"></i>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2.5 py-1 rounded-full">Properties</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Total Listings</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalProperties ?? 0 }}</h3>
        </div>

        <!-- Pending Verifications -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white p-3 rounded-xl shadow-lg">
                    <i class="fa-solid fa-clock text-xl"></i>
                </div>
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">Pending</span>
            </div>
            <p class="text-sm text-gray-500 mb-1">Pending Verifications</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $pendingVerifications ?? 0 }}</h3>
        </div>
    </div>
</div>


<!-- ================= MAIN CONTENT GRID ================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    
    <!-- Left Column - Quick Actions & Chart -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Quick Actions -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Actions</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Landlord Screening -->
                <a href="{{ route('admin.verifications.index') }}"
                   class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 rounded-2xl shadow-lg hover:shadow-xl p-8 transition-all duration-300 hover:scale-105 relative overflow-hidden">
                    
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-8 -translate-y-8"></div>
                    
                    <div class="relative z-10">
                        <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                            <i class="fa-solid fa-user-check text-white text-2xl"></i>
                        </div>
                        
                        <h3 class="text-white font-bold text-xl mb-2">
                            Landlord Screening
                        </h3>
                        
                        <p class="text-white/80 text-sm leading-relaxed">
                            Review and approve landlord verification requests.
                        </p>

                        @if(($pendingVerifications ?? 0) > 0)
                        <span class="absolute top-4 right-4 bg-white text-amber-600 text-xs font-bold px-2.5 py-1 rounded-full shadow-lg">
                           {{ $pendingVerifications }} Pending
                        </span>
                        @endif
                    </div>
                </a>

                <!-- Manage Resources -->
                <a href="{{ route('admin.resources.index') }}" 
                   class="group bg-gradient-to-br from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 rounded-2xl shadow-lg hover:shadow-xl p-8 transition-all duration-300 hover:scale-105 relative overflow-hidden">
                    
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-8 -translate-y-8"></div>
                    
                    <div class="relative z-10">
                        <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                            <i class="fa-solid fa-building-columns text-white text-2xl"></i>
                        </div>
                        
                        <h3 class="text-white font-bold text-xl mb-2">
                            UMPSA Resources
                        </h3>
                        
                        <p class="text-white/80 text-sm leading-relaxed">
                            Manage student support services and resources.
                        </p>
                    </div>
                </a>

                <!-- View All Listings -->
                <a href="{{ route('admin.properties.map') }}"  
                   class="group bg-gradient-to-br from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-2xl shadow-lg hover:shadow-xl p-8 transition-all duration-300 hover:scale-105 relative overflow-hidden">
                    
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-8 -translate-y-8"></div>
                    
                    <div class="relative z-10">
                        <div class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-xl flex items-center justify-center mb-4">
                            <i class="fa-solid fa-list text-white text-2xl"></i>
                        </div>
                        
                        <h3 class="text-white font-bold text-xl mb-2">
                            All Listings
                        </h3>
                        
                        <p class="text-white/80 text-sm leading-relaxed">
                            Monitor and moderate property listings.
                        </p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Properties Breakdown Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Properties by Type</h3>
            
            <div class="grid grid-cols-3 gap-4">
                @if(isset($propertiesByType) && $propertiesByType->count() > 0)
                    @foreach($propertiesByType as $type)
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ $type->count }}</div>
                        <div class="text-sm text-gray-600">{{ $type->property_type }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-3 text-center py-8 text-gray-500">
                        No properties data available
                    </div>
                @endif
            </div>

            <!-- Additional Stats -->
            <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800 mb-1">{{ $occupiedListings ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Occupied Properties</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800 mb-1">{{ $averageRating ?? '—' }}</div>
                    <div class="text-sm text-gray-600">Avg Rating</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Activity & Announcements -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Recent Activity -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Recent Activity</h2>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-5">
                    @forelse($activities ?? [] as $activity)
                        <div class="flex gap-4">
                            {{-- ICON --}}
                            <div
                                class="
                                    w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $activity['type'] === 'verification'
                                        ? 'bg-purple-100 text-purple-600'
                                        : 'bg-blue-100 text-blue-600' }}
                                ">
                                <i class="fa-solid
                                    {{ $activity['type'] === 'verification'
                                        ? 'fa-user-check'
                                        : 'fa-file-lines' }}">
                                </i>
                            </div>

                            {{-- CONTENT --}}
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $activity['title'] }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $activity['subtitle'] }}
                                    · {{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        @if(!$loop->last)
                        <div class="border-t border-gray-100"></div>
                        @endif

                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">
                            No recent activity
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- System Health Card -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100 p-6">
            <div class="flex items-start gap-3">
                <div class="bg-green-500 text-white w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-heartbeat"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">System Status</h4>
                    <p class="text-sm text-gray-600 leading-relaxed mb-3">
                        All systems operational. Platform running smoothly.
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-xs text-green-700 font-medium">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection