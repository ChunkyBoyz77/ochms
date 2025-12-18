<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Landlord Login | OCHMS</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white">

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    <!-- LEFT: AUTH FORM -->
    <div class="flex flex-col justify-center px-8 sm:px-16">
        
        <!-- LOGO -->
        <div class="mb-10">
            <h1 class="text-2xl font-bold text-gray-900">
                OCHMS <span class="text-sm font-normal text-gray-500">Landlord</span>
            </h1>
        </div>

       <!-- LOGIN FORM -->
        <div id="loginForm">

            <h2 class="text-3xl font-bold mb-2">Let's log you in</h2>
            <p class="text-gray-500 mb-8">Manage your properties and enquiries.</p>

            {{-- LOGIN ERROR --}}
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200
                            text-sm text-red-700">
                    Incorrect email or password.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <input type="hidden" name="role" value="landlord">

                <!-- EMAIL -->
                <div class="mb-4">
                    <label class="font-medium">Email address</label>
                    <input type="email" name="email" required
                        placeholder="Enter your email"
                        class="w-full mt-1 p-3 border rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-gray-900">
                </div>

                <!-- PASSWORD -->
                <div class="mb-2 relative">
                    <div class="flex justify-between items-center">
                        <label class="font-medium">Password</label>

                        <a href="{{ route('password.request') }}"
                        class="text-sm text-gray-900 hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <input type="password"
                        id="loginPassword"
                        name="password"
                        required
                        placeholder="Enter your password"
                        class="w-full mt-1 p-3 border rounded-lg pr-10
                                focus:outline-none focus:ring-2 focus:ring-gray-900">

                    <!-- TOGGLE -->
                    <button type="button"
                            onclick="togglePassword('loginPassword', this)"
                            class="absolute right-3 top-10 text-gray-500">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                <!-- REMEMBER ME -->
                <div class="flex items-center gap-2 mt-4">
                    <input type="checkbox"
                        name="remember"
                        id="remember"
                        class="rounded border-gray-300 text-gray-900
                                focus:ring-gray-900">

                    <label for="remember" class="text-sm text-gray-600">
                        Remember me
                    </label>
                </div>

                <!-- SUBMIT -->
                <button type="submit"
                        class="w-full mt-6 bg-gray-900 hover:bg-gray-800
                            text-white py-3 rounded-lg">
                    Login
                </button>
            </form>

            <p class="text-sm text-center mt-6">
                Don't have a landlord account?
                <button onclick="showRegister()"
                        class="text-gray-900 font-semibold">
                    Create one
                </button>
            </p>

        </div>



        <!-- REGISTER FORM -->
        <div id="registerForm" class="hidden">

            <h2 class="text-3xl font-bold mb-2">Create landlord account</h2>
            <p class="text-gray-500 mb-8">
                Start listing properties for UMPSA students.
            </p>

            <form method="POST" action="{{ route('register.landlord.store') }}">
                @csrf
                <input type="hidden" name="role" value="landlord">

                <!-- STEP 1 -->
                <div id="registerStep1">

                    <div class="mb-4">
                        <label class="font-medium">Full name</label>
                        <input type="text" name="name" required
                            class="w-full mt-1 p-3 border rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label class="font-medium">Email address</label>
                        <input type="email" name="email" required
                            class="w-full mt-1 p-3 border rounded-lg">
                    </div>

                    <!-- PHONE NUMBER -->
                    <div class="mb-6">
                        <label class="font-medium">Phone number</label>

                        <div class="flex gap-2 mt-1">
                            <select name="country_code"
                                    class="w-1/3 p-3 border rounded-lg bg-white">
                                <option value="+60">🇲🇾 +60</option>
                                <option value="+65">🇸🇬 +65</option>
                                <option value="+62">🇮🇩 +62</option>
                                <option value="+86">🇨🇳 +86</option>
                                <option value="+91">🇮🇳 +91</option>
                            </select>

                            <input type="text" name="phone_local" required
                                placeholder="Phone number"
                                class="w-2/3 p-3 border rounded-lg">
                        </div>
                    </div>


                    <button type="button"
                            onclick="goToPasswordStep()"
                            class="w-full bg-gray-900 hover:bg-gray-800 text-white py-3 rounded-lg">
                        Continue
                    </button>

                </div>

                <!-- STEP 2 -->
                <div id="registerStep2" class="hidden">

                    <!-- PASSWORD -->
                    <div class="mb-2 relative">
                        <label class="font-medium">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full mt-1 p-3 border rounded-lg pr-10"
                            oninput="checkPasswordStrength(); checkPasswordMatch();">

                        <button type="button"
                                onclick="togglePassword('password', this)"
                                class="absolute right-3 top-10 text-gray-500">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <!-- PASSWORD STATUS -->
                    <p id="passwordStatus"
                    class="text-sm mt-1 text-gray-500 hidden"></p>

                    <!-- CONFIRM PASSWORD -->
                    <div class="mb-2 relative mt-4">
                        <label class="font-medium">Confirm password</label>
                        <input type="password" id="confirmPassword"
                            name="password_confirmation" required
                            class="w-full mt-1 p-3 border rounded-lg pr-10"
                            oninput="checkPasswordMatch()">

                        <button type="button"
                                onclick="togglePassword('confirmPassword', this)"
                                class="absolute right-3 top-10 text-gray-500">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <p id="matchMessage" class="text-sm mt-1"></p>

                    <div class="flex gap-3 mt-6">
                        <button type="button"
                                onclick="backToDetailsStep()"
                                class="w-1/3 border border-gray-300 py-3 rounded-lg">
                            Back
                        </button>

                        <button type="submit"
                                class="w-2/3 bg-gray-900 hover:bg-gray-800 text-white py-3 rounded-lg">
                            Create account
                        </button>
                    </div>

                </div>
            </form>

            <p class="text-sm text-center mt-6">
                Already have an account?
                <button onclick="showLogin()"
                        class="text-gray-900 font-semibold">
                    Log in
                </button>
            </p>

        </div>
        <!-- BACK TO OCHMS (PILL STYLE) -->
        <div class="mt-10 flex justify-center">
            <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2
                    px-4 py-2 rounded-full
                    border border-gray-200
                    text-sm text-gray-500
                    hover:border-gray-300 hover:text-gray-700
                    hover:bg-gray-50
                    transition">
                <i class="fa-solid fa-angle-left text-xs"></i>
                <span>Back to OCHMS</span>
            </a>
        </div>

    </div>

    <!-- RIGHT: IMAGE -->
    <div class="hidden lg:block relative">
        <img src="{{ asset('images/become-landlord-hero.jpg') }}"
             class="absolute inset-0 w-full h-full object-cover"
             alt="Placeholder image">

        <div class="absolute inset-0 bg-black/20"></div>
    </div>

</div>

<!-- TOGGLE SCRIPT -->
<script>
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    function showRegister() {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
    }

    function showLogin() {
        registerForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
    }

    function goToPasswordStep() {
        document.getElementById('registerStep1').classList.add('hidden');
        document.getElementById('registerStep2').classList.remove('hidden');
    }

    function backToDetailsStep() {
        document.getElementById('registerStep2').classList.add('hidden');
        document.getElementById('registerStep1').classList.remove('hidden');
    }

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

        const rulesPassed =
            password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /\d/.test(password);

        status.classList.remove('hidden');

        if (rulesPassed) {
            status.textContent = 'All requirements fulfilled';
            status.className = 'text-sm mt-1 text-green-600';
        } else {
            status.textContent =
                'At least 8 characters, one uppercase letter and one number';
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

</body>
</html>
