<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>List Your Property | OCHMS</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">

@include('layouts.header.ocs-welcome-header')

<!-- HERO SECTION -->
<section class="relative h-[70vh] flex items-center justify-center bg-gray-900">

    <!-- Background image placeholder -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/become-landlord-hero.jpg') }}"
             class="w-full h-full object-cover opacity-60"
             alt="hero">
    </div>

    <div class="relative z-10 text-center px-6 flex flex-col items-center">
        <h1 class="text-white text-4xl sm:text-5xl lg:text-6xl font-bold">
            List your property on OCHMS
        </h1>

        <p class="text-gray-200 mt-4 max-w-2xl mx-auto text-lg">
            Reach UMPSA students directly and manage your rental listings with ease.
        </p>

        <a href="{{ route('landlord.auth') }}"
        class="inline-block mt-8 bg-gray-900 hover:bg-gray-800 text-white px-10 py-4 rounded-lg text-lg shadow-lg">
            Add Property
        </a>
    </div>

</section>

<!-- WHY LIST SECTION -->
<section class="py-16 px-6 max-w-7xl mx-auto">

    <h2 class="text-3xl font-bold text-center mb-12">
        Why list on OCHMS
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

        <!-- LEFT TEXT -->
        <div class="space-y-6">
            <h3 class="text-xl font-semibold">Go-to platform for UMPSA students</h3>
            <p class="text-gray-600 leading-relaxed">
                OCHMS is built specifically for UMPSA off-campus living.
                Your listings are shown directly to students searching
                near campus — no wasted traffic.
            </p>

            <h3 class="text-xl font-semibold">Verified & trusted ecosystem</h3>
            <p class="text-gray-600 leading-relaxed">
                Listings are moderated and supported by JHEPA and UMPSA resources,
                increasing trust and reducing low-quality enquiries.
            </p>
        </div>

        <!-- RIGHT IMAGE -->
        <div class="rounded-xl overflow-hidden shadow">
            <img src="{{ asset('images/become-landlord-p1.jpg') }}"
                 class="w-full h-[300px] object-cover"
                 alt="Placeholder">
        </div>

    </div>
</section>

<!-- BENEFITS GRID -->
<section class="bg-gray-50 py-16 px-6">

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- CARD -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <img src="{{ asset('images/become-landlord-p2.jpg') }}"
                 class="w-full h-40 object-cover rounded mb-4"
                 alt="Placeholder">

            <h4 class="font-semibold text-lg mb-2">Cost-effective exposure</h4>
            <p class="text-gray-600 text-sm">
                Advertise once and reach thousands of students actively
                looking for accommodation.
            </p>
        </div>

        <!-- CARD -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <img src="{{ asset('images/become-landlord-p3.jpg') }}"
                 class="w-full h-40 object-cover rounded mb-4"
                 alt="Placeholder">

            <h4 class="font-semibold text-lg mb-2">Higher efficiency</h4>
            <p class="text-gray-600 text-sm">
                Receive enquiries directly through the platform and
                manage communication in one place.
            </p>
        </div>

        <!-- CARD -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <img src="{{ asset('images/become-landlord-p4.jpg') }}"
                 class="w-full h-40 object-cover rounded mb-4"
                 alt="Placeholder">

            <h4 class="font-semibold text-lg mb-2">Simple management</h4>
            <p class="text-gray-600 text-sm">
                Create, update, and manage listings with a clean dashboard
                designed for landlords.
            </p>
        </div>

    </div>
</section>

<!-- STATS / TRUST -->
<section class="py-16 px-6 text-center max-w-6xl mx-auto">

    <h2 class="text-3xl font-bold mb-10">
        Built for UMPSA’s housing ecosystem
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">

        <div>
            <p class="text-4xl font-bold text-gray-900">100%</p>
            <p class="text-gray-600 mt-2">Student-focused platform</p>
        </div>

        <div>
            <p class="text-4xl font-bold text-gray-900">Verified</p>
            <p class="text-gray-600 mt-2">Listings & resources</p>
        </div>

        <div>
            <p class="text-4xl font-bold text-gray-900">Direct</p>
            <p class="text-gray-600 mt-2">Landlord-student communication</p>
        </div>

    </div>
</section>

<section class="bg-gray-900 py-16">
    <div class="max-w-4xl mx-auto text-center flex flex-col items-center">

        <h2 class="text-white text-3xl font-bold">
            Ready to list your property?
        </h2>

        <p class="text-gray-300 mt-4">
            Join OCHMS and connect with UMPSA students today.
        </p>

        <a href="{{ route('landlord.auth') }}"
           class="inline-block mt-8 bg-white text-gray-900 hover:bg-gray-100
                  px-10 py-4 rounded-lg text-lg font-semibold">
            Add Property
        </a>

    </div>
</section>


</body>
</html>
