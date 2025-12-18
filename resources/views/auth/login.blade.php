<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OCHMS</title>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
      style="background-image: url('{{ asset('images/umpsa-bg.jpg') }}');">

    <div class="bg-white/95 p-10 rounded-xl shadow-2xl w-[430px]">

        <a href="{{ url('/') }}" class="text-gray-600 text-sm hover:text-black">
            ← Back
        </a>

        <h2 class="text-2xl font-bold mt-4 mb-6 text-center">Login to OCHMS</h2>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="font-semibold">Email</label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       class="w-full p-3 rounded border mt-1"
                       required autofocus>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="font-semibold">Password</label>
                <input type="password" name="password"
                       class="w-full p-3 rounded border mt-1"
                       required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mt-3 mb-2">
                <input type="checkbox" name="remember" id="remember_me"
                       class="mr-2 rounded border-gray-400">
                <label for="remember_me" class="text-gray-600 text-sm">Remember me</label>
            </div>

            <!-- Forgot Password -->
            <div class="text-right mb-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-sm text-gray-600 hover:text-black">
                        Forgot your password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-lg font-semibold shadow">
                Log in
            </button>

        </form>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Don't have an account?
            <a href="{{ route('register.choose') }}" class="text-blue-600 font-semibold hover:underline">
                Register here
            </a>
        </p>

    </div>

</body>

</html>
