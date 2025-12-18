<!-- Landlord Header (Solid, Non-sticky) -->
<nav class="sticky top-0 w-full bg-red-500 backdrop-blur-md shadow-md py-4 px-10 flex items-center justify-between">

    <!-- Left Section -->
    <div class="flex items-center space-x-6">
        <img src="{{ asset('images/umpsa-logo.png') }}" class="w-10" alt="Logo">

        <a href="{{ route('landlord.dashboard') }}" 
           class="font-medium text-white hover:text-white-900">
            Dashboard
        </a>

        <a href="{{ route('landlord.listings') }}" 
           class="font-medium text-white hover:text-white-900">
            Listings
        </a>

        <a href="{{ route('landlord.bookings') }}" 
           class="font-medium text-white hover:text-white-900">
            Booking Requests
        </a>
    </div>

    <!-- Right Section: Profile dropdown -->
    <div class="relative">
        <button id="profileMenuBtn" 
                class="flex items-center space-x-2 cursor-pointer">

            <i class="fa-solid fa-user-circle text-3xl text-white"></i>
            <span class="font-medium text-white">{{ Auth::user()->name ?? 'User' }}</span>
        </button>

        <!-- Dropdown -->
        <div id="profileMenu"
             class="hidden absolute right-0 mt-2 bg-white shadow-lg rounded-md w-44 py-2 z-50">

            <a href="#" class="block px-4 py-2 hover:bg-gray-100">
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
