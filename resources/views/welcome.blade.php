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

@include('layouts.header.ocs-welcome-header')
<!-- HERO SECTION -->
<div class="relative w-full h-screen bg-cover bg-center shadow"
     style="background-image: url('{{ asset('images/ocs-hero-bg.jpg') }}')">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-6">
        <h1 class="text-5xl sm:text-7xl lg:text-8xl font-semibold font-[Barrio] drop-shadow-xl">OCHMS</h1>
        <p class="mt-2 font-[Barriecito] text-2xl">
            Off-Campus Living Simplified for UMPSA.
        </p>
        <div class="mt-6 w-full max-w-xl">
            <form method="GET"
                action="{{ route('ocs.listings.browse') }}"
                class="bg-white rounded-full px-5 py-3 shadow
                        flex items-center w-full gap-3
                        focus-within:ring-2
                        focus-within:ring-gray-900
                        transition">

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search keywords..."
                    class="flex-1 bg-transparent text-gray-700
                        placeholder-gray-400 text-sm
                        border-0
                        focus:outline-none
                        focus:ring-0
                        focus:border-transparent">

                <button type="submit"
                        class="text-gray-500 hover:text-gray-800 transition">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </button>
            </form>
        </div>


        <div class="flex space-x-4 mt-4 text-sm">
            <div class="flex items-center space-x-2 bg-black/30 backdrop-blur-md
                        border border-white/20 px-4 py-2 rounded-full shadow">
                <i class="fa-solid fa-circle-check text-green-400"></i>
                <span>100% Verified Listings</span>
                <i class="fa-solid fa-circle-check text-green-400"></i>
                <span>JHEPA Certified Resources</span>
            </div>
        </div>
    </div>
</div>

<!-- HOUSING SECTION -->
<div class="relative px-6 md:px-10 py-24 bg-gradient-to-b from-white via-gray-50 to-white">

    <!-- Decorative Background Blobs -->
    <div class="pointer-events-none absolute inset-0 overflow-hidden">

        <!-- BIG / FAR BACK -->
        <div class="absolute -top-40 -left-40 w-[34rem] h-[34rem]
                    bg-indigo-500/30 rounded-full blur-[70px]
                    animate-[drift-a_42s_ease-in-out_infinite]"></div>

        <div class="absolute -top-32 right-1/3 w-[30rem] h-[30rem]
                    bg-rose-500/30 rounded-full blur-[70px]
                    animate-[drift-b_38s_ease-in-out_infinite]"></div>

        <!-- MEDIUM / MID LAYER -->
        <div class="absolute top-24 -right-32 w-[26rem] h-[26rem]
                    bg-amber-500/35 rounded-full blur-[60px]
                    animate-[drift-c_34s_ease-in-out_infinite]"></div>

        <div class="absolute top-1/3 left-20 w-[24rem] h-[24rem]
                    bg-emerald-500/35 rounded-full blur-[60px]
                    animate-[drift-a_30s_ease-in-out_infinite]"></div>

        <div class="absolute bottom-24 right-1/4 w-[22rem] h-[22rem]
                    bg-sky-500/35 rounded-full blur-[55px]
                    animate-[drift-b_28s_ease-in-out_infinite]"></div>

        <!-- SMALL / NEAR LAYER -->
        <div class="absolute bottom-10 left-1/4 w-[18rem] h-[18rem]
                    bg-violet-500/40 rounded-full blur-[45px]
                    animate-[drift-c_22s_ease-in-out_infinite]"></div>

        <div class="absolute top-1/2 right-12 w-[16rem] h-[16rem]
                    bg-pink-500/40 rounded-full blur-[40px]
                    animate-[drift-a_20s_ease-in-out_infinite]"></div>

        <div class="absolute bottom-1/3 left-1/2 w-[14rem] h-[14rem]
                    bg-teal-400/40 rounded-full blur-[40px]
                    animate-[drift-b_18s_ease-in-out_infinite]"></div>

    </div>

    <div class="relative">

        <!-- Section Header -->
        <div class="text-center mb-20">
            <span class="inline-block mb-6 px-4 py-1 rounded-full bg-gray-100 font-semibold text-gray-700">
                Popular Areas Near UMPSA
            </span>

            <h2 class="text-4xl md:text-5xl font-bold mb-5">
                Book Your Perfect Student Housing
            </h2>

            <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                Browse trusted student housing options located strategically around UMPSA,
                complete with verified landlords and transparent pricing.
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            @php use Illuminate\Support\Str; @endphp
            @foreach ([
                ['name' => 'Taman Mentiga Jaya', 'distance' => '6.9 km', 'price' => 'From RM 350'],
                ['name' => 'Taman LKNP', 'distance' => '7.2 km', 'price' => 'From RM 300'],
                ['name' => 'Taman Beruas Baru', 'distance' => '4.5 km', 'price' => 'From RM 380'],
                ['name' => 'Kampung Beruas', 'distance' => '1.8 km', 'price' => 'From RM 280'],
            ] as $area)

            <a href="{{ route('ocs.listings.byArea', [
                'area' => Str::slug($area['name']) ]) }}"
               class="group relative h-[22rem] rounded-2xl overflow-hidden
                      shadow-lg hover:shadow-2xl transition-all duration-500">

                <!-- Image -->
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}"
                     class="absolute inset-0 w-full h-full object-cover
                            group-hover:scale-110 transition duration-700">

                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t
                            from-black/80 via-black/40 to-transparent"></div>

                <!-- Badges -->
                <div class="absolute top-4 left-4 flex gap-2">
                    <span class="px-3 py-1 text-xs rounded-full bg-white/90 font-semibold">
                        {{ $area['distance'] }}
                    </span>
                </div>

                <!-- Content -->
                <div class="absolute bottom-0 inset-x-0 p-6 space-y-3">
                    <h3 class="text-white text-2xl font-bold">
                        {{ $area['name'] }}
                    </h3>

                    <div class="flex items-center justify-between text-sm text-white/90">
                        <span>{{ $area['price'] }}</span>
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-house"></i> Listings
                        </span>
                    </div>

                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        <span class="text-sm font-semibold text-white">Explore Area</span>
                        <i class="fa-solid fa-arrow-right text-white text-sm"></i>
                    </div>
                </div>

            </a>
            @endforeach
        </div>

        <!-- CTA -->
        <div class="flex justify-center mt-20">
            <a href="{{ route('ocs.listings.browse') }}"
               class="inline-flex items-center gap-3
                      bg-gray-900 hover:bg-gray-800
                      px-10 py-4 rounded-xl
                      text-white font-semibold text-lg
                      shadow-xl transition">
                View All Rental Listing
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

    </div>
