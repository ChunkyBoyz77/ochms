@extends('layouts.ocs')

@section('title', 'Photos - ' . $listing->title)

@section('content')

<div class="px-10 mb-20 w-full mx-auto">

    {{-- BREADCRUMB --}}
    <nav class="mb-6 text-sm lg:text-md xl:text-lg text-gray-500">
        <ol class="flex items-center gap-1">
            <li>
                <a href="{{ route('landlord.dashboard') }}" class="hover:text-gray-700">
                    Home
                </a>
            </li>
            <li><i class="fa-solid fa-chevron-right text-xs text-gray-300 mx-1"></i></li>
            <li>
                <a href="{{ route('ocs.listings.browse' , $listing) }}" class="hover:text-gray-700">
                    Listings
                </a>
            </li>
            <li><i class="fa-solid fa-chevron-right text-xs text-gray-300 mx-1"></i></li>
            <li>
                <a href="{{ route('ocs.listings.show', $listing) }}"
                   class="hover:text-gray-700">
                    {{ $listing->title }}
                </a>
            </li>
            <li><i class="fa-solid fa-chevron-right text-xs text-gray-300 mx-1"></i></li>
            <li class="text-gray-700 font-medium">
                Medias
            </li>
        </ol>
    </nav>

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-xl lg:text-2xl font-semibold mb-1">
            All Medias
        </h1>
        <p class="text-sm text-gray-500">
            {{ $listing->images->count() }} medias for this property
        </p>
    </div>

    {{-- IMAGE GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($listing->images as $img)
            <div class="bg-white border rounded-xl overflow-hidden shadow-sm">

                <img
                    src="{{ asset('storage/'.$img->image_path) }}"
                    class="w-full h-72 object-contain bg-gray-100">

            </div>
        @empty
            <p class="text-gray-500">
                No photos uploaded for this property.
            </p>
        @endforelse

    </div>

</div>

@endsection
