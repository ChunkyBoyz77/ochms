<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Property Details - OCHMS</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="w-full bg-white shadow flex items-center justify-between px-10 py-4">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/umpsa-logo.png') }}" class="w-10" alt="Logo">
            <span class="font-bold text-xl">OCHMS</span>
            <a href="#" class="font-semibold hover:text-blue-600">Rentals</a>
            <a href="#" class="font-semibold hover:text-blue-600">UMPSA Resources</a>
        </div>

        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" placeholder="Search..." class="px-4 py-2 rounded-full border">
                <i class="fa-solid fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
            <i class="fa-solid fa-user-circle text-3xl text-gray-700 cursor-pointer"></i>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="px-10 mt-10 mb-20 max-w-7xl mx-auto">

        <!-- TITLE + RATING + BOOKING BUTTON -->
        <div class="flex justify-between items-start mb-4">

            <div>
                <h1 class="text-2xl font-bold">Room for Rent – All UMPSA Student</h1>

                <div class="mt-2 flex space-x-2">
                    <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">Martial Lombok</span>
                    <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">On-site Inspection</span>
                </div>

                <p class="text-gray-600 mt-3 flex items-center">
                    <i class="fa-solid fa-location-dot mr-2 text-gray-500"></i>
                    Lot 25J, Taman PLKN, 26600 Pekan, Pahang
                </p>
            </div>

            <div class="text-right">

                <!-- Rating badge -->
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold text-sm">
                    4.4 ★ (96)
                </span>

                <div class="mt-3">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Request Booking
                    </button>
                </div>

                <p class="mt-3 text-gray-700 font-bold">
                    From <span class="text-xl">RM500</span>/month
                </p>
            </div>

        </div>

        <!-- PROPERTY IMAGE -->
        <div class="rounded-2xl overflow-hidden shadow mb-10 h-[700px]">
            <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}"
                 class="w-full h-full object-cover">
        </div>

        <!-- TABS -->
        <div class="flex space-x-8 border-b pb-3 mb-8 text-gray-600">

            <button class="font-semibold hover:text-black border-b-2 border-black pb-1">
                Basic info
            </button>

            <button class="hover:text-black">Amenities</button>
            <button class="hover:text-black">Location</button>
            <button class="hover:text-black">Fees</button>

            <button class="hover:text-black flex items-center space-x-2">
                <span>Reviews</span>
                <span class="bg-green-100 text-green-700 px-2 rounded-full text-xs font-bold">4.4 ★ (10)</span>
            </button>

        </div>


        <!-- REVIEWS SECTION -->
        <h2 class="text-xl font-bold mb-4">Ratings and Reviews</h2>

        <!-- REVIEW CARD -->
        <div class="space-y-6">

            <!-- Review #1 -->
            <div class="bg-white rounded-xl shadow p-6 border">

                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                        <div>
                            <p class="font-bold">Mohd Anwar</p>
                            <p class="text-xs text-gray-500">
                                Tenant from May 2021 – May 2023
                            </p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">6 June 2023</p>
                </div>

                <div class="mt-3 text-yellow-400">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>

                <p class="mt-3 text-gray-700 text-sm leading-relaxed">
                    My 2 years in this house has been a blast. It is super comfortable and affordable as well,
                    can’t recommend this enough. But the landlord is a bit of a micromanager, enough for me to dock a star.
                </p>
            </div>


            <!-- Review #2 -->
            <div class="bg-white rounded-xl shadow p-6 border">

                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                        <div>
                            <p class="font-bold">Tan Wei Xie</p>
                            <p class="text-xs text-gray-500">
                                Tenant from January 2021 – March 2022
                            </p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">3 March 2023</p>
                </div>

                <div class="mt-3 text-yellow-400">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>

                <p class="mt-3 text-gray-700 text-sm leading-relaxed">
                    The landlord is quite annoying, always checking the house.
                    But it’s still a good housing, very close to UMPSA.
                </p>
            </div>

        </div>

        <p class="mt-6 text-blue-600 font-semibold cursor-pointer">
            More Reviews →
        </p>

    </div>


    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white p-10 text-sm mt-20">
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
