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


<nav class="sticky top-0 w-full bg-purple-500 backdrop-blur-md shadow-md
            py-4 px-10 flex items-center justify-between z-50">

    <!-- LEFT SECTION -->
    <div class="flex items-center space-x-8">
        <img src="{{ asset('images/umpsa-logo.png') }}" class="w-10" alt="Logo">

        
            <a href="{{ route('admin.dashboard') }}"
               class="text-white font-medium hover:opacity-80">
                Dashboard
            </a>

            <a href="#"
               class="text-white font-medium hover:opacity-80">
                Landlord Screening
            </a>

            <a href="#"
               class="text-white font-medium hover:opacity-80">
                Manage UMPSA Resources
            </a>

    </div>

    <!-- RIGHT SECTION -->
    <div class="flex items-center space-x-4">

        <!-- Notification Bell -->
        <button class="relative w-11 h-11 rounded-full
                       bg-white/20 hover:bg-white/30
                       flex items-center justify-center transition">

            <i class="fa-solid fa-bell text-white text-lg"></i>

            {{-- unread indicator --}}
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-600 rounded-full"></span>
        </button>

        <!-- Profile Button -->
        <div class="relative">
            <button id="profileMenuBtn"
                class="flex items-center justify-center
                       w-11 h-11 rounded-full
                       bg-white/20 hover:bg-white/30
                       transition">

                {{-- INITIALS AVATAR (same as OCS) --}}
                <span class="text-white font-semibold">
                    {{ $initials }}
                </span>
            </button>

            <!-- DROPDOWN -->
            <div id="profileMenu"
                class="hidden absolute right-0 mt-3
                       bg-white shadow-lg rounded-xl
                       w-60 py-2 z-50">

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 hover:bg-gray-100">
                    Profile
                </a>

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

