<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OCHMS @yield('title')</title>

    <!-- Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts used by OCS -->
    <link href="https://fonts.googleapis.com/css2?family=Barrio&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barriecito&display=swap" rel="stylesheet">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<style>
.price-pin {
    position: relative;
    background: #111827;
    color: white;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transform: translateY(-6px);
}

.price-pin::after {
    content: "";
    position: absolute;
    left: 50%;
    bottom: -6px;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 6px solid #111827;
}
</style>

<body class="bg-gray-100 overflow-x-hidden">

    <!-- AUTH MODAL OVERLAY -->
    <div id="authModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[999] flex items-center justify-center">

        <!-- MODAL BOX -->
        <div id="authModalBox"
            class="bg-white rounded-2xl w-full max-w-xl mx-4 shadow-xl relative max-h-[90vh] overflow-y-auto">

            <!-- Close Button -->
            <button onclick="closeAuthModal()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-900">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <div class="p-8">

                <h2 class="text-xl font-semibold mb-6">Log in or sign up</h2>

                <!-- LOGIN FORM -->
                <div id="loginForm">
                    {{-- ONLY show login errors --}}
                    @if (session('login_error'))
                        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                            {{ session('login_error') }}
                        </div>
                    @endif


                    <form id="loginFormElement" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="font-semibold">Email</label>
                            <input type="email" name="email" required
                                class="w-full p-3 border rounded mt-1">
                        </div>

                        <div class="mb-4 relative">
                            <label class="font-semibold">Password</label>
                            <input type="password" id="loginPassword" name="password" required
                                class="w-full p-3 border rounded mt-1 pr-10">

                            <button type="button"
                                    onclick="togglePassword('loginPassword', this)"
                                    class="absolute right-3 top-10 text-gray-500">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                                <input type="checkbox" name="remember"
                                    class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                Remember me
                            </label>

                            <a href="{{ route('password.request') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-sm text-gray-900 hover:underline">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-3 rounded-lg">
                            Continue
                        </button>
                    </form>

                    <p class="text-sm text-center mt-4">
                        New here?
                        <button onclick="showRegister()"
                                class="text-gray-900 font-semibold">
                            Create an account
                        </button>
                    </p>
                </div>

                <!-- REGISTER FORM -->
                <div id="registerForm" class="hidden">

                    <form method="POST" action="{{ route('register.ocs.store') }}">
                        @csrf
                        <input type="hidden" name="role" value="ocs">

                        <div class="mb-4">
                            <label class="font-semibold">Full Name</label>
                            <input type="text" name="name" required
                                class="w-full p-3 border rounded mt-1">
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold">Student ID</label>
                            <input type="text" name="matric_id" required
                                class="w-full p-3 border rounded mt-1">
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold">Email</label>
                            <input type="email" name="register_email" required
                                class="w-full p-3 border rounded mt-1">
                        </div>

                        <div class="mb-2 relative">
                            <label class="font-semibold">Password</label>
                            <input type="password" id="registerPassword" name="password" required
                                class="w-full p-3 border rounded mt-1 pr-10"
                                oninput="checkPasswordStrength(); checkPasswordMatch();">

                            <button type="button"
                                    onclick="togglePassword('registerPassword', this)"
                                    class="absolute right-3 top-10 text-gray-500">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        <p id="passwordStatus"
                        class="text-sm mt-1 text-gray-500 hidden"></p>

                        <div class="mb-2 relative mt-4">
                            <label class="font-semibold">Confirm Password</label>
                            <input type="password" id="registerConfirmPassword"
                                name="password_confirmation" required
                                class="w-full p-3 border rounded mt-1 pr-10"
                                oninput="checkPasswordMatch()">

                            <button type="button"
                                    onclick="togglePassword('registerConfirmPassword', this)"
                                    class="absolute right-3 top-10 text-gray-500">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        <p id="matchMessage" class="text-sm mt-1"></p>

                        <button type="submit"
                                class="w-full mt-6 bg-gray-900 hover:bg-gray-800 text-white py-3 rounded-lg">
                            Sign up
                        </button>
                    </form>

                    <p class="text-sm text-center mt-4">
                        Already have an account?
                        <button onclick="showLogin()"
                                class="text-gray-900 font-semibold">
                            Log in
                        </button>
                    </p>

                </div>

            </div>
        </div>
    </div>

    {{-- Header --}}
    @include('layouts.header.ocs-header')
    
    @if (session('success'))
        <div id="flashMessage"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                    bg-green-600 text-white
                    px-6 py-3 rounded-lg shadow-lg
                    flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ERROR / REJECTION BANNER --}}
    @if (session('error'))
        <div id="flashMessage"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                    bg-red-600 text-white
                    px-6 py-3 rounded-lg shadow-lg
                    flex items-center gap-3">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Flash / Error Slot (optional future use) --}}
    @yield('alerts')

    <!-- ================= REQUEST BOOKING MODAL ================= -->
    <div id="requestBookingModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center
                bg-black bg-opacity-50">

        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

            <!-- Header -->
            <h2 class="text-lg font-semibold text-gray-800 mb-2">
                Confirm Booking Request
            </h2>

            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to request booking for this property?
                The landlord will review your request before approval.
            </p>

            <!-- Actions -->
            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeRequestBookingModal()"
                    class="px-4 py-2 rounded-lg
                        border border-gray-300
                        text-gray-700
                        hover:bg-gray-100 transition">
                    Cancel
                </button>

                <button
                    type="button"
                    onclick="confirmRequestBooking()"
                    class="px-4 py-2 rounded-lg
                        bg-gray-900 hover:bg-gray-800
                        text-white font-medium transition">
                    Yes, Request
                </button>

            </div>
        </div>
    </div>

    <form id="requestBookingForm" method="POST" style="display:none;">
        @csrf
    </form>

    {{-- Main Content --}}
    <main class="min-h-screen px-10 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    @yield('scripts')

