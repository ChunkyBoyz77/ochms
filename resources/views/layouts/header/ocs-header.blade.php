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

<nav id="mainNav"
    class="sticky top-0 left-0 w-full z-50 px-10 py-4 flex items-center justify-between transition-all duration-200 bg-white shadow-md">

    <!-- LEFT SECTION -->
    <div class="flex items-center space-x-10">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/umpsa-logo.png') }}"
                id="navLogo"
                class="w-10 drop-shadow-md cursor-pointer"
                alt="Logo">
        </a>

        <a href="{{ route('home') }}" class="nav-link font-regular text-gray drop-shadow text-lg hover:text-black-200 cursor-pointer">Home</a>
        <a href="{{ route('ocs.listings.browse') }}" class="nav-link font-regular text-gray drop-shadow text-lg hover:text-black-200 cursor-pointer">Rentals</a>
        <a href="{{ route('resources.ocs.index') }}" class="nav-link font-regular text-gray drop-shadow text-lg hover:text-black-200 cursor-pointer">UMPSA Resources</a>
    </div>


    <!-- RIGHT SECTION -->
    <div class="flex items-center space-x-4">

        {{-- Become a landlord (hide for OCS) --}}
        @guest
            <a href="{{ route('landlord.become') }}" class="nav-link font-semibold text-gray drop-shadow text-lg cursor-pointer">
                Become a landlord
            </a>
        @endguest

        @auth
            @if(auth()->user()->role !== 'ocs')
                <a class="nav-link font-semibold text-gray drop-shadow text-lg cursor-pointer">
                    Become a landlord
                </a>
            @endif
        @endauth

        <!-- Profile Button -->
        <div class="relative">
            <button id="profileButton"
                class="flex items-center justify-center w-11 h-11 rounded-full transition
                    bg-gray-100 hover:bg-gray-200 backdrop-blur">

                @auth
                    @if(auth()->user()->role === 'ocs')
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
                    @else
                        {{-- Hamburger for non-OCS --}}
                        <i id="hamburgerIcon" class="fa-solid fa-bars text-gray text-xl"></i>
                    @endif
                @endauth

                @guest
                    <i id="hamburgerIcon" class="fa-solid fa-bars text-gray text-xl"></i>
                @endguest
            </button>

            <!-- DROPDOWN MENU -->
            <div id="profileMenu"
                class="hidden absolute right-0 mt-3 bg-white shadow-lg rounded-xl w-80 py-2 z-50">

                @guest
                    <button onclick="openAuthModal()"
                            class="block px-4 py-2 hover:bg-gray-100 w-full text-left">
                        Log in or Sign up
                    </button>
                    
                    <a href="{{ route('ocs.listings.browse') }}"
                    class="block px-4 py-2 hover:bg-gray-100 text-gray-800">
                        Rentals
                        </span>
                    </a>
                    <a href="{{ route('resources.ocs.index') }}"
                    class="block px-4 py-2 hover:bg-gray-100 text-gray-800">
                        UMPSA Resources
                        </span>
                    </a>
                    <a href="{{ route('landlord.auth') }}"
                    class="block px-4 py-2 hover:bg-gray-100 text-red-500">
                        OCHMS
                        <span class="ml-1 align-baseline text-xs text-gray-500 relative top-1">
                            Landlord
                        </span>
                    </a>

                    <!-- Hidden Admin Login -->
                    <button id="adminLoginBtn"
                            onclick="window.location='{{ route('admin.login') }}'"
                            class="hidden px-4 py-2 text-left text-sm text-gray-500 hover:bg-gray-100 w-full">
                        Admin Login
                    </button>
                @endguest


                @auth
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
                  
                    <a href="{{ route('ocs.listings.browse') }}"
                    class="block px-4 py-2 hover:bg-gray-100 text-gray-800">
                        Rentals
                    </a>
                    <a href="{{ route('resources.ocs.index') }}"
                    class="block px-4 py-2 hover:bg-gray-100 text-gray-800">
                        UMPSA Resources
                        </span>
                    </a>

                    <a href="{{ route('ocs.bookings.index') }}" class="block px-4 py-2 hover:bg-gray-100">Track Booking</a>

                    

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                @endauth

            </div>
        </div>

    </div>

</nav>


{{-- DROPDOWN SCRIPT (ONLY THIS JS IS NEEDED) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('profileButton');
            const menu = document.getElementById('profileMenu');

            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });

            document.addEventListener('click', (event) => {
                if (!btn.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
   
</script>