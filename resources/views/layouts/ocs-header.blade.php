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
        class="fixed top-0 left-0 w-full z-50 px-10 py-4 flex items-center justify-between transition-all duration-200 bg-transparent">

        <!-- LEFT SECTION -->
        <div class="flex items-center space-x-10">
            <img src="{{ asset('images/umpsa-logo.png') }}" id="navLogo" class="w-10 drop-shadow-md" alt="Logo">

            <a class="nav-link font-regular text-white drop-shadow text-lg hover:text-black-200 cursor-pointer">Home</a>
            <a class="nav-link font-regular text-white drop-shadow text-lg hover:text-black-200 cursor-pointer">Rentals</a>
            <a class="nav-link font-regular text-white drop-shadow text-lg hover:text-black-200 cursor-pointer">UMPSA Resources</a>
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
                            {{-- Avatar with initials --}}
                            <span id="initials" class="text-white font-semibold">
                                {{ $initials }}
                            </span>
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
                    class="hidden absolute right-0 mt-3 bg-white shadow-lg rounded-xl w-44 py-2 z-50">

                    @guest
                        <button onclick="openAuthModal()"
                                class="block px-4 py-2 hover:bg-gray-100 w-full text-left">
                            Log in or Sign up
                        </button>
                        <a href="{{ route('landlord.auth') }}" class="block px-4 py-2 hover:bg-gray-100">OCHMSLord</a>
                    @endguest


                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Dashboard</a>

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


    </script>