<script
src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}"
async>
</script>

<script>
// Request Booking Modal Functions
let listingToRequest = null;

function openRequestBookingModal(listingId) {
    listingToRequest = listingId;
    const modal = document.getElementById('requestBookingModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeRequestBookingModal() {
    listingToRequest = null;
    const modal = document.getElementById('requestBookingModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function confirmRequestBooking() {
    if (!listingToRequest) return;

    const form = document.getElementById('requestBookingForm');
    if (form) {
        form.action = `/ocs/listings/${listingToRequest}/request`;
        form.submit();
    }
}

// Flash Message Auto-hide
document.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('flashMessage');
    if (!flash) return;

    setTimeout(() => {
        flash.classList.add('opacity-0', 'transition-opacity', 'duration-500');
    }, 1500);

    setTimeout(() => {
        flash.remove();
    }, 2000);
});

// Auth Modal Functions
function getScrollbarWidth() {
    return window.innerWidth - document.documentElement.clientWidth;
}

function openAuthModal() {
    const authModal = document.getElementById('authModal');
    if (!authModal) return;

    const scrollbarWidth = getScrollbarWidth();

    document.body.style.paddingRight = scrollbarWidth + 'px';
    document.body.classList.add('overflow-hidden');

    authModal.classList.remove('hidden');
    
    const profileMenu = document.getElementById('profileMenu');
    if (profileMenu) profileMenu.classList.add('hidden');
    
    const mainNav = document.getElementById('mainNav');
    if (mainNav) mainNav.classList.add('pointer-events-none');

    showLogin();
}

function closeAuthModal() {
    const authModal = document.getElementById('authModal');
    if (!authModal) return;

    document.body.classList.remove('overflow-hidden');
    document.body.style.paddingRight = '';

    authModal.classList.add('hidden');
    
    const mainNav = document.getElementById('mainNav');
    if (mainNav) mainNav.classList.remove('pointer-events-none');
}

function showRegister() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (loginForm) loginForm.classList.add('hidden');
    if (registerForm) registerForm.classList.remove('hidden');
}

function showLogin() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) registerForm.classList.add('hidden');
    if (loginForm) loginForm.classList.remove('hidden');
}

// Close auth modal when clicking outside
document.addEventListener('DOMContentLoaded', () => {
    const authModal = document.getElementById('authModal');
    if (authModal) {
        authModal.addEventListener('click', e => {
            if (e.target === authModal) closeAuthModal();
        });
    }
    
    // ONLY open auth modal if login failed
    @if (session('show_auth_modal'))
        openAuthModal();
    @endif
});

// Password Toggle
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (!input) return;
    
    const icon = btn.querySelector('i');
    if (!icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Password Strength Checker
function checkPasswordStrength() {
    const password = document.getElementById('registerPassword');
    const status = document.getElementById('passwordStatus');
    
    if (!password || !status) return;

    const rules = [
        password.value.length >= 8,
        /[A-Z]/.test(password.value),
        /\d/.test(password.value)
    ];

    status.classList.remove('hidden');

    if (rules.every(Boolean)) {
        status.textContent = 'All requirements fulfilled';
        status.className = 'text-sm mt-1 text-green-600';
    } else {
        status.textContent = 'Password must be at least 8 characters, include an uppercase letter and a number';
        status.className = 'text-sm mt-1 text-gray-500';
    }
}

// Password Match Checker
function checkPasswordMatch() {
    const pwd = document.getElementById('registerPassword');
    const confirm = document.getElementById('registerConfirmPassword');
    const msg = document.getElementById('matchMessage');
    
    if (!pwd || !confirm || !msg) return;

    if (!confirm.value) {
        msg.textContent = '';
        return;
    }

    if (pwd.value === confirm.value) {
        msg.textContent = 'Passwords match';
        msg.className = 'text-sm text-green-600';
    } else {
        msg.textContent = 'Passwords do not match';
        msg.className = 'text-sm text-red-500';
    }
}

// Hidden Admin Login
document.addEventListener('keydown', function (e) {
    if (e.altKey) {
        const adminBtn = document.getElementById('adminLoginBtn');
        if (adminBtn) adminBtn.classList.remove('hidden');
    }
});

document.addEventListener('keyup', function () {
    const adminBtn = document.getElementById('adminLoginBtn');
    if (adminBtn) adminBtn.classList.add('hidden');
});
</script>

</body>
</html>