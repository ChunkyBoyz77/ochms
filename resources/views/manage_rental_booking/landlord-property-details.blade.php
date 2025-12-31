@extends('layouts.landlord')

@section('title', $listing->title)

@section('content')

<div class="px-10 mb-20 w-full mx-auto">

    {{-- BREADCRUMB --}}
    <nav class="mb-4 text-sm lg:text-md xl:text-lg text-gray-500" aria-label="Breadcrumb">
        <ol class="flex items-center flex-wrap gap-1">

            <li>
                <a href="{{ route('landlord.dashboard') }}"
                class="hover:text-gray-700 transition">
                    Home
                </a>
            </li>

            <li class="mx-1 text-gray-400"><i class="fa-solid fa-chevron-right text-xs mx-1 text-gray-300"></i></li>

            <li>
                <a href="{{ route('landlord.listings') }}"
                class="hover:text-gray-700 transition">
                    Listings
                </a>
            </li>

            <li class="mx-1 text-gray-400"><i class="fa-solid fa-chevron-right text-xs mx-1 text-gray-300"></i></li>

            <li class="text-gray-700 font-medium truncate max-w-[500px]">
                {{ $listing->title }}
            </li>

        </ol>
    </nav>

    {{-- HEADER --}}
    <div class="mb-6 mt-10">
        
        <div class="flex flex-wrap items-center gap-4 mb-5">

            {{-- TITLE --}}
            <h1 class="text-lg lg:text-2xl xl:text-3xl font-semibold">
                {{ $listing->title }}
            </h1>
            @if($listing->status === 'published')
                {{-- RATING BOX (HARDCODED FOR NOW) --}}
                <div
                    class="flex items-center gap-2
                        bg-green-50 text-green-600
                        px-3 py-2 rounded-lg
                        border border-blue-100
                        text-lg font-medium ml-5">

                    {{-- STARS --}}
                    <div class="flex items-center gap-0.5">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>

                    {{-- SCORE --}}
                    <span class="font-semibold">
                        4.8
                    </span>

                    {{-- REVIEWS --}}
                    <span class="text-green-500 font-normal">
                        (6 Reviews)
                    </span>
                </div>
            @endif

        </div>


        <div class="flex items-center gap-3 text-sm text-gray-600">

            <span class="text-[17px] sm:text-[12px] lg:text-[14px] xl:text-[15px] 2xl:text-[17px]">
                <i class="fa-solid fa-location-dot text-red-500 mr-1"></i>
                {{ $listing->address }}
            </span>

            @if($listing->status === 'pending')
                <span class="px-3 py-2 text-xs bg-yellow-50 text-yellow-600 rounded-lg border flex items-center gap-1.5">
                    <i class="fa-solid fa-hourglass-half"></i> Pending Approval
                </span>
            @endif
        </div>

        {{-- BADGES --}}
        <div class="flex flex-wrap gap-2 mt-4 text-xs">
            @foreach($listing->badges as $badge)
                <span
                    class="inline-flex items-center gap-1.5
                        px-3 py-2 rounded-xl border
                        {{ $badge['style'] === 'green'
                                ? 'bg-green-50 text-green-600 border-green-300 text-[15px]'
                                : 'bg-gray-100 text-gray-600 border-gray-300 text-[15px]' }}">

                    @if(!empty($badge['icon']))
                        <i class="fa-solid {{ $badge['icon'] }}"></i>
                    @endif

                    {{ $badge['label'] }}
                </span>

            @endforeach
        </div>
    </div>

   {{-- IMAGE GALLERY --}}
    <div class="relative grid grid-cols-12 gap-6 mb-10">
        @if ($listing->images->count() === 1)
            {{-- SINGLE IMAGE (FULL WIDTH) --}}
            <div class="col-span-12">
                <img
                    src="{{ asset('storage/'.$listing->images->first()->image_path) }}"
                    class="w-full h-[600px] object-cover bg-gray-100
                        rounded-2xl border">
            </div>
        @else

        {{-- MAIN IMAGE --}}
        <div class="col-span-8">

            <img
                src="{{ $listing->images->first()
                    ? asset('storage/'.$listing->images->first()->image_path)
                    : asset('images/ocs-taman-placeholder.jpg') }}"
                class="w-full h-[600px] object-cover rounded-2xl border">

        </div>

        {{-- THUMBNAILS --}}
        <div class="col-span-4 grid grid-cols-2 gap-4">
            @foreach($listing->images->slice(1,4) as $img)
                <img
                    src="{{ asset('storage/'.$img->image_path) }}"
                    class="w-full h-[290px] object-cover rounded-xl border">
            @endforeach
        </div>

        {{-- SEE ALL PHOTOS BUTTON --}}
        @if($listing->images->count() > 5)
            <a
                href="{{ route('landlord.listings.media', $listing) }}"
                class="absolute bottom-6 right-6
                    bg-black/80 hover:bg-black
                    text-white text-sm px-4 py-2
                    rounded-lg flex items-center gap-2
                    shadow-lg transition"
            >
                <i class="fa-solid fa-images"></i>
                See all photos ({{ $listing->images->count() }})
            </a>
        @endif

        @endif

    </div>

