@extends('layouts.ocs')

@section('title', '| Browse Rentals')

@section('content')


<div class="px-10 mb-20 w-full mx-auto">



    {{-- PAGE HEADER --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- LEFT: TITLE --}}
        <div>
            @if(request()->filled('q'))
                <h1 class="text-2xl font-semibold">
                    Search Results for "{{ request('q') }}"
                </h1>
            @elseif(!empty($areaName) && $areaName !== 'All Areas')
                <h1 class="text-2xl font-semibold">
                    Rental Housing in {{ $areaName }}
                </h1>
            @else
                <h1 class="text-2xl font-semibold">
                    Browse All Rental Properties
                </h1>
            @endif

            <p class="text-gray-500">
                {{ $listings->total() }} available properties found
            </p>
        </div>

        {{-- RIGHT: SEARCH BAR --}}
        <form method="GET"
            action="{{ url()->current() }}"
            class="w-full md:w-[420px]">

            <div class="flex items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search by title or address..."
                    class="flex-1 px-4 py-3 rounded-xl border
                        focus:outline-none focus:ring-2 focus:ring-gray-900">

                <button
                    type="submit"
                    class="px-6 py-3 bg-gray-900 hover:bg-gray-800
                        text-white rounded-xl font-medium">
                    Search
                </button>
            </div>
        </form>

    </div>



    <div class="flex gap-10">

        {{-- LEFT SIDEBAR --}}
        <aside class="w-80 space-y-6">

           <form method="GET" action="{{ url()->current() }}">

                {{-- Preserve search query --}}
                <input type="hidden" name="q" value="{{ request('q') }}">

                {{-- MAP --}}
                <div class="bg-white rounded-xl shadow overflow-hidden mb-6 relative">
                    <div id="map" class="h-64 w-full"></div>

                    {{-- SHOW ON MAP BUTTON --}}
                    <a href="{{ route('ocs.rentals.map', request()->query()) }}"
                    class="absolute bottom-4 right-4 z-10
                            bg-gray-900 hover:bg-gray-800
                            text-white text-sm font-medium
                            px-4 py-2 rounded-full
                            shadow-lg flex items-center gap-2">

                        <i class="fa-solid fa-map"></i>
                        Show on Map
                    </a>
                </div>



                {{-- FILTER --}}
                <div class="bg-white rounded-xl shadow p-6 space-y-6">

                    <h3 class="font-semibold">Filter</h3>

                    {{-- PROPERTY TYPE --}}
                    <div>
                        <p class="font-semibold text-sm mb-2">Property Type</p>
                        <div class="space-y-2 text-sm ">
                            @foreach(['Room','House','Apartment'] as $type)
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="type[]"
                                        class="
                                        w-4 h-4
                                        rounded-full
                                        border-gray-300
                                        appearance-auto
                                        accent-gray-900
                                        focus:ring-gray-900
                                    "
                                        value="{{ $type }}"
                                        {{ in_array($type, (array) request('type')) ? 'checked' : '' }}>
                                    {{ $type }}
                                </label>
                            @endforeach
                           
                        </div>
                    </div>

                    {{-- AMENITIES --}}
                    <div>
                        <p class="font-semibold text-sm mb-2">Amenities</p>
                        <div class="space-y-2 text-sm">
                            @foreach(['Parking','Air Conditioning','Washing Machine'] as $amenity)
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="amenities[]"
                                        class="
                                        w-4 h-4
                                        rounded-full
                                        border-gray-300
                                        appearance-auto
                                        accent-gray-900
                                        focus:ring-gray-900
                                    "
                                        value="{{ $amenity }}"
                                        {{ in_array($amenity, (array) request('amenities')) ? 'checked' : '' }}>
                                    {{ $amenity }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- PRICE --}}
                    <div>
                        <p class="font-semibold text-sm mb-2">Max Price</p>
                        <input
                            type="range"
                            name="max_price"
                            class="w-full border-gray-300
                                    appearance-auto
                                    accent-gray-900
                                    focus:ring-gray-900
                                    "
                            min="100"
                            max="5000"
                            step="50"
                            value="{{ request('max_price', 5000) }}"
                            class="w-full">

                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>RM100</span>
                            <span>RM{{ request('max_price', 5000) }}</span>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-gray-900 hover:bg-gray-800
                            text-white py-2 rounded-lg text-sm">
                        Apply Filters
                    </button>

                </div>
            </form>


        </aside>

        {{-- RESULTS --}}
        <main class="flex-1">

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

            @forelse($listings as $listing)

            <div class="w-full bg-white rounded-xl shadow-sm overflow-hidden border mb-6 relative group">

                {{-- CLICKABLE OVERLAY --}}
                <a href="{{ route('ocs.listings.show', $listing) }}"
                   class="absolute inset-0 z-10"
                   aria-label="View listing details"></a>

                <div class="grid grid-cols-12">

                    {{-- IMAGE --}}
                    <div class="col-span-3 relative">
                        <img
                            src="{{ $listing->images->first()?->image_path
                                    ? asset('storage/'.$listing->images->first()->image_path)
                                    : asset('images/ocs-taman-placeholder.jpg') }}"
                            class="w-full h-48 object-cover">

                        {{-- ⭐ RATING BADGE --}}
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
                                    {{ number_format($listing->reviews_avg_rating, 1) }}
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
                            <div class="mt-2 bg-gray-100 inline-block
                                        text-xs px-2 py-1 rounded mb-2">
                                <i class="fa-solid fa-location-dot
                                          mr-1 text-red-500"></i>
                                {{ number_format($listing->distance_to_umpsa, 2) }} km from UMPSA
                            </div>
                        @endif

                        {{-- AMENITIES --}}
                        <div class="mt-2 flex items-center space-x-3 text-sm text-gray-600">
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



                    </div>

                    {{-- PRICE --}}
                    <div class="col-span-3 flex justify-end items-center pr-10 relative z-20">

                        <div class="text-right">
                            <p class="text-sm text-gray-500">From</p>
                            <p class="text-2xl font-bold">
                                RM {{ number_format($listing->monthly_rent) }} 
                                <span class="text-sm text-gray">/ Month</span>
                            </p>

                            <a href="{{ route('ocs.listings.show', $listing) }}"
                                class="inline-block mt-3
                                        bg-gray-900 hover:bg-gray-800
                                        text-white px-4 py-2
                                        rounded-lg text-sm font-medium">
                                    Request Booking
                                </a>

                        </div>

                    </div>

                </div>
            </div>

            @empty
                <div class="bg-white rounded-xl shadow p-10 text-center text-gray-600">

                    @if(request()->filled('q'))
                        <p class="text-lg font-medium">
                            No listings found for "{{ request('q') }}"
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Try different keywords or remove some filters.
                        </p>

                    @elseif(!empty($areaName) && $areaName !== 'All Areas')
                        <p class="text-lg font-medium">
                            No listings found in {{ $areaName }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Check nearby areas or adjust your filters.
                        </p>

                    @else
                        <p class="text-lg font-medium">
                            No rental listings available
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Please check back later.
                        </p>
                    @endif

                </div>
            @endforelse


            {{-- PAGINATION --}}
            <div class="mt-10 text-white">
                {{ $listings->links()}}
            </div>

        </main>

    </div>

</div>

@php
    $mapListings = $listings->map(function ($l) {
        return [
            'id' => $l->id,
            'title' => $l->title,
            'lat' => (float) $l->latitude,
            'lng' => (float) $l->longitude,
            'price' => number_format($l->monthly_rent),
            'url' => route('ocs.listings.show', $l),
        ];
    })->values();
@endphp


<script>
    window.mapListings = @json($mapListings);

    document.addEventListener('DOMContentLoaded', () => {

    if (!window.mapListings || window.mapListings.length === 0) {
        return;
    }

    const first = window.mapListings[0];

    const map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: first.lat, lng: first.lng },
        zoom: 14,
        disableDefaultUI: true,
        zoomControl: true,
    });

    const bounds = new google.maps.LatLngBounds();

    window.mapListings.forEach(listing => {
        const marker = new google.maps.Marker({
            position: { lat: listing.lat, lng: listing.lng },
            map,
            title: listing.title,
        });

        const info = new google.maps.InfoWindow({
            content: `
                <div style="font-size:14px">
                    <strong>${listing.title}</strong><br>
                    RM ${listing.price} / month<br>
                    <a href="${listing.url}" style="color:#2563eb">
                        View listing
                    </a>
                </div>
            `,
        });

        marker.addListener('click', () => {
            info.open(map, marker);
        });

        bounds.extend(marker.position);
    });

    map.fitBounds(bounds);
})
</script>

<script>
// Update slider visual on load and change
document.addEventListener('DOMContentLoaded', () => {
    const priceSlider = document.querySelector('input[name="max_price"]');
    
    function updateSlider() {
        const value = ((priceSlider.value - priceSlider.min) / (priceSlider.max - priceSlider.min)) * 100;
        priceSlider.style.setProperty('--value', value + '%');
        
        // Update the displayed value in real-time
        const displaySpan = priceSlider.parentElement.querySelector('.flex.justify-between span:last-child');
        if (displaySpan) {
            displaySpan.textContent = 'RM' + priceSlider.value;
        }
    }
    
    updateSlider();
    priceSlider.addEventListener('input', updateSlider);
});
</script>

@endsection
