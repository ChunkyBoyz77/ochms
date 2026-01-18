@extends('layouts.landlord')

@section('title', ' | Rental Listings')

@section('content')

<div class="px-10 mb-20 w-full mx-auto">


    {{-- Create Listings --}}
    <div class="flex items-center justify-between mb-6">

        <h1 class="text-2xl font-semibold">Your Rental Listings</h1>

        <a href="{{ route('landlord.createlistings') }}"
        class="border border-gray-400 text-gray-700 px-5 py-2 rounded-lg 
                transition hover:bg-red-500 hover:text-white">
            + Create Listing
        </a>

    </div>

@php
$amenityIcons = [
    'WiFi' => 'fa-wifi',
    'Parking' => 'fa-car',
    'Air Conditioning' => 'fa-snowflake',
    'Washing Machine' => 'fa-soap',
    'Security' => 'fa-shield-halved',
    'Furnished' => 'fa-couch',
];
@endphp

<h2 class="text-xl font-semibold mb-4 mt-5">Published Listings</h2>

@if($allListings->isEmpty())
    <p class="text-gray-500 mb-6">No approved listings yet.</p>
@endif

@foreach($allListings as $listing)
<div class="w-full bg-white rounded-xl shadow-sm overflow-hidden border mb-6 relative group">
<a href="{{ route('landlord.listings.show', $listing) }}"
   class="absolute inset-0 z-10"
   aria-label="View listing details">
</a>

    <div class="grid grid-cols-12">
        @php
            $thumbnail = $listing->images
                ->filter(function ($media) {
                    return in_array(
                        strtolower(pathinfo($media->image_path, PATHINFO_EXTENSION)),
                        ['jpg','jpeg','png','webp']
                    );
                })
                ->first();
        @endphp

        {{-- IMAGE --}}
        <div class="col-span-3 relative">

            <img
                src="{{  $thumbnail
                        ? asset('storage/'.$thumbnail->image_path)
                        : asset('images/ocs-taman-placeholder.jpg') }}"
                        class="w-full h-48 object-cover">

            {{-- RATING BADGE --}}
            @if($listing->reviews_count > 0)
                <div
                    class="absolute top-3 left-3
                        bg-black/80 backdrop-blur
                        text-white text-xs
                        px-3 py-1.5 rounded-lg
                        flex items-center gap-1.5
                        shadow-md">

                    <i class="fa-solid fa-star text-yellow-400"></i>

                    <span class="font-semibold">
                        {{ $listing->avg_rating }}
                    </span>

                    <span class="text-gray-300">
                        ({{ $listing->reviews_count }})
                    </span>
                </div>
            @endif

        </div>


        {{-- CONTENT --}}
        <div class="col-span-6 px-6 py-4">

            <h2 class="font-semibold text-lg mb-1">
                {{ $listing->title }}
            </h2>

            <p class="text-xs text-gray-600">
                {{ $listing->address }}
            </p>

            @if($listing->distance_to_umpsa)
                <div class="mt-2 bg-gray-100 inline-block text-xs px-2 py-1 rounded mb-2">
                    <i class="fa-solid fa-location-dot mr-1 text-red-500"></i>
                    {{ number_format($listing->distance_to_umpsa, 2) }} km from UMPSA
                </div>
            @endif

            {{-- AMENITIES ICONS --}}
            <div class="mt-2 flex items-center space-x-2 text-sm text-gray-600">
                @foreach($listing->amenities ?? [] as $amenity)
                    @if(isset($amenityIcons[$amenity]))
                        <i class="fa-solid {{ $amenityIcons[$amenity] }}"
                           title="{{ $amenity }}"></i>
                    @endif
                @endforeach
            </div>

            {{-- BADGES --}}
            @php
                $visibleBadges = collect($listing->badges)->take(3);
                $extraCount = collect($listing->badges)->count() - $visibleBadges->count();
            @endphp

            <div class="flex flex-wrap gap-2 mt-3 text-xs">
                @foreach($visibleBadges as $badge)
                    <span
                        class="px-3 py-1 rounded-full border
                            {{ $badge['style'] === 'green'
                                ? 'bg-green-50 text-green-600'
                                : 'bg-gray-100 text-gray-600' }}">
                        {{ $badge['label'] }}
                    </span>
                @endforeach

                @if($extraCount > 0)
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-500">
                        +{{ $extraCount }} more
                    </span>
                @endif
            </div>


        </div> {{-- END CONTENT --}}
      
        {{-- ACTIONS --}}
        <div class="col-span-3 flex justify-end items-center pr-10 relative z-20">
            <div class="w-36 flex flex-col gap-3">

                {{-- EDIT / DELETE (ONLY if NOT linked to OCS) --}}
                @if ($listing->ocs_id === null)

                    <a href="{{ route('landlord.listings.edit', $listing) }}"
                    class="bg-yellow-500 hover:bg-yellow-600
                            text-white font-medium px-3 py-2
                            rounded-lg text-center">
                        Edit
                    </a>

                    <button
                        type="button"
                        onclick="openDeleteListingModal({{ $listing->id }})"
                        class="bg-red-600 hover:bg-red-700
                            text-white font-medium px-3 py-2
                            rounded-lg">
                        Delete
                    </button>

                @else
                {{-- ICON-ONLY OPEN BUTTON --}}
                <a href="{{ route('landlord.listings.show', $listing) }}"
                onclick="event.stopPropagation()"
                class="w-14 h-14
                        rounded-full
                        flex items-center justify-center
                        text-gray-600
                        hover:bg-gray-100 hover:text-gray-900
                        transition ml-20"
                title="Open listing">

                    <i class="fa-solid fa-arrow-up-right-from-square text-lg"></i>
                </a>
            @endif
            </div>
        </div>


    </div> {{-- END GRID --}}
