<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - OCHMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Barrio&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Barriecito&display=swap" rel="stylesheet"> 
    
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>

<body class="bg-white">

<!-- Header -->
<div class="absolute top-0 left-0 right-0 p-6 flex justify-between items-center">
    <div class="flex items-center space-x-5">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/umpsa-logo.png') }}"
                id="navLogo"
                class="w-10 drop-shadow-md cursor-pointer"
                alt="Logo">
        </a>
        <h1 class="text-2xl font-semibold font-[Barrio] text-gray-900">OCHMS</h1>
    </div>
    <!-- BACK TO OCHMS (PILL STYLE) -->
    <div class="flex justify-center">
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

<!-- Main Container -->
<div class="min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        
        <!-- Left Side - Illustration -->
        <div class="hidden lg:block">
            <img src="{{ asset('images/forgot-password.png') }}"
                 alt="Forgot password illustration" 
                 class="w-full max-w-lg mx-auto">
        </div>

        <!-- Right Side - Form -->
        <div class="w-full max-w-md mx-auto lg:mx-0">
            
            <div class="mb-8">
                <h2 class="text-4xl font-bold text-gray-900 mb-3">
                    Forgot your password?
                </h2>
                <p class="text-gray-600">
                    Enter your email and we'll send you a link to reset your password.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                {{ session('status') }}
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6" id="resetForm">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email address
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 rounded-lg border-2 border-blue-500
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               outline-none transition-all
                               text-gray-900"
                        placeholder="Email address"
                    >
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full bg-gray-900 hover:bg-gray-800
                           text-white font-semibold py-3 rounded-lg
                           transition-all duration-300 shadow-lg
                           disabled:opacity-70 disabled:cursor-not-allowed">
                    <span id="btnText">Send reset link</span>
                    <span id="btnLoading" class="hidden items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-notch animate-spin"></i>
                        Sending...
                    </span>
                </button>

                <!-- Rate Limit Warning -->
                <p class="text-xs text-gray-500 text-center">
                    Too many attempts may be temporarily blocked.
                </p>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Remembered your password? 
                    <a href="{{ route('home') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition">
                        Log in
                    </a>
                </p>
            </div>

        </div>

    </div>
</div>

<script>
    document.getElementById('resetForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        
        // Disable button and show loading state
        btn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        btnLoading.classList.add('flex');
    });
</script>

</body>
</html>