{{-- CONTENT WRAPPER --}}
<div class="relative grid grid-cols-12 gap-10">

    {{-- RIGHT: DETAILS (SCROLLABLE) --}}
    <div class="col-span-12 lg:col-span-8">

        {{-- QUICK HIGHLIGHTS --}}
        <div class="bg-white border rounded-2xl shadow-sm px-6 py-5 mb-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                @if($listing->distance_to_umpsa)
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-500
                                flex items-center justify-center">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Distance to UMPSA</p>
                        <p class="font-semibold text-gray-900">
                            {{ number_format($listing->distance_to_umpsa, 2) }} km
                        </p>
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500
                                flex items-center justify-center">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Property Type</p>
                        <p class="font-semibold text-gray-900">
                            {{ $listing->property_type }}
                        </p>
                    </div>
                </div>

            </div>

        </div>

        {{-- DESCRIPTION --}}
        <div class="bg-white rounded-2xl border shadow-sm p-6 mb-8">
            <h3 class="font-semibold text-2xl lg:text-2xl md:text-lg sm:text-base mb-3">
                About this property
            </h3>
            <p class="text-sm lg:text-base md:text-lg sm:text-basetext-gray-700 leading-relaxed">
                {!! $listing->description !!}

            </p>
        </div>

    </div>

    {{-- LEFT: STICKY PRICE PANEL --}}
    <div class="col-span-12 lg:col-span-4 relative">

        <div class="sticky top-24 transition-transform duration-200">

            <div class="bg-white rounded-2xl border shadow-sm p-6">

                {{-- PRICE --}}
                <div class="mb-4">
                    <span class="text-3xl font-semibold text-gray-900">
                        RM {{ number_format($listing->monthly_rent) }}
                    </span>
                    <span class="text-gray-500 text-sm">
                        / month
                    </span>
                </div>

                {{-- DEPOSIT --}}
                <div class="mb-6 text-sm text-gray-600">
                    Deposit: <span class="font-medium text-gray-800">
                        RM {{ number_format($listing->deposit) }}
                    </span>
                </div>

                {{-- CTA --}}
                <button
                    class="w-full bg-red-500 hover:bg-red-600
                           text-white font-semibold py-3
                           rounded-xl transition mb-3">
                    Contact Landlord
                </button>

                <button
                    class="w-full border border-gray-300
                           text-gray-700 py-3
                           rounded-xl hover:bg-gray-50 transition">
                    Edit Listing
                </button>

                {{-- NOTE --}}
                <p class="text-xs text-gray-500 text-center mt-4">
                    No charges will be made yet
                </p>

            </div>

        </div>

    </div>

</div>

{{-- FULL WIDTH TABS SECTION --}}
<div id="amenities-section" class="w-full mt-10">

    <div class="bg-white border rounded-2xl shadow-sm px-8 py-6">

        {{-- TAB HEADERS --}}
        <div class="flex gap-8 border-b mb-8 font-medium text-gray-600">
            <button
                class="pb-3 border-b-2 border-red-500 text-red-500 transition"
                data-tab="amenities">
                Amenities
            </button>

            <button
                class="pb-3 transition"
                data-tab="policies">
                Policies
            </button>

            <button
                class="pb-3 transition"
                data-tab="location">
                Location
            </button>
        </div>
