<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register As</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-cover bg-center flex items-center justify-center"
      style="background-image: url('{{ asset('images/umpsa-bg.jpg') }}');">

    <div class="bg-white/90 backdrop-blur-md p-10 rounded-xl shadow-2xl w-[450px] text-center">

        <img src="{{ asset('images/umpsa-logo.png') }}" class="mx-auto w-20 mb-4">

        <h2 class="text-2xl font-bold mb-2">OCHMS</h2>
        <p class="text-gray-600 font-semibold mb-6">Register As</p>

        <div class="grid grid-cols-2 gap-4">

            <!-- OCS -->
            <a href="{{ route('register.ocs') }}"
               class="p-6 bg-gray-100 rounded-lg hover:bg-gray-200 shadow text-center transition">
                
                <i class="fa-solid fa-user-graduate text-4xl text-gray-700 mb-2"></i>
                <p class="font-semibold">OCS</p>
            </a>

            <!-- Landlord -->
            <a href="{{ route('register.landlord') }}"
               class="p-6 bg-red-100 rounded-lg hover:bg-red-200 shadow text-center transition">
                
                <i class="fa-solid fa-house text-4xl text-red-600 mb-2"></i>
                <p class="font-semibold">Landlord</p>
            </a>

            <!-- Admin -->
            <a href="{{ route('register.admin') }}"
               class="col-span-2 p-6 bg-purple-100 rounded-lg hover:bg-purple-200 shadow text-center transition">
                
                <i class="fa-solid fa-user-shield text-4xl text-purple-600 mb-2"></i>
                <p class="font-semibold">JHEPA Admin</p>
            </a>

        </div>

    </div>

</body>

</html>
