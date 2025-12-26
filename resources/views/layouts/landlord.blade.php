<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OCHMS - Landlord @yield('title')</title>

    <!-- Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts (optional, if you use nicer ones) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-[Poppins]">

    {{-- Header --}}
    @include('layouts.header.landlord-header')

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

@if (session('withdraw_success'))
    <div id="flashMessage"
         class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                bg-red-600 text-white
                px-6 py-3 rounded-lg shadow-lg
                flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>{{ session('withdraw_success') }}</span>
    </div>
@endif

    {{-- Main Content --}}
    <main class="min-h-screen px-10 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

</body>

<script>
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
</script>

</html>