@php
$amenityGroups = [
    'Safety' => [
        'icon' => 'fa-shield-halved',
        'items' => [
            'CCTV' => 'fa-video',
            '24 Hours Security' => 'fa-user-shield',
            'Fire System' => 'fa-fire-extinguisher',
            'Controlled Access' => 'fa-key',
            'Security Guard' => 'fa-user-lock',
        ]
    ],
    'Property Services' => [
        'icon' => 'fa-concierge-bell',
        'items' => [
            'Reception' => 'fa-bell-concierge',
        ]
    ],
    'Home Ammenities' => [
        'icon' => 'fa-users',
        'items' => [
            'WiFi' => 'fa-wifi',
            'Laundry Room' => 'fa-jug-detergent',
            'Study Room' => 'fa-book',
            'Lounge' => 'fa-couch',
            'Bike Storage' => 'fa-bicycle',
            'Vending Machine' => 'fa-store',
        ]
    ],
    'Fitness & Recreation' => [
        'icon' => 'fa-dumbbell',
        'items' => [
            'Pool Table' => 'fa-table-tennis-paddle-ball',
        ]
    ],
];
@endphp

        {{-- AMENITIES --}}
        <div data-tab-content="amenities">

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    Amenities
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                @foreach($amenityGroups as $groupName => $group)
                    @php
                        $available = collect($group['items'])
                            ->keys()
                            ->intersect($listing->amenities ?? []);
                    @endphp

                    @if($available->isNotEmpty())
                        <div class="border rounded-xl p-5">

                            {{-- HEADER --}}
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fa-solid {{ $group['icon'] }} text-gray-700"></i>
                                <h4 class="font-semibold text-gray-900">
                                    {{ $groupName }} ({{ $available->count() }})
                                </h4>
                            </div>

                            {{-- ITEMS --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 text-sm text-gray-700">
                                @foreach($group['items'] as $item => $icon)
                                    @if(in_array($item, $listing->amenities ?? []))
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid {{ $icon }} text-gray-600"></i>
                                            <span>{{ $item }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        </div>
                    @endif
                @endforeach

            </div>
        </div>


        {{-- POLICIES --}}
        <div data-tab-content="policies" class="hidden space-y-6">

            @if($listing->policy_cancellation)
                <div>
                    <h4 class="font-semibold text-lg text-gray-900 mb-5">
                        Cancellation Policy
                    </h4>
                   <div class="prose prose-sm max-w-none text-gray-700
                    [&>*]:mt-0
                    [&>*+*]:mt-2">
                        {!! $listing->policy_cancellation !!}
                    </div>
                </div>
            @endif

            @if($listing->policy_refund)
                <div>
                    <h4 class="font-semibold text-lg text-gray-900 mb-5">
                        Refund Policy
                    </h4>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! $listing->policy_refund !!}
                    </div>
                </div>
            @endif

            @if($listing->policy_late_payment)
                <div>
                    <h4 class="font-semibold text-lg text-gray-900 mb-5">
                        Late Payment Policy
                    </h4>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! $listing->policy_late_payment !!}
                    </div>
                </div>
            @endif
        </div>

        {{-- LOCATION TAB --}}
        <div data-tab-content="location" class="hidden space-y-4">

            {{-- 📍 ADDRESS --}}
            <div class="flex items-start gap-3
                        bg-white border rounded-xl
                        px-4 py-3 shadow-sm">

                <div class="w-9 h-9 rounded-full bg-red-100
                            flex items-center justify-center
                            text-red-500 shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>

                <div>
                    <p class="font-medium text-gray-900">
                        Property Location
                    </p>
                    <p class="text-gray-600 leading-snug">
                        {{ $listing->address ?? 'Address not provided' }}
                    </p>

                    {{-- 📏 DISTANCE HINT --}}
                    @if($listing->distance_to_umpsa)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format($listing->distance_to_umpsa, 2) }} km from UMPSA
                        </p>
                    @endif
                </div>
            </div>

            {{-- 🗺️ MAP (RELATIVE CONTAINER) --}}
            <div class="relative w-full h-[420px] rounded-2xl border shadow-sm overflow-hidden">

                <div id="listing-map" class="w-full h-full"></div>

                {{-- 🧭 VIEW ON GOOGLE MAPS BUTTON (BOTTOM LEFT) --}}
                <div class="absolute bottom-4 left-4 z-10">
                    <a
                        href="https://www.google.com/maps/search/?api=1&query={{ $listing->latitude }},{{ $listing->longitude }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2
                            bg-white/95 backdrop-blur
                            px-4 py-2 rounded-xl
                            shadow-md border
                            text-sm font-medium text-gray-700
                            hover:bg-gray-100 transition">

                        <i class="fa-solid fa-diamond-turn-right text-blue-500"></i>
                        View on Google Maps
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>

