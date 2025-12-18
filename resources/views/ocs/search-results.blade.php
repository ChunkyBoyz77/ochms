<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rental Search Results</title>

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
            <a class="font-semibold hover:text-blue-600 cursor-pointer">Rentals</a>
            <a class="font-semibold hover:text-blue-600 cursor-pointer">UMPSA Resources</a>
        </div>

        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" placeholder="Search..." class="px-4 py-2 rounded-full border">
                <i class="fa-solid fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>

            <i class="fa-solid fa-user-circle text-3xl text-gray-700 cursor-pointer"></i>
        </div>
    </nav>


    <div class="flex px-10 mt-10 mb-20 gap-12">

        <!-- FILTER SIDEBAR -->
        <aside class="w-64 bg-white rounded-xl shadow p-6">

            <h3 class="font-bold mb-4">Filter</h3>

            <!-- Property Type -->
            <p class="font-semibold text-sm mb-2">Property Type</p>
            <div class="space-y-1 mb-4">
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>Room</span></label>
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>House</span></label>
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>Apartment</span></label>
            </div>

            <!-- Amenities -->
            <p class="font-semibold text-sm mb-2">Amenities</p>
            <div class="space-y-1 mb-4">
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>Parking</span></label>
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>Aircond</span></label>
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>Washing Machine</span></label>
            </div>

            <!-- Stay Duration -->
            <p class="font-semibold text-sm mb-2">Stay Duration</p>
            <div class="space-y-1 mb-4">
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>Short Term</span></label>
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>Long Term</span></label>
                <label class="flex items-center space-x-2"><input type="checkbox"> <span>Flexible</span></label>
            </div>

            <!-- Price Range -->
            <p class="font-semibold text-sm mb-2">Price Range</p>

            <div class="mt-2 mb-4">
                <input type="range" min="100" max="1000" class="w-full">
                <div class="flex justify-between text-xs mt-1">
                    <span>RM100</span>
                    <span>RM1000</span>
                </div>
            </div>

            <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Apply Filters
            </button>

        </aside>


        <!-- RESULTS SECTION -->
        <main class="flex-1 ml-10">

            <h2 class="text-2xl font-bold mb-2">Rental Housing in Taman PLKN</h2>
            <p class="text-gray-500 mb-6">23 Available Properties Found</p>

            <div class="space-y-6">

                <!-- RESULT CARD -->
                @for ($i = 1; $i <= 5; $i++)
                <div class="bg-white rounded-xl shadow flex overflow-hidden hover:shadow-lg">

                    <!-- IMAGE -->
                    <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}"
                         class="w-48 h-40 object-cover">

                    <!-- DETAILS -->
                    <div class="p-4 flex-1">

                        <h3 class="font-bold text-lg">
                            Room for Rent - All UMPSA Student
                        </h3>

                        <p class="text-gray-600 text-sm">
                            Lot 20, Taman PLKN, Pekan, Pahang
                        </p>

                        <div class="flex space-x-3 mt-3 text-gray-500 text-sm">
                            <span><i class="fa-solid fa-bed"></i> 2 Rooms</span>
                            <span><i class="fa-solid fa-shower"></i> 1 Bathroom</span>
                            <span><i class="fa-solid fa-wifi"></i> WiFi</span>
                        </div>

                        <div class="flex space-x-3 mt-2">
                            <span class="px-3 py-1 rounded-full bg-gray-200 text-sm">100m from UMPSA</span>
                            <span class="px-3 py-1 rounded-full bg-gray-200 text-sm">Gated</span>
                        </div>

                    </div>

                    <!-- PRICE + RATING -->
                    <div class="p-4 flex flex-col justify-between items-end w-48">

                        <!-- Rating badge -->
                        <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold text-sm">
                            4.6 ★ (90)
                        </div>

                        <div class="text-right">
                            <p class="text-gray-600 text-sm">From</p>
                            <p class="text-xl font-bold">RM500/<span class="text-sm">month</span></p>

                            <button class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                                Request Booking
                            </button>
                        </div>

                    </div>

                </div>
                @endfor

            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-10 space-x-3">
                <button class="w-8 h-8 bg-white shadow rounded-lg">1</button>
                <button class="w-8 h-8 bg-white shadow rounded-lg">2</button>
                <button class="w-8 h-8 bg-white shadow rounded-lg">3</button>
            </div>

        </main>

    </div>


    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white p-10 text-sm mt-16">
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
