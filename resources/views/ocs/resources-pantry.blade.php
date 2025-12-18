<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UMPSA Campus Pantry</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="w-full bg-white shadow flex items-center justify-between px-10 py-4">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/umpsa-logo.png') }}" class="w-10">
            <span class="font-bold text-xl">OCHMS</span>
            <a href="#" class="font-semibold hover:text-blue-600">Rentals</a>
            <a href="#" class="font-semibold hover:text-blue-600">UMPSA Resources</a>
        </div>

        <div>
            <i class="fa-solid fa-user-circle text-3xl text-gray-700 cursor-pointer"></i>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="px-10 mt-10 mb-20 max-w-7xl mx-auto">

        <h1 class="text-2xl font-bold mb-6">UMPSA Campus Pantry</h1>

        <!-- IMAGE BOX -->
        <div class="rounded-xl overflow-hidden bg-white shadow">
            <img src="{{ asset('images/umpsa_pantry.png') }}"
                 class="w-full h-[500px] object-cover">
        </div>

        <!-- CONTACT INFO -->
        <div class="mt-6 flex items-center justify-center space-x-4">

            <div class="border rounded-full px-6 py-3 bg-white shadow text-center text-sm">
                UMPSA Campus Pantry Phone Line
            </div>

            <div class="border rounded-full px-6 py-3 bg-white shadow text-center text-sm">
                +6019956349
            </div>

        </div>

        <!-- CALL BUTTONS -->
        <div class="flex justify-center space-x-10 mt-6 text-3xl text-gray-700">
            <i class="fa-solid fa-phone hover:text-blue-600 cursor-pointer"></i>
            <i class="fa-brands fa-whatsapp hover:text-green-600 cursor-pointer"></i>
        </div>

        <!-- INVENTORY -->
        <h2 class="text-lg font-bold mt-10 mb-4">Pantry Inventory</h2>

        <div class="space-y-6">

            <!-- ITEM 1 -->
            <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow text-sm">
                <p>1. Biscuits Chips-Ahoy</p>

                <div class="flex items-center space-x-2">
                    <input type="number"
                           value="1"
                           class="w-16 p-2 border rounded text-center">
                    <button class="border px-4 py-2 rounded text-xs hover:bg-gray-100">
                        Take One Only
                    </button>
                </div>
            </div>

            <!-- ITEM 2 -->
            <div class="flex items-center justify-between bg-white p-4 rounded-lg shadow text-sm">
                <p>2. Body Soap</p>

                <div class="flex items-center space-x-2">
                    <input type="number"
                           value="1"
                           class="w-16 p-2 border rounded text-center">
                    <button class="border px-4 py-2 rounded text-xs hover:bg-gray-100">
                        Take One Only
                    </button>
                </div>
            </div>

        </div>

        <!-- DONATE BUTTON -->
        <div class="flex justify-center mt-10">
            <button class="border border-gray-600 px-6 py-3 rounded-lg hover:bg-gray-200">
                Donate to Pantry
            </button>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white p-10 text-sm">
        <div class="flex justify-between max-w-5xl mx-auto">

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
