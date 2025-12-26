<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>OCS Landing Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Barrio&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barriecito&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200
                            text-sm text-red-700">
                    Incorrect email or password.
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

                <!-- REMEMBER ME + FORGOT PASSWORD -->
                <div class="flex items-center justify-between mb-4">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        Remember me
                    </label>

                    <a href="{{ route('password.request') }}"
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


                    <!-- PASSWORD -->
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

                    <!-- PASSWORD STATUS -->
                    <p id="passwordStatus"
                       class="text-sm mt-1 text-gray-500 hidden"></p>

                    <!-- CONFIRM PASSWORD -->
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



    @include('layouts.header.ocs-header')
    <!-- HERO SECTION -->
    <div class="relative w-full h-screen bg-cover bg-center shadow"
        style="background-image: url('{{ asset('images/ocs-hero-bg.jpg') }}')">


        <div class="absolute inset-0 bg-black/40 "></div>

        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-6">
            <h1 class="text-5xl sm:text-7xl lg:text-8xl font-semibold font-[Barrio] drop-shadow-xl">OCHMS</h1>
            <p class="mt-2 font-[Barriecito] text-2xl">Off-Campus Living Simplified for UMPSA.</p>

        <!-- Search Bar -->
        <div class="mt-6 w-full max-w-xl">
            <div class="bg-white rounded-full px-5 py-3 shadow flex items-center">

                <input type="text" placeholder="Search keywords..."
                    class="flex-1 bg-transparent text-gray-700 placeholder-gray-400 text-sm 
                            focus:outline-none focus:ring-0 focus:border-0 border-0 shadow-none">

                <i class="fa-solid fa-magnifying-glass text-gray-500 text-xl"></i>

            </div>
        </div>


            <!-- Badges -->
            <div class="flex space-x-4 mt-4 text-sm">

                <div class="flex items-center space-x-2 bg-black/30 backdrop-blur-md border border-white/20
                            px-4 py-2 rounded-full shadow text-white">
                    <i class="fa-solid fa-circle-check text-green-400"></i>
                    <span>100% Verified Listings</span>
                    <i class="fa-solid fa-circle-check text-green-400"></i>
                    <span>JHEPA Certified Resources</span>
                </div>

            </div>

        </div>
    </div>

    <!-- HOUSING SECTION -->
    <div class="px-10 mt-10">
        <h2 class="text-xl font-bold mb-4">Book Your Housing Now</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
            @foreach (['Taman Mentiga Jaya', 'Taman PLKN', 'Taman Beruas Baru', 'Kampung Beruas'] as $taman)
            
            <div class="relative h-80 rounded-xl overflow-hidden shadow-sm cursor-pointer group">

                <!-- Background image -->
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}"
                    class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition duration-500">

                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition"></div>

                <!-- Text -->
                <div class="absolute bottom-0 left-0 w-full p-4 text-center">
                    <h3 class="text-white text-center font-semibold text-2xl drop-shadow">
                        {{ $taman }}
                    </h3>
                </div>

            </div>

            @endforeach
        </div>
    </div>


    <!-- UMPSA RESOURCES SECTION -->
    <div class="px-10 mt-14">
        <h2 class="text-xl font-bold mb-4">What UMPSA Can Do For You</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">

            @foreach (['UMPSA Campus Pantry','UMPSA PKU Hotline','UMPSA Sejahtera Center','UMPSA OKU Center'] as $service)
            <div class="relative h-80 rounded-xl overflow-hidden shadow-sm cursor-pointer group">
                <!-- Background image -->
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}"
                    class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition duration-500">

                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition"></div>

                <!-- Text -->
                <div class="absolute bottom-0 left-0 w-full p-4 text-center">
                    <h3 class="text-white text-center font-semibold text-2xl drop-shadow">
                        {{ $service }}
                    </h3>
                </div>
            </div>
            @endforeach

        </div>
    </div>

@include('layouts.footer')
</body>
</html>