<script
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}"
  defer>
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const gallery = document.querySelector('[data-gallery]');
    if (!gallery) return;

    const images = JSON.parse(gallery.dataset.images || '[]');
    if (!images.length) return;

    const mainImg = document.getElementById('gallery-main-image');
    const counter = document.getElementById('gallery-counter');

    let current = 0;

    const update = () => {
        mainImg.src = `/storage/${images[current]}`;
        counter.textContent = `${current + 1} / ${images.length}`;
    };

    gallery.querySelector('[data-next]').addEventListener('click', () => {
        current = (current + 1) % images.length;
        update();
    });

    gallery.querySelector('[data-prev]').addEventListener('click', () => {
        current = (current - 1 + images.length) % images.length;
        update();
    });

    gallery.querySelectorAll('[data-thumb]').forEach(thumb => {
        thumb.addEventListener('click', () => {
            current = parseInt(thumb.dataset.thumb, 10);
            update();
        });
    });

});
</script>
<script>
document.querySelectorAll('[data-tab]').forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.dataset.tab;

        document.querySelectorAll('[data-tab]').forEach(t => {
            t.classList.remove('border-red-500', 'text-red-500');
            t.classList.add('text-gray-600');
        });

        tab.classList.add('border-red-500', 'text-red-500', 'border-b-2');

        document.querySelectorAll('[data-tab-content]').forEach(content => {
            content.classList.toggle(
                'hidden',
                content.dataset.tabContent !== target
            );
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // LISTING LOCATION (from DB)
    const listingLocation = {
        lat: {{ $listing->latitude ?? 3.5449 }},
        lng: {{ $listing->longitude ?? 103.4281 }}
    };

    // 🔵 HARDCODED HOTSPOTS
    const hotspots = [
        {
            name: 'UMPSA Pekan',
            lat: 3.5449,
            lng: 103.4281,
            type: 'campus'
        },
        {
            name: 'Lotus’s Pekan',
            lat: 3.5431,
            lng: 103.4256,
            type: 'mart'
        },
        {
            name: 'Bus Stop Pekan',
            lat: 3.5472,
            lng: 103.4305,
            type: 'transport'
        },
        {
            name: 'Restaurant Area',
            lat: 3.5420,
            lng: 103.4328,
            type: 'food'
        }
    ];

    const mapEl = document.getElementById('listing-map');
    if (!mapEl) return;

    const map = new google.maps.Map(mapEl, {
        center: listingLocation,
        zoom: 30,
        mapTypeControl: false,
        streetViewControl: false,
    });

    const bounds = new google.maps.LatLngBounds();

    // 🏠 Listing Marker (RED)
    const listingMarker = new google.maps.Marker({
        position: listingLocation,
        map,
        title: 'Property Location',
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        }
    });

    bounds.extend(listingMarker.getPosition());

    // 📍 Hotspot Markers (BLUE)
    hotspots.forEach(hotspot => {
        const marker = new google.maps.Marker({
            position: { lat: hotspot.lat, lng: hotspot.lng },
            map,
            title: hotspot.name,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
            }
        });

        const info = new google.maps.InfoWindow({
            content: `
                <div class="text-sm">
                    <strong>${hotspot.name}</strong><br>
                    Type: ${hotspot.type}
                </div>
            `
        });

        marker.addListener('click', () => info.open(map, marker));
        bounds.extend(marker.getPosition());
    });

    if (hotspots.length <= 1) {
        map.setCenter(listingLocation);
        map.setZoom(18);
    } else {
        map.fitBounds(bounds);
        google.maps.event.addListenerOnce(map, 'bounds_changed', () => {
            map.setZoom(Math.min(map.getZoom(), 17));
        });
    }


});
</script>




@endsection
