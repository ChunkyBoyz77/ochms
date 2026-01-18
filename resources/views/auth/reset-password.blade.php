<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - OCHMS</title>

    <!-- Icons & Fonts -->
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
                 class="w-10 drop-shadow-md cursor-pointer"
                 alt="Logo">
        </a>
        <h1 class="text-2xl font-semibold font-[Barrio] text-gray-900">OCHMS</h1>
    </div>

    <a href="{{ route('home') }}"
       class="inline-flex items-center gap-2
              px-4 py-2 rounded-full
              border border-gray-200
              text-sm text-gray-500
              hover:border-gray-300 hover:text-gray-700
              hover:bg-gray-50 transition">
        <i class="fa-solid fa-angle-left text-xs"></i>
        Back to OCHMS
    </a>
</div>

<!-- Main Container -->
<div class="min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <!-- Illustration -->
        <div class="hidden lg:block">
            <img src="{{ asset('images/forgot-password.png') }}"
                 alt="Reset password illustration"
                 class="w-full max-w-lg mx-auto">
        </div>

        <!-- Reset Form -->
        <div class="w-full max-w-md mx-auto lg:mx-0">

            <div class="mb-8">
                <h2 class="text-4xl font-bold text-gray-900 mb-3">
                    Reset your password
                </h2>
                <p class="text-gray-600">
                    Create a new secure password for your account.
                </p>
            </div>

            <!-- Form -->
            <form method="POST"
                  action="{{ route('password.store') }}"
                  class="space-y-6"
                  id="resetPasswordForm">

                @csrf

                <!-- Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email address
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        readonly
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200
                               bg-gray-50 text-gray-700 cursor-not-allowed">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>

                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 pr-10
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                outline-none transition-all"
                            oninput="checkPasswordStrength(); checkPasswordMatch();"
                            placeholder="Enter new password">

                        <button type="button"
                                onclick="togglePasswordVisibility('password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <p id="passwordStatus" class="text-sm mt-4 text-gray-500 hidden"></p>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password
                    </label>

                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 pr-10
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                outline-none transition-all"
                            oninput="checkPasswordMatch();"
                            placeholder="Confirm new password">

                        <button type="button"
                                onclick="togglePasswordVisibility('password_confirmation', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <p id="matchMessage" class="text-sm mt-2"></p>
                </div>


                <!-- Submit Button -->
                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full bg-gray-900 hover:bg-gray-800
                           text-white font-semibold py-3 rounded-lg
                           transition-all duration-300 shadow-lg
                           disabled:opacity-70 disabled:cursor-not-allowed">

                    <span id="btnText">Reset Password</span>
                    <span id="btnLoading" class="hidden items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-notch animate-spin"></i>
                        Resetting...
                    </span>
                </button>

            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Remembered your password?
                    <a href="{{ route('home') }}"
                       class="font-semibold text-blue-600 hover:text-blue-700 transition">
                        Log in
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script>
function checkPasswordStrength() {
    const password = document.getElementById('password');
    const status = document.getElementById('passwordStatus');

    if (!password.value) {
        status.classList.add('hidden');
        return;
    }

    const rules = [
        password.value.length >= 8,
        /[A-Z]/.test(password.value),
        /\d/.test(password.value)
    ];

    status.classList.remove('hidden', 'text-green-600', 'text-gray-500');

    if (rules.every(Boolean)) {
        status.textContent = 'All requirements fulfilled';
        status.className = 'text-sm mt-3 text-green-600';
    } else {
        status.textContent = 'Password must be at least 8 characters, include an uppercase letter and a number';
        status.className = 'text-sm mt-3 text-gray-500';
    }
}

function checkPasswordMatch() {
    const pwd = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;
    const msg = document.getElementById('matchMessage');

    if (!confirm) {
        msg.textContent = '';
        return;
    }

    if (pwd === confirm) {
        msg.textContent = 'Passwords match';
        msg.className = 'text-sm mt-2 text-green-600';
    } else {
        msg.textContent = 'Passwords do not match';
        msg.className = 'text-sm mt-2 text-red-500';
    }
}

document.getElementById('resetPasswordForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    document.getElementById('btnText').classList.add('hidden');
    document.getElementById('btnLoading').classList.remove('hidden');
    document.getElementById('btnLoading').classList.add('flex');
    btn.disabled = true;
});

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');

    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

</body>
</html>
