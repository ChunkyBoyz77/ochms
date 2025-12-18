<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>OCS Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="w-full bg-white shadow flex items-center justify-between px-10 py-4">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/umpsa-logo.png') }}" class="w-10" alt="Logo">
            <a class="font-semibold hover:text-blue-600">Home</a>
            <a class="font-semibold hover:text-blue-600">Rentals</a>
            <a class="font-semibold hover:text-blue-600">UMPSA Resources</a>
        </div>

        <div>
            <i class="fa-solid fa-user-circle text-3xl text-gray-700 cursor-pointer"></i>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative w-full h-[500px] bg-cover bg-center rounded-b-3xl shadow"
         style="background-image: url('{{ asset('images/ocs-hero-bg.jpg') }}')">

        <div class="absolute inset-0 bg-black/40 rounded-b-3xl"></div>

        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-6">
            <h1 class="text-4xl font-extrabold drop-shadow-lg">OCHMS</h1>
            <p class="mt-2 text-lg">Off-Campus Living Simplified for UMPSA.</p>

            <!-- Search Bar -->
            <div class="mt-6 w-full max-w-xl">
                <div class="bg-white rounded-full p-2 shadow flex items-center">
                    <input type="text" placeholder="Search keywords..."
                           class="flex-1 px-4 py-2 rounded-full focus:outline-none">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Badges -->
            <div class="flex space-x-4 mt-4 text-sm">
                <div class="bg-green-100 text-green-700 px-4 py-1 rounded-full shadow">
                    ✔ 100% Verified Listings
                </div>
                <div class="bg-green-100 text-green-700 px-4 py-1 rounded-full shadow">
                    ✔ JHEPA Certified Resources
                </div>
            </div>
        </div>
    </div>

    <!-- HOUSING SECTION -->
    <div class="px-10 mt-10">
        <h2 class="text-xl font-bold mb-4">Book Your Housing Now</h2>

        <div class="grid grid-cols-4 gap-6">

            <!-- CARD 1 -->
            <div class="bg-white rounded-xl shadow hover:shadow-lg cursor-pointer">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}" class="rounded-t-xl w-full h-50 object-cover">
                <div class="p-4 font-semibold">Taman Mentiga Jaya</div>
            </div>

            <!-- CARD 2 -->
            <div class="bg-white rounded-xl shadow hover:shadow-lg cursor-pointer">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}" class="rounded-t-xl w-full h-50 object-cover">
                <div class="p-4 font-semibold">Taman PLKN</div>
            </div>

            <!-- CARD 3 -->
            <div class="bg-white rounded-xl shadow hover:shadow-lg cursor-pointer">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}" class="rounded-t-xl w-full h-50 object-cover">
                <div class="p-4 font-semibold">Taman Beruas Baru</div>
            </div>

            <!-- CARD 4 -->
            <div class="bg-white rounded-xl shadow hover:shadow-lg cursor-pointer">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}" class="rounded-t-xl w-full h-50 object-cover">
                <div class="p-4 font-semibold">Kampung Beruas</div>
            </div>

        </div>
    </div>

    <!-- UMPSA RESOURCES SECTION -->
    <div class="px-10 mt-14">
        <h2 class="text-xl font-bold mb-4">What UMPSA Can Do For You</h2>

        <div class="grid grid-cols-4 gap-6">

            <div class="bg-white rounded-xl shadow hover:shadow-lg cursor-pointer text-center">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}" class="rounded-t-xl w-full h-50 object-cover">
                <div class="p-4 font-semibold">UMPSA Campus Pantry</div>
            </div>

            <div class="bg-white rounded-xl shadow hover:shadow-lg cursor-pointer text-center">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}" class="rounded-t-xl w-full h-50 object-cover">
                <div class="p-4 font-semibold">UMPSA PKU Hotline</div>
            </div>

            <div class="bg-white rounded-xl shadow hover:shadow-lg cursor-pointer text-center">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}" class="rounded-t-xl w-full h-50 object-cover">
                <div class="p-4 font-semibold">UMPSA Sejahtera Center</div>
            </div>

            <div class="bg-white rounded-xl shadow hover:shadow-lg cursor-pointer text-center">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}" class="rounded-t-xl w-full h-50 object-cover">
                <div class="p-4 font-semibold">UMPSA OKU Center</div>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white mt-16 p-10 text-sm">
        <div class="flex justify-between">

            <div>
                <img src="{{ asset('images/umpsa-logo.png') }}" class="w-14 mb-2">
                <p>Universiti Malaysia Pahang</p>
                <p>26600 Pekan, Pahang, Malaysia</p>
            </div>

            <div>
                <p>Tel: +609 424 5000</p>
                <p>Fax: +609 455 5555</p>
                <p>Email: pro@umpsa.edu.my</p>
            </div>

        </div>
    </footer>

</body>
</html>
