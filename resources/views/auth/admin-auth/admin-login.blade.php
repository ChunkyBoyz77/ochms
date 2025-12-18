<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JHEPA Admin Login | OCHMS</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800
             flex items-center justify-center px-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-10">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                JHEPA Admin
            </h1>
            <p class="text-gray-500 mt-2">
                Secure access for authorized staff only
            </p>
        </div>

        {{-- LOGIN ERROR --}}
        @if ($errors->any())
            <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-200
                        text-sm text-red-700 text-center">
                Incorrect Staff ID or password.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- STAFF ID -->
            <div class="mb-5">
                <label class="font-semibold text-gray-700">Staff ID</label>
                <input type="text"
                       name="staff_id"
                       required
                       autofocus
                       placeholder="e.g. JHEPA-001"
                       class="w-full mt-1 p-3 border rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>

            <!-- PASSWORD -->
            <div class="mb-4 relative">
                <label class="font-semibold text-gray-700">Password</label>

                <input type="password"
                       id="adminPassword"
                       name="password"
                       required
                       placeholder="Enter your password"
                       class="w-full mt-1 p-3 border rounded-lg pr-10
                              focus:outline-none focus:ring-2 focus:ring-gray-900">

                <!-- TOGGLE -->
                <button type="button"
                        onclick="togglePassword('adminPassword', this)"
                        class="absolute right-3 top-10 text-gray-500">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            <!-- REMEMBER ME -->
            <div class="flex items-center gap-2 mb-6">
                <input type="checkbox"
                       name="remember"
                       id="remember_admin"
                       class="rounded border-gray-300 text-gray-900
                              focus:ring-gray-900">

                <label for="remember_admin" class="text-sm text-gray-600">
                    Remember me
                </label>
            </div>

            <!-- LOGIN BUTTON -->
            <button type="submit"
                    class="w-full bg-gray-900 hover:bg-gray-800
                           text-white py-3 rounded-lg text-lg font-semibold">
                Login
            </button>
        </form>

        <!-- Footer note -->
        <p class="text-center text-xs text-gray-400 mt-8">
            This system is restricted to JHEPA administrators.
        </p>

    </div>

    <!-- PASSWORD TOGGLE SCRIPT -->
    <script>
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
    </script>

</body>
</html>
