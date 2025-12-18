@extends('layouts.landlord')

@section('title', ' | Rental Listings')

@section('content')

<div class="px-10 mt-10 mb-20 w-full mx-auto">


    {{-- Create Listings --}}
    <div class="flex items-center justify-between mb-6">

        <h1 class="text-2xl font-semibold">Your Rental Listings</h1>

        <a href="{{ route('landlord.createlistings') }}"
        class="border border-gray-400 text-gray-700 px-5 py-2 rounded-lg 
                transition hover:bg-red-500 hover:text-white">
            + Create Listing
        </a>

    </div>


    {{-- LISTINGS LOOP --}}
    @foreach([1,2,3] as $i)
    <div class="w-full  bg-white rounded-xl shadow-sm overflow-hidden border mb-6">

        <div class="grid grid-cols-12">

            {{-- LEFT IMAGE --}}
            <div class="col-span-3">
                <img src="{{ asset('images/ocs-taman-placeholder.jpg') }}"
                     class="w-full h-48 object-cover">
            </div>

            {{-- MIDDLE CONTENT --}}
            <div class="col-span-6 px-6 py-4">

                {{-- Title --}}
                <h2 class="font-semibold text-lg">Room for Rent - All UMPSA Student</h2>

                {{-- Address --}}
                <p class="text-xs text-gray-600 mt-1">
                    Lot 231, Taman PLKN, 26600 Pekan, Pahang
                </p>

                {{-- Distance --}}
                <div class="mt-2 bg-gray-100 inline-block text-xs px-2 py-1 rounded">
                    <i class="fa-solid fa-location-dot mr-1 text-red-500"></i>
                    5km From UMPSA Pekan
                </div>

                {{-- Icons row --}}
                <div class="mt-2 flex items-center space-x-2 text-sm text-gray-600">
                    <i class="fa-solid fa-bed"></i>
                    <i class="fa-solid fa-wifi"></i>
                    <i class="fa-solid fa-snowflake"></i>
                    <i class="fa-solid fa-shower"></i>
                </div>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-2 mt-3 text-xs">

                    <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full border">
                        ✔ Verified Landlord
                    </span>

                    <span class="px-3 py-1 bg-gray-100 rounded-full border text-gray-600">
                        Fully Furnished
                    </span>

                    <span class="px-3 py-1 bg-gray-100 rounded-full border text-gray-600">
                        UMPSA Student Only
                    </span>

                    <span class="px-3 py-1 bg-gray-100 rounded-full border text-gray-600">
                        Cat Friendly
                    </span>

                </div>

            </div>

            {{-- RIGHT SECTION (Flex container to push buttons right) --}}
            <div class="col-span-3 flex justify-end items-center pr-10">
                
                {{-- BUTTON COLUMN --}}
                <div class="w-28 flex flex-col gap-3">

                    <a href="#"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-3 py-2 rounded-lg text-center transition">
                        Edit
                    </a>

                    <button class="bg-red-600 hover:bg-red-700 text-white font-medium px-3 py-2 rounded-lg">
                        Delete
                    </button>

                </div>

            </div>



        </div>
    </div>
    @endforeach

</div>

@endsection