</div>
@endforeach

<h2 class="text-xl font-semibold mb-4 mt-14">Pending Approval</h2>

@if($pendingListings->isEmpty())
    <p class="text-gray-500 mb-6">No pending listings.</p>
@endif

@foreach($pendingListings as $listing)
<div class="w-full bg-white rounded-xl shadow-sm overflow-hidden border mb-6 relative group">
<a href="{{ route('landlord.listings.show', $listing) }}"
   class="absolute inset-0 z-10"
   aria-label="View listing details">
</a>

    <div class="grid grid-cols-12">
        @php
            $thumbnail = $listing->images
                ->filter(fn ($media) =>
                    in_array(
                        strtolower(pathinfo($media->image_path, PATHINFO_EXTENSION)),
                        ['jpg','jpeg','png','webp']
                    )
                )
                ->first();
        @endphp


        {{-- IMAGE --}}
        <div class="col-span-3">
            <img
                src="{{  $thumbnail
                        ? asset('storage/'.$thumbnail->image_path)
                        : asset('images/ocs-taman-placeholder.jpg') }}"
                        class="w-full h-48 object-cover">
        </div>

        {{-- CONTENT --}}
        <div class="col-span-6 px-6 py-4">

            <h2 class="font-semibold text-lg mb-1">
                {{ $listing->title }}
            </h2>

            <p class="text-xs text-gray-600">
                {{ $listing->address }}
            </p>

            @if($listing->distance_to_umpsa)
                <div class="mt-2 bg-gray-100 inline-block text-xs px-2 py-1 rounded mb-2">
                    <i class="fa-solid fa-location-dot mr-1 text-red-500"></i>
                    {{ number_format($listing->distance_to_umpsa, 2) }} km from UMPSA
                </div>
            @endif

            {{-- AMENITIES ICONS --}}
            <div class="mt-2 flex items-center space-x-2 text-sm text-gray-600">
                @foreach($listing->amenities ?? [] as $amenity)
                    @if(isset($amenityIcons[$amenity]))
                        <i class="fa-solid {{ $amenityIcons[$amenity] }}"
                           title="{{ $amenity }}"></i>
                    @endif
                @endforeach
            </div>

            {{-- BADGES --}}
            <div class="flex flex-wrap gap-2 mt-3 text-xs">
                @foreach($listing->badges as $badge)
                    <span
                        class="px-3 py-1 rounded-full border
                            {{ $badge['style'] === 'green'
                                ? 'bg-green-50 text-green-600'
                                : 'bg-gray-100 text-gray-600' }}">
                        {{ $badge['label'] }}
                    </span>
                @endforeach
            </div>

        </div> {{-- END CONTENT --}}

        {{-- ACTIONS --}}
        <div class="col-span-3 flex justify-end items-center pr-10 relative z-20">
            <div class="w-28 flex flex-col gap-3">

                <a href="{{ route('landlord.listings.edit', $listing) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-3 py-2 rounded-lg text-center">
                    Edit
                </a>

                <form method="POST">
                    @csrf
                    @method('DELETE')

                    <button
                    type="button"
                    onclick="openDeleteListingModal({{ $listing->id }})"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium px-3 py-2 rounded-lg w-full">
                        Delete
                    </button>
                </form>

            </div>
        </div>

    </div> {{-- END GRID --}}
</div>
@endforeach

</div>


@endsection
