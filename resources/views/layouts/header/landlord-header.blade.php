@php
    $initials = '';
    if (auth()->check()) {
        $names = explode(' ', auth()->user()->name);
        $initials = strtoupper(
            substr($names[0], 0, 1) .
            (isset($names[1]) ? substr($names[1], 0, 1) : '')
        );
    }
@endphp


<nav class="sticky top-0 w-full bg-red-500 backdrop-blur-md shadow-md
            py-4 px-10 flex items-center justify-between z-50">

    <!-- LEFT SECTION -->
    <div class="flex items-center space-x-8">
        <img src="{{ asset('images/umpsa-logo.png') }}" class="w-10" alt="Logo">

        {{-- VERIFIED LANDLORD ONLY --}}
        @if(
            auth()->check() &&
            auth()->user()->landlord &&
            auth()->user()->landlord->screening_status === 'approved'
        )
            <a href="{{ route('landlord.dashboard') }}"
               class="text-white font-medium hover:opacity-80">
                Dashboard
            </a>

            <a href="{{ route('landlord.listings') }}"
               class="text-white font-medium hover:opacity-80">
                Listings
            </a>

            <a href="{{ route('landlord.booking.requests') }}"
               class="text-white font-medium hover:opacity-80">
                Booking Requests
            </a>
        
        @else
        <a href="{{ route('landlord.dashboard') }}"
               class="text-white font-medium hover:opacity-80">
                Dashboard
        </a>
        @endif
    </div>

    <!-- RIGHT SECTION -->
    <div class="flex items-center space-x-4">

        <!-- Profile Button -->
        <div class="relative">
            <button id="profileMenuBtn"
                class="flex items-center justify-center
                       w-11 h-11 rounded-full
                       bg-white/20 hover:bg-white/30
                       transition">

                @if(auth()->user()->profile_pic)
                        {{-- PROFILE IMAGE --}}
                        <img
                            src="{{ asset('storage/' . auth()->user()->profile_pic) }}"
                            alt="Profile picture"
                            class="w-full h-full object-cover rounded-full">
                    @else
                        {{-- INITIALS FALLBACK --}}
                        <span class="text-white font-semibold">
                            {{ $initials }}
                        </span>
                    @endif
            </button>

            <!-- DROPDOWN -->
            <div id="profileMenu"
                class="hidden absolute right-0 mt-3
                       bg-white shadow-lg rounded-xl
                       w-80 py-2 z-50">
                
                @if(
                auth()->check() &&
                auth()->user()->landlord &&
                auth()->user()->landlord->screening_status === 'approved'
                )

                <a href="{{ route('profile.edit') }}">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="font-medium text-gray-900">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </a>
                
                <a href="{{ route('landlord.dashboard') }}"
                class="block px-4 py-2 hover:bg-gray-100 text-gray-800">
                    Dashboard
                </a>
                <a href="{{ route('landlord.listings') }}"
                class="block px-4 py-2 hover:bg-gray-100 text-gray-800">
                    Listings
                    </span>
                </a>

                <a href="{{ route('landlord.booking.requests') }}" class="block px-4 py-2 hover:bg-gray-100">Booking Request</a>
                @endif

                {{-- NON-VERIFIED LANDLORD CTA --}}
                @if(
                    auth()->check() &&
                    auth()->user()->landlord &&
                    auth()->user()->landlord->screening_status !== 'approved' &&
                    auth()->user()->landlord->screening_submitted_at === null
                )
                    <a href="{{ route('landlord.dashboard') }}"
                       class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Complete Verification
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('profileMenuBtn');
    const menu = document.getElementById('profileMenu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
});
</script>

