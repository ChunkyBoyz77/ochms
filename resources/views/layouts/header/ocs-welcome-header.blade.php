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
                        <input type="email" name="email" required
                               class="w-full p-3 border rounded mt-1">
                    </div>

                    <div class="mb-2 relative">
                        <label class="font-semibold">Password</label>
                        <input type="password" id="password" name="password" required
                               class="w-full p-3 border rounded mt-1 pr-10"
                               oninput="checkPasswordStrength(); checkPasswordMatch();">

                        <button type="button"
                                onclick="togglePassword('password', this)"
                                class="absolute right-3 top-10 text-gray-500">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <p id="passwordStatus"
                       class="text-sm mt-1 text-gray-500 hidden"></p>

                    <div class="mb-2 relative mt-4">
                        <label class="font-semibold">Confirm Password</label>
                        <input type="password" id="confirmPassword"
                               name="password_confirmation" required
                               class="w-full p-3 border rounded mt-1 pr-10"
                               oninput="checkPasswordMatch()">

                        <button type="button"
                                onclick="togglePassword('confirmPassword', this)"
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

 
 <nav id="mainNav"
        class="fixed top-0 left-0 w-full z-50 px-10 py-4 flex items-center justify-between transition-all duration-200 bg-transparent">

        <!-- LEFT SECTION -->
        <div class="flex items-center space-x-10">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/umpsa-logo.png') }}"
                    id="navLogo"
                    class="w-10 drop-shadow-md cursor-pointer"
                    alt="Logo">
            </a>

            <a href="{{ route('home') }}" class="nav-link font-regular text-white drop-shadow text-lg hover:text-black-200 cursor-pointer">Home</a>
            <a href="{{ route('ocs.listings.browse') }}" class="nav-link font-regular text-white drop-shadow text-lg hover:text-black-200 cursor-pointer">Rentals</a>
            <a href="{{ route('resources.ocs.index') }}" class="nav-link font-regular text-white drop-shadow text-lg hover:text-black-200 cursor-pointer">UMPSA Resources</a>
        </div>


        <!-- RIGHT SECTION -->
        <div class="flex items-center space-x-4">

            {{-- Become a landlord (hide for OCS) --}}
            @guest
                <a href="{{ route('landlord.become') }}" class="nav-link font-semibold text-white drop-shadow text-lg cursor-pointer">
                    Become a landlord
                </a>
            @endguest

            @auth
                @if(auth()->user()->role !== 'ocs')
                    <a class="nav-link font-semibold text-white drop-shadow text-lg cursor-pointer">
                        Become a landlord
                    </a>
                @endif
            @endauth

            <!-- Profile Button -->
            <div class="relative">
                <button id="profileButton"
                    class="flex items-center justify-center w-11 h-11 rounded-full transition
                        bg-white/20 hover:bg-white/30 backdrop-blur">

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
                            <i id="hamburgerIcon" class="fa-solid fa-bars text-white text-xl"></i>
                        @endif
                    @endauth

                    @guest
                        <i id="hamburgerIcon" class="fa-solid fa-bars text-white text-xl"></i>
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
                            </span>
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

    

    <!-- PROFILE DROPDOWN SCRIPT -->
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

        document.addEventListener('scroll', function () {

        const nav = document.getElementById('mainNav');
        const links = document.querySelectorAll('.nav-link');
        const profileBtn = document.getElementById('profileButton');
        const icon = document.getElementById('hamburgerIcon');
        const initials = document.getElementById('initials');

        if (window.scrollY > 52) {

            // Navbar background
            nav.classList.remove('bg-transparent');
            nav.classList.add('bg-gray-100/90', 'shadow-md');

            // Links to black
            links.forEach(link => {
                link.classList.remove('text-white', 'drop-shadow');
                link.classList.add('text-gray-900');
            });

            // Icon to black
            if (icon) {
                icon.classList.remove('text-white');
                icon.classList.add('text-gray-900');
            }

            // Change the ROUND BUTTON background (becomes light gray like Airbnb)
            profileBtn.classList.remove('bg-white/20', 'hover:bg-white/30', 'backdrop-blur');
            profileBtn.classList.add('bg-gray-300', 'hover:bg-gray-500');

            if (initials) {
                initials.classList.remove('text-white');
                initials.classList.add('text-gray-900');
            }

        } else {

            // Navbar back to transparent
            nav.classList.add('bg-transparent');
            nav.classList.remove('bg-gray-100/90', 'shadow-md');

            // Links back to white
            links.forEach(link => {
                link.classList.add('text-white', 'drop-shadow');
                link.classList.remove('text-gray-900');
            });

            if (icon) {
                icon.classList.add('text-white');
                icon.classList.remove('text-gray-900');
            }
            
            // Restore the round button background (glass effect)
            profileBtn.classList.remove('bg-gray-200', 'hover:bg-gray-300');
            profileBtn.classList.add('bg-white/20', 'hover:bg-white/30', 'backdrop-blur');

            if (initials) {
                initials.classList.add('text-white');
                initials.classList.remove('text-gray-900');
            }

        }
    });

    const authModal = document.getElementById('authModal');
    const modalBox = document.getElementById('authModalBox');

    function getScrollbarWidth() {
        return window.innerWidth - document.documentElement.clientWidth;
    }


   function openAuthModal() {
        const scrollbarWidth = getScrollbarWidth();

        document.body.style.paddingRight = scrollbarWidth + 'px';
        document.body.classList.add('overflow-hidden');

        authModal.classList.remove('hidden');
        document.getElementById('profileMenu')?.classList.add('hidden');
        document.getElementById('mainNav')?.classList.add('pointer-events-none');

        showLogin();
    }

    function closeAuthModal() {
        document.body.classList.remove('overflow-hidden');
        document.body.style.paddingRight = '';

        authModal.classList.add('hidden');
        document.getElementById('mainNav')?.classList.remove('pointer-events-none');
    }


    function showRegister() {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
    }

    function showLogin() {
        registerForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
    }

    authModal.addEventListener('click', e => {
        if (e.target === authModal) closeAuthModal();
    });


    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function checkPasswordStrength() {
        const password = document.getElementById('password').value;
        const status = document.getElementById('passwordStatus');

        const rules = [
            password.length >= 8,
            /[A-Z]/.test(password),
            /\d/.test(password)
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

    function checkPasswordMatch() {
        const pwd = document.getElementById('password').value;
        const confirm = document.getElementById('confirmPassword').value;
        const msg = document.getElementById('matchMessage');

        if (!confirm) {
            msg.textContent = '';
            return;
        }

        if (pwd === confirm) {
            msg.textContent = 'Passwords match';
            msg.className = 'text-sm text-green-600';
        } else {
            msg.textContent = 'Passwords do not match';
            msg.className = 'text-sm text-red-500';
        }
    }

    //Hidden Admin Login
    document.addEventListener('keydown', function (e) {
        if (e.altKey) {
            document.getElementById('adminLoginBtn')?.classList.remove('hidden');
        }
    });

    document.addEventListener('keyup', function () {
        document.getElementById('adminLoginBtn')?.classList.add('hidden');
    });

    </script>

    @if (session('show_auth_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            openAuthModal();
        });
    </script>
    @endif
