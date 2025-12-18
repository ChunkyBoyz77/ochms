@extends('layouts.landlord')

@section('title', ' | Create Listings')

@section('content')

<div class="max-w-3xl mx-auto mt-6 mb-16 bg-white rounded-xl shadow p-8">

    {{-- HEADER BAR --}}
    <div class="flex items-center mb-6">
        <a href="{{ route('landlord.listings') }}" class="text-black text-2xl mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-xl font-semibold">Create Listings</h1>
    </div>

    <form action='#' method="POST" enctype="multipart/form-data">
        @csrf

        {{-- SECTION: PROPERTY DETAILS --}}
        <h2 class="font-semibold mb-3">Property Details</h2>

        {{-- TITLE --}}
        <input type="text" name="title"
               placeholder="Title"
               class="w-full mb-3 px-4 py-2.5 border rounded-full focus:ring-red-400">

        {{-- PROPERTY TYPE --}}
        <select name="property_type"
                class="w-full mb-3 px-4 py-2.5 border rounded-full bg-white focus:ring-red-400">
            <option value="" selected disabled>Property Type</option>
            <option value="Room">Room</option>
            <option value="House">House</option>
            <option value="Apartment">Apartment</option>
        </select>

        {{-- ADDRESS --}}
        <input type="text" name="address"
               placeholder="Address"
               class="w-full mb-3 px-4 py-2.5 border rounded-full focus:ring-red-400">

        {{-- DISTANCE --}}
        <input type="text" name="distance"
               placeholder="Distance to UMPSA"
               class="w-full mb-3 px-4 py-2.5 border rounded-full focus:ring-red-400">

        {{-- RENT + DEPOSIT --}}
        <div class="grid grid-cols-2 gap-3">
            <input type="number" name="monthly_rent"
                   placeholder="Monthly Rent"
                   class="px-4 py-2.5 border rounded-full focus:ring-red-400">

            <input type="number" name="deposit"
                   placeholder="Deposit"
                   class="px-4 py-2.5 border rounded-full focus:ring-red-400">
        </div>

        {{-- AMENITIES SECTION --}}
        <h2 class="font-semibold mt-6 mb-3">Amenities</h2>

        <div class="grid grid-cols-2 gap-2 text-sm">

            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="Fully-furnished">
                <span>Fully-furnished</span>
            </label>

            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="Elevator">
                <span>Elevator</span>
            </label>

            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="Wifi">
                <span>Wifi</span>
            </label>

            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="Parking">
                <span>Parking</span>
            </label>

            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="Paid Bills">
                <span>Paid Bills</span>
            </label>

            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="Security">
                <span>Security</span>
            </label>

        </div>

        {{-- UPLOAD SECTION --}}
        <h2 class="font-semibold mt-6 mb-3">Uploads</h2>

        {{-- Property Grant --}}
        <div class="relative mb-4">
            <input type="file" name="grant"
                   class="absolute inset-0 opacity-0 cursor-pointer z-10">

            <div class="w-full border rounded-full px-4 py-2.5 flex justify-between items-center">
                <span class="text-gray-600">Property Grant</span>
                <i class="fa-solid fa-upload text-gray-500"></i>
            </div>
        </div>

        {{-- Property Pictures --}}
        <div class="relative mb-6">
            <input type="file" name="images[]" multiple
                   class="absolute inset-0 opacity-0 cursor-pointer z-10">

            <div class="w-full border rounded-full px-4 py-2.5 flex justify-between items-center">
                <span class="text-gray-600">Property Pictures</span>
                <i class="fa-solid fa-upload text-gray-500"></i>
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div class="text-center">
            <button type="submit"
                    class="px-10 py-2.5 rounded-full border border-gray-400 hover:bg-red-500 hover:text-white transition">
                Add
            </button>
        </div>

    </form>

</div>

@endsection