</div>



<!-- UMPSA RESOURCES -->
<div class="relative px-6 md:px-10 py-24 bg-gray-900 text-white">

    <!-- Decorative Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black"></div>

    <div class="relative max-w-7xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-20">
            <span class="inline-block mb-6 px-4 py-1 rounded-full bg-white/10 text-sm font-semibold">
                Student Support Services
            </span>

            <h2 class="text-4xl md:text-5xl font-bold mb-5">
                What UMPSA Can Do For You
            </h2>

            <p class="text-gray-300 max-w-2xl mx-auto text-lg">
                UMPSA provides a wide range of student-focused services designed to
                support your academic journey, wellbeing, and daily life.
            </p>
        </div>

        <!-- Services -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

            @foreach ([
                ['name' => 'Campus Pantry', 'icon' => 'fa-utensils', 'desc' => 'Food assistance for students in need'],
                ['name' => 'PKU Hotline', 'icon' => 'fa-phone', 'desc' => 'Immediate health and emergency support'],
                ['name' => 'Sejahtera Center', 'icon' => 'fa-heart', 'desc' => 'Mental health & counselling services'],
                ['name' => 'OKU Center', 'icon' => 'fa-wheelchair', 'desc' => 'Support for students with disabilities'],
            ] as $service)

            <div class="group relative rounded-2xl bg-white/5 backdrop-blur-md
                        p-8 hover:bg-white/10 transition shadow-xl">

                <div class="w-16 h-16 rounded-xl bg-white/10 flex items-center justify-center mb-6
                            group-hover:scale-110 transition">
                    <i class="fa-solid {{ $service['icon'] }} text-2xl"></i>
                </div>

                <h3 class="text-xl font-bold mb-3">
                    {{ $service['name'] }}
                </h3>

                <p class="text-gray-300 text-sm mb-6">
                    {{ $service['desc'] }}
                </p>

                <div class="flex items-center gap-2 text-sm font-semibold opacity-70 group-hover:opacity-100 transition">
                    Learn more
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </div>

            @endforeach
        </div>

        <!-- Support Banner -->
        <div class="mt-24 bg-white/10 backdrop-blur-md rounded-2xl p-10 text-center shadow-2xl">
            <p class="text-xl font-semibold mb-3">
                Need immediate help?
            </p>
            <p class="text-gray-300 mb-6">
                UMPSA support services are available around the clock to assist you.
            </p>

            <a href="#"
               class="inline-flex items-center gap-3
                      bg-white text-gray-900
                      px-8 py-4 rounded-xl
                      font-semibold shadow-lg hover:bg-gray-100 transition">
                Contact Support
                <i class="fa-solid fa-headset"></i>
            </a>
        </div>

    </div>
</div>


@include('layouts.footer')
</body>
</